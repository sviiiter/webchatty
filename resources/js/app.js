import { createApp } from 'vue';
import axios from 'axios';
import App from './components/App.vue';

axios.defaults.baseURL = '/api';
axios.defaults.headers.common['Accept'] = 'application/json';

const token = localStorage.getItem('auth_token');
if (token) {
    axios.defaults.headers.common['Authorization'] = `Bearer ${token}`;
}

createApp(App).mount('#app');
