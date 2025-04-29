import { ref, computed } from 'vue';
import {
    LayoutDashboard,
    Users,
    SettingsIcon,
    Building,
    BellIcon,
    MailIcon,
    InfoIcon,
    Key as KeyIcon,
    Info as ProjectIcon,
    Server as ServerIcon,
} from 'lucide-vue-next';
import type { MenuGroup, ProfileMenuItem } from '../types';

// Define the dashboard data type inline to avoid import issues
interface DashboardCounts {
    user_count: number;
    tickets_count: number;
    departments_count: number;
    announcements_count: number;
    settings_count: number;
    mail_templates_count: number;
    projects_count: number;
    license_keys_count: number;
    instances_count: number;
}

interface DashboardData {
    count: DashboardCounts;
}

// Use a simpler type for the route
export function useAdminMenu(route: { path: string }, dashBoard: { value: DashboardData }) {
    const adminBaseUri = '/mc-admin';

    const menuGroups = ref<MenuGroup[]>([
        {
            title: 'Main Menu',
            items: [
                {
                    name: 'Dashboard',
                    path: `${adminBaseUri}`,
                    icon: LayoutDashboard,
                    active: route.path === `${adminBaseUri}`,
                },
            ],
        },
        {
            title: 'Management',
            items: [
                {
                    name: 'Users',
                    path: `${adminBaseUri}/users`,
                    icon: Users,
                    count: computed(() => dashBoard.value.count.user_count || 0),
                    active: route.path === `${adminBaseUri}/users`,
                },
                {
                    name: 'Departments',
                    path: `${adminBaseUri}/departments`,
                    icon: Building,
                    count: computed(() => dashBoard.value.count.departments_count || 0),
                    active: route.path === `${adminBaseUri}/departments`,
                },
                {
                    name: 'Projects',
                    path: `${adminBaseUri}/projects`,
                    icon: ProjectIcon,
                    count: computed(() => dashBoard.value.count.projects_count || 0),
                },
                {
                    name: 'License Keys',
                    path: `${adminBaseUri}/license-keys`,
                    icon: KeyIcon,
                    count: computed(() => dashBoard.value.count.license_keys_count || 0),
                },
                {
                    name: 'Instances',
                    path: `${adminBaseUri}/mythicaldash`,
                    icon: ServerIcon,
                    count: computed(() => dashBoard.value.count.instances_count || 0),
                },
            ],
        },
        {
            title: 'Support Buddy',
            items: [
                {
                    name: 'Tickets',
                    path: `${adminBaseUri}/tickets`,
                    icon: InfoIcon,
                    active: route.path === `${adminBaseUri}/tickets`,
                    count: computed(() => dashBoard.value.count.tickets_count || 0),
                },
                {
                    name: 'Announcements',
                    path: `${adminBaseUri}/announcements`,
                    icon: BellIcon,
                    active: route.path === `${adminBaseUri}/announcements`,
                    count: computed(() => dashBoard.value.count.announcements_count || 0),
                },
            ],
        },
        {
            title: 'Advanced',
            items: [
                {
                    name: 'Settings',
                    path: `${adminBaseUri}/settings`,
                    icon: SettingsIcon,
                    active: route.path === `${adminBaseUri}/settings`,
                    count: computed(() => dashBoard.value.count.settings_count || 0),
                },
                {
                    name: 'Mail Templates',
                    path: `${adminBaseUri}/mail-templates`,
                    icon: MailIcon,
                    active: route.path === `${adminBaseUri}/mail-templates`,
                    count: computed(() => dashBoard.value.count.mail_templates_count || 0),
                },
            ],
        },
    ]);

    const profileMenu: ProfileMenuItem[] = [
        { name: 'Profile', path: '/account' },
        { name: 'Exit Admin', path: '/dashboard' },
        { name: 'Sign out', path: '/auth/logout' },
    ];

    return {
        menuGroups,
        profileMenu,
    };
}
