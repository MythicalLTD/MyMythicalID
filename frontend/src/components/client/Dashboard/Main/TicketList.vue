<script setup lang="ts">
import CardComponent from '@/components/client/ui/Card/CardComponent.vue';
import Tickets from '@/mymythicalid/Tickets';
import Button from '@/components/client/ui/Button.vue';
import { ref, onMounted, computed } from 'vue';
import { useI18n } from 'vue-i18n';
import { useSettingsStore } from '@/stores/settings';

const Settings = useSettingsStore();

interface Ticket {
    id: number;
    title: string;
    date: string;
    status: string;
}

const isTicketsEnabled = computed(() => {
    return Settings.getSetting('allow_tickets') === 'true';
});
const { t } = useI18n();
const recentTickets = ref<Ticket[]>([]);

import { format } from 'date-fns';
import { useRouter } from 'vue-router';

const router = useRouter();

const goToTicketList = () => {
    router.push('/ticket');
};

const fetchRecentTickets = async () => {
    try {
        const response = await Tickets.getTickets();
        if (response.success && Array.isArray(response.tickets)) {
            recentTickets.value = response.tickets
                .slice(0, 3)
                .map((ticket: { date: string | number | Date; status: string }) => ({
                    ...ticket,
                    date: format(new Date(ticket.date), 'PPP'),
                    status: ticket.status
                        .split(' ')
                        .map((word) => word.charAt(0).toUpperCase() + word.slice(1).toLowerCase())
                        .join(' ')
                        .replace('Inprogress', 'In Progress'),
                }));
        } else {
            throw new Error(response.error || 'Failed to fetch tickets');
        }
    } catch (err) {
        console.error('Error fetching recent tickets:', err);
    }
};

onMounted(fetchRecentTickets);
</script>

<template>
    <!-- Recent Tickets -->
    <CardComponent
        v-if="recentTickets.length > 0 && isTicketsEnabled"
        :cardTitle="t('Components.Tickets.title')"
        :cardDescription="t('Components.Tickets.description')"
    >
        <div class="space-y-3">
            <div
                v-for="ticket in recentTickets"
                :key="ticket.id"
                class="flex items-center justify-between py-2 border-b border-blue-700 last:border-0"
            >
                <div>
                    <div class="font-medium text-white">{{ ticket.title }}</div>
                    <div class="text-sm text-blue-500">{{ ticket.date }}</div>
                </div>
                <span
                    :class="[
                        'px-2 py-1 rounded-sm text-xs font-medium',
                        ticket.status === t('Components.Tickets.status.Open')
                            ? 'bg-green-500/20 text-green-400'
                            : ticket.status === t('Components.Tickets.status.Closed')
                              ? 'bg-red-500/20 text-red-400'
                              : ticket.status === t('Components.Tickets.status.Waiting')
                                ? 'bg-orange-500/20 text-orange-400'
                                : ticket.status === t('Components.Tickets.status.Replied')
                                  ? 'bg-blue-500/20 text-blue-400'
                                  : ticket.status === t('Components.Tickets.status.InProgress')
                                    ? 'bg-blue-500/20 text-blue-400'
                                    : '',
                    ]"
                >
                    {{ ticket.status }}
                </span>
            </div>
        </div>
        <Button @click="goToTicketList" class="mt-4 block w-full px-4 py-2">
            {{ t('Components.Tickets.viewMore') }}
        </Button>
    </CardComponent>
</template>
