<?php
// app/Services/BookingService.php

namespace App\Services;

use App\Builders\BookingBuilder;
use App\DTO\Booking\BookingDto;
use App\Jobs\ProcessBookingJob;
use App\Models\Booking;
use App\Models\TimeSlot;
use App\Resources\Booking\BookingResource;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;

class BookingService extends AbstractApiService
{
    public function create(BookingDto $dto): JsonResponse
    {
        DB::beginTransaction();
        try {
            $booking = (new BookingBuilder())
                ->setUserId($dto->user_id ?? auth()->id())
                ->setTimeSlotId($dto->time_slot_id)
                ->setStatus($dto->status ?? 'pending')
                ->setCreatedBy(auth()->id())
                ->setUpdatedBy(auth()->id())
                ->build();

            // Сохраняем бронирование
            $booking->save();

            // Обновляем доступность слота
            TimeSlot::where('id', $dto->time_slot_id)
                ->update(['is_available' => false]);

            ProcessBookingJob::dispatch($booking)
                ->onQueue('bookings')
                ->delay(now()->addSeconds(15));

            DB::commit();

            Log::info('Booking created successfully', [
                'booking_id' => $booking->id,
                'user_id' => auth()->id(),
                'time_slot_id' => $dto->time_slot_id,
            ]);

            return $this->success(
                new BookingResource($booking),
                $this->langMessage('response_messages.create_success')
            );

        } catch (ValidationException $e) {
            DB::rollBack();
            throw $e;
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Booking creation failed', [
                'error' => $e->getMessage(),
                'user_id' => auth()->id(),
                'time_slot_id' => $dto->time_slot_id ?? null,
            ]);
            throw new \Exception('Failed to create booking');
        }
    }

    public function show(int $id): JsonResponse
    {
        $booking = Booking::with(['user', 'timeSlot.bookingObject'])
            ->findOrFail($id);

        // Проверка прав доступа
        if (auth()->id() !== $booking->user_id && !auth()->user()->hasRole(['admin', 'manager'])) {
            throw ValidationException::withMessages([
                'booking' => ['You do not have permission to view this booking']
            ]);
        }

        return $this->success(new BookingResource($booking), $this->langMessage('response_messages.read_success'));
    }

    public function updateStatus(int $id, string $status): JsonResponse
    {
        $booking = Booking::findOrFail($id);

        // Проверка прав доступа
        if (auth()->id() !== $booking->user_id && !auth()->user()->hasRole(['admin', 'manager'])) {
            throw ValidationException::withMessages([
                'booking' => ['You do not have permission to update this booking']
            ]);
        }

        $validStatuses = ['pending', 'confirmed', 'cancelled'];
        if (!in_array($status, $validStatuses)) {
            throw ValidationException::withMessages([
                'status' => ['Invalid booking status']
            ]);
        }

        DB::beginTransaction();
        try {
            $booking->update(['status' => $status]);

            // Если отменено, возвращаем слот в доступное состояние
            if ($status === 'cancelled') {
                $booking->timeSlot()->update(['is_available' => true]);
            }

            DB::commit();

            ProcessBookingJob::dispatch($booking)
                ->onQueue('bookings')
                ->delay(now()->addSeconds(15));

            return $this->success(
                new BookingResource($booking),
                $this->langMessage('response_messages.update_success')
            );
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    private function langMessage(string $name): string
    {
        return trans('dialogue.' . $name);
    }
}
