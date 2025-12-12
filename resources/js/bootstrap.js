import 'bootstrap';

/**
 * We'll load the axios HTTP library which allows us to easily issue requests
 * to our Laravel back-end. This library automatically handles sending the
 * CSRF token as a header based on the value of the "XSRF" token cookie.
 */

import axios from 'axios';
window.axios = axios;

window.axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';

// Global response interceptor: reload page after successful non-GET actions
axios.interceptors.response.use(function(response) {
    try {
        // Only reload when response came from a mutating request (POST/PUT/PATCH/DELETE)
        const method = (response.config && response.config.method) ? response.config.method.toLowerCase() : 'get';
        // Allow opt-out by setting header 'X-No-Reload' on specific requests
        const noReload = response.config && response.config.headers && response.config.headers['X-No-Reload'];
        if (!noReload && ['post', 'put', 'patch', 'delete'].includes(method)) {
            // If server explicitly returns { reload: false } then skip
            if (response.data && typeof response.data.reload !== 'undefined') {
                if (response.data.reload) {
                    window.location.reload();
                }
            } else {
                // Default behaviour: reload after successful mutating request
                window.location.reload();
            }
        }
    } catch (e) {
        // fail silently
    }
    return response;
}, function(error) {
    return Promise.reject(error);
});

/**
 * Echo exposes an expressive API for subscribing to channels and listening
 * for events that are broadcast by Laravel. Echo and event broadcasting
 * allows your team to easily build robust real-time web applications.
 */

// import Echo from 'laravel-echo';

// import Pusher from 'pusher-js';
// window.Pusher = Pusher;

// window.Echo = new Echo({
//     broadcaster: 'pusher',
//     key: import.meta.env.VITE_PUSHER_APP_KEY,
//     cluster: import.meta.env.VITE_PUSHER_APP_CLUSTER ?? 'mt1',
//     wsHost: import.meta.env.VITE_PUSHER_HOST ?? `ws-${import.meta.env.VITE_PUSHER_APP_CLUSTER}.pusher.com`,
//     wsPort: import.meta.env.VITE_PUSHER_PORT ?? 80,
//     wssPort: import.meta.env.VITE_PUSHER_PORT ?? 443,
//     forceTLS: (import.meta.env.VITE_PUSHER_SCHEME ?? 'https') === 'https',
//     enabledTransports: ['ws', 'wss'],
// });