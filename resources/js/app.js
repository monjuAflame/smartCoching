/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./bootstrap');
window.Vue = require('vue');

// vuex
import Vuex from 'vuex';
import storeVuex from './store/chatStore.js';
Vue.use(Vuex)
const store = new Vuex.Store(storeVuex)

//Gate js
import Gate from './Gate';
Vue.prototype.$gate = new Gate(window.user);

// moment js 
import moment from 'moment';
Vue.prototype.moment = moment;
window.moment = moment;

// vue progressbar
import VueProgressBar from 'vue-progressbar'
Vue.use(VueProgressBar, {
  color: 'rgb(143, 255, 199)',
  failedColor: 'red',
  height: '3px'
})

// sweet alert
import Swal from 'sweetalert2'
window.Swal = Swal;

const Toast = Swal.mixin({
  toast: true,
  position: 'top-end',
  showConfirmButton: false,
  timer: 3000,
  timerProgressBar: true
})

window.Toast = Toast;

// vue chat scroll
import VueChatScroll from 'vue-chat-scroll'
Vue.use(VueChatScroll)

// v-form (npm i axios vform) 
import { Form, HasError, AlertError } from 'vform';

window.Form = Form;
Vue.component(HasError.name, HasError)
Vue.component(AlertError.name, AlertError)

// vueRouter
import VueRouter from 'vue-router'
Vue.use(VueRouter)

//Route 
import {routes} from "./routes";
window.routes = routes;
const router = new VueRouter({
	mode: 'history',
	routes
})



// vue filter
Vue.filter('upText', function(text) {
  return text.charAt(0).toUpperCase() + text.slice(1)
  // return text.toUpperCase();
})
Vue.filter('shortenTaxt', function(text, sign='...') {
  return text.slice(0,15) + sign;
})
Vue.filter('parcentage', function(value, sign=' %') {
  let multi = value*100;
  return  multi.toFixed(2) + sign;
})
Vue.filter('myDate', function(date) {
  return moment(date).format('MMMM-D-YYYY')
})
Vue.filter('dateTime', function(date) {
  return moment(date).format('D MMMM YYYY - H:mm')
})
Vue.filter('trsansactDate', function(date) {
  return moment(date).format('MMMM-D-YYYY h:m:s')
})
Vue.filter('currency', function (value, sign = '$ ') {
  if (!value) return 0;
  return sign + (value).toFixed( 2 );
})

Vue.filter('reableTime', function(date) {
  return moment(date).fromNow();
})
// flash msg
$(".alert.flash").fadeTo(5000,500).slideUp(500, function(){
  $(".alert.flash").slideUp(500);
});

// Fire for load data( like laravel echo ## for an event)

let Fire = new Vue;
window.Fire = Fire;

//  laravel vue pagination
Vue.component('pagination', require('laravel-vue-pagination'));

// passport
Vue.component(
    'passport-clients',
    require('./components/passport/Clients.vue').default
);

Vue.component(
    'passport-authorized-clients',
    require('./components/passport/AuthorizedClients.vue').default
);

Vue.component(
    'passport-personal-access-tokens',
    require('./components/passport/PersonalAccessTokens.vue').default
);
Vue.component(
    'not-found',
    require('./components/NotFound.vue').default
);
Vue.component('example-component', require('./components/ExampleComponent.vue').default);


const app = new Vue({
    el: '#app',
    router,
    store,
    data:{
      search: ''
    },
    methods:{
      searchit: _.debounce(()=>{
        Fire.$emit('searcing');
      }, 2000),
    }
});
