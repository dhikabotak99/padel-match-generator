import { createRouter, createWebHistory } from 'vue-router';
import Home from './views/Home.vue';
import MatchSetup from './views/MatchSetup.vue';
import MatchLobby from './views/MatchLobby.vue';
import MatchView from './views/MatchView.vue';
import MatchList from './views/MatchList.vue';

const routes = [
    {
        path: '/',
        name: 'Home',
        component: Home
    },
    {
        path: '/matches',
        name: 'MatchList',
        component: MatchList
    },
    {
        path: '/create',
        name: 'MatchSetup',
        component: MatchSetup
    },
    {
        path: '/match/:id/lobby',
        name: 'MatchLobby',
        component: MatchLobby
    },
    {
        path: '/match/:id',
        name: 'MatchView',
        component: MatchView
    }
];

const router = createRouter({
    history: createWebHistory(),
    routes
});

export default router;
