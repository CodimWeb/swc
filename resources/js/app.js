import { createApp } from "vue";
import router from "./router/router";
import App from './App.vue'
import 'bootstrap/dist/css/bootstrap.css'

createApp(App)
    .use(router)
    .mount('#root')
