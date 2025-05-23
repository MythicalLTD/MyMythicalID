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
use MyMythicalID\Chat\User\Session;
use MyMythicalID\Chat\Project\Project;
use MyMythicalID\Chat\columns\UserColumns;
use MyMythicalID\Chat\LicenseKey\LicenseKey;

$router->add('/api/user/licenses', function () {
    App::init();
    $appInstance = App::getInstance(true);
    $appInstance->allowOnlyGET();
    $s = new Session($appInstance);
    $keyParsed = [];
    $licenses = LicenseKey::getByUserUuid($s->getInfo(UserColumns::UUID, false));
    foreach ($licenses as $license) {
        $keyParsed[] = [
            'id' => $license['id'],
            'project' => Project::getById($license['project']),
            'context' => $license['context'],
            'key' => $license['license_key_uuid'],
            'status' => $license['status'],
            'expires_at' => $license['expires_at'],
        ];
    }

    $appInstance->OK('Licenses fetched successfully', ['licenses' => $keyParsed, 'total' => count($keyParsed)]);
});
