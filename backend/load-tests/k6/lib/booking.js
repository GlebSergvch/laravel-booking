import { check } from 'k6';
import { parseIntegerList, pickRandom, requireEnv } from './config.js';
import { get, post } from './http.js';

function getBookingObjectPage(token) {
  const response = get('/api/v1/booking-objects?per_page=100', token);

  check(response, {
    'booking-object list status is 200': (r) => r.status === 200,
  });

  if (response.status !== 200) {
    throw new Error(`Could not load booking objects: ${response.status} ${response.body}`);
  }

  return response.json('body.data') || [];
}

function getTimeSlotPage(token) {
  const response = get('/api/v1/time-slot?per_page=100', token);

  check(response, {
    'time-slot list status is 200': (r) => r.status === 200,
  });

  if (response.status !== 200) {
    throw new Error(`Could not load time slots: ${response.status} ${response.body}`);
  }

  return response.json('body.data') || [];
}

function findAvailableSlotIds(token) {
  return getTimeSlotPage(token)
    .filter((slot) => slot && slot.is_available === true && Number.isInteger(slot.id))
    .map((slot) => slot.id);
}

function createFutureSlotPayload(bookingObjectId, offsetHours) {
  const start = new Date(Date.now() + offsetHours * 60 * 60 * 1000);
  const end = new Date(start.getTime() + 60 * 60 * 1000);

  return {
    booking_object_id: bookingObjectId,
    start_time: start.toISOString(),
    end_time: end.toISOString(),
    is_available: true,
  };
}

function findNextSlotStart(token, bookingObjectId) {
  const slots = getTimeSlotPage(token)
    .filter((slot) => slot && slot.booking_object_id === bookingObjectId && slot.end_time)
    .map((slot) => new Date(slot.end_time).getTime())
    .filter((value) => Number.isFinite(value));

  const latestEndTime = slots.length ? Math.max(...slots) : Date.now() + 24 * 60 * 60 * 1000;

  return new Date(latestEndTime + 60 * 60 * 1000);
}

function createSequentialSlotPayload(bookingObjectId, startDate, index) {
  const start = new Date(startDate.getTime() + index * 2 * 60 * 60 * 1000);
  const end = new Date(start.getTime() + 60 * 60 * 1000);

  return {
    booking_object_id: bookingObjectId,
    start_time: start.toISOString(),
    end_time: end.toISOString(),
    is_available: true,
  };
}

function createTimeSlots(token, count = 1) {
  const bookingObjects = getBookingObjectPage(token);
  const bookingObjectId = bookingObjects[0]?.id;

  if (!bookingObjectId) {
    throw new Error(
      'No booking objects found. Create at least one booking object or set K6_TIME_SLOT_ID/K6_TIME_SLOT_IDS explicitly.'
    );
  }

  const createdSlotIds = [];
  const initialStart = findNextSlotStart(token, bookingObjectId);

  for (let index = 0; index < count; index += 1) {
    let response;

    for (let attempt = 0; attempt < 10; attempt += 1) {
      response = post(
        '/api/v1/time-slot',
        createSequentialSlotPayload(bookingObjectId, initialStart, index + attempt),
        token
      );

      check(response, {
        'time-slot create status is 200/422': (r) => [200, 422].includes(r.status),
      });

      if (response.status === 200) {
        break;
      }

      if (response.status !== 422 || !String(response.body).includes('overlaps')) {
        throw new Error(`Could not create time slot: ${response.status} ${response.body}`);
      }
    }

    if (!response || response.status !== 200) {
      throw new Error('Could not create a non-overlapping time slot after multiple attempts');
    }

    const slotId = response.json('body.id');

    if (!slotId) {
      throw new Error(`Time slot was created but response did not include an id: ${response.body}`);
    }

    createdSlotIds.push(slotId);
  }

  return createdSlotIds;
}

export function resolveBookingSlotIds(token) {
  const configuredIds = parseIntegerList(__ENV.K6_TIME_SLOT_IDS || __ENV.K6_TIME_SLOT_ID || '');

  if (configuredIds.length) {
    return configuredIds;
  }

  let discoveredIds = findAvailableSlotIds(token);

  if (!discoveredIds.length) {
    discoveredIds = createTimeSlots(token, 5);
  }

  return discoveredIds;
}

export function resolveRaceSlotId(token) {
  const configuredId = __ENV.K6_TIME_SLOT_ID;

  if (configuredId) {
    return Number.parseInt(requireEnv('K6_TIME_SLOT_ID'), 10);
  }

  return pickRandom(resolveBookingSlotIds(token));
}
