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
        path: '/store/add-credits',
        name: 'Add Credits',
        component: () => import('@/views/client/store/AddCredits.vue'),
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
