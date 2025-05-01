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

$router->get('/api/system/license/(.*)/info', function (string $licenseKey): void {
    App::init();
    $appInstance = App::getInstance(true);
    $appInstance->allowOnlyGET();

    $data = $appInstance->addLicenseCheck($licenseKey, $appInstance);

    $appInstance->OK('MythicalZero', ['data' => $data]);
});
