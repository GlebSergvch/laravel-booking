import { check, sleep } from 'k6';
import { getToken } from '../lib/auth.js';
import { get } from '../lib/http.js';

export const options = {
  vus: 1,
  iterations: 5,
  thresholds: {
    http_req_failed: ['rate<0.01'],
    http_req_duration: ['p(95)<1000'],
  },
};

export function setup() {
  return {
    token: getToken(),
  };
}

export default function (data) {
  const response = get('/api/v1/time-slot', data.token);

  check(response, {
    'time-slot smoke status is 200': (r) => r.status === 200,
  });

  sleep(1);
}
