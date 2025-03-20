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
    public function read(DtoInterface $dto): JsonResponse
    {
        $hotels = Hotel::query();

        return $this->success(
            HotelListResource::collection($hotels)
        );
    }

    /**
     * Создаёт бланк ПДБ
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
