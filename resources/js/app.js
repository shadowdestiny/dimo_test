import Vue from "vue";
import router from "@/router";
import store from "@/store";
import App from "@/components/App";

import "@/plugins";
import "@/components";

Vue.config.productionTip = false;
Vue.config.delimiters = ['${', '}']

new Vue({
  router,
  store,
  ...App
});
