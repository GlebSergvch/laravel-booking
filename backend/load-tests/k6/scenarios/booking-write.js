import { check, sleep } from 'k6';
import { Counter } from 'k6/metrics';
import { getToken } from '../lib/auth.js';
import { allow422, pickRandom } from '../lib/config.js';
import { resolveBookingSlotIds } from '../lib/booking.js';
import { expectedStatuses, post } from '../lib/http.js';

const validationErrors = new Counter('booking_validation_errors');

export const options = {
  scenarios: {
    booking_write: {
      executor: 'ramping-arrival-rate',
      startRate: 5,
      timeUnit: '1s',
      preAllocatedVUs: 20,
      maxVUs: 200,
      stages: [
        { target: 10, duration: '1m' },
        { target: 30, duration: '2m' },
        { target: 60, duration: '2m' },
        { target: 100, duration: '2m' },
      ],
    },
  },
  thresholds: {
    http_req_failed: ['rate<0.05'],
    http_req_duration: ['p(95)<1500'],
  },
};

export function setup() {
  const token = getToken();

  return {
    token,
    slotIds: resolveBookingSlotIds(token),
  };
}

export default function (data) {
  const timeSlotId = pickRandom(data.slotIds);
  const allowedStatuses = allow422 ? [200, 201, 422] : [200, 201];
  const response = post(
    '/api/v1/booking',
    { time_slot_id: timeSlotId },
    data.token,
    { responseCallback: expectedStatuses(...allowedStatuses) }
  );

  if (response.status === 422) {
    validationErrors.add(1);
  }

  check(response, {
    'booking status is allowed': (r) => allowedStatuses.includes(r.status),
  });

  sleep(0.2);
}
