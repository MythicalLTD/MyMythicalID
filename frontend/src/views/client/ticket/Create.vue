<script setup lang="ts">
import LayoutDashboard from '@/components/client/LayoutDashboard.vue';
import CardComponent from '@/components/client/ui/Card/CardComponent.vue';
import Button from '@/components/client/ui/Button.vue';
import SelectInput from '@/components/client/ui/TextForms/SelectInput.vue';
import { ref, onMounted } from 'vue';
import TextInput from '@/components/client/ui/TextForms/TextInput.vue';
import TextArea from '@/components/client/ui/TextForms/TextArea.vue';
import Tickets from '@/mymythicalid/Tickets';
import Swal from 'sweetalert2';
import failedAlertSfx from '@/assets/sounds/error.mp3';
import successAlertSfx from '@/assets/sounds/success.mp3';
import { useI18n } from 'vue-i18n';
import { useSound } from '@vueuse/sound';
import { useRouter } from 'vue-router';
import { MythicalDOM } from '@/mymythicalid/MythicalDOM';
import { useSettingsStore } from '@/stores/settings';

const Settings = useSettingsStore();
const { t } = useI18n();
const { play: playError } = useSound(failedAlertSfx);
const { play: playSuccess } = useSound(successAlertSfx);
const router = useRouter();

MythicalDOM.setPageTitle(t('tickets.pages.create_ticket.title'));

interface Department {
    id: number;
    name: string;
    description: string;
    time_open: string;
    time_close: string;
    enabled: string;
    deleted: string;
    locked: string;
    date: string;
}

interface TicketCreateInfo {
    departments: Department[];
}

const ticketCreateInfo = ref<TicketCreateInfo | null>(null);
const loading = ref(false);

const fetchTicketCreateInfo = async () => {
    try {
        const response = await Tickets.getTicketCreateInfo();
        if (response.success) {
            ticketCreateInfo.value = {
                departments: response.departments,
            };
        } else {
            console.error('Failed to fetch ticket create info:', response.error);
        }
    } catch (error) {
        console.error('Error fetching ticket create info:', error);
    }
};

onMounted(() => {
    fetchTicketCreateInfo();
});

const ticket = ref({
    department: '',
    priority: 'medium',
    subject: '',
    message: '',
});

const priorities = [
    { value: 'low', label: t('tickets.pages.create_ticket.types.priority.low') },
    { value: 'medium', label: t('tickets.pages.create_ticket.types.priority.medium') },
    { value: 'high', label: t('tickets.pages.create_ticket.types.priority.high') },
    { value: 'urgent', label: t('tickets.pages.create_ticket.types.priority.urgent') },
];

const submitTicket = async () => {
    loading.value = true;
    try {
        // Handle ticket submission
        console.log('Submitting ticket:', ticket.value);

        const response = await Tickets.createTicket(
            Number(ticket.value.department),
            ticket.value.subject,
            ticket.value.message,
            ticket.value.priority,
        );

        if (!response.success) {
            const error_code = response.error_code as keyof typeof errorMessages;

            const errorMessages = {
                LIMIT_REACHED: t('tickets.pages.create_ticket.alerts.error.limit_reached'),
                FAILED_TO_CREATE_TICKET: t('tickets.pages.create_ticket.alerts.error.generic'),
                DEPARTMENT_NOT_FOUND: t('tickets.pages.create_ticket.alerts.error.department_not_found'),
                DEPARTMENT_ID_MISSING: t('tickets.pages.create_ticket.alerts.error.department_id_missing'),
                MESSAGE_MISSING: t('tickets.pages.create_ticket.alerts.error.message_missing'),
                SUBJECT_MISSING: t('tickets.pages.create_ticket.alerts.error.subject_missing'),
            };

            if (errorMessages[error_code]) {
                playError();
                Swal.fire({
                    icon: 'error',
                    title: t('tickets.pages.create_ticket.alerts.error.title'),
                    text: errorMessages[error_code],
                    footer: t('tickets.pages.create_ticket.alerts.error.footer'),
                    showConfirmButton: true,
                });
                loading.value = false;
                throw new Error('Login failed');
            } else {
                playError();
                Swal.fire({
                    icon: 'error',
                    title: t('tickets.pages.create_ticket.alerts.error.title'),
                    text: t('tickets.pages.create_ticket.alerts.error.generic'),
                    footer: t('tickets.pages.create_ticket.alerts.error.footer'),
                    showConfirmButton: true,
                });
                loading.value = false;
                throw new Error('Ticket creation failed');
            }
        } else {
            playSuccess();
            Swal.fire({
                icon: 'success',
                title: t('tickets.pages.create_ticket.alerts.success.title'),
                text: t('tickets.pages.create_ticket.alerts.success.ticket_success'),
                footer: t('tickets.pages.create_ticket.alerts.success.footer'),
                showConfirmButton: true,
            });
            loading.value = false;
            setTimeout(() => {
                router.push('/ticket');
            }, 1500);
            console.log('Ticket submitted successfully:', response.ticket);
        }

        await new Promise((resolve) => setTimeout(resolve, 2000));
    } catch (error) {
        console.error('Error submitting ticket:', error);
    } finally {
        loading.value = false;
    }
};
</script>

<template>
    <LayoutDashboard>
        <div class="space-y-6">
            <div class="flex justify-between items-center">
                <h1 class="text-2xl font-semibold text-gray-100">{{ t('tickets.pages.create_ticket.title') }}</h1>
                <router-link to="/ticket">
                    <Button variant="secondary">
                        {{ t('tickets.pages.create_ticket.form.back') }}
                    </Button>
                </router-link>
            </div>
            <CardComponent
                :cardTitle="t('tickets.pages.create_ticket.title')"
                :cardDescription="t('tickets.pages.create_ticket.subTitle')"
            >
                <form @submit.prevent="submitTicket" class="space-y-6">
                    <!-- Department -->
                    <div>
                        <label class="block text-sm font-medium text-gray-300 mb-2">
                            {{ t('tickets.pages.create_ticket.form.department') }}
                        </label>
                        <SelectInput
                            v-model="ticket.department"
                            :options="
                                ticketCreateInfo?.departments
                                    .filter((dept) => dept.enabled === 'true')
                                    .map((dept) => ({
                                        value: dept.id.toString(),
                                        label: `${dept.name} (${dept.time_open} - ${dept.time_close}) - ${dept.description}`,
                                    })) || []
                            "
                        />
                    </div>

                    <!-- Priority -->
                    <div>
                        <label class="block text-sm font-medium text-gray-300 mb-2">
                            {{ t('tickets.pages.create_ticket.form.priority') }}
                        </label>
                        <SelectInput v-model="ticket.priority" :options="priorities" />
                    </div>

                    <!-- Subject -->
                    <div>
                        <label class="block text-sm font-medium text-gray-300 mb-2">
                            {{ t('tickets.pages.create_ticket.form.subject') }}
                        </label>
                        <TextInput v-model="ticket.subject" type="text" required placeholder="Enter ticket subject" />
                    </div>

                    <!-- Message -->
                    <div>
                        <label class="block text-sm font-medium text-gray-300 mb-2">
                            {{ t('tickets.pages.create_ticket.form.message') }}
                        </label>
                        <TextArea v-model="ticket.message" :rows="6" required placeholder="Describe your issue..." />
                    </div>

                    <!-- Submit Button -->
                    <div class="flex justify-end">
                        <Button
                            type="submit"
                            variant="primary"
                            :disabled="loading || Settings.getSetting('allow_tickets') === 'false'"
                        >
                            <span v-if="loading">{{ t('tickets.pages.create_ticket.form.loading') }}</span>
                            <span v-else>{{ t('tickets.pages.create_ticket.form.submit') }}</span>
                        </Button>
                    </div>
                </form>
            </CardComponent>
        </div>
    </LayoutDashboard>
</template>
