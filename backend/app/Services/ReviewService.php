<?php

namespace App\Services;

use App\DTO\Review\ReviewDto;
use App\Models\Review;
use App\Resources\Review\ReviewResource;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class ReviewService extends AbstractApiService
{
    public function create(ReviewDto $dto): JsonResponse
    {
        $review = Review::query()->make([
            'user_id' => $dto->user_id ?? auth()->id(),
            'booking_object_id' => $dto->booking_object_id,
            'review' => $dto->review,
            'rating' => $dto->rating,
            'created_by' => auth()->id(),
            'updated_by' => auth()->id(),
        ]);

        DB::beginTransaction();
        try {
            $review->save();
            DB::commit();
            Log::info('Review created', [
                'review_id' => $review->id,
                'user_id' => auth()->id(),
            ]);
            Cache::forget('reviews');
            return $this->success(
                new ReviewResource($review),
                $this->langMessage('response_messages.create_success')
            );
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    public function read(ReviewDto $dto): JsonResponse
    {
        $query = Review::query();

        if ($dto->booking_object_id) {
            $query->where('booking_object_id', $dto->booking_object_id);
        }

        if ($dto->hotel_id) {
            $query->whereHas('bookingObject.room.hotel', function ($q) use ($dto) {
                $q->where('id', $dto->hotel_id);
            });
        }

        $reviews = $query->paginate($dto->per_page);

        return $this->success(
            ReviewListResource::collection($reviews),
            $this->langMessage('response_messages.read_success')
        );
    }

    public function show(int $id): JsonResponse
    {
        $cacheKey = "review:{$id}";
        $review = Cache::remember($cacheKey, now()->addMinutes(10), function () use ($id) {
            return Review::findOrFail($id);
        });

        return $this->success(
            new ReviewResource($review),
            $this->langMessage('response_messages.read_success')
        );
    }

    public function update(ReviewDto $dto): JsonResponse
    {
        return DB::transaction(function () use ($dto) {
            $review = Review::findOrFail($dto->id);
            if (!auth()->user()->hasRole(['admin', 'manager']) && $review->user_id !== auth()->id()) {
                throw new \Exception('Unauthorized access to review', 403);
            }

            $review->update([
                'booking_object_id' => $dto->booking_object_id ?? $review->booking_object_id,
                'review' => $dto->review ?? $review->review,
                'rating' => $dto->rating ?? $review->rating,
                'updated_by' => auth()->id(),
            ]);

            Log::info('Review updated', [
                'review_id' => $review->id,
                'user_id' => auth()->id(),
            ]);

            Cache::forget("review:{$review->id}");
            Cache::forget('reviews');

            return $this->success(
                new ReviewResource($review),
                $this->langMessage('response_messages.update_success')
            );
        });
    }

    public function delete(int $id): JsonResponse
    {
        $review = Review::findOrFail($id);
        if (!auth()->user()->hasRole(['admin', 'manager']) && $review->user_id !== auth()->id()) {
            throw new \Exception('Unauthorized access to review', 403);
        }

        Log::info('Review deleted', [
            'review_id' => $id,
            'user_id' => auth()->id(),
        ]);

        $review->delete();

        Cache::forget("review:{$id}");
        Cache::forget('reviews');

        return $this->success(null, $this->langMessage('response_messages.delete_success'));
    }

    private function langMessage(string $name): string
    {
        return trans('dialogue.' . $name);
    }
}
