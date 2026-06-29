export const baseUrl = __ENV.K6_BASE_URL || 'http://nginx';
export const userName = __ENV.K6_USER_NAME || 'Load Test';
export const email = __ENV.K6_EMAIL || 'loadtest@example.com';
export const password = __ENV.K6_PASSWORD || 'secret123';
export const providedToken = __ENV.K6_TOKEN || '';
export const allow422 = String(__ENV.K6_ALLOW_422 || 'false').toLowerCase() === 'true';

export function requireEnv(name) {
  const value = __ENV[name];

  if (!value) {
    throw new Error(`Missing required env var: ${name}`);
  }

  return value;
}

export function parseIntegerList(value) {
  if (!value) {
    return [];
  }

  return String(value)
    .split(',')
    .map((item) => Number.parseInt(item.trim(), 10))
    .filter((item) => Number.isInteger(item) && item > 0);
}

export function pickRandom(items) {
  if (!items.length) {
    throw new Error('No items available to pick from');
  }

  return items[Math.floor(Math.random() * items.length)];
}
