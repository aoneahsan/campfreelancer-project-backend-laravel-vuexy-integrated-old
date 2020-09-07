// window._ = require('lodash');

/**
 * We'll load jQuery and the Bootstrap jQuery plugin which provides support
 * for JavaScript based Bootstrap features such as modals and tabs. This
 * code may be modified to fit the specific needs of your application.
 */

// try {
//     window.Popper = require('popper.js').default;
//     window.$ = window.jQuery = require('jquery');

//     require('bootstrap');
// } catch (e) { }

/**
 * We'll load the axios HTTP library which allows us to easily issue requests
 * to our Laravel back-end. This library automatically handles sending the
 * CSRF token as a header based on the value of the "XSRF" token cookie.
 */

// window.axios = require('axios');

// window.axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';

/**
 * Echo exposes an expressive API for subscribing to channels and listening
 * for events that are broadcast by Laravel. Echo and event broadcasting
 * allows your team to easily build robust real-time web applications.
 */

// import Echo from 'laravel-echo';

// window.Pusher = require('pusher-js');

// let WS_HOST = window.location.hostname;

// if (process.env.MIN_APP_ENV == 'local') {
//     WS_HOST = '127.0.0.1';
//     alert("Local Envoriment ");
//     console.log("Local Envoriment Working, WS_HOST = ", WS_HOST);
// }
// else if (process.env.MIN_APP_ENV == 'production') {
//     alert("Production Envoriment ");
//     console.log("Production Envoriment Working, WS_HOST = ", WS_HOST);
//     WS_HOST = 'api.campfreelancer.com';
// }
// else {
//     alert("Production Envoriment ");

//     console.log("Other than Local || Production Envoriment Working, WS_HOST = ", WS_HOST);
// }
// alert("Production Envoriment ");

// if (process.env.MIN_WEBSOCKET_ENV_MODE == 'local') {
//     window.Echo = new Echo({
//         broadcaster: process.env.MIN_BROADCAST_DRIVER,
//         key: process.env.MIN_PUSHER_APP_KEY,
//         encrypted: false,
//         wsHost: process.env.MIN_WEBSOCKET_HOST_URL,
//         wsPort: 6001,
//         disableStats: false,
//         forceTLS: false,
//         enabledTransports: ['ws']
//     });
// } else if (process.env.MIN_WEBSOCKET_ENV_MODE == 'production') {
//     window.Echo = new Echo({
//         broadcaster: process.env.MIN_BROADCAST_DRIVER,
//         key: process.env.MIN_PUSHER_APP_KEY,
//         encrypted: true,
//         wsHost: process.env.MIN_WEBSOCKET_HOST_URL,
//         wsPort: 6001,
//         disableStats: false,
//         forceTLS: false,
//         enabledTransports: ['ws']
//     });
// }

// window.Echo.channel('testWebsockets').listen('WebsocketDemoEvent', (e) => {
//     console.log("Real Time Websockets Working event data = ", e);
// });

// to test websockets use to through a custom event
// testWebsockets
// App\Events\WebsocketDemoEvent
// [{"somedata": "asadasd"}]
