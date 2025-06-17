<?php
declare(strict_types=1);
namespace App\Services;

use App\DTO\Hotel\HotelDto;
use App\Interfaces\DtoInterface;
use App\Models\Hotel;
use App\Resources\Hotel\HotelCreateResource;
use App\Resources\Hotel\HotelListResource;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;

class HotelService extends AbstractApiService
{

    /**
     * @param HotelDto $dto
     * @return JsonResponse
     */
    public function read(int $perPage = 15): JsonResponse
    {
        $hotels = Hotel::query()->paginate($perPage);

        return $this->success(
            HotelListResource::collection($hotels)
        );
    }

    /**
     * @param int $id
     * @return JsonResponse
     */
    public function find(int $id): JsonResponse
    {
        $hotel = Hotel::findOrFail($id);

        return $this->success(
            new HotelListResource($hotel)
        );
    }

    /**
     * @param int $id
     * @return JsonResponse
     */
    public function delete(int $id): JsonResponse
    {
        $hotel = Hotel::findOrFail($id);
        $hotel->delete();

        return $this->success(
            []
        );
    }

    /**
     * Создаёт отель
     *
     * @param HotelDto $dto
     * @return JsonResponse
     * @throws \Exception
     */
    public function create(DtoInterface $dto): JsonResponse
    {
        /* @var Hotel $hotel */
        $hotel = Hotel::query()->make([
            'name'    => $dto->name,
            'address' => $dto->address,
            'city'    => $dto->city,
            'country' => $dto->country,
        ]);

        DB::beginTransaction();
        try {
            $hotel->save();
            DB::commit();
            return $this->success(
                new HotelCreateResource($hotel),
                $this->langMessage('response_messages.create_success')
            );
        }
        catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    /**
     * @param int $id
     * @return JsonResponse
     */
    public function update(HotelDto $hotelDto): JsonResponse
    {
//        var_dump($hotelDto->id); die();
        $hotel = Hotel::findOrFail($hotelDto->id);
        $hotel->name = $hotelDto->name;
        $hotel->save();

        return $this->success(
            new HotelListResource($hotel)
        );
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
