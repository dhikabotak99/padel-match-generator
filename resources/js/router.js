import { createRouter, createWebHistory } from 'vue-router';
import Home from './views/Home.vue';
import MatchSetup from './views/MatchSetup.vue';
import MatchLobby from './views/MatchLobby.vue';
import MatchView from './views/MatchView.vue';

const routes = [
    {
        path: '/',
        name: 'Home',
        component: Home
    },
    {
        path: '/create',
        name: 'MatchSetup',
        component: MatchSetup
    },
    {
        path: '/match/:id',
        name: 'MatchView',
        component: MatchView
    },
    {
        path: '/match/:id/lobby',
        name: 'MatchLobby',
        component: MatchLobby
    }
];

const router = createRouter({
    history: createWebHistory(),
    routes
});

export default router;
