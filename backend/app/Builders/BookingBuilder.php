<?php
// app/Builders/BookingBuilder.php

namespace App\Builders;

use App\Models\Booking;
use App\Models\TimeSlot;
use InvalidArgumentException;

class BookingBuilder
{
    private ?int $userId = null;
    private ?int $timeSlotId = null;
    private string $status = 'pending';
    private ?int $createdBy = null;
    private ?int $updatedBy = null;

    public function setUserId(int $userId): self
    {
        $this->userId = $userId;
        return $this;
    }

    public function setTimeSlotId(int $timeSlotId): self
    {
        $this->timeSlotId = $timeSlotId;
        return $this;
    }

    public function setStatus(string $status): self
    {
        $validStatuses = ['pending', 'confirmed', 'cancelled'];
        if (!in_array($status, $validStatuses)) {
            throw new InvalidArgumentException("Status must be one of: " . implode(', ', $validStatuses));
        }
        $this->status = $status;
        return $this;
    }

    public function setCreatedBy(int $createdBy): self
    {
        $this->createdBy = $createdBy;
        return $this;
    }

    public function setUpdatedBy(int $updatedBy): self
    {
        $this->updatedBy = $updatedBy;
        return $this;
    }

    public function validateAvailability(): self
    {
        if (!$this->timeSlotId) {
            throw new InvalidArgumentException('Time slot ID is required for availability check');
        }

        $timeSlot = TimeSlot::findOrFail($this->timeSlotId);

        if (!$timeSlot->is_available) {
            throw new InvalidArgumentException('Time slot is not available');
        }

        $existingBooking = Booking::where('time_slot_id', $this->timeSlotId)->first();
        if ($existingBooking) {
            throw new InvalidArgumentException('Time slot is already booked');
        }

        return $this;
    }

    public function build(): Booking
    {
        if (!$this->userId || !$this->timeSlotId) {
            throw new InvalidArgumentException('User ID and Time Slot ID are required');
        }

        $this->validateAvailability();

        return Booking::query()->make([
            'user_id' => $this->userId,
            'time_slot_id' => $this->timeSlotId,
            'status' => $this->status,
            'created_by' => $this->createdBy,
            'updated_by' => $this->updatedBy,
        ]);
    }
}
