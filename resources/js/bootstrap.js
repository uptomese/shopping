window._ = require("lodash");

/**
 * We'll load jQuery and the Bootstrap jQuery plugin which provides support
 * for JavaScript based Bootstrap features such as modals and tabs. This
 * code may be modified to fit the specific needs of your application.
 */

try {
    window.Popper = require("popper.js").default;
    window.$ = window.jQuery = require("jquery");

    require("bootstrap");
} catch (e) {}

/**
 * We'll load the axios HTTP library which allows us to easily issue requests
 * to our Laravel back-end. This library automatically handles sending the
 * CSRF token as a header based on the value of the "XSRF" token cookie.
 */

window.axios = require("axios");

window.axios.defaults.headers.common["X-Requested-With"] = "XMLHttpRequest";

/**
 * Echo exposes an expressive API for subscribing to channels and listening
 * for events that are broadcast by Laravel. Echo and event broadcasting
 * allows your team to easily build robust real-time web applications.
 */

// import Echo from 'laravel-echo';
import Vue from "vue";
import VueChatScroll from "vue-chat-scroll";
import VueImg from "v-img";

const moment = require("moment");
require("moment/locale/th");

Vue.use(VueChatScroll);
Vue.use(require("vue-moment"), {
    moment
});
Vue.use(VueImg);

// window.Pusher = require('pusher-js');

// window.Echo = new Echo({
//     broadcaster: 'pusher',
//     key: '9ddb8cc60550f9a254c6',
//     cluster: 'ap2',
//     forceTLS: true
// });

//install
//npm install
//npm install --save vue-chat-scroll
//composer require intervention/image
//npm install v-img --save
//npm install vue-moment
//npm i socket.io
//npm i vue-socket.io
//npm install serve-favicon
//npm install ejs
//npm install body-parser
//npm install express
//npm install --save vue-chartjs 
//npm install chart.js --save
