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
use MyMythicalID\Chat\User\Can;
use MyMythicalID\Chat\columns\UserColumns;
use MyMythicalID\Chat\User\UserActivities;
use MyMythicalID\CloudFlare\CloudFlareRealIP;
use MyMythicalID\Chat\interface\UserActivitiesTypes;

$router->post('/api/admin/settings/update', function (): void {
    App::init();
    $appInstance = App::getInstance(true);
    $appInstance->allowOnlyPOST();
    $config = $appInstance->getConfig();
    $session = new MyMythicalID\Chat\User\Session($appInstance);

    if (Can::canAccessAdminUI($session->getInfo(UserColumns::ROLE_ID, false))) {
        if (isset($_POST['key']) && isset($_POST['value'])) {
            $key = $_POST['key'];
            $value = $_POST['value'];
            if ($value == '' || $value == null || $key == '' || $key == null) {
                $appInstance->BadRequest('Invalid request', ['error_code' => 'INVALID_REQUEST']);
            }

            $config = $config->setSetting($key, $value);
            if ($config) {
                UserActivities::add(
                    $session->getInfo(UserColumns::UUID, false),
                    UserActivitiesTypes::$admin_settings_update,
                    CloudFlareRealIP::getRealIP(),
                    "Updated setting $key"
                );
                $appInstance->OK('Settings updated successfully.', []);
            } else {
                $appInstance->InternalServerError('Failed to update settings', ['error_code' => 'SERVICE_UNAVAILABLE']);
            }
        } else {
            $appInstance->BadRequest('Invalid request', ['error_code' => 'INVALID_REQUEST']);
        }
    } else {
        $appInstance->Unauthorized('Unauthorized', ['error_code' => 'INVALID_SESSION']);
    }
});

$router->get('/api/admin/settings/get', function (): void {
    App::init();
    $appInstance = App::getInstance(true);
    $appInstance->allowOnlyGET();
    $session = new MyMythicalID\Chat\User\Session($appInstance);

    if (Can::canAccessAdminUI($session->getInfo(UserColumns::ROLE_ID, false))) {
        $config = $appInstance->getConfig();
        $appInstance->OK('Settings retrieved successfully.', [
            'settings' => $config->dumpSettings(),
        ]);
    } else {
        $appInstance->Unauthorized('Unauthorized', ['error_code' => 'INVALID_SESSION']);
    }
});
