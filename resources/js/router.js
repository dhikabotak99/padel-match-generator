import { createRouter, createWebHistory } from 'vue-router';
import Home from './views/Home.vue';
import MatchSetup from './views/MatchSetup.vue';
import MatchLobby from './views/MatchLobby.vue';
import MatchView from './views/MatchView.vue';
import MatchList from './views/MatchList.vue';

import Login from './views/Login.vue';
import Register from './views/Register.vue';

const routes = [
    {
        path: '/',
        name: 'Home',
        component: Home
    },
    {
        path: '/login',
        name: 'Login',
        component: Login,
        meta: { guest: true }
    },
    {
        path: '/register',
        name: 'Register',
        component: Register,
        meta: { guest: true }
    },
    {
        path: '/matches',
        name: 'MatchList',
        component: MatchList,
        meta: { requiresAuth: true }
    },
    {
        path: '/create',
        name: 'MatchSetup',
        component: MatchSetup,
        meta: { requiresAuth: true }
    },
    {
        path: '/match/:id/lobby',
        name: 'MatchLobby',
        component: MatchLobby,
        meta: { requiresAuth: true }
    },
    {
        path: '/match/:id',
        name: 'MatchView',
        component: MatchView,
        meta: { requiresAuth: true }
    }
];

const router = createRouter({
    history: createWebHistory(),
    routes
});

router.beforeEach((to, from, next) => {
    const user = localStorage.getItem('user');

    if (to.matched.some(record => record.meta.requiresAuth)) {
        if (!user) {
            next({ name: 'Login' });
        } else {
            next();
        }
    } else if (to.matched.some(record => record.meta.guest)) {
        if (user) {
            next({ name: 'MatchList' });
        } else {
            next();
        }
    } else {
        next();
    }
});

export default router;
