// imports
require('./bootstrap');
window.Vue = require('vue');
import moment from 'moment';
import router from './router';
import Vuex from 'vuex';
import { store } from './store/store';
import { mapState } from 'vuex';
import { ToggleButton } from "vue-js-toggle-button";
import excel from 'vue-excel-export'
import VueFormWizard from 'vue-form-wizard'
import 'vue-form-wizard/dist/vue-form-wizard.min.css'
import VueCountdownTimer from 'vuejs-countdown-timer'
import { Datetime } from 'vue-datetime';
import VuePdfReader from 'vue-pdf-reader';
import tinymce from 'vue-tinymce-editor'
import VueImageLoader from '@kevindesousa/vue-image-loader'
import VueChatScroll from 'vue-chat-scroll'
import VueCoreVideoPlayer from 'vue-core-video-player'

Vue.use(VueCoreVideoPlayer)
Vue.use(VueChatScroll)
Vue.use(VueImageLoader)
Vue.component('tinymce', tinymce)
Vue.use(VuePdfReader);
Vue.component('datetime', Datetime);
Vue.use(VueCountdownTimer)

Vue.use(VueFormWizard)


// Import stylesheet


import VueVideoPlayer from 'vue-video-player'
Vue.use(VueVideoPlayer);


Vue.use(excel)
Vue.use(Vuex)
Vue.component("ToggleButton", ToggleButton);
Vue.config.productionTip = false

import ToggleSwitch from 'vuejs-toggle-switch'
Vue.use(ToggleSwitch)

import { Form, HasError, AlertError } from 'vform';
import Chartkick from 'vue-chartkick'
import Chart from 'chart.js'
import { VueCsvImport } from 'vue-csv-import';
// import {
//     PdfViewerPlugin, Toolbar, Magnification,
//     Navigation, LinkAnnotation, BookmarkView, ThumbnailView,
//     Print, TextSelection, TextSearch
// } from '@syncfusion/ej2-vue-pdfviewer';
// Vue.use(PdfViewerPlugin);
Vue.use(Chartkick.use(Chart));
//full calende
import App from './App.vue'
import Fullcalendar from "@fullcalendar/vue";
import DaygridPlugin from "@fullcalendar/daygrid";
import TimegridPlugin from "@fullcalendar/timegrid";
import InteractionPlugin from "@fullcalendar/interaction";
import ListPlugin from "@fullcalendar/list";
Fullcalendar.plugins = (
    {
        DaygridPlugin,
        TimegridPlugin
        , InteractionPlugin,
        ListPlugin
    })

Vue.component(Fullcalendar)
// Gate
import Gate from "./Gate";
Vue.prototype.$gate = new Gate(window.user);
// import  ResultConfig  from "./ResultConfig";



// ProgressBar
import VueProgressBar from 'vue-progressbar'
Vue.use(VueProgressBar, {
    color: 'rgb(143, 255, 199)',
    failedColor: 'red',
    height: '3px',
    transition: {
        speed: '0.2s',
        opacity: '0.6s',
        termination: 3000
      },
   autoRevert: true,
  inverse: false
})

// Pagination
Vue.component('pagination', require('laravel-vue-pagination'));

// Vform for handlinng forms
window.Form = Form;
Vue.component(HasError.name, HasError)
Vue.component(AlertError.name, AlertError)
Vue.component(VueCsvImport)

// sweet Alert

import swal from 'sweetalert2'
import Axios from 'axios';
window.swal = swal;

const toast = swal.mixin({
    toast: true,
    position: 'top-end',
    showConfirmButton: false,
    timer: 3000
});

window.toast = toast;

// Routes registration/Defination


Vue.component(
    'not-found',
    require('./components/NotFound.vue').default
);
Vue.component(
    'profile',
    require('./components/Profile.vue').default
);
Vue.component(
    'assignment',
    require('./components/assignments/Assignment.vue').default
);
Vue.component(
    'lesson-notes',
    require('./components/assignments/Lesson_note.vue').default
);
Vue.component(
    'first-second-term',
    require('./components/results/First_second_term.vue').default
);
Vue.component(
    'grading',
    require('./components/results/Grading.vue').default
);
Vue.component(
    'side-menu',
    require('./App.vue').default
);
Vue.component(
    'header-view',
    require('./components/layout/Header.vue').default
);
Vue.component(
    'menutogglebtn',
    require('./components/layout/MenuToggleBtn.vue').default
);
Vue.component(
    'timer',
    require('./components/cbt/Timer.vue').default
);
Vue.component(
    'student-assignment',
    require('./components/assignments/StudentAssignment.vue').default
);
Vue.component(
    'chats',
    require('./components/messaging/Chats.vue').default
);



// Vue Router


// formating using moment
Vue.filter('upText', function (text) {
    return text.charAt(0).toUpperCase() + text.slice(1)
});

Vue.filter('myDate', function (created) {
    return moment(created).format('MMMM Do YYYY');
});

Vue.filter('myDateTime', function (input_date) {
    return moment(input_date).format('MMMM Do YYYY h:mm:ss a')
});

// Initializing Vue

window.Fire = new Vue();
//window.bus = new Vue();

// new Vue({
//     router,
//     store: store,
//     data: {
//         search: '',
//         message: 'wecome',

//     },
//     computed: mapState([ 'school' ]),
//     created() {
//         console.log(this.school.name)

//     },
//     methods: {
//         searchit: _.debounce(() => {
//             Fire.$emit('searching');
//         }, 1000),

//         printme() {
//             window.print();
//         },


//     },
//     render: h => h(App)

// }).$mount('#app')


const app = new Vue({
    el: '#app',
    router,
    store: store,
    data: {

        search: '',
        message: 'weledrcome',
        level_id:''


    },


    computed: mapState([ 'school' ]),



    methods: {
        searchit: _.debounce(() => {
            Fire.$emit('searching');
        }, 1000),

        printme() {
            window.print();
        },


    },
    created() {



    }



});
