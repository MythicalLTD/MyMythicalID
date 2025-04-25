<?php

/*
 * This file is part of MyMythicalID.
 * Please view the LICENSE file that was distributed with this source code.
 *
 * # MythicalSystems License v2.0
 *
 * ## Copyright (c) 2021–2025 MythicalSystems and Cassian Gherman
 *
 * Breaking any of the following rules will result in a permanent ban from the MythicalSystems community and all of its services.
 */

namespace MyMythicalID\Config;

class PublicConfig extends ConfigFactory
{
    /**
     * ⚠️ DANGER ZONE - HANDLE WITH EXTREME CAUTION ⚠️.
     *
     * This is a critical configuration section that defines default values for public settings.
     * Any changes made here will affect the entire application's behavior.
     *
     * IMPORTANT SECURITY CONSIDERATIONS:
     * - This is the ONLY place where default values should be modified
     * - All values defined here are PUBLIC and accessible from the frontend
     * - These values are visible to ALL users of the application
     * - The data is also collected and sent to telemetry services
     *
     * NEVER add sensitive information such as:
     * - API keys or tokens
     * - Passwords or credentials
     * - Private configuration values
     * - Internal system details
     *
     * Any sensitive data added here will be exposed publicly and could lead to
     * security vulnerabilities. Always use proper secure storage for sensitive values.
     *
     * @return array An array of public configuration defaults
     */
    public static function getPublicSettingsWithDefaults(): array
    {
        // Define settings configuration with defaults
        return [
            // App settings
            ConfigInterface::APP_NAME => 'MyMythicalID',
            ConfigInterface::APP_LANG => 'en_US',
            ConfigInterface::APP_URL => 'framework.mythical.systems',
            ConfigInterface::APP_VERSION => '1.0.0',
            ConfigInterface::APP_TIMEZONE => 'UTC',
            ConfigInterface::APP_LOGO => 'https://github.com/mythicalltd.png',
            ConfigInterface::SEO_DESCRIPTION => 'Change this in the settings area!',
            ConfigInterface::SEO_KEYWORDS => 'some,random,keywords',

            // Turnstile settings
            ConfigInterface::TURNSTILE_ENABLED => 'false',
            ConfigInterface::TURNSTILE_KEY_PUB => 'XXXX',

            // Legal links
            ConfigInterface::LEGAL_TOS => '/tos',
            ConfigInterface::LEGAL_PRIVACY => '/privacy',

            // Email settings
            ConfigInterface::SMTP_ENABLED => 'false',

            // External URLs
            ConfigInterface::WEBSITE_URL => '',
            ConfigInterface::STATUS_PAGE_URL => '',
            ConfigInterface::DISCORD_INVITE_URL => '',
            ConfigInterface::TWITTER_URL => '',
            ConfigInterface::GITHUB_URL => '',
            ConfigInterface::LINKEDIN_URL => '',
            ConfigInterface::INSTAGRAM_URL => '',
            ConfigInterface::YOUTUBE_URL => '',
            ConfigInterface::TIKTOK_URL => '',
            ConfigInterface::FACEBOOK_URL => '',
            ConfigInterface::REDDIT_URL => '',
            ConfigInterface::TELEGRAM_URL => '',
            ConfigInterface::WHATSAPP_URL => '',

            // Company settings
            ConfigInterface::COMPANY_NAME => 'Mythical Systems',
            ConfigInterface::COMPANY_ADDRESS => '1234 Main St, Anytown, USA',
            ConfigInterface::COMPANY_CITY => 'Anytown',
            ConfigInterface::COMPANY_STATE => 'CA',
            ConfigInterface::COMPANY_ZIP => '12345',
            ConfigInterface::COMPANY_COUNTRY => 'USA',
            ConfigInterface::COMPANY_VAT => '1234567890',

            // Gateway settings
            ConfigInterface::CREDITS_RECHARGE_ENABLED => 'false',
            ConfigInterface::ENABLE_STRIPE => 'false',
            ConfigInterface::ENABLE_PAYPAL => 'false',
            ConfigInterface::PAYPAL_CLIENT_ID => '',
            ConfigInterface::PAYPAL_IS_SANDBOX => 'false',
            ConfigInterface::STRIPE_PUBLISHABLE_KEY => '',

            // Currency settings
            ConfigInterface::CURRENCY => 'EUR',
            ConfigInterface::CURRENCY_SYMBOL => '€',

            // Discord Integration
            ConfigInterface::DISCORD_ENABLED => 'false',
            ConfigInterface::DISCORD_SERVER_ID => '',
            ConfigInterface::DISCORD_CLIENT_ID => '',
            ConfigInterface::DISCORD_LINK_ALLOWED => 'false',

            // Github Integration
            ConfigInterface::GITHUB_ENABLED => 'false',
            ConfigInterface::GITHUB_CLIENT_ID => '',
            ConfigInterface::GITHUB_LINK_ALLOWED => 'false',

            // Allow Tickets
            ConfigInterface::ALLOW_TICKETS => 'false',
        ];

    }
}
