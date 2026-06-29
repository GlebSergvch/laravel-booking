# k6 Load Tests

Этот набор сценариев рассчитан на текущий Laravel API в этом репозитории.

## Что покрыто

- `smoke.js`: базовая проверка, что авторизация и защищенный `GET` вообще работают.
- `read-catalog.js`: чтение каталогов `hotel`, `booking-objects`, `time-slot`.
- `booking-write.js`: постепенное наращивание write-нагрузки на `POST /api/v1/booking`.
- `booking-race.js`: конкурентная атака в один и тот же `time_slot_id` для проверки гонок и двойного бронирования.

## Ограничение текущего проекта

Публичный маршрут бронирования сейчас не нагружает RabbitMQ напрямую.
В [app/Services/BookingService.php](../../app/Services/BookingService.php) job диспатчится в `updateStatus()`, но этот метод не открыт маршрутом в `routes/api/booking.php`.

Это значит:

- HTTP write-нагрузка проверяет Laravel + PostgreSQL + блокировки данных.
- Очереди RabbitMQ в этом наборе тестируются только косвенно, через наблюдение за системой, а не через прямой публичный endpoint.

## Подготовка

1. Поднимите проект:

```bash
docker compose up -d
```

2. Убедитесь, что есть пользователь для логина.

Если пользователя нет, можно создать его через API:

```bash
curl -X POST http://localhost/api/v1/register \
  -H 'Content-Type: application/json' \
  -H 'Accept: application/json' \
  -d '{"name":"Load Test","email":"loadtest@example.com","password":"secret123"}'
```

3. Подготовьте доступные `time_slot_id` для сценариев записи.

Если `K6_TIME_SLOT_ID` или `K6_TIME_SLOT_IDS` не заданы, сценарии попытаются сами найти свободные слоты через `/api/v1/time-slot`.
Если свободных слотов нет, write/race завершатся с ошибкой.

4. Загрузите переменные окружения:

```bash
set -a
source load-tests/k6/env.example
set +a
```

При необходимости замените значения в окружении перед запуском:

```bash
export K6_BASE_URL="http://nginx"
export K6_EMAIL="loadtest@example.com"
export K6_PASSWORD="secret123"
export K6_TIME_SLOT_IDS="10,11,12,13,14"
export K6_TIME_SLOT_ID="10"
export K6_ALLOW_422="true"
```

Если не хотите логиниться на каждый прогон, можно сразу передать токен:

```bash
export K6_TOKEN="your_bearer_token"
```

По умолчанию сценарии используют `http://nginx`, это подходит для запуска из php-контейнера.
Если запускаете `k6` с хоста, обычно нужно явно указать `K6_BASE_URL=http://localhost`.

## Установка k6

Если `k6` не установлен, проверьте официальную установку для вашей ОС:

```bash
k6 version
```

## Запуск

Через `npm`-скрипты:

```bash
npm run load:smoke
npm run load:read
npm run load:booking
npm run load:race
```

Или напрямую:

```bash
k6 run load-tests/k6/scenarios/smoke.js
k6 run load-tests/k6/scenarios/read-catalog.js
k6 run load-tests/k6/scenarios/booking-write.js
k6 run load-tests/k6/scenarios/booking-race.js
```

## Как интерпретировать

### 1. Smoke

Цель: убедиться, что токен, маршруты и базовый ответ API корректны.

Если падает `smoke.js`, дальше в stress идти рано.

### 2. Read

Нормальная отправная точка для локального Docker-окружения:

- `50-100 RPS` уже полезны.
- `100-200 RPS` это хороший стресс для чтения.

Если на чтении уже высокий `p95`, искать надо в PHP-FPM, Nginx, базе и Docker-ресурсах.

### 3. Booking Write

Рекомендуемая лестница для этого проекта:

- `10-30 RPS`: базовая рабочая нагрузка.
- `50-100 RPS`: заметная нагрузка на запись.
- `200+ RPS`: агрессивный тест для локального стенда.

`100000 RPS` для этого проекта и такого окружения не имеет практического смысла.

### 4. Race

Это самый важный сценарий для бронирования.

Он шлет 50 одновременных запросов в один `time_slot_id`.
Ожидаемо:

- один запрос должен пройти успешно;
- остальные должны получить отказ, обычно `422`.

Если после теста в базе появляется несколько бронирований на один слот, значит в логике бронирования есть гонка.

## Что смотреть во время теста

- `docker compose ps`
- `docker stats`
- RabbitMQ UI: `http://localhost:15672`
- Nginx/API: `http://localhost`
- PostgreSQL health

## Практический порядок прогонов

1. `smoke.js`
2. `read-catalog.js`
3. `booking-write.js` на небольшом пуле слотов
4. `booking-race.js` на одном свободном слоте

## Важный риск в текущей реализации

Валидация и запись брони разнесены по разным запросам к базе:

- проверка доступности слота идет в `BookingRequest`
- сохранение брони и смена `is_available` идут позже в `BookingService`

Без явной блокировки строки слота или атомарного условия обновления это уязвимо к race condition под конкурентной нагрузкой.
