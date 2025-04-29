import type { RouteRecordRaw } from 'vue-router';

const licenseKeysRoutes: RouteRecordRaw[] = [
    {
        path: '/mc-admin/license-keys',
        name: 'admin-license-keys',
        component: () => import('@/views/admin/license-keys/Index.vue'),
        meta: {
            requiresAuth: true,
            requiresAdmin: true,
        },
    },
    {
        path: '/mc-admin/license-keys/create',
        name: 'admin-license-keys-create',
        component: () => import('@/views/admin/license-keys/Create.vue'),
        meta: {
            requiresAuth: true,
            requiresAdmin: true,
        },
    },
    {
        path: '/mc-admin/license-keys/:id/edit',
        name: 'admin-license-keys-edit',
        component: () => import('@/views/admin/license-keys/Edit.vue'),
        meta: {
            requiresAuth: true,
            requiresAdmin: true,
        },
    },
    {
        path: '/mc-admin/license-keys/:id/delete',
        name: 'admin-license-keys-delete',
        component: () => import('@/views/admin/license-keys/Delete.vue'),
        meta: {
            requiresAuth: true,
            requiresAdmin: true,
        },
    },
];

export default licenseKeysRoutes;
