<?php

namespace App\Services;

use App\DTO\Room\RoomDto;
use App\Models\Room;
use App\Models\Option;
use App\Resources\Room\RoomListResource;
use App\Resources\Room\RoomResource;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Spatie\DataTransferObject\Exceptions\UnknownProperties;

class RoomService extends AbstractApiService
{
    /**
     * Создание номера.
     *
     * @param RoomDto $dto
     * @return array
     * @throws UnknownProperties
     */
    public function create(RoomDto $dto): JsonResponse
    {
        return DB::transaction(function () use ($dto) {
            // Проверка существования отеля
            $this->validateHotelId($dto->hotel_id);

            // Создание номера
            $room = Room::create([
                'hotel_id' => $dto->hotel_id,
                'name' => $dto->name,
                'capacity' => $dto->capacity,
                'price_per_night' => $dto->price_per_night,
                'created_by' => auth()->id(),
                'updated_by' => auth()->id(),
            ]);

            // Привязка опций, если указаны
            if (!empty($dto->option_ids)) {
                $this->validateOptionIds($dto->option_ids);
                $room->options()->sync($dto->option_ids);
            }

            // Логирование действия
            Log::info('Room created', [
                'room_id' => $room->id,
                'hotel_id' => $dto->hotel_id,
                'user_id' => auth()->id(),
            ]);

            // Очистка кэша для списка номеров отеля
            Cache::forget("hotel:{$dto->hotel_id}:rooms");

            // Возвращаем данные номера с загруженными опциями
            return $this->success(
                new RoomResource($room),
                $this->langMessage('response_messages.create_success')
            );
        });
    }

    /**
     * Получение списка номеров с фильтрацией и пагинацией.
     *
     * @param RoomDto $dto
     * @return array
     */
    public function read(int $perPage = 15): JsonResponse
    {
        $hotels = Room::query()->paginate($perPage);

        return $this->success(
            RoomListResource::collection($hotels)
        );
    }

    /**
     * Получение информации о номере по ID.
     *
     * @param int $id
     * @return JsonResponse
     */
    public function show(int $id): JsonResponse
    {
        $room = Room::findOrFail($id);

        return $this->success(
            new RoomListResource($room)
        );
    }

    /**
     * Обновление номера.
     *
     * @param RoomDto $dto
     * @return JsonResponse
     */
    public function update(RoomDto $dto): JsonResponse
    {
        $room = Room::findOrFail($dto->id);
        $room->name = $dto->name;
        $room->save();

        return $this->success(
            new RoomListResource($room)
        );
    }

    /**
     * Удаление номера.
     *
     * @param int $id
     * @throws ModelNotFoundException
     */
    public function delete(int $id): JsonResponse
    {
        $room = Room::findOrFail($id);

        // Логирование перед удалением
        Log::info('Room deleted', [
            'room_id' => $id,
            'hotel_id' => $room->hotel_id,
            'user_id' => auth()->id(),
        ]);

        // Удаление номера (каскадное удаление связей в room_option)
        $room->delete();

        // Очистка кэша
        Cache::forget("room:{$id}");
        Cache::forget("hotel:{$room->hotel_id}:rooms");

        return $this->success();
    }

    /**
     * Проверка существования отеля.
     *
     * @param int $hotelId
     * @throws ModelNotFoundException
     */
    private function validateHotelId(int $hotelId): void
    {
        \App\Models\Hotel::findOrFail($hotelId);
    }

    /**
     * Проверка существования опций.
     *
     * @param array $optionIds
     * @throws \InvalidArgumentException
     */
    private function validateOptionIds(array $optionIds): void
    {
        $existingOptions = Option::whereIn('id', $optionIds)->pluck('id')->toArray();
        $invalidOptions = array_diff($optionIds, $existingOptions);
        if (!empty($invalidOptions)) {
            throw new \InvalidArgumentException('Invalid option IDs: ' . implode(', ', $invalidOptions));
        }
    }

    /**
     * Возвращяет сообщение с локализацией
     *
     * @param string $name
     * @return string
     */
    private function langMessage(string $name): string
    {
        return trans('dialogue.' . $name);
    }
}
