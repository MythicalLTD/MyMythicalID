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

namespace MyMythicalID\Chat\columns;

class UserColumns
{
    public const USERNAME = 'username';
    public const PASSWORD = 'password';
    public const EMAIL = 'email';
    public const FIRST_NAME = 'first_name';
    public const LAST_NAME = 'last_name';
    public const AVATAR = 'avatar';
    public const CREDITS = 'credits';
    public const UUID = 'uuid';
    public const ACCOUNT_TOKEN = 'token';
    public const ROLE_ID = 'role';
    public const FIRST_IP = 'first_ip';
    public const LAST_IP = 'last_ip';
    public const BANNED = 'banned';
    public const VERIFIED = 'verified';
    public const SUPPORT_PIN = 'support_pin';
    public const TWO_FA_ENABLED = '2fa_enabled';
    public const TWO_FA_KEY = '2fa_key';
    public const TWO_FA_BLOCKED = '2fa_blocked';
    public const DELETED = 'deleted';
    public const LAST_SEEN = 'last_seen';
    public const FIRST_SEEN = 'first_seen';
    public const BACKGROUND = 'background';

    public const ID = 'id';

    public const DISCORD_ID = 'discord_id';
    public const DISCORD_USERNAME = 'discord_username';
    public const DISCORD_GLOBAL_NAME = 'discord_global_name';
    public const DISCORD_EMAIL = 'discord_email';
    public const DISCORD_LINKED = 'discord_linked';

    public const GITHUB_ID = 'github_id';
    public const GITHUB_USERNAME = 'github_username';
    public const GITHUB_EMAIL = 'github_email';
    public const GITHUB_LINKED = 'github_linked';

    /**
     * @return string[]
     */
    public static function getColumns(): array
    {
        return [
            self::ID,
            self::USERNAME,
            self::PASSWORD,
            self::EMAIL,
            self::FIRST_NAME,
            self::LAST_NAME,
            self::AVATAR,
            self::CREDITS,
            self::UUID,
            self::ACCOUNT_TOKEN,
            self::ROLE_ID,
            self::FIRST_IP,
            self::LAST_IP,
            self::BANNED,
            self::SUPPORT_PIN,
            self::VERIFIED,
            self::TWO_FA_ENABLED,
            self::TWO_FA_KEY,
            self::TWO_FA_BLOCKED,
            self::DELETED,
            self::LAST_SEEN,
            self::FIRST_SEEN,
            self::BACKGROUND,
            self::DISCORD_ID,
            self::DISCORD_USERNAME,
            self::DISCORD_GLOBAL_NAME,
            self::DISCORD_EMAIL,
            self::DISCORD_LINKED,
            self::GITHUB_ID,
            self::GITHUB_USERNAME,
            self::GITHUB_EMAIL,
            self::GITHUB_LINKED,
        ];
    }
}
