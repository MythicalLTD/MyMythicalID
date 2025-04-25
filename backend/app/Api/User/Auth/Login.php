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

namespace MyMythicalID\Api\User\Auth;

use MyMythicalID\App;
use MyMythicalID\Mail\Mail;
use MyMythicalID\Chat\User\User;
use MyMythicalID\Config\ConfigInterface;
use MyMythicalID\Chat\columns\UserColumns;
use MyMythicalID\CloudFlare\CloudFlareRealIP;
use MyMythicalID\Hooks\MythicalSystems\CloudFlare\Turnstile;

$router->add('/api/user/auth/login', function (): void {
    $appInstance = App::getInstance(true);
    $config = $appInstance->getConfig();

    $appInstance->allowOnlyPOST();

    // Check login field
    if (!isset($_POST['login']) || $_POST['login'] == '') {
        $appInstance->BadRequest('Bad Request', ['error_code' => 'MISSING_LOGIN']);
    }

    // Check password field
    if (!isset($_POST['password']) || $_POST['password'] == '') {
        $appInstance->BadRequest('Bad Request', ['error_code' => 'MISSING_PASSWORD']);
    }

    // Process turnstile if enabled
    if ($appInstance->getConfig()->getSetting(ConfigInterface::TURNSTILE_ENABLED, 'false') == 'true') {
        if (!isset($_POST['turnstileResponse']) || $_POST['turnstileResponse'] == '') {
            $appInstance->BadRequest('Bad Request', ['error_code' => 'TURNSTILE_FAILED']);
        }

        $cfTurnstileResponse = $_POST['turnstileResponse'];
        if (!Turnstile::validate($cfTurnstileResponse, CloudFlareRealIP::getRealIP(), $config->getSetting(ConfigInterface::TURNSTILE_KEY_PRIV, 'XXXX'))) {
            $appInstance->BadRequest('Invalid TurnStile Key', ['error_code' => 'TURNSTILE_FAILED']);
        }
    }

    $login = $_POST['login'];
    $password = $_POST['password'];

    $loginResult = User::login($login, $password);
    if ($loginResult == 'false') {
        $appInstance->BadRequest('Invalid login credentials', ['error_code' => 'INVALID_CREDENTIALS']);
    }

    try {
        $userInfoArray = User::getInfoArray($loginResult, [
            UserColumns::VERIFIED,
            UserColumns::BANNED,
            UserColumns::DELETED,
            UserColumns::USERNAME,
            UserColumns::TWO_FA_ENABLED,
            UserColumns::TWO_FA_BLOCKED,
            UserColumns::EMAIL,
            UserColumns::PASSWORD,
            UserColumns::UUID,
            UserColumns::FIRST_NAME,
            UserColumns::LAST_NAME,
            UserColumns::CREDITS,
            UserColumns::DISCORD_ID,
            UserColumns::GITHUB_ID,
        ], [
            UserColumns::FIRST_NAME,
            UserColumns::LAST_NAME,
            UserColumns::PASSWORD,
        ]);
    } catch (\Exception $e) {
        $appInstance->getLogger()->error('Failed to get user info: ' . $e->getMessage());
        $appInstance->InternalServerError('Internal Server Error', ['error_code' => 'DATABASE_ERROR']);
    }

    // Check account verification if mail is enabled
    if ($userInfoArray[UserColumns::VERIFIED] == 'false' && Mail::isEnabled()) {
        User::logout();
        $appInstance->BadRequest('Account not verified', ['error_code' => 'ACCOUNT_NOT_VERIFIED']);
    }

    $appInstance->getLogger()->debug('User info array: ' . json_encode($userInfoArray));

    // Check if account is banned
    if (!$userInfoArray[UserColumns::BANNED] == 'NO') {
        User::logout();
        $appInstance->BadRequest('Account is banned', ['error_code' => 'ACCOUNT_BANNED']);
    }

    // Check if account is deleted
    if ($userInfoArray[UserColumns::DELETED] == 'true') {
        User::logout();
        $appInstance->BadRequest('Account is deleted', ['error_code' => 'ACCOUNT_DELETED']);
    }

    // Handle 2FA if enabled
    if ($userInfoArray[UserColumns::TWO_FA_ENABLED] == 'true') {
        User::updateInfo($login, UserColumns::TWO_FA_BLOCKED, 'true', false);
    }

    // Set cookie based on debug mode
    if (APP_DEBUG) {
        // Set the cookie to expire in 1 year if the app is in debug mode
        setcookie('user_token', $loginResult, time() + 3600 * 31 * 3600, '/');
    } else {
        setcookie('user_token', $loginResult, time() + 3600, '/');
    }

    $appInstance->OK('Successfully logged in', []);
});
