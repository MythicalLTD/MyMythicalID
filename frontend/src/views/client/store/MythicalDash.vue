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

                    <!-- Step 6: License Agreement -->
                    <div v-if="currentStep === 5" class="space-y-4">
                        <!-- License Text -->
                        <div class="form-group">
                            <div
                                class="max-h-96 overflow-y-auto bg-[#1a1a2e]/50 border border-[#2a2a3f]/30 rounded-lg p-4 text-gray-300 text-sm"
                            >
                                <h2 class="text-xl font-bold mb-4">MythicalSystems License v2.0</h2>

                                <h3 class="font-semibold mb-2">
                                    Copyright (c) 2021–2025 MythicalSystems and Cassian Gherman
                                </h3>

                                <h4 class="font-semibold mb-2">Preamble</h4>
                                <p class="mb-4">
                                    This license governs the use, modification, and distribution of the software known
                                    as MythicalDash or MythicalDash ("the Software"). By using the Software, you agree
                                    to the terms outlined in this document. These terms aim to protect the Software's
                                    integrity, ensure fair use, and establish guidelines for authorized distribution,
                                    modification, and commercial use.
                                </p>

                                <p class="mb-4">
                                    For any inquiries, abuse reports, or violation notices, contact us at
                                    <a
                                        href="mailto:abuse@mythical.systems"
                                        class="text-indigo-400 hover:text-indigo-300"
                                        >abuse@mythical.systems</a
                                    >.
                                </p>

                                <h4 class="font-semibold mb-2">1. Grant of License</h4>
                                <h5 class="font-medium mb-1">1.1. Usage Rights</h5>
                                <ul class="list-disc pl-6 mb-4">
                                    <li>
                                        You are granted a non-exclusive, revocable license to use the Software, provided
                                        you comply with the terms herein.
                                    </li>
                                    <li>
                                        The Software must be linked to an active account on our public platform,
                                        MythicalSystems.
                                    </li>
                                </ul>

                                <h5 class="font-medium mb-1">1.2. Modification Rights</h5>
                                <ul class="list-disc pl-6 mb-4">
                                    <li>
                                        You may modify the Software only for personal use and must not distribute
                                        modified versions unless explicitly approved by MythicalSystems or Cassian
                                        Gherman.
                                    </li>
                                </ul>

                                <h5 class="font-medium mb-1">1.3. Redistribution and Commercial Use</h5>
                                <ul class="list-disc pl-6 mb-4">
                                    <li>
                                        Redistribution of the Software, whether modified or unmodified, is strictly
                                        prohibited unless explicitly authorized in writing by MythicalSystems or Cassian
                                        Gherman.
                                    </li>
                                    <li>
                                        Selling the Software or its derivatives is only permitted on authorized
                                        marketplaces specified by MythicalSystems.
                                    </li>
                                    <li>
                                        Unauthorized leaking, sharing, or redistribution of the Software or its
                                        components is illegal and subject to legal action.
                                    </li>
                                </ul>

                                <h5 class="font-medium mb-1">1.4. Third-Party Addons and Plugins</h5>
                                <ul class="list-disc pl-6 mb-4">
                                    <li>
                                        The creation, sale, and distribution of third-party addons or plugins for the
                                        Software are permitted, provided they comply with this license.
                                    </li>
                                    <li>
                                        All third-party addons or plugins must not attempt to bypass, modify, or
                                        interfere with the core functionality or licensing requirements of the Software.
                                    </li>
                                </ul>

                                <h5 class="font-medium mb-1">1.5. Forking</h5>
                                <ul class="list-disc pl-6 mb-4">
                                    <li>
                                        Forking the Software is allowed, provided that the forked version continues to
                                        comply with all terms and conditions outlined in this license. Any modifications
                                        made in the fork must respect the original license and maintain all attributions
                                        and copyright notices.
                                    </li>
                                    <li>
                                        Redistribution of forked versions is subject to the same restrictions as the
                                        original Software, requiring explicit authorization from MythicalSystems or
                                        Cassian Gherman.
                                    </li>
                                    <li>
                                        Forked versions must not attempt to bypass, modify, or interfere with the core
                                        functionality or licensing requirements of the Software.
                                    </li>
                                    <li>
                                        Unauthorized leaking, sharing, or redistribution of forked versions is illegal
                                        and subject to legal action.
                                    </li>
                                    <li>
                                        Forked versions must be linked to an active account on our public platform,
                                        MythicalSystems.
                                    </li>
                                </ul>

                                <h4 class="font-semibold mb-2">2. Restrictions</h4>
                                <h5 class="font-medium mb-1">2.1. Account Requirement</h5>
                                <ul class="list-disc pl-6 mb-4">
                                    <li>
                                        The Software requires an active account on MythicalSystems. Attempts to modify,
                                        bypass, or remove this requirement are strictly prohibited.
                                    </li>
                                </ul>

                                <h5 class="font-medium mb-1">2.2. Unauthorized Use</h5>
                                <ul class="list-disc pl-6 mb-4">
                                    <li>
                                        Use of the Software to perform unauthorized actions, including but not limited
                                        to exploiting vulnerabilities, bypassing authentication, or reverse engineering,
                                        is prohibited.
                                    </li>
                                </ul>

                                <h5 class="font-medium mb-1">2.3. Leaking and Distribution</h5>
                                <ul class="list-disc pl-6 mb-4">
                                    <li>
                                        Any unauthorized leaking, sharing, or distribution of the Software is a direct
                                        violation of this license. Legal action will be taken against violators.
                                    </li>
                                    <li>
                                        Leaked or pirated copies of the Software are considered illegal, and users found
                                        utilizing such versions will face immediate termination of access and potential
                                        legal consequences.
                                    </li>
                                </ul>

                                <h5 class="font-medium mb-1">2.4. Modification of Terms</h5>
                                <ul class="list-disc pl-6 mb-4">
                                    <li>
                                        The terms and conditions of this license may not be modified, replaced, or
                                        overridden in any distributed version of the Software.
                                    </li>
                                </ul>

                                <h4 class="font-semibold mb-2">3. Attribution and Copyright</h4>
                                <h5 class="font-medium mb-1">3.1. Attribution</h5>
                                <ul class="list-disc pl-6 mb-4">
                                    <li>
                                        You must retain all copyright notices, attributions, and references to
                                        MythicalSystems and Cassian Gherman in all copies, derivatives, or distributions
                                        of the Software.
                                    </li>
                                </ul>

                                <h5 class="font-medium mb-1">3.2. Copyright Invariance</h5>
                                <ul class="list-disc pl-6 mb-4">
                                    <li>
                                        Copyright notices must remain intact and unaltered in all versions of the
                                        Software, including modified versions.
                                    </li>
                                </ul>

                                <h4 class="font-semibold mb-2">4. Legal and Liability Terms</h4>
                                <h5 class="font-medium mb-1">4.1. Disclaimer of Liability</h5>
                                <ul class="list-disc pl-6 mb-4">
                                    <li>
                                        The Software is provided "as is," without any warranty, express or implied,
                                        including but not limited to warranties of merchantability, fitness for a
                                        particular purpose, or non-infringement.
                                    </li>
                                    <li>
                                        MythicalSystems and Cassian Gherman shall not be held liable for any damages
                                        arising from the use, misuse, or inability to use the Software, including but
                                        not limited to:
                                        <ul class="list-disc pl-6 mt-2">
                                            <li>Loss of data, profits, or revenue.</li>
                                            <li>
                                                Security vulnerabilities such as SQL injection, zero-day exploits, or
                                                other potential risks.
                                            </li>
                                            <li>System failures, downtime, or disruptions.</li>
                                        </ul>
                                    </li>
                                </ul>

                                <h5 class="font-medium mb-1">4.2. Enforcement</h5>
                                <ul class="list-disc pl-6 mb-4">
                                    <li>
                                        Violations of this license will result in immediate termination of access to the
                                        Software and may involve legal action.
                                    </li>
                                    <li>
                                        MythicalSystems reserves the right to suspend or terminate access to any user,
                                        client, or hosting provider without prior notice.
                                    </li>
                                </ul>

                                <h5 class="font-medium mb-1">4.3. No Guarantees</h5>
                                <ul class="list-disc pl-6 mb-4">
                                    <li>
                                        MythicalSystems does not guarantee uninterrupted or error-free operation of the
                                        Software.
                                    </li>
                                </ul>

                                <h4 class="font-semibold mb-2">5. Privacy and Data Sharing</h4>
                                <h5 class="font-medium mb-1">5.1. Public Information</h5>
                                <ul class="list-disc pl-6 mb-4">
                                    <li>
                                        Some user information may be shared with third parties or made publicly visible
                                        in accordance with our Privacy Policy and Terms of Service. For more details,
                                        please visit:
                                        <ul class="list-disc pl-6 mt-2">
                                            <li>
                                                <a
                                                    href="https://www.mythical.systems/privacy"
                                                    class="text-indigo-400 hover:text-indigo-300"
                                                    >Privacy Policy</a
                                                >
                                            </li>
                                            <li>
                                                <a
                                                    href="https://www.mythical.systems/terms"
                                                    class="text-indigo-400 hover:text-indigo-300"
                                                    >Terms of Service</a
                                                >
                                            </li>
                                        </ul>
                                    </li>
                                </ul>

                                <h5 class="font-medium mb-1">5.2. Data Collection</h5>
                                <ul class="list-disc pl-6 mb-4">
                                    <li>
                                        The Software may collect and transmit anonymized usage data to improve
                                        performance and functionality.
                                    </li>
                                </ul>

                                <h4 class="font-semibold mb-2">6. Governing Law</h4>
                                <h5 class="font-medium mb-1">6.1. Jurisdiction</h5>
                                <ul class="list-disc pl-6 mb-4">
                                    <li>
                                        This license shall be governed and construed in accordance with the laws of
                                        Romania.
                                    </li>
                                </ul>

                                <h5 class="font-medium mb-1">6.2. Dispute Resolution</h5>
                                <ul class="list-disc pl-6 mb-4">
                                    <li>
                                        All disputes arising under or in connection with this license shall be subject
                                        to the exclusive jurisdiction of the courts in Romania.
                                    </li>
                                </ul>

                                <h5 class="font-medium mb-1">6.3. GDPR</h5>
                                <ul class="list-disc pl-6 mb-4">
                                    <li>
                                        You may send us an email to remove your data or your hosting's data at:
                                        <code class="bg-gray-800 px-1 rounded">gdpr@mythical.systems</code> and we will
                                        take care of it in the next 7 working days!
                                    </li>
                                </ul>

                                <h4 class="font-semibold mb-2">7. Termination</h4>
                                <h5 class="font-medium mb-1">7.1. Violation of Terms</h5>
                                <ul class="list-disc pl-6 mb-4">
                                    <li>
                                        MythicalSystems reserves the right to terminate access to the Software for any
                                        user found in violation of this license.
                                    </li>
                                </ul>

                                <h5 class="font-medium mb-1">7.2. Immediate Termination</h5>
                                <ul class="list-disc pl-6 mb-4">
                                    <li>Termination may occur immediately and without prior notice.</li>
                                </ul>

                                <h4 class="font-semibold mb-2">8. Contact Information</h4>
                                <p class="mb-4">
                                    For abuse reports, legal inquiries, or support, contact
                                    <a
                                        href="mailto:abuse@mythical.systems"
                                        class="text-indigo-400 hover:text-indigo-300"
                                        >abuse@mythical.systems</a
                                    >.
                                </p>

                                <h4 class="font-semibold mb-2">9. Acceptance</h4>
                                <p class="mb-4">
                                    By using, modifying, or distributing the Software, you agree to the terms outlined
                                    in this license.
                                </p>

                                <h4 class="font-semibold mb-2">10. Additional Terms</h4>
                                <h5 class="font-medium mb-1">10.1. OAuth Authentication</h5>
                                <ul class="list-disc pl-6 mb-4">
                                    <li>
                                        If you use the Software in conjunction with OAuth or similar, you acknowledge
                                        and agree that the system may automatically:
                                        <ul class="list-disc pl-6 mt-2">
                                            <li>
                                                Star the <strong>MythicalDash</strong> repository or any related public
                                                repositories owned by the <strong>MythicalSystems</strong> organization.
                                            </li>
                                            <li>
                                                Follow the <strong>MythicalDash</strong> organization on GitHub on
                                                behalf of your authenticated GitHub account.
                                            </li>
                                        </ul>
                                    </li>
                                    <li>
                                        If you use Discord authentication, you acknowledge and agree that the system may
                                        automatically:
                                        <ul class="list-disc pl-6 mt-2">
                                            <li>
                                                Join the official <strong>MythicalSystems</strong> Discord server on
                                                behalf of your authenticated Discord account.
                                            </li>
                                        </ul>
                                    </li>
                                    <li>
                                        This behavior is intended to support the community and visibility of the
                                        Software and is considered part of the license terms.
                                    </li>
                                </ul>

                                <h5 class="font-medium mb-1">10.2. Hosting Provider Requirements</h5>
                                <ul class="list-disc pl-6 mb-4">
                                    <li>
                                        If you are a hosting provider offering the Software as a managed service or as
                                        part of your hosting panel, you must:
                                        <ul class="list-disc pl-6 mt-2">
                                            <li>
                                                Explicitly include this license in your Terms of Service (ToS) or User
                                                Agreement.
                                            </li>
                                            <li>
                                                Ensure that all end users are aware of and agree to the terms of the
                                                MythicalSystems License v2.0.
                                            </li>
                                            <li>
                                                Maintain a clear reference to <strong>MythicalDash</strong> and
                                                <strong>MythicalSystems</strong> in all relevant documentation, portals,
                                                and legal notices.
                                            </li>
                                        </ul>
                                    </li>
                                </ul>

                                <h5 class="font-medium mb-1">10.3. User Data Collection and Sharing</h5>
                                <ul class="list-disc pl-6 mb-4">
                                    <li>
                                        By using the Software, you acknowledge and agree that certain user data may be
                                        collected and shared with MythicalSystems, including but not limited to:
                                        <ul class="list-disc pl-6 mt-2">
                                            <li>
                                                Account information (username, email address, first name, last name)
                                            </li>
                                            <li>Account status (banned status, verification status, 2FA settings)</li>
                                            <li>
                                                Connected service data (Discord and GitHub and Google account
                                                information)
                                            </li>
                                        </ul>
                                    </li>
                                    <li>
                                        This data collection is used for:
                                        <ul class="list-disc pl-6 mt-2">
                                            <li>Analyzing user behavior and Software usage patterns</li>
                                            <li>Supporting the ZeroTrust initiative to prevent abuse</li>
                                            <li>
                                                Enabling communication between hosting providers about potentially
                                                malicious users
                                            </li>
                                            <li>Improving Software security and functionality</li>
                                        </ul>
                                    </li>
                                    <li>
                                        MythicalSystems will handle all collected data in accordance with applicable
                                        privacy laws and regulations.
                                    </li>
                                    <li>
                                        This data sharing agreement is considered an integral part of using the Software
                                        and cannot be opted out of while using the system.
                                    </li>
                                </ul>

                                <h5 class="font-medium mb-1">10.4. Data Privacy and Third-Party Sharing</h5>
                                <ul class="list-disc pl-6 mb-4">
                                    <li>
                                        Your personal data (including first name and last name) will never be shared
                                        with any third parties other than MythicalSystems and Cassian Gherman.
                                    </li>
                                    <li>
                                        All data held by MythicalSystems can be requested for deletion, specifically:
                                        <ul class="list-disc pl-6 mt-2">
                                            <li>First name</li>
                                            <li>Last name</li>
                                        </ul>
                                    </li>
                                    <li>
                                        To request deletion of your personal data, please contact gdpr@mythical.systems
                                    </li>
                                    <li>Data deletion requests will be processed within 7 working days of receipt</li>
                                </ul>
                            </div>
                        </div>

                        <!-- Declaration Statement -->
                        <div class="form-group mb-4">
                            <div
                                class="bg-[#1a1a2e]/50 border border-[#2a2a3f]/30 rounded-lg p-4 text-gray-300 text-sm"
                            >
                                <p class="mb-2">I hereby declare and affirm that:</p>
                                <ul class="list-disc pl-6">
                                    <li>
                                        All information provided in this application is true, accurate, and complete to
                                        the best of my knowledge.
                                    </li>
                                    <li>
                                        I have not knowingly provided any false, misleading, or fraudulent information.
                                    </li>
                                    <li>
                                        I understand that providing false information may result in immediate
                                        termination of access and potential legal consequences.
                                    </li>
                                    <li>
                                        I am authorized to submit this application on behalf of the business entity.
                                    </li>
                                </ul>
                            </div>
                        </div>

                        <!-- License Accepted -->
                        <div class="form-group">
                            <label class="flex items-center space-x-2 text-sm font-medium text-gray-300">
                                <input
                                    v-model="formData.licenseAccepted"
                                    type="checkbox"
                                    required
                                    class="w-4 h-4 rounded border-gray-600 bg-[#1a1a2e]/50 text-indigo-600 focus:ring-indigo-500/50"
                                />
                                <span>I have read and accept the license terms</span>
                            </label>
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
    {
        id: 5,
        title: 'License Agreement',
        description: 'Please review and accept the license terms',
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

    // License Agreement
    licenseAccepted: false,
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
        case 5:
            return formData.value.licenseAccepted;
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
