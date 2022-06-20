import {createRouter, createWebHistory} from 'vue-router';

import NotFoundPage from "../components/Pages/NotFoundPage.vue";
import Role from "../components/Pages/Roles.vue";
import Permission from "../components/Pages/Permissions.vue";
import Example from "../components/ExampleComponent.vue";
import User from "../components/Pages/Users.vue";
import Profile from "../components/Pages/Profile.vue";
import Group from "../components/Pages/Group.vue";
import LeaveType from "../components/Pages/LeaveType.vue";

import Dashboard from "../components/Dashboard.vue";

var admin_prefix = '/admin';
const routes = [
  {
    path: '/dashboard',
    name: "dashboard",
    components:{
      backend : Dashboard
    }
  },
  {
    path: admin_prefix+"/profile",
    name: "profile",
    components:{
      backend : Profile
    }
  },
  {
    path: admin_prefix+"/groups",
    name: "groups",
    components:{
      backend : Group
    }
  },
  {
    path: admin_prefix+"/leave-types",
    name: "leave-types",
    components:{
      backend : LeaveType
    }
  },

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

