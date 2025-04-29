import type { RouteRecordRaw } from 'vue-router';
import departmentRoutes from './departments';
import userRoutes from './users';
import announcementRoutes from './announcements';
import ticketRoutes from './tickets';
import mailTemplatesRoutes from './mail-templates';
import settingsRoutes from './settings';
import projectsRoutes from './projects';
import licenseKeysRoutes from './license-keys';
import instancesRoutes from './instances';
// Main admin dashboard route
const mainAdminRoutes: RouteRecordRaw[] = [
    {
        path: '/mc-admin',
        name: 'Admin Home',
        component: () => import('@/views/admin/Home.vue'),
        meta: {
            requiresAuth: true,
            requiresAdmin: true,
        },
    },
];

// Combine all admin routes
const adminRoutes: RouteRecordRaw[] = [
    ...mainAdminRoutes,
    ...departmentRoutes,
    ...userRoutes,
    ...settingsRoutes,
    ...mailTemplatesRoutes,
    ...announcementRoutes,
    ...ticketRoutes,
    ...projectsRoutes,
    ...licenseKeysRoutes,
    ...instancesRoutes,
];

export default adminRoutes;
