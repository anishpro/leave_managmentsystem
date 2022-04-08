import {createRouter, createWebHistory} from 'vue-router';

import NotFoundPage from "../components/Pages/NotFoundPage.vue";
import Role from "../components/Pages/Roles.vue";
import Permission from "../components/Pages/Permissions.vue";
import Example from "../components/ExampleComponent.vue";
import User from "../components/Pages/Users.vue";

var admin_prefix = '/admin';
const routes = [
  {
    path: admin_prefix+"/roles",
    name: "roles",
    components:{
      backend : Role
    }
  },
  {
    path: admin_prefix+"/users",
    name: "users",
    components:{
      backend : User
    }
  },
  {
    path: admin_prefix+"/permissions",
    name: "permissions",
    components:{
      backend : Permission
    }
  },
  {
    path: admin_prefix+'/*',
    name: "notfound",
    components:{
      backend : NotFoundPage
    }
  },
  {
    path: admin_prefix+'/example',
    name: "example",
    components:{
      backend : Example
    }
  },

];


export const router = createRouter({
  history: createWebHistory(),
  routes,
  linkActiveClass: 'active'
});

