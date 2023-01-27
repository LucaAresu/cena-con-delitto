/*
 * Welcome to your app's main JavaScript file!
 *
 * We recommend including the built version of this JavaScript file
 * (and its CSS file) in your base layout (base.html.twig).
 */

// any CSS you import will output into a single css file (app.css in this case)
import './styles/app.css';

// start the Stimulus application

import { createApp } from "vue";
import App from "./App.vue";
import { createPinia } from "pinia";
// import routes from "./router/routes";

//
// axios.defaults.headers.common['Content-type'] = 'application/json'
// axios.defaults.headers.common['Accept'] = 'application/json'
const app = createApp({});
const pinia = createPinia();

app.component("App", App);
app.use(pinia);
// app.use(routes);
app.mount("#app");
