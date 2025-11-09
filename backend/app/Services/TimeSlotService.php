<?php

namespace App\Services;

use App\DTO\TimeSlot\TimeSlotDto;
use App\DTO\TimeSlot\UpdateAvailabilityDto;
use App\Models\Booking;
use App\Models\TimeSlot;
use App\Resources\TimeSlot\TimeSlotAvailabilityResource;
use App\Resources\TimeSlot\TimeSlotListResource;
use App\Resources\TimeSlot\TimeSlotResource;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;

class TimeSlotService extends AbstractApiService
{
    public function create(TimeSlotDto $dto): JsonResponse
    {
        $timeSlot = TimeSlot::query()->make([
            'booking_object_id' => $dto->booking_object_id,
            'start_time' => $dto->start_time,
            'end_time' => $dto->end_time,
            'is_available' => $dto->is_available,
            'created_by' => auth()->id(),
            'updated_by' => auth()->id(),
        ]);

        DB::beginTransaction();
        try {
            $timeSlot->save();
            DB::commit();
            Log::info('TimeSlot created', [
                'time_slot_id' => $timeSlot->id,
                'user_id' => auth()->id(),
            ]);
            Cache::forget('time_slots');
            return $this->success(
                new TimeSlotResource($timeSlot),
                $this->langMessage('response_messages.create_success')
            );
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    public function read(int $perPage = 15): JsonResponse
    {
        $timeSlots = TimeSlot::query()->paginate($perPage);

        return $this->success(
            TimeSlotListResource::collection($timeSlots),
            $this->langMessage('response_messages.read_success')
        );
    }

    public function show(int $id): JsonResponse
    {
        $cacheKey = "time_slot:{$id}";
        $timeSlot = Cache::remember($cacheKey, now()->addMinutes(10), function () use ($id) {
            return TimeSlot::findOrFail($id);
        });

        return $this->success(
            new TimeSlotResource($timeSlot),
            $this->langMessage('response_messages.read_success')
        );
    }

    public function update(TimeSlotDto $dto): JsonResponse
    {
        return DB::transaction(function () use ($dto) {
            $timeSlot = TimeSlot::findOrFail($dto->id);
            $timeSlot->update([
                'booking_object_id' => $dto->booking_object_id ?? $timeSlot->booking_object_id,
                'start_time' => $dto->start_time ?? $timeSlot->start_time,
                'end_time' => $dto->end_time ?? $timeSlot->end_time,
                'is_available' => $dto->is_available ?? $timeSlot->is_available,
                'updated_by' => auth()->id(),
            ]);

            Log::info('TimeSlot updated', [
                'time_slot_id' => $timeSlot->id,
                'user_id' => auth()->id(),
            ]);

            Cache::forget("time_slot:{$timeSlot->id}");
            Cache::forget('time_slots');

            return $this->success(
                new TimeSlotResource($timeSlot),
                $this->langMessage('response_messages.update_success')
            );
        });
    }

    public function delete(int $id): JsonResponse
    {
        $timeSlot = TimeSlot::findOrFail($id);

        Log::info('TimeSlot deleted', [
            'time_slot_id' => $id,
            'user_id' => auth()->id(),
        ]);

        $timeSlot->delete();

        Cache::forget("time_slot:{$id}");
        Cache::forget('time_slots');

        return $this->success(null, $this->langMessage('response_messages.delete_success'));
    }

    public function updateAvailability(UpdateAvailabilityDto $dto): JsonResponse
    {
        $timeSlot = TimeSlot::findOrFail($dto->id);

        // Запрещаем делать доступным слот, если он уже забронирован
        if ($dto->is_available === true) {
            $existingBooking = Booking::where('time_slot_id', $dto->id)
                ->whereIn('status', ['pending', 'confirmed'])
                ->exists();

            if ($existingBooking) {
                throw ValidationException::withMessages([
                    'is_available' => 'Нельзя сделать слот доступным: он уже забронирован'
                ]);
            }
        }

        DB::beginTransaction();
        try {
            $timeSlot->update([
                'is_available' => $dto->is_available,
                'updated_by' => $dto->updated_by ?? auth()->id(),
            ]);

            DB::commit();

            Log::info('Time slot availability updated', [
                'time_slot_id' => $timeSlot->id,
                'is_available' => $dto->is_available,
                'updated_by' => auth()->id(),
            ]);

            return $this->success(
                new TimeSlotAvailabilityResource($timeSlot->fresh()),
                $this->langMessage('response_messages.update_success')
            );

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Failed to update time slot availability', [
                'error' => $e->getMessage(),
                'time_slot_id' => $dto->id,
            ]);

            throw $e;
        }
    }

    private function langMessage(string $name): string
    {
        return trans('dialogue.' . $name);
    }
}
