/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

import './bootstrap';

import 'admin-lte/plugins/bootstrap/js/bootstrap.bundle.min.js';
import 'admin-lte/dist/js/adminlte.min.js';
import { createApp } from 'vue';
import { createRouter, createWebHistory } from 'vue-router';
import Routes from './routes.js'
import AdminLayout from "./pages/admin/AdminLayout.vue";
import { createPinia } from "pinia";
import { useAuthUserStore } from "./stores/AuthUserStore.js";
import App from "./App.vue";
/**
 * Next, we will create a fresh Vue application instance. You may then begin
 * registering components with the application instance so they are ready
 * to use in your application's views. An example is included for you.
 */
const pinia = createPinia();
const app = createApp(App);

// app.component('prop-user-name', AdminLayout);

const router = createRouter({
    routes: Routes,
    history: createWebHistory(),

})

router.beforeEach(async (to, from) => {
    const authUserStore = useAuthUserStore()
    if (authUserStore.user.name === '' && to.name !== 'login' && to.name !== 'register')
    {
        await Promise.all([authUserStore.getAuthUser()])
    }
});

app.use(pinia)
app.use(router)


/**
 * The following block of code may be used to automatically register your
 * Vue components. It will recursively scan this directory for the Vue
 * components and automatically register them with their "basename".
 *
 */

// Object.entries(import.meta.glob('./**/*.vue', { eager: true })).forEach(([path, definition]) => {
//     app.component(path.split('/').pop().replace(/\.\w+$/, ''), definition.default);
// });

/**
 * Finally, we will attach the application instance to a HTML element with
 * an "id" attribute of "app". This element is included with the "auth"
 * scaffolding. Otherwise, you will need to add an element yourself.
 */

app.mount('#app');
