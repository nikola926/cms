require('./bootstrap');

import { createApp } from 'vue'
import axios from 'axios'
import router from './router.js'
import App from './App.vue'

import store from './store'






const app = createApp(App)
app.config.globalProperties.$axios = axios;
app.use(router)
app.use(store)
app.mount('#app')





