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

use MyMythicalID\App;
use MyMythicalID\Chat\User\User;
use MyMythicalID\Chat\User\Verification;
use MyMythicalID\Config\ConfigInterface;
use MyMythicalID\Chat\columns\UserColumns;
use MyMythicalID\Chat\User\UserActivities;
use MyMythicalID\CloudFlare\CloudFlareRealIP;
use MyMythicalID\Chat\interface\UserActivitiesTypes;
use MyMythicalID\Chat\columns\EmailVerificationColumns;
use MyMythicalID\Hooks\MythicalSystems\CloudFlare\Turnstile;

$router->get('/api/user/auth/reset', function (): void {
    App::init();
    $appInstance = App::getInstance(true);
    $config = $appInstance->getConfig();

    $appInstance->allowOnlyGET();

    if (isset($_GET['code']) && $_GET['code'] != '') {
        $code = $_GET['code'];

        if (Verification::verify($code, EmailVerificationColumns::$type_password)) {
            $appInstance->OK('Code is valid', ['reset_code' => $code]);
        } else {
            $appInstance->BadRequest('Bad Request', ['error_code' => 'INVALID_CODE']);
        }
    } else {
        $appInstance->BadRequest('Bad Request', ['error_code' => 'MISSING_CODE']);
    }
});

$router->post('/api/user/auth/reset', function (): void {
    App::init();
    $appInstance = App::getInstance(true);
    $config = $appInstance->getConfig();

    $appInstance->allowOnlyPOST();

    if (!isset($_POST['email_code']) || $_POST['email_code'] == '') {
        $appInstance->BadRequest('Bad Request', ['error_code' => 'MISSING_CODE']);
    }

    if (!isset($_POST['password']) || $_POST['password'] == '') {
        $appInstance->BadRequest('Bad Request', ['error_code' => 'MISSING_PASSWORD']);
    }

    if (!isset($_POST['confirmPassword']) || $_POST['confirmPassword'] == '') {
        $appInstance->BadRequest('Bad Request', ['error_code' => 'MISSING_PASSWORD_CONFIRM']);
    }

    if ($_POST['password'] != $_POST['confirmPassword']) {
        $appInstance->BadRequest('Bad Request', ['error_code' => 'PASSWORDS_DO_NOT_MATCH']);
    }

    $code = $_POST['email_code'];
    $password = $_POST['password'];

    /**
     * Process the turnstile response.
     *
     * IF the turnstile is enabled
     */
    if ($appInstance->getConfig()->getSetting(ConfigInterface::TURNSTILE_ENABLED, 'false') == 'true') {
        if (!isset($_POST['turnstileResponse']) || $_POST['turnstileResponse'] == '') {
            $appInstance->BadRequest('Bad Request', ['error_code' => 'TURNSTILE_FAILED']);
        }
        $cfTurnstileResponse = $_POST['turnstileResponse'];
        if (!Turnstile::validate($cfTurnstileResponse, CloudFlareRealIP::getRealIP(), $config->getSetting(ConfigInterface::TURNSTILE_KEY_PRIV, 'XXXX'))) {
            $appInstance->BadRequest('Invalid TurnStile Key', ['error_code' => 'TURNSTILE_FAILED']);
        }
    }

    if (Verification::verify($code, EmailVerificationColumns::$type_password)) {
        $uuid = Verification::getUserUUID($code);
        if ($uuid == null || $uuid == '') {
            $appInstance->BadRequest('Bad Request', ['error_code' => 'INVALID_CODE']);
        }
        $userToken = User::getTokenFromUUID($uuid);
        if ($userToken == null || $userToken == '') {
            $appInstance->BadRequest('Bad Request', ['error_code' => 'INVALID_CODE']);
        }
        try {
            $userInfoArray = User::getInfoArray($userToken, [
                UserColumns::VERIFIED,
                UserColumns::BANNED,
                UserColumns::DELETED,
                UserColumns::USERNAME,
                UserColumns::TWO_FA_ENABLED,
                UserColumns::TWO_FA_BLOCKED,
                UserColumns::EMAIL,
                UserColumns::PASSWORD,
                UserColumns::FIRST_NAME,
                UserColumns::LAST_NAME,
                UserColumns::UUID,
            ], [
                UserColumns::FIRST_NAME,
                UserColumns::LAST_NAME,
                UserColumns::PASSWORD,
            ]);
        } catch (Exception $e) {
            $appInstance->getLogger()->error('Failed to get user info: ' . $e->getMessage());
            $appInstance->InternalServerError('Internal Server Error', ['error_code' => 'DATABASE_ERROR']);
        }

        $appInstance->getLogger()->debug('User info array: ' . json_encode($userInfoArray));

        if (User::updateInfo($userToken, UserColumns::PASSWORD, $password, true) == true) {
            Verification::delete($code);
            $token = App::getInstance(true)->encrypt(date('Y-m-d H:i:s') . $uuid . random_bytes(16) . base64_encode($code));
            User::updateInfo($userToken, UserColumns::ACCOUNT_TOKEN, $token, true);
            UserActivities::add(
                $userInfoArray[UserColumns::UUID],
                UserActivitiesTypes::$change_password,
                CloudFlareRealIP::getRealIP()
            );
            $appInstance->OK('Password has been reset', []);
        } else {
            $appInstance->BadRequest('Failed to reset password', ['error_code' => 'FAILED_TO_RESET_PASSWORD']);
        }
    } else {
        $appInstance->BadRequest('Bad Request', ['error_code' => 'INVALID_CODE']);
    }
});
