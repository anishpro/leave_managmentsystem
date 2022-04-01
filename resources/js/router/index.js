import Vue from "vue";
import VueRouter from "vue-router";
Vue.use(VueRouter);
import NotFoundPage from "../components/Pages/NotFoundPage.vue";
import Role from "../components/Pages/Roles.vue";
import Permission from "../components/Pages/Permissions.vue";


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



];

const router = new VueRouter({
  mode: "history",
  routes,
  linkActiveClass: 'active'
});

export default router;
