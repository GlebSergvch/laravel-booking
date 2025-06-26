<?php

namespace App\Services;

use App\DTO\Option\OptionDto;
use App\Models\Option;
use App\Resources\Option\OptionListResource;
use App\Resources\Option\OptionResource;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class OptionService extends AbstractApiService
{

    public function create(OptionDto $dto): JsonResponse
    {
        /* @var Option $option */
        $option = Option::query()->make([
            'name' => $dto->name,
            'created_by' => auth()->id(),
            'updated_by' => auth()->id(),
        ]);

        DB::beginTransaction();
        try {
            $option->save();
            DB::commit();
            return $this->success(
                new OptionResource($option),
                $this->langMessage('response_messages.create_success')
            );
        }
        catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    public function read(int $perPage = 15): JsonResponse
    {
        $hotels = Option::query()->paginate($perPage);

        return $this->success(
            OptionListResource::collection($hotels)
        );
    }

    public function show(int $id): OptionResource
    {
        $cacheKey = "option:{$id}";
        $option = Cache::remember($cacheKey, now()->addMinutes(10), function () use ($id) {
            return Option::findOrFail($id);
        });

        return new OptionResource($option);
    }

    public function update(OptionDto $dto): JsonResponse
    {
        return DB::transaction(function () use ($dto) {
            $option = Option::findOrFail($dto->id);
            $option->update([
                'name' => $dto->name,
                'updated_by' => auth()->id(),
            ]);

            Log::info('Option updated', [
                'option_id' => $option->id,
                'user_id' => auth()->id(),
            ]);

            Cache::forget("option:{$option->id}");
            Cache::forget('options');

            return $this->success(
                new OptionResource($option),
                $this->langMessage('response_messages.create_success')
            );
        });
    }

    public function delete(int $id): JsonResponse
    {
        $option = Option::findOrFail($id);

        Log::info('Option deleted', [
            'option_id' => $id,
            'user_id' => auth()->id(),
        ]);

        $option->delete();

        Cache::forget("option:{$id}");
        Cache::forget('options');

        return $this->success();
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
