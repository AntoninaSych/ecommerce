import {createApp} from 'vue'
import store from './store'
import router from './router'
import CKEditor from '@ckeditor/ckeditor5-vue'
import './index.css';
import App from './App.vue'


createApp(App)
    .use(store)
    .use(router)
    .use(CKEditor)
    .mount('#app')
