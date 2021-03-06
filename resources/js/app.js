/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./bootstrap');

//font awesome
import { library } from '@fortawesome/fontawesome-svg-core'

import { faHatWizard } from '@fortawesome/free-solid-svg-icons'

import { FontAwesomeIcon } from '@fortawesome/vue-fontawesome'


library.add(faHatWizard)

require('./bootstrap');
import { createApp } from 'vue'

const app = createApp({
    router,
    // data(){
    //     return {
    //         gate : window.user
    //     }
    // }
})




app.component('font-awesome-icon', FontAwesomeIcon)

//router import
import { router } from "./router/index";

//store import
import { store } from "./store/index";

// /*Sweet alert start*/
import VueSweetalert2 from 'vue-sweetalert2';
import 'sweetalert2/dist/sweetalert2.min.css';

const options = {
    confirmButtonColor: '#41b882',
    cancelButtonColor: '#ff7674',
  };

app.use(VueSweetalert2, options);

// //vform

import {
    Button,
    HasError,
    AlertError,
    AlertErrors,
    AlertSuccess
  } from
  'vform/src/components/bootstrap5'
//   'vform/src/components/bootstrap4'
  // 'vform/src/components/tailwind'

  app.component(Button.name, Button)
  app.component(HasError.name, HasError)
  app.component(AlertError.name, AlertError)
  app.component(AlertErrors.name, AlertErrors)
  app.component(AlertSuccess.name, AlertSuccess)

app.component( 'not-found',require('./components/Pages/NotFoundPage.vue').default);

/*This is global filters for Vue JS*/
/*Upper case Filter*/

app.config.globalProperties.$filters = {
    upText(text) {
        return text.charAt(0).toUpperCase() + text.slice(1);
    },
}

app.config.globalProperties.$filters = {
    subStr(text) {
        return text.substring(0, 10);
    },
}

/*Moment JS to format Date*/
import moment from 'moment'; //format date in vue
app.config.globalProperties.$filters = {
    myDate(created) {
        return moment(created).format('YYYY / MM  / DD'); // April 7th 2019,(h:mm:ss a) 3:34:44 pm
    },
}
app.config.globalProperties.$filters = {
    duration(created) {
        return moment(created).toNow('UTC'); // April 7th 2019,(h:mm:ss a) 3:34:44 pm
    },
}
import VueProgressBar from "@aacassandra/vue3-progressbar";
const option = {
    color: '#008dc9',
    failedColor: '#874b4b',
    thickness: '5px',
    transition: {
      speed: '0.2s',
      opacity: '0.6s',
      termination: 300
    },
    autoRevert: true,
    location: 'top',
    inverse: false
  }

app.use(VueProgressBar, option)
/*Gate for Vue ACL in frontend*/
import Gate from "./Gate";
//global gate

import Functions from "./functions";


app.config.globalProperties.$function = new Functions(app);


/*End of ACL authontication*/
/*Start of Custom Event Listner Vue - Fires an event after a change*/
import mitt from 'mitt';
const emitter = mitt();
app.config.globalProperties.emitter = emitter;

import VCalendar from 'v-calendar';

// Use plugin with defaults
app.use(VCalendar, {})


app.use(router)
app.use(store)
import VueGates from 'vue-gates';

app.use(VueGates,{persistent: true});

app.mount('#app')
