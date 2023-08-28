import './bootstrap';
import { createApp } from 'vue';
import router from './router.js';
import store from './store/index.js';
import vueClipboard from 'vue-clipboard2';
import {createVuetify} from 'vuetify'
import 'vuetify/styles'
import '@mdi/font/css/materialdesignicons.css'
import * as components from 'vuetify/components'
import * as directives from 'vuetify/directives'

const vuetify = createVuetify({
    components,
    directives,
    icons: {
        defaultSet: 'mdi'
    },
    theme: {
        defaultTheme : localStorage.getItem('isDark') == 1 ? 'dark' : 'light',
    }
})
const app = createApp({
    name: 'app',
});

app.use(store).use(vuetify).use(vueClipboard).use(router).mount('#app');


