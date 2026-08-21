import Echo from 'laravel-echo';

import Pusher from 'pusher-js';
window.Pusher = Pusher;
window.dashboardLiveConnected = false;

window.Echo = new Echo({
    broadcaster: 'reverb',
    key: import.meta.env.VITE_REVERB_APP_KEY,
    wsHost: import.meta.env.VITE_REVERB_HOST,
    wsPort: import.meta.env.VITE_REVERB_PORT ?? 80,
    wssPort: import.meta.env.VITE_REVERB_PORT ?? 443,
    forceTLS: (import.meta.env.VITE_REVERB_SCHEME ?? 'https') === 'https',
    enabledTransports: ['ws', 'wss'],
});

window.Echo.connector.pusher.connection.bind('connected', () => {
    window.dashboardLiveConnected = true;
    window.dispatchEvent(new CustomEvent('dashboard-live-connected'));
});

window.Echo.channel('dashboard-stats').listen('.dashboard.stats.updated', (stats) => {
    window.dispatchEvent(new CustomEvent('dashboard-stats-updated', { detail: stats }));
});
