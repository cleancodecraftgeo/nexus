import { createApp } from 'vue'
import { createPinia } from 'pinia'
import App from './App.vue'
import router from './router'
import piniaPluginPersistedstate from "pinia-plugin-persistedstate";
import { SnackbarService, Vue3Snackbar } from "vue3-snackbar";
import "vue3-snackbar/styles";
import {i18n} from './i18n'

const app = createApp(App)
const pinia = createPinia();
pinia.use(piniaPluginPersistedstate);
app.use(pinia)
app.use(i18n)
app.use(router)
app.use(SnackbarService);
app.component("vue3-snackbar", Vue3Snackbar);

app.mount('#app')
