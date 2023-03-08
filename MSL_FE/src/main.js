import Vue from "vue";
import App from "./App.vue";
import router from "./router";
import store from "./store";
import { library } from "@fortawesome/fontawesome-svg-core";
import {
  faClock,
  faCirclePlus,
  faCircleArrowDown,
  faTimesSquare,
  faAnglesDown,
  faStar,
  faBaseball,
  faTrophy,
} from "@fortawesome/free-solid-svg-icons";
import { FontAwesomeIcon } from "@fortawesome/vue-fontawesome";
import VueConfirmDialog from "vue-confirm-dialog";

Vue.use(VueConfirmDialog);
Vue.component("vue-confirm-dialog", VueConfirmDialog.default);
Vue.component("font-awesome-icon", FontAwesomeIcon);
library.add(faClock);
library.add(faCirclePlus);
library.add(faCircleArrowDown);
library.add(faTimesSquare);
library.add(faAnglesDown);
library.add(faStar);
library.add(faBaseball);
library.add(faTrophy);

Vue.config.productionTip = false;
new Vue({
  router,
  store,
  render: (h) => h(App),
}).$mount("#app");
