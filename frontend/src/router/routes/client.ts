import type { RouteRecordRaw } from 'vue-router';

const clientRoutes: RouteRecordRaw[] = [
    {
        path: '/dashboard',
        name: 'Dashboard',
        component: () => import('@/views/client/Home.vue'),
        meta: {
            requiresAuth: true,
        },
    },
    {
        path: '/account',
        name: 'Account',
        component: () => import('@/views/client/Account.vue'),
        meta: {
            requiresAuth: true,
        },
    },
    {
        path: '/announcements',
        name: 'Announcements',
        component: () => import('@/views/client/Announcements.vue'),
        meta: {
            requiresAuth: true,
        },
    },
    {
        path: '/store',
        name: 'Store',
        component: () => import('@/views/client/Store.vue'),
        meta: {
            requiresAuth: true,
        },
    },
    {
        path: '/store/add-credits',
        name: 'Add Credits',
        component: () => import('@/views/client/store/AddCredits.vue'),
        meta: {
            requiresAuth: true,
        },
    },
    {
        path: '/store/buy/premium/mythicaldash',
        name: 'MythicalDashPremium',
        component: () => import('@/views/client/store/buy/premium/MythicalDash.vue'),
        meta: {
            requiresAuth: true,
        },
    },
    {
        path: '/store/mythicaldash',
        name: 'MythicalDash',
        component: () => import('@/views/client/store/MythicalDash.vue'),
        meta: {
            requiresAuth: true,
        },
    },
    {
        path: '/logs/:id',
        name: 'Logs',
        component: () => import('@/views/Logs.vue'),
        meta: {
            requiresAuth: true,
        },
    },
    {
        path: '/',
        redirect: '/dashboard',
    },
];

export default clientRoutes;
