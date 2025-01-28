<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Таблица отелей
        Schema::create('hotels', function (Blueprint $table) {
            $table->id(); // Уникальный идентификатор отеля
            $table->string('name'); // Название отеля
            $table->string('address')->nullable(); // Адрес отеля
            $table->string('city'); // Город
            $table->string('country'); // Страна
            $table->timestamps(); // Даты создания и обновления
        });

        // Таблица номеров
        Schema::create('rooms', function (Blueprint $table) {
            $table->id(); // Уникальный идентификатор номера
            $table->foreignId('hotel_id')->constrained('hotels')->onDelete('cascade'); // ID отеля
            $table->string('name'); // Название или номер комнаты
            $table->integer('capacity'); // Вместимость (кол-во человек)
            $table->decimal('price_per_night', 8, 2); // Цена за ночь
            $table->timestamps(); // Даты создания и обновления
        });

        // Таблица опций
        Schema::create('options', function (Blueprint $table) {
            $table->id(); // Уникальный идентификатор опции
            $table->string('name'); // Название опции (например, Wi-Fi, Завтрак)
            $table->timestamps(); // Даты создания и обновления
        });

        // Таблица связи номеров и опций (многие ко многим)
        Schema::create('room_option', function (Blueprint $table) {
            $table->id(); // Уникальный идентификатор записи
            $table->foreignId('room_id')->constrained('rooms')->onDelete('cascade'); // ID номера
            $table->foreignId('option_id')->constrained('options')->onDelete('cascade'); // ID опции
            $table->timestamps(); // Даты создания и обновления
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('room_option');
        Schema::dropIfExists('options');
        Schema::dropIfExists('rooms');
        Schema::dropIfExists('hotels');
    }
};
