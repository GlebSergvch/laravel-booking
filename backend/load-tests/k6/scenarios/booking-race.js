import { check } from 'k6';
import { Counter } from 'k6/metrics';
import { getToken } from '../lib/auth.js';
import { resolveRaceSlotId } from '../lib/booking.js';
import { expectedStatuses, post } from '../lib/http.js';

const duplicateLikeResponses = new Counter('booking_race_validation_errors');

export const options = {
  scenarios: {
    booking_race: {
      executor: 'per-vu-iterations',
      vus: 50,
      iterations: 1,
      maxDuration: '2m',
    },
  },
  thresholds: {
    http_req_failed: ['rate<0.2'],
    http_req_duration: ['p(95)<1500'],
  },
};

export function setup() {
  const token = getToken();

  return {
    token,
    timeSlotId: resolveRaceSlotId(token),
  };
}

export default function (data) {
  const response = post(
    '/api/v1/booking',
    { time_slot_id: data.timeSlotId },
    data.token,
    { responseCallback: expectedStatuses(200, 201, 422) }
  );

  if (response.status === 422) {
    duplicateLikeResponses.add(1);
  }

  check(response, {
    'race status is 200/201/422': (r) => [200, 201, 422].includes(r.status),
  });
}
