import { check, sleep } from 'k6';
import { getToken } from '../lib/auth.js';
import { get } from '../lib/http.js';

export const options = {
  scenarios: {
    read_catalog: {
      executor: 'ramping-arrival-rate',
      startRate: 20,
      timeUnit: '1s',
      preAllocatedVUs: 30,
      maxVUs: 150,
      stages: [
        { target: 50, duration: '1m' },
        { target: 100, duration: '2m' },
        { target: 200, duration: '2m' },
      ],
    },
  },
  thresholds: {
    http_req_failed: ['rate<0.02'],
    http_req_duration: ['p(95)<800'],
  },
};

const catalogPaths = [
  '/api/v1/hotel',
  '/api/v1/booking-objects',
  '/api/v1/time-slot',
];

export function setup() {
  return {
    token: getToken(),
  };
}

export default function (data) {
  const path = catalogPaths[Math.floor(Math.random() * catalogPaths.length)];
  const response = get(path, data.token);

  check(response, {
    'catalog status is 200': (r) => r.status === 200,
  });

  sleep(0.2);
}
