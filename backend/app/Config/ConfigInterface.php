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

interface ConfigInterface
{
    /**
     * App.
     */
    public const APP_NAME = 'app_name';
    public const APP_LANG = 'app_lang';
    public const APP_URL = 'app_url';
    public const APP_VERSION = 'app_version';
    public const APP_TIMEZONE = 'app_timezone';
    public const APP_LOGO = 'app_logo';
    public const SEO_DESCRIPTION = 'seo_description';
    public const SEO_KEYWORDS = 'seo_keywords';
    /**
     * Turnstile.
     */
    public const TURNSTILE_ENABLED = 'turnstile_enabled';
    public const TURNSTILE_KEY_PUB = 'turnstile_key_pub';
    public const TURNSTILE_KEY_PRIV = 'turnstile_key_priv';
    /**
     * SMTP.
     */
    public const SMTP_ENABLED = 'smtp_enabled';
    public const SMTP_HOST = 'smtp_host';
    public const SMTP_PORT = 'smtp_port';
    public const SMTP_USER = 'smtp_user';
    public const SMTP_PASS = 'smtp_pass';
    public const SMTP_FROM = 'smtp_from';
    public const SMTP_ENCRYPTION = 'smtp_encryption';
    /**
     * Legal Values.
     */
    public const LEGAL_TOS = 'legal_tos_url';
    public const LEGAL_PRIVACY = 'legal_privacy_url';
    /**
     * Misc.
     */
    public const WEBSITE_URL = 'website_url';
    public const STATUS_PAGE_URL = 'status_page_url';
    public const DISCORD_INVITE_URL = 'discord_invite_url';
    public const TWITTER_URL = 'twitter_url';
    public const GITHUB_URL = 'github_url';
    public const LINKEDIN_URL = 'linkedin_url';
    public const INSTAGRAM_URL = 'instagram_url';
    public const YOUTUBE_URL = 'youtube_url';
    public const TIKTOK_URL = 'tiktok_url';
    public const FACEBOOK_URL = 'facebook_url';
    public const REDDIT_URL = 'reddit_url';
    public const TELEGRAM_URL = 'telegram_url';
    public const WHATSAPP_URL = 'whatsapp_url';

    /**
     * Credits Recharge.
     */
    public const CREDITS_RECHARGE_ENABLED = 'credits_recharge_enabled';
    public const COMPANY_NAME = 'company_name';
    public const COMPANY_ADDRESS = 'company_address';
    public const COMPANY_CITY = 'company_city';
    public const COMPANY_STATE = 'company_state';
    public const COMPANY_ZIP = 'company_zip';
    public const COMPANY_COUNTRY = 'company_country';
    public const COMPANY_VAT = 'company_vat';

    /**
     * Gateway Configs.
     */
    public const ENABLE_STRIPE = 'enable_stripe';
    public const ENABLE_PAYPAL = 'enable_paypal';

    /**
     * Paypal Configs.
     */
    public const PAYPAL_CLIENT_ID = 'paypal_email';
    public const PAYPAL_IS_SANDBOX = 'paypal_is_sandbox';

    /**
     * Stripe Configs.
     */
    public const STRIPE_SECRET_KEY = 'stripe_secret_key';
    public const STRIPE_PUBLISHABLE_KEY = 'stripe_publishable_key';
    public const STRIPE_WEBHOOK_ID = 'stripe_webhook_id';

    /**
     * Currency Configs.
     */
    public const CURRENCY = 'currency';
    public const CURRENCY_SYMBOL = 'currency_symbol';

    /**
     * Discord Integration.
     */
    public const DISCORD_ENABLED = 'discord_enabled';
    public const DISCORD_SERVER_ID = 'discord_server_id';
    public const DISCORD_CLIENT_ID = 'discord_client_id';
    public const DISCORD_CLIENT_SECRET = 'discord_client_secret';
    public const DISCORD_LINK_ALLOWED = 'discord_link_allowed';

    /**
     * Github Integration.
     */
    public const GITHUB_ENABLED = 'github_enabled';
    public const GITHUB_CLIENT_ID = 'github_client_id';
    public const GITHUB_CLIENT_SECRET = 'github_client_secret';
    public const GITHUB_LINK_ALLOWED = 'github_link_allowed';

    /**
     * Allow Tickets.
     */
    public const ALLOW_TICKETS = 'allow_tickets';

}
