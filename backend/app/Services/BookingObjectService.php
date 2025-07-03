<?php

namespace App\Services;

use App\DTO\Booking\BookingObjectDto;
use App\Models\BookingObject;
use App\Resources\Booking\BookingObjectListResource;
use App\Resources\Booking\BookingObjectResource;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class BookingObjectService extends AbstractApiService
{
    public function create(BookingObjectDto $dto): JsonResponse
    {
        $bookingObject = BookingObject::query()->make([
            'room_id' => $dto->room_id,
            'name' => $dto->name,
            'description' => $dto->description,
            'type' => $dto->type,
            'created_by' => auth()->id(),
            'updated_by' => auth()->id(),
        ]);

        DB::beginTransaction();
        try {
            $bookingObject->save();
            DB::commit();
            Log::info('BookingObject created', [
                'booking_object_id' => $bookingObject->id,
                'user_id' => auth()->id(),
            ]);
            Cache::forget('booking_objects');
            return $this->success(
                new BookingObjectResource($bookingObject),
                $this->langMessage('response_messages.create_success')
            );
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    public function read(int $perPage = 15): JsonResponse
    {
        $bookingObjects = BookingObject::query()->paginate($perPage);

        return $this->success(
            BookingObjectListResource::collection($bookingObjects),
            $this->langMessage('response_messages.read_success')
        );
    }

    public function show(int $id): JsonResponse
    {
        $cacheKey = "booking_object:{$id}";
        $bookingObject = Cache::remember($cacheKey, now()->addMinutes(10), function () use ($id) {
            return BookingObject::findOrFail($id);
        });

        return $this->success(
            new BookingObjectResource($bookingObject),
            $this->langMessage('response_messages.read_success')
        );
    }

    public function update(BookingObjectDto $dto): JsonResponse
    {
        return DB::transaction(function () use ($dto) {
            $bookingObject = BookingObject::findOrFail($dto->id);
            $bookingObject->update([
                'room_id' => $dto->room_id ?? $bookingObject->room_id,
                'name' => $dto->name ?? $bookingObject->name,
                'description' => $dto->description ?? $bookingObject->description,
                'type' => $dto->type ?? $bookingObject->type,
                'updated_by' => auth()->id(),
            ]);

            Log::info('BookingObject updated', [
                'booking_object_id' => $bookingObject->id,
                'user_id' => auth()->id(),
            ]);

            Cache::forget("booking_object:{$bookingObject->id}");
            Cache::forget('booking_objects');

            return $this->success(
                new BookingObjectResource($bookingObject),
                $this->langMessage('response_messages.update_success')
            );
        });
    }

    public function delete(int $id): JsonResponse
    {
        $bookingObject = BookingObject::findOrFail($id);

        Log::info('BookingObject deleted', [
            'booking_object_id' => $id,
            'user_id' => auth()->id(),
        ]);

        $bookingObject->delete();

        Cache::forget("booking_object:{$id}");
        Cache::forget('booking_objects');

        return $this->success(null, $this->langMessage('response_messages.delete_success'));
    }

    private function langMessage(string $name): string
    {
        return trans('dialogue.' . $name);
    }
}
