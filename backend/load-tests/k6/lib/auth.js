import { check } from 'k6';
import { email, password, providedToken, userName } from './config.js';
import { post } from './http.js';

function login() {
  return post('/api/v1/login', {
    email,
    password,
  });
}

function register() {
  return post('/api/v1/register', {
    name: userName,
    email,
    password,
  });
}

export function getToken() {
  if (providedToken) {
    return providedToken;
  }

  let response = login();

  if (response.status === 422 && String(response.body).includes('Invalid credentials')) {
    const registerResponse = register();

    check(registerResponse, {
      'register status is 201/422': (r) => [201, 422].includes(r.status),
    });

    response = login();
  }

  check(response, {
    'login status is 200': (r) => r.status === 200,
    'login token exists': (r) => Boolean(r.json('access_token')),
  });

  const token = response.json('access_token');

  if (!token) {
    throw new Error(`Login failed: ${response.status} ${response.body}`);
  }

  return token;
}
