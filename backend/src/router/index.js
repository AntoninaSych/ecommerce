import {createRouter, createWebHistory} from "vue-router";
import Login from "../views/Login.vue";
import Dashboard from "../views/Dashboard.vue";
import Register from "../views/RequestPassword.vue";
import ResetPassword from "../views/ResetPassword.vue";
// import DefaultLayout from "../components/DefaultLayout.vue";

const routes = [
    // {
    //     path: '/',
    //     redirect: '/dashboard',
    //     name: 'Dashboard',
    //     component: DefaultLayout,
    //     children: [
    //         {
    //             path: '/dashboard',
    //             name: 'dashboard',
    //             component: Dashboard
    //         }
    //     ]
    // },
    {
        path: '/login',
        name: 'login',
        component: Login
    },
    {
        path: '/register',
        name: 'register',
        component: Register
    },
    {
        path: '/resetPassword',
        name: 'resetPassword',
        component: ResetPassword
    },
];
const router = createRouter({
    history: createWebHistory(),
    routes
})

export default router;



