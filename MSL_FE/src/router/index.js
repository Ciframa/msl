import Vue from "vue";
import VueRouter from "vue-router";
import Home from "../views/Home.vue";
import Admin from "../views/Admin.vue";
import AdminPanel from "../views/AdminPanel.vue";

//import PageNotFound from "../views/404-page.vue";

Vue.use(VueRouter);

const router = new VueRouter({
  mode: "history",
  routes: [
    {
      path: "/",
      name: "Home",
      component: Home,
    },
    {
      path: "/admin",
      name: "Admin",
      component: Admin,
    },
    {
      path: "/adminPanel",
      name: "AdminPanel",
      component: AdminPanel,
    },
  ],
});

export default router;
