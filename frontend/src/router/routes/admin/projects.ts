import type { RouteRecordRaw } from 'vue-router';

const projectsRoutes: RouteRecordRaw[] = [
    {
        path: '/mc-admin/projects',
        name: 'admin-projects',
        component: () => import('@/views/admin/projects/Index.vue'),
        meta: {
            requiresAuth: true,
            requiresAdmin: true,
        },
    },
    {
        path: '/mc-admin/projects/create',
        name: 'admin-projects-create',
        component: () => import('@/views/admin/projects/Create.vue'),
        meta: {
            requiresAuth: true,
            requiresAdmin: true,
        },
    },
    {
        path: '/mc-admin/projects/:id/edit',
        name: 'admin-projects-edit',
        component: () => import('@/views/admin/projects/Edit.vue'),
        meta: {
            requiresAuth: true,
            requiresAdmin: true,
        },
    },
    {
        path: '/mc-admin/projects/:id/delete',
        name: 'admin-projects-delete',
        component: () => import('@/views/admin/projects/Delete.vue'),
        meta: {
            requiresAuth: true,
            requiresAdmin: true,
        },
    },
];

export default projectsRoutes;
