import http from 'k6/http';
import { baseUrl } from './config.js';

export function buildUrl(path) {
  return `${baseUrl}${path}`;
}

export function jsonHeaders(token = '') {
  const headers = {
    Accept: 'application/json',
    'Content-Type': 'application/json',
  };

  if (token) {
    headers.Authorization = `Bearer ${token}`;
  }

  return headers;
}

export function get(path, token = '', params = {}) {
  return http.get(buildUrl(path), {
    ...params,
    headers: {
      ...jsonHeaders(token),
      ...(params.headers || {}),
    },
  });
}

export function post(path, payload, token = '', params = {}) {
  return http.post(buildUrl(path), JSON.stringify(payload), {
    ...params,
    headers: {
      ...jsonHeaders(token),
      ...(params.headers || {}),
    },
  });
}

export function expectedStatuses(...statuses) {
  return http.expectedStatuses(...statuses);
}
