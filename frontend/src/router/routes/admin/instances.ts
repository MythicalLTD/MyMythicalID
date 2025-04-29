import type { RouteRecordRaw } from 'vue-router';

const projectsRoutes: RouteRecordRaw[] = [
    {
        path: '/mc-admin/mythicaldash',
        name: 'admin-instances',
        component: () => import('@/views/admin/mythicaldash/Index.vue'),
        meta: {
            requiresAuth: true,
            requiresAdmin: true,
        },
    },
    {
        path: '/mc-admin/mythicaldash/create',
        name: 'admin-instances-create',
        component: () => import('@/views/admin/mythicaldash/Create.vue'),
        meta: {
            requiresAuth: true,
            requiresAdmin: true,
        },
    },
    {
        path: '/mc-admin/mythicaldash/:id/info',
        name: 'admin-instances-info',
        component: () => import('@/views/admin/mythicaldash/Info.vue'),
        meta: {
            requiresAuth: true,
            requiresAdmin: true,
        },
    },
];

export default projectsRoutes;
