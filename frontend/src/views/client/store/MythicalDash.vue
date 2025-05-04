<template>
    <LayoutDashboard>
        <div class="p-6 space-y-6">
            <BeforeContent />

            <!-- Header Section with Animation -->
            <div class="text-center mb-8 animate-fade-in">
                <h1
                    class="text-4xl font-bold text-white mb-3 bg-gradient-to-r from-indigo-400 to-purple-500 bg-clip-text text-transparent"
                >
                    MythicalDash
                </h1>
                <p class="text-gray-400 max-w-2xl mx-auto">
                    Let's set up your hosting dashboard. We'll need some information about your hosting business.
                </p>
            </div>

            <!-- Progress Steps -->
            <div class="max-w-3xl mx-auto mb-8">
                <div class="flex items-center justify-between">
                    <div v-for="(step, index) in steps" :key="step.id" class="flex items-center">
                        <div
                            class="w-8 h-8 rounded-full flex items-center justify-center"
                            :class="[currentStep >= index ? 'bg-indigo-600 text-white' : 'bg-gray-700 text-gray-400']"
                        >
                            {{ index + 1 }}
                        </div>
                        <div
                            v-if="index < steps.length - 1"
                            class="w-24 h-1 mx-2"
                            :class="[currentStep > index ? 'bg-indigo-600' : 'bg-gray-700']"
                        ></div>
                    </div>
                </div>
                <div class="flex justify-between mt-2">
                    <span
                        v-for="step in steps"
                        :key="step.id"
                        class="text-sm"
                        :class="[currentStep >= step.id ? 'text-indigo-400' : 'text-gray-500']"
                    >
                        {{ step.title }}
                    </span>
                </div>
            </div>

            <!-- Configuration Form with Interactive Elements -->
            <CardComponent
                :cardTitle="steps[currentStep].title"
                :cardDescription="steps[currentStep].description"
                class="max-w-3xl mx-auto animate-slide-up"
            >
                <form @submit.prevent="handleSubmit" class="space-y-6">
                    <!-- Step 1: Company Information -->
                    <div v-if="currentStep === 0" class="space-y-4">
                        <!-- Company Name -->
                        <div class="form-group">
                            <label class="block text-sm font-medium text-gray-300 mb-2"> Company Name </label>
                            <input
                                v-model="formData.companyName"
                                type="text"
                                required
                                class="w-full px-4 py-2 bg-[#1a1a2e]/50 border border-[#2a2a3f]/30 rounded-lg text-white placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-indigo-500/50 focus:border-transparent transition-all duration-200"
                                placeholder="Your Company Name"
                            />
                        </div>

                        <!-- Company Website -->
                        <div class="form-group">
                            <label class="block text-sm font-medium text-gray-300 mb-2"> Company Website </label>
                            <input
                                v-model="formData.companyWebsite"
                                type="url"
                                required
                                class="w-full px-4 py-2 bg-[#1a1a2e]/50 border border-[#2a2a3f]/30 rounded-lg text-white placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-indigo-500/50 focus:border-transparent transition-all duration-200"
                                placeholder="https://your-company.com"
                            />
                        </div>

                        <!-- Business Description -->
                        <div class="form-group">
                            <label class="block text-sm font-medium text-gray-300 mb-2"> Business Description </label>
                            <textarea
                                v-model="formData.businessDescription"
                                rows="3"
                                class="w-full px-4 py-2 bg-[#1a1a2e]/50 border border-[#2a2a3f]/30 rounded-lg text-white placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-indigo-500/50 focus:border-transparent transition-all duration-200"
                                placeholder="Tell us about your hosting business..."
                            ></textarea>
                        </div>
                    </div>

                    <!-- Step 2: Business Details -->
                    <div v-if="currentStep === 1" class="space-y-4">
                        <!-- Hosting Type -->
                        <div class="form-group">
                            <label class="block text-sm font-medium text-gray-300 mb-2"> Hosting Type </label>
                            <select
                                v-model="formData.hostingType"
                                required
                                class="w-full px-4 py-2 bg-[#1a1a2e]/50 border border-[#2a2a3f]/30 rounded-lg text-white placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-indigo-500/50 focus:border-transparent transition-all duration-200"
                            >
                                <option value="">Select hosting type</option>
                                <option value="free">Free Hosting</option>
                                <option value="paid">Paid Hosting</option>
                                <option value="both">Both Free and Paid</option>
                            </select>
                        </div>

                        <!-- Current Users -->
                        <div class="form-group">
                            <label class="block text-sm font-medium text-gray-300 mb-2">
                                Current Number of Users
                            </label>
                            <input
                                v-model="formData.currentUsers"
                                type="number"
                                min="0"
                                required
                                class="w-full px-4 py-2 bg-[#1a1a2e]/50 border border-[#2a2a3f]/30 rounded-lg text-white placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-indigo-500/50 focus:border-transparent transition-all duration-200"
                                placeholder="Number of current users"
                            />
                        </div>

                        <!-- Expected Users -->
                        <div class="form-group">
                            <label class="block text-sm font-medium text-gray-300 mb-2">
                                Expected Number of Users (6 months)
                            </label>
                            <input
                                v-model="formData.expectedUsers"
                                type="number"
                                min="0"
                                required
                                class="w-full px-4 py-2 bg-[#1a1a2e]/50 border border-[#2a2a3f]/30 rounded-lg text-white placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-indigo-500/50 focus:border-transparent transition-all duration-200"
                                placeholder="Expected number of users in 6 months"
                            />
                        </div>
                    </div>

                    <!-- Step 3: Technical Information -->
                    <div v-if="currentStep === 2" class="space-y-4">
                        <!-- Instance URL -->
                        <div class="form-group">
                            <label class="block text-sm font-medium text-gray-300 mb-2"> Instance URL </label>
                            <input
                                v-model="formData.instanceUrl"
                                type="url"
                                required
                                class="w-full px-4 py-2 bg-[#1a1a2e]/50 border border-[#2a2a3f]/30 rounded-lg text-white placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-indigo-500/50 focus:border-transparent transition-all duration-200"
                                placeholder="https://your-instance.com"
                            />
                        </div>

                        <!-- Server Type -->
                        <div class="form-group">
                            <label class="block text-sm font-medium text-gray-300 mb-2"> Server Type </label>
                            <select
                                v-model="formData.serverType"
                                required
                                class="w-full px-4 py-2 bg-[#1a1a2e]/50 border border-[#2a2a3f]/30 rounded-lg text-white placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-indigo-500/50 focus:border-transparent transition-all duration-200"
                            >
                                <option value="">Select server type</option>
                                <option value="vps">VPS</option>
                                <option value="dedicated">Dedicated Server</option>
                                <option value="docker">Docker</option>
                                <option value="other">Other</option>
                            </select>
                        </div>

                        <!-- Expected Server Count -->
                        <div class="form-group">
                            <label class="block text-sm font-medium text-gray-300 mb-2"> Expected Server Count </label>
                            <input
                                v-model="formData.serverCount"
                                type="number"
                                min="1"
                                required
                                class="w-full px-4 py-2 bg-[#1a1a2e]/50 border border-[#2a2a3f]/30 rounded-lg text-white placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-indigo-500/50 focus:border-transparent transition-all duration-200"
                                placeholder="Number of servers you plan to manage"
                            />
                        </div>
                    </div>

                    <!-- Step 4: Contact Information -->
                    <div v-if="currentStep === 3" class="space-y-4">
                        <!-- Primary Contact Email -->
                        <div class="form-group">
                            <label class="block text-sm font-medium text-gray-300 mb-2"> Primary Contact Email </label>
                            <input
                                v-model="formData.primaryEmail"
                                type="email"
                                required
                                class="w-full px-4 py-2 bg-[#1a1a2e]/50 border border-[#2a2a3f]/30 rounded-lg text-white placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-indigo-500/50 focus:border-transparent transition-all duration-200"
                                placeholder="contact@your-domain.com"
                            />
                        </div>

                        <!-- Abuse Email -->
                        <div class="form-group">
                            <label class="block text-sm font-medium text-gray-300 mb-2"> Abuse Email </label>
                            <input
                                v-model="formData.abuseEmail"
                                type="email"
                                required
                                class="w-full px-4 py-2 bg-[#1a1a2e]/50 border border-[#2a2a3f]/30 rounded-lg text-white placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-indigo-500/50 focus:border-transparent transition-all duration-200"
                                placeholder="abuse@your-domain.com"
                            />
                        </div>

                        <!-- Support Email -->
                        <div class="form-group">
                            <label class="block text-sm font-medium text-gray-300 mb-2"> Support Email </label>
                            <input
                                v-model="formData.supportEmail"
                                type="email"
                                required
                                class="w-full px-4 py-2 bg-[#1a1a2e]/50 border border-[#2a2a3f]/30 rounded-lg text-white placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-indigo-500/50 focus:border-transparent transition-all duration-200"
                                placeholder="support@your-domain.com"
                            />
                        </div>
                    </div>

                    <!-- Step 5: Owner Information -->
                    <div v-if="currentStep === 4" class="space-y-4">
                        <!-- First Name -->
                        <div class="form-group">
                            <label class="block text-sm font-medium text-gray-300 mb-2"> First Name </label>
                            <input
                                v-model="formData.ownerFirstName"
                                type="text"
                                required
                                class="w-full px-4 py-2 bg-[#1a1a2e]/50 border border-[#2a2a3f]/30 rounded-lg text-white placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-indigo-500/50 focus:border-transparent transition-all duration-200"
                                placeholder="Owner's first name"
                            />
                        </div>

                        <!-- Last Name -->
                        <div class="form-group">
                            <label class="block text-sm font-medium text-gray-300 mb-2"> Last Name </label>
                            <input
                                v-model="formData.ownerLastName"
                                type="text"
                                required
                                class="w-full px-4 py-2 bg-[#1a1a2e]/50 border border-[#2a2a3f]/30 rounded-lg text-white placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-indigo-500/50 focus:border-transparent transition-all duration-200"
                                placeholder="Owner's last name"
                            />
                        </div>

                        <!-- Birth Date -->
                        <div class="form-group">
                            <label class="block text-sm font-medium text-gray-300 mb-2"> Birth Date </label>
                            <input
                                v-model="formData.ownerBirthDate"
                                type="date"
                                required
                                class="w-full px-4 py-2 bg-[#1a1a2e]/50 border border-[#2a2a3f]/30 rounded-lg text-white placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-indigo-500/50 focus:border-transparent transition-all duration-200"
                            />
                        </div>
                    </div>

                    <!-- Navigation Buttons -->
                    <div class="flex justify-between pt-4">
                        <Button
                            v-if="currentStep > 0"
                            type="button"
                            variant="secondary"
                            @click="currentStep--"
                            class="animate-fade-in"
                        >
                            Previous Step
                        </Button>
                        <Button
                            v-if="currentStep < steps.length - 1"
                            type="button"
                            @click="currentStep++"
                            :disabled="!isStepValid"
                            class="animate-fade-in"
                        >
                            Next Step
                        </Button>
                        <Button
                            v-if="currentStep === steps.length - 1"
                            type="submit"
                            :loading="isSubmitting"
                            :disabled="!isFormValid"
                            class="animate-pulse"
                        >
                            <template #icon>
                                <CheckCircle class="w-4 h-4" />
                            </template>
                            Get Started
                        </Button>
                    </div>
                </form>
            </CardComponent>

            <AfterContent />
        </div>
    </LayoutDashboard>
</template>

<script setup lang="ts">
import { ref, computed } from 'vue';
import { useRouter } from 'vue-router';
import { CheckCircle } from 'lucide-vue-next';
import LayoutDashboard from '@/components/client/LayoutDashboard.vue';
import CardComponent from '@/components/client/ui/Card/CardComponent.vue';
import Button from '@/components/client/ui/Button.vue';
import BeforeContent from '@/plugins/components/Dashboard/BeforeContent.vue';
import AfterContent from '@/plugins/components/Dashboard/AfterContent.vue';
import { MythicalDOM } from '@/mymythicalid/MythicalDOM';
import MythicalDash from '@/mymythicalid/admin/MythicalDash';
import Session from '@/mymythicalid/Session';
import Swal from 'sweetalert2';
import failedAlertSfx from '@/assets/sounds/error.mp3';
import successAlertSfx from '@/assets/sounds/success.mp3';
import { useSound } from '@vueuse/sound';

const router = useRouter();
const isSubmitting = ref(false);
const currentStep = ref(0);
const { play: playError } = useSound(failedAlertSfx);
const { play: playSuccess } = useSound(successAlertSfx);

const steps = [
    {
        id: 0,
        title: 'Company Info',
        description: 'Basic information about your company',
    },
    {
        id: 1,
        title: 'Business Details',
        description: 'Information about your hosting business',
    },
    {
        id: 2,
        title: 'Technical Info',
        description: 'Technical details about your servers',
    },
    {
        id: 3,
        title: 'Contact Info',
        description: 'Contact information for your business',
    },
    {
        id: 4,
        title: 'Owner Info',
        description: 'Information about the business owner',
    },
];

const formData = ref({
    // Company Information
    companyName: '',
    companyWebsite: '',
    businessDescription: '',

    // Business Details
    hostingType: '',
    currentUsers: '',
    expectedUsers: '',

    // Technical Information
    instanceUrl: '',
    serverType: '',
    serverCount: '',

    // Contact Information
    primaryEmail: '',
    abuseEmail: '',
    supportEmail: '',

    // Owner Information
    ownerFirstName: '',
    ownerLastName: '',
    ownerBirthDate: '',
});

const isStepValid = computed(() => {
    switch (currentStep.value) {
        case 0:
            return formData.value.companyName && formData.value.companyWebsite;
        case 1:
            return formData.value.hostingType && formData.value.currentUsers && formData.value.expectedUsers;
        case 2:
            return formData.value.instanceUrl && formData.value.serverType && formData.value.serverCount;
        case 3:
            return formData.value.primaryEmail && formData.value.abuseEmail && formData.value.supportEmail;
        case 4:
            return formData.value.ownerFirstName && formData.value.ownerLastName && formData.value.ownerBirthDate;
        default:
            return false;
    }
});

const isFormValid = computed(() => {
    return Object.values(formData.value).every((value) => value !== '');
});

const handleSubmit = async () => {
    if (!isFormValid.value) return;
    isSubmitting.value = true;
    try {
        const response = await MythicalDash.createInstance(
            Session.getInfo('uuid') || '', // user
            1, // project
            formData.value.companyName,
            formData.value.companyWebsite,
            formData.value.businessDescription,
            formData.value.hostingType as 'free' | 'paid' | 'both',
            parseInt(formData.value.currentUsers),
            parseInt(formData.value.expectedUsers),
            formData.value.instanceUrl,
            formData.value.serverType as 'vps' | 'dedicated' | 'docker' | 'other',
            parseInt(formData.value.serverCount),
            formData.value.primaryEmail,
            formData.value.abuseEmail,
            formData.value.supportEmail,
            formData.value.ownerFirstName,
            formData.value.ownerLastName,
            formData.value.ownerBirthDate,
            false,
        );

        if (response.success) {
            playSuccess();
            await Swal.fire({
                icon: 'success',
                title: 'Success!',
                text: 'MythicalDash instance created successfully',
                showConfirmButton: true,
            });
            router.push('/dashboard');
        } else {
            playError();
            await Swal.fire({
                icon: 'error',
                title: 'Error',
                text: response.message || 'Failed to create instance',
                showConfirmButton: true,
            });
        }
    } catch (error: unknown) {
        console.error('Error submitting form:', error);
        playError();
        await Swal.fire({
            icon: 'error',
            title: 'Error',
            text: error instanceof Error ? error.message : 'An error occurred while creating the instance',
            showConfirmButton: true,
        });
    } finally {
        isSubmitting.value = false;
    }
};

MythicalDOM.setPageTitle('MythicalDash - Get Started');
</script>

<style scoped>
/* Animations */
@keyframes fade-in {
    from {
        opacity: 0;
    }
    to {
        opacity: 1;
    }
}

@keyframes slide-up {
    from {
        opacity: 0;
        transform: translateY(20px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

@keyframes pulse {
    0%,
    100% {
        opacity: 1;
    }
    50% {
        opacity: 0.7;
    }
}

.animate-fade-in {
    animation: fade-in 0.5s ease-out;
}

.animate-slide-up {
    animation: slide-up 0.5s ease-out;
}

.animate-pulse {
    animation: pulse 2s infinite;
}

/* Form styles */
.form-group {
    position: relative;
    transition: all 0.3s ease;
}

.form-group:hover {
    transform: translateX(5px);
}

/* Input focus styles */
input:focus,
textarea:focus,
select:focus {
    border-color: rgba(99, 102, 241, 0.5);
    box-shadow: 0 0 0 2px rgba(99, 102, 241, 0.1);
    transform: scale(1.01);
}

/* Smooth transitions */
.transition-all {
    transition-property: all;
    transition-timing-function: cubic-bezier(0.4, 0, 0.2, 1);
    transition-duration: 200ms;
}
</style>
