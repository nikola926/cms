import {createWebHistory, createRouter} from "vue-router";

import Login from "./components/Login.vue";

import Register from "./components/Register.vue";
import Home from "./components/Home.vue";





const router = createRouter({
    history: createWebHistory(),
    routes: [
        {
            path: "/",
            name: "home",
            component: Home,
        },
        {
            path: "/login",
            name: "login",
            component: Login,
        },
        {
            path: "/register",
            name: "register",
            component: Register,
        }

    ],
});

export default router;

