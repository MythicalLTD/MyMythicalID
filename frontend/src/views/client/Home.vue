<template>
    <LayoutDashboard>
        <div class="p-6 space-y-6">
            <BeforeContent />
            <Header />
            <div class="grid gap-6 lg:grid-cols-4">
                <!-- Left Column -->
                <div class="space-y-6">
                    <UserProfile />
                    <SupportPin />
                    <QuickLinks />
                    <AnnouncementsPreview />
                </div>
                <!-- Main Content -->
                <div class="lg:col-span-3 space-y-6">
                    <LicenseList />
                    <TicketList />
                </div>
            </div>
            <AfterContent />
        </div>
    </LayoutDashboard>
</template>

<script setup lang="ts">
import LayoutDashboard from '@/components/client/LayoutDashboard.vue';
import SupportPin from '@/components/client/Dashboard/Main/SupportPin.vue';
import TicketList from '@/components/client/Dashboard/Main/TicketList.vue';
import Header from '@/components/client/Dashboard/Main/Header.vue';
import UserProfile from '@/components/client/Dashboard/Main/UserProfile.vue';
import QuickLinks from '@/components/client/Dashboard/Main/QuickLinks.vue';
import AnnouncementsPreview from '@/components/client/Dashboard/Main/AnnouncementsPreview.vue';
import LicenseList from '@/components/client/Dashboard/Main/LicenseList.vue';
import { MythicalDOM } from '@/mymythicalid/MythicalDOM';

import BeforeContent from '@/plugins/components/Dashboard/BeforeContent.vue';
import AfterContent from '@/plugins/components/Dashboard/AfterContent.vue';

MythicalDOM.setPageTitle(MythicalDOM.getTranslation('dashboard.title'));
</script>
