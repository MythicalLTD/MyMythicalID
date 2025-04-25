import type { RouteRecordRaw } from 'vue-router';
import departmentRoutes from './departments.ts';
import userRoutes from './users.ts';
import announcementRoutes from './announcements.ts';
import ticketRoutes from './tickets.ts';
import mailTemplatesRoutes from './mail-templates.ts';
import settingsRoutes from './settings.ts';

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
];

export default adminRoutes;
