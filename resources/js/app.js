/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./bootstrap');

import router from "./router/index";


window.Vue = require('vue').default;
/*Start of Progress Bar*/
// Progress bar include
import VueProgressBar from 'vue-progressbar';
/*Define Options for progress bar*/
const options = {
    color: '#bffaf3',
    failedColor: '#874b4b',
    thickness: '8px',
    transition: {
        speed: '0.2s',
        opacity: '0.6s',
        termination: 300
    },
    autoRevert: true,
    location: 'top',
    inverse: false
};
/*End of defination*/
/*Call the progress bar*/
Vue.use(VueProgressBar, options)
/*End of Progress Bar*/

/*Sweet alert start*/

import Swal from "sweetalert2";
window.swal = Swal;

const Toast = Swal.mixin({
    toast: true,
    position: 'top-end',
    showConfirmButton: false,
    timer: 3000
});

window.Toast = Toast;

//vform
import Vue from 'vue'
import { Form, HasError, AlertError } from 'vform'
window.Form = Form;

Vue.component(HasError.name, HasError)
Vue.component(AlertError.name, AlertError)

Vue.component('pagination', require('laravel-vue-pagination'));

Vue.component( 'not-found',require('./components/Pages/NotFoundPage.vue').default
);

/*This is global filters for Vue JS*/
/*Upper case Filter*/
Vue.filter('upText', function(text){
    return text.charAt(0).toUpperCase() + text.slice(1);
});

Vue.filter('subStr', function(text){
    return text.substring(0, 10);
});

/*Moment JS to format Date*/
import moment from 'moment'; //format date in vue

Vue.filter('myDate', function(created){
    return moment(created).format('YYYY / MM  / DD'); // April 7th 2019,(h:mm:ss a) 3:34:44 pm
});
Vue.filter('duration', function(created){
    return moment(created).toNow('UTC'); // April 7th 2019,(h:mm:ss a) 3:34:44 pm
});



/*Start of Custom Event Listner Vue - Fires an event after a change*/
let Fire = new Vue();
window.Fire = Fire;

/*End of Custom event listner*/

/*Gate for Vue ACL in frontend*/
import Gate from "./Gate";
Vue.prototype.$gate = new Gate(window.user);

/*End of ACL authontication*/

const app = new Vue({
    el: '#app',
    router,
    data:{
        search:'',
    },
    methods:{
        searchit: _.debounce(() =>{
            Fire.$emit('searching');
        },1000) 
    }
});
