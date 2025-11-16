<?php

namespace App\Jobs;

use App\Models\Booking;
//use App\Notifications\BookingConfirmedNotification;
//use App\Notifications\NewBookingForHotelNotification;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

class ProcessBookingJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

//    public $queue = 'bookings';
    public $tries = 5;
    public $backoff = [10, 30, 60, 120, 300];
    public $timeout = 120;

    public function __construct(
        public Booking $booking
    ) {
        $this->booking->loadMissing([
            'user',
//            'timeSlot.bookingObject.room.hotel.managers'
        ]);
    }

    public function handle(): void
    {
        $booking = $this->booking;
        $hotel = $booking->timeSlot->bookingObject->room->hotel;

        // 1. Пользователю — подтверждение
//        $booking->user->notify(new BookingConfirmedNotification($booking));

        // 2. Менеджерам отеля
//        foreach ($hotel->managers as $manager) {
//            $manager->notify(new NewBookingForHotelNotification($booking));
//        }

        // 3. Статистика отеля
//        $hotel->increment('total_bookings');

        // 4. Очистка кэша доступных слотов
//        Cache::tags(["time-slots-hotel-{$hotel->id}"])->flush();
//        Cache::forget("available-slots-{$booking->timeSlot->booking_object_id}");

        // 5. Аналитика
        Log::channel('bookings_analytics')->info('booking_processed', [
            'booking_id' => $booking->id,
            'hotel_id' => $hotel->id,
            'user_id' => $booking->user_id,
            'price_per_night' => $booking->timeSlot->price ?? null,
            'duration_hours' => $booking->timeSlot->end_time->diffInHours($booking->timeSlot->start_time),
            'processed_at' => now()->toIso8601String(),
        ]);

        Log::info('Booking created successful');

//        // 6. Напоминание об отзыве через 7 дней
//        if ($booking->status === 'confirmed') {
//            \App\Jobs\SendReviewReminderJob::dispatch($booking)
//                ->delay(now()->addDays(7))
//                ->onQueue('notifications');
//        }
    }

    public function failed(\Throwable $exception): void
    {
        Log::error('ProcessBookingJob failed', [
            'booking_id' => $this->booking->id,
            'error' => $exception->getMessage(),
        ]);
    }
}
