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
use MyMythicalID\Chat\User\Session;
use MyMythicalID\Config\ConfigInterface;
use MyMythicalID\Chat\columns\UserColumns;
use MyMythicalID\Chat\User\UserActivities;
use MyMythicalID\CloudFlare\CloudFlareRealIP;
use MyMythicalID\Chat\interface\UserActivitiesTypes;

$router->get('/api/user/auth/callback/discord/link', function () {
    App::init();
    $appInstance = App::getInstance(true);
    $config = $appInstance->getConfig();
    $s = new Session($appInstance);
    if (
        $config->getSetting(ConfigInterface::DISCORD_ENABLED, 'false') === 'false'
        || $config->getSetting(ConfigInterface::DISCORD_CLIENT_ID, '') === ''
        || $config->getSetting(ConfigInterface::DISCORD_CLIENT_SECRET, '') === ''
    ) {
        header('Location: /account?error=discord_not_enabled');
        exit;
    }

    $appId = $config->getSetting(ConfigInterface::DISCORD_CLIENT_ID, '');
    $appSecret = $config->getSetting(ConfigInterface::DISCORD_CLIENT_SECRET, '');
    $url = $config->getSetting(ConfigInterface::APP_URL, 'https://mymythicalid-v3.mythical.systems');
    $redirectUri = $url . '/api/user/auth/callback/discord/link';

    if (isset($_GET['code'])) {
        $code = $_GET['code'];
        $tokenUrl = 'https://discord.com/api/oauth2/token';
        $data = [
            'client_id' => $appId,
            'client_secret' => $appSecret,
            'grant_type' => 'authorization_code',
            'code' => $code,
            'redirect_uri' => $redirectUri,
            'scope' => 'identify guilds email guilds.join',
        ];
        $options = [
            'http' => [
                'header' => "Content-type: application/x-www-form-urlencoded\r\n",
                'method' => 'POST',
                'content' => http_build_query($data),
            ],
        ];
        $context = stream_context_create($options);
        $result = file_get_contents($tokenUrl, false, $context);
        $accessToken = json_decode($result, true)['access_token'];

        $userUrl = 'https://discord.com/api/users/@me';

        $options = [
            'http' => [
                'header' => "Authorization: Bearer $accessToken\r\n",
                'method' => 'GET',
            ],
        ];
        $context = stream_context_create($options);
        $result = file_get_contents($userUrl, false, $context);

        $userInfo = json_decode($result, true);

        $id = $userInfo['id'];
        $username = $userInfo['username'];
        $global_name = $userInfo['global_name'];
        $email = $userInfo['email'];

        if (isset($userInfo)) {
            $s->setInfo(UserColumns::DISCORD_ID, $id, false);
            $s->setInfo(UserColumns::DISCORD_USERNAME, $username, false);
            $s->setInfo(UserColumns::DISCORD_GLOBAL_NAME, $global_name, false);
            $s->setInfo(UserColumns::DISCORD_EMAIL, $email, false);
            $s->setInfo(UserColumns::DISCORD_LINKED, 'true', false);
            UserActivities::add(
                $s->getInfo(UserColumns::UUID, false),
                UserActivitiesTypes::$discord_link,
                CloudFlareRealIP::getRealIP(),
                "Linked Discord account: $id"
            );
            header('Location: ' . $url . '/');
            exit;
        }
        header('Location: ' . $url . '/api/user/auth/callback/discord/link');

    } else {
        $authorizeUrl = 'https://discord.com/api/oauth2/authorize?client_id=' . $appId . '&redirect_uri=' . urlencode($redirectUri) . '&response_type=code&scope=' . urlencode('identify guilds email guilds.join');
        header('Location: ' . $authorizeUrl);
    }
});

$router->get('/api/user/auth/callback/discord/unlink', function () {
    App::init();
    $appInstance = App::getInstance(true);
    $config = $appInstance->getConfig();
    $s = new Session($appInstance);

    $s->setInfo(UserColumns::DISCORD_ID, null, false);
    $s->setInfo(UserColumns::DISCORD_USERNAME, null, false);
    $s->setInfo(UserColumns::DISCORD_GLOBAL_NAME, null, false);
    $s->setInfo(UserColumns::DISCORD_EMAIL, null, false);
    $s->setInfo(UserColumns::DISCORD_LINKED, 'false', false);
    UserActivities::add(
        $s->getInfo(UserColumns::UUID, false),
        UserActivitiesTypes::$discord_unlink,
        CloudFlareRealIP::getRealIP(),
        'Unlinked Discord account'
    );
    header('Location: /account');
    exit;
});

$router->get('/api/user/auth/callback/discord/login', function () {
    App::init();
    $appInstance = App::getInstance(true);
    $config = $appInstance->getConfig();

    if (
        $config->getSetting(ConfigInterface::DISCORD_ENABLED, 'false') === 'false'
        || $config->getSetting(ConfigInterface::DISCORD_CLIENT_ID, '') === ''
        || $config->getSetting(ConfigInterface::DISCORD_CLIENT_SECRET, '') === ''
    ) {
        header('Location: /account?error=discord_not_enabled');
        exit;
    }

    $appId = $config->getSetting(ConfigInterface::DISCORD_CLIENT_ID, '');
    $appSecret = $config->getSetting(ConfigInterface::DISCORD_CLIENT_SECRET, '');
    $url = $config->getSetting(ConfigInterface::APP_URL, 'https://mymythicalid-v3.mythical.systems');
    $redirectUri = $url . '/api/user/auth/callback/discord/login';

    if (isset($_GET['code'])) {
        $code = $_GET['code'];
        $tokenUrl = 'https://discord.com/api/oauth2/token';
        $data = [
            'client_id' => $appId,
            'client_secret' => $appSecret,
            'grant_type' => 'authorization_code',
            'code' => $code,
            'redirect_uri' => $redirectUri,
            'scope' => 'identify guilds email guilds.join',
        ];
        $options = [
            'http' => [
                'header' => "Content-type: application/x-www-form-urlencoded\r\n",
                'method' => 'POST',
                'content' => http_build_query($data),
            ],
        ];
        $context = stream_context_create($options);
        $result = file_get_contents($tokenUrl, false, $context);
        $accessToken = json_decode($result, true)['access_token'];

        $userUrl = 'https://discord.com/api/users/@me';

        $options = [
            'http' => [
                'header' => "Authorization: Bearer $accessToken\r\n",
                'method' => 'GET',
            ],
        ];
        $context = stream_context_create($options);
        $result = file_get_contents($userUrl, false, $context);

        $userInfo = json_decode($result, true);

        $id = $userInfo['id'];

        if (isset($userInfo)) {
            if (User::exists(UserColumns::DISCORD_ID, $id)) {
                $uuid = User::getUUIDFromDiscordID($id);
                $email = User::getInfo(User::getTokenFromUUID($uuid), UserColumns::EMAIL, false);
                $password = User::getInfo(User::getTokenFromUUID($uuid), UserColumns::PASSWORD, true);
                header('Location: ' . $url . '/auth/login?email=' . urlencode(base64_encode($email)) . '&password=' . urlencode(base64_encode($password)) . '&performLogin=true');

                UserActivities::add(
                    $uuid,
                    UserActivitiesTypes::$discord_login,
                    CloudFlareRealIP::getRealIP(),
                    'Logged in with Discord'
                );
                exit;
            }
            header('Location: ' . $url . '/auth/login?error=discord');
            exit;

        }
        header('Location: ' . $url . '/api/user/auth/callback/discord/login');

    } else {
        $authorizeUrl = 'https://discord.com/api/oauth2/authorize?client_id=' . $appId . '&redirect_uri=' . urlencode($redirectUri) . '&response_type=code&scope=' . urlencode('identify guilds email guilds.join');
        header('Location: ' . $authorizeUrl);
    }

});
