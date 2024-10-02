import './bootstrap'; // Keep this line if you need to load Bootstrap
import { createApp } from 'vue';
import App from './App.vue';
import router from './router';

// Create the Vue app
const app = createApp(App);

// Use the router
app.use(router);

// Mount the app to the DOM
app.mount('#app');
