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
use MyMythicalID\Chat\Database;
use MyMythicalID\Chat\User\User;
use MyMythicalID\Chat\User\Roles;
use MyMythicalID\Chat\User\Session;
use MyMythicalID\Chat\columns\UserColumns;
use MyMythicalID\Chat\User\UserActivities;
use MyMythicalID\CloudFlare\CloudFlareRealIP;
use MyMythicalID\Chat\interface\UserActivitiesTypes;

$router->post('/api/user/session/info/update', function (): void {
    App::init();
    $appInstance = App::getInstance(true);
    $config = $appInstance->getConfig();

    $appInstance->allowOnlyPOST();
    $session = new Session($appInstance);

    try {
        if (!isset($_POST['first_name']) && $_POST['first_name'] == '') {
            $appInstance->BadRequest('First name is missing!', ['error_code' => 'FIRST_NAME_MISSING']);
        }
        if (!isset($_POST['last_name']) && $_POST['last_name'] == '') {
            $appInstance->BadRequest('Last name is missing!', ['error_code' => 'LAST_NAME_MISSING']);
        }
        if (!isset($_POST['email']) && $_POST['email'] == '') {
            $appInstance->BadRequest('Email is missing!', ['error_code' => 'EMAIL_MISSING']);
        }
        if (!isset($_POST['avatar']) && $_POST['avatar'] == '') {
            $appInstance->BadRequest('Avatar is missing!', ['error_code' => 'AVATAR_MISSING']);
        }
        if (!isset($_POST['background']) && $_POST['background'] == '') {
            $appInstance->BadRequest('Background is missing!', ['error_code' => 'BACKGROUND_MISSING']);
        }

        if ($_POST['email'] != $session->getInfo(UserColumns::EMAIL, false) && User::exists(UserColumns::EMAIL, $_POST['email'])) {
            $appInstance->BadRequest('Email already exists!', ['error_code' => 'EMAIL_EXISTS']);
        }

        $session->setInfo(UserColumns::FIRST_NAME, $_POST['first_name'], true);
        $session->setInfo(UserColumns::LAST_NAME, $_POST['last_name'], true);
        $session->setInfo(UserColumns::EMAIL, $_POST['email'], false);
        $session->setInfo(UserColumns::AVATAR, $_POST['avatar'], false);
        $session->setInfo(UserColumns::BACKGROUND, $_POST['background'], false);
        UserActivities::add(
            $session->getInfo(UserColumns::UUID, false),
            UserActivitiesTypes::$user_update,
            CloudFlareRealIP::getRealIP()
        );
        $appInstance->OK('User info updated successfully!', []);
    } catch (Exception $e) {
        $appInstance->getLogger()->error('Failed to update user info! ' . $e->getMessage());
        $appInstance->BadRequest('Bad Request', ['error_code' => 'DB_ERROR', 'error' => $e->getMessage()]);
    }
});

$router->add('/api/user/session/apiKey/reset', function (): void {
    App::init();
    $appInstance = App::getInstance(true);
    $appInstance->allowOnlyPOST();
    $session = new Session($appInstance);
    $token = 'mymythicalid_clientapi_' . App::getInstance(true)->encrypt(date('Y-m-d H:i:s') . 'MyMythicalID' . random_bytes(16) . base64_encode($appInstance->generateCode()));
    $session->setInfo(UserColumns::ACCOUNT_TOKEN, $token, false);
    UserActivities::add(
        $session->getInfo(UserColumns::UUID, false),
        UserActivitiesTypes::$user_reset_api_key,
        CloudFlareRealIP::getRealIP()
    );
    setcookie('user_token', $token, time() + (86400 * 30), '/');
    $appInstance->OK('API KEY Reset!', ['api_key' => $token]);
});

$router->add('/api/user/session/newPin', function (): void {
    App::init();
    $appInstance = App::getInstance(true);
    $appInstance->allowOnlyPOST();
    $session = new Session($appInstance);
    $pin = $appInstance->generatePin();
    try {
        $session->setInfo(UserColumns::SUPPORT_PIN, $pin, false);
        UserActivities::add(
            $session->getInfo(UserColumns::UUID, false),
            UserActivitiesTypes::$user_new_support_pin,
            CloudFlareRealIP::getRealIP()
        );

        $appInstance->OK('Support pin updated successfully!', ['pin' => $pin]);
    } catch (Exception $e) {
        $appInstance->getLogger()->error('Failed to generate new pin: ' . $e->getMessage());
        $appInstance->BadRequest('Bad Request', ['error_code' => 'DB_ERROR', 'error' => $e->getMessage()]);
    }
});

$router->get('/api/user/session', function (): void {
    App::init();
    $appInstance = App::getInstance(true);
    $appInstance->allowOnlyGET();
    $session = new Session($appInstance);
    $accountToken = $session->SESSION_KEY;
    try {
        $stats_tickets = Database::getTableColumnCount('mymythicalid_tickets', [
            'user' => User::getInfo($accountToken, UserColumns::UUID, false),
        ]);

        $stats_servers = Database::getTableColumnCount('mymythicalid_servers', [
            'user' => User::getInfo($accountToken, UserColumns::UUID, false),
        ]);

        $columns = [
            UserColumns::USERNAME,
            UserColumns::EMAIL,
            UserColumns::VERIFIED,
            UserColumns::SUPPORT_PIN,
            UserColumns::BANNED,
            UserColumns::TWO_FA_BLOCKED,
            UserColumns::TWO_FA_ENABLED,
            UserColumns::TWO_FA_KEY,
            UserColumns::FIRST_NAME,
            UserColumns::LAST_NAME,
            UserColumns::AVATAR,
            UserColumns::CREDITS,
            UserColumns::UUID,
            UserColumns::ROLE_ID,
            UserColumns::FIRST_IP,
            UserColumns::LAST_IP,
            UserColumns::DELETED,
            UserColumns::LAST_SEEN,
            UserColumns::FIRST_SEEN,
            UserColumns::BACKGROUND,

            UserColumns::DISCORD_LINKED,
            UserColumns::DISCORD_USERNAME,
            UserColumns::DISCORD_EMAIL,
            UserColumns::DISCORD_ID,

            UserColumns::GITHUB_LINKED,
            UserColumns::GITHUB_USERNAME,
            UserColumns::GITHUB_EMAIL,
            UserColumns::GITHUB_ID,

        ];

        $info = User::getInfoArray($accountToken, $columns, [
            UserColumns::FIRST_NAME,
            UserColumns::LAST_NAME,
            UserColumns::TWO_FA_KEY,
        ]);
        $info['role_name'] = Roles::getUserRoleName($info[UserColumns::UUID]);
        $info['role_real_name'] = strtolower($info['role_name']);

        $appInstance->OK('Account token is valid', [
            'user_info' => $info,
            'stats' => [
                'tickets' => $stats_tickets,
                'servers' => $stats_servers,
            ],
        ]);

    } catch (Exception $e) {
        $appInstance->BadRequest('Bad Request', ['error_code' => 'INVALID_ACCOUNT_TOKEN', 'error' => $e->getMessage()]);
    }

});

$router->get('/api/user/session/activities', function (): void {
    App::init();
    $appInstance = App::getInstance(true);
    $appInstance->allowOnlyGET();
    $session = new Session($appInstance);
    $accountToken = $session->SESSION_KEY;
    $appInstance->OK('User activities', [
        'activities' => UserActivities::get(User::getInfo($accountToken, UserColumns::UUID, false)),
    ]);
});
