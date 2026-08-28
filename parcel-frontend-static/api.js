// Shared API helper for the Parcel Tracker static frontend.
// Talks to the Lumen backend in parcel-api, which requires a JWT bearer
// token on every /parcels route. Since this frontend has no login page yet,
// we auto-authenticate a fixed dev account and cache the token in
// localStorage so it survives page navigation and reloads.

const API_BASE = 'http://localhost:8000';
const DEV_USER = { name: 'Linda', email: 'linda@test.com', password: 'password123' };

async function authenticate() {
  let res = await fetch(`${API_BASE}/auth/login`, {
    method: 'POST',
    headers: { 'Content-Type': 'application/json' },
    body: JSON.stringify({ email: DEV_USER.email, password: DEV_USER.password }),
  });

  if (!res.ok) {
    // Account doesn't exist yet (or bad credentials) — try registering it.
    // If it already exists with a different password, this will also fail,
    // and the error below will surface that clearly.
    res = await fetch(`${API_BASE}/auth/register`, {
      method: 'POST',
      headers: { 'Content-Type': 'application/json' },
      body: JSON.stringify(DEV_USER),
    });
  }

  if (!res.ok) {
    const body = await res.json().catch(() => ({}));
    throw new Error(body.message || 'Could not authenticate with the API.');
  }

  const body = await res.json();
  const token = body.data.access_token;
  localStorage.setItem('parcel_token', token);
  return token;
}

async function getToken() {
  const cached = localStorage.getItem('parcel_token');
  return cached || authenticate();
}

// Wrapper around fetch that attaches the bearer token and, if the token
// turns out to be expired/invalid (401), re-authenticates once and retries.
async function apiFetch(path, options = {}) {
  let token = await getToken();

  const doFetch = (tok) => fetch(`${API_BASE}${path}`, {
    ...options,
    headers: {
      'Content-Type': 'application/json',
      Authorization: `Bearer ${tok}`,
      ...(options.headers || {}),
    },
  });

  let res = await doFetch(token);

  if (res.status === 401) {
    localStorage.removeItem('parcel_token');
    token = await authenticate();
    res = await doFetch(token);
  }

  return res;
}
