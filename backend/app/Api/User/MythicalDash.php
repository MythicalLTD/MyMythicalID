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
use MyMythicalID\Chat\Project\Project;
use MyMythicalID\Chat\columns\UserColumns;
use MyMythicalID\Chat\LicenseKey\LicenseKey;
use MyMythicalID\Chat\Project\MythicalDashInstance;

$router->post('/api/user/mythicaldash/create', function (): void {
    App::init();
    $appInstance = App::getInstance(true);
    $appInstance->allowOnlyPOST();

    $session = new MyMythicalID\Chat\User\Session($appInstance);

    $allowedFields = [
        'companyName',
        'companyWebsite',
        'businessDescription',
        'hostingType',
        'currentUsers',
        'expectedUsers',
        'instanceUrl',
        'serverType',
        'serverCount',
        'primaryEmail',
        'abuseEmail',
        'supportEmail',
        'ownerFirstName',
        'ownerLastName',
        'ownerBirthDate',
    ];

    $updateData = [];
    foreach ($allowedFields as $field) {
        if (isset($_POST[$field])) {
            $updateData[$field] = $_POST[$field];
        }
    }

    if (!empty($updateData)) {
        // Validate project exists
        if (!Project::getById((int) $_POST['project'])) {
            $appInstance->BadRequest('Project not found', ['error_code' => 'PROJECT_NOT_FOUND']);

            return;
        }

        // Validate user exists
        if (!User::exists(UserColumns::UUID, (string) $_POST['user'])) {
            $appInstance->BadRequest('User not found', ['error_code' => 'USER_NOT_FOUND']);

            return;
        }

        // Generate UUID for the instance
        $uuid = 'xxxxxxxx-xxxx-4xxx-yxxx-xxxxxxxxxxxx';
        $uuid = preg_replace_callback('/[xy]/', function ($matches) {
            $r = mt_rand(0, 15);
            $v = $matches[0] === 'x' ? $r : ($r & 0x3 | 0x8);

            return dechex($v);
        }, $uuid);

        // Create license key first
        $licenseKey = LicenseKey::create(
            (int) $_POST['project'],
            (string) $_POST['user'],
            $uuid,
            'MythicalDash Free License',
            'active',
            date('Y-m-d H:i:s', strtotime('+5 year'))
        );

        if (!$licenseKey) {
            $appInstance->BadRequest('Failed to create license key', ['error_code' => 'FAILED_TO_CREATE_LICENSE']);

            return;
        }

        $instanceUUID = $uuid;

        // Create the instance with the license key ID
        $instance = MythicalDashInstance::create(
            uuid: $instanceUUID,
            user: $_POST['user'],
            project: (int) $_POST['project'],
            licenseKey: $licenseKey,
            companyName: $_POST['companyName'],
            companyWebsite: $_POST['companyWebsite'],
            businessDescription: $_POST['businessDescription'],
            hostingType: $_POST['hostingType'],
            currentUsers: (int) $_POST['currentUsers'],
            expectedUsers: (int) $_POST['expectedUsers'],
            instanceUrl: $_POST['instanceUrl'],
            serverType: $_POST['serverType'],
            serverCount: (int) $_POST['serverCount'],
            primaryEmail: $_POST['primaryEmail'],
            abuseEmail: $_POST['abuseEmail'],
            supportEmail: $_POST['supportEmail'],
            ownerFirstName: $_POST['ownerFirstName'],
            ownerLastName: $_POST['ownerLastName'],
            ownerBirthDate: $_POST['ownerBirthDate']
        );

        if ($instance) {
            $context = 'Company Name: ' . $_POST['companyName'] . " \n" .
                'Company Website: ' . $_POST['companyWebsite'] . " \n" .
                'Business Description: ' . $_POST['businessDescription'] . " \n" .
                'Hosting Type: ' . $_POST['hostingType'] . " \n" .
                'Current Users: ' . $_POST['currentUsers'] . " \n" .
                'Expected Users: ' . $_POST['expectedUsers'] . " \n" .
                'Instance URL: ' . $_POST['instanceUrl'] . " \n" .
                'Server Type: ' . $_POST['serverType'] . " \n" .
                'Server Count: ' . $_POST['serverCount'] . " \n" .
                'Primary Email: ' . $_POST['primaryEmail'] . " \n" .
                'Abuse Email: ' . $_POST['abuseEmail'] . " \n" .
                'Support Email: ' . $_POST['supportEmail'] . " \n" .
                'Owner First Name: ' . $_POST['ownerFirstName'] . " \n" .
                'Owner Last Name: ' . $_POST['ownerLastName'] . " \n" .
                'Owner Birth Date: ' . $_POST['ownerBirthDate'] . " \n" .
                'License Key: ' . $uuid . " \n" .
                'License Key UUID: ' . $uuid . " \n";

            LicenseKey::update($licenseKey, [
                'context' => $context,
            ]);
            $appInstance->OK('MythicalDash instance created successfully', ['instance' => $instance]);
        } else {
            // If instance creation fails, delete the license key
            LicenseKey::delete($licenseKey);
            $appInstance->BadRequest('Failed to create MythicalDash instance', ['error_code' => 'FAILED_TO_CREATE_INSTANCE']);
        }
    } else {
        $appInstance->BadRequest('Invalid request', ['error_code' => 'INVALID_REQUEST']);
    }
});

$router->get('/api/user/mythicaldash/instances', function (): void {
    App::init();
    $appInstance = App::getInstance(true);
    $appInstance->allowOnlyGET();

    $session = new MyMythicalID\Chat\User\Session($appInstance);
});

$router->get('/api/admin/mythicaldash/instance/user/(.*)', function (string $userUuid): void {
    App::init();
    $appInstance = App::getInstance(true);
    $appInstance->allowOnlyGET();

    $session = new MyMythicalID\Chat\User\Session($appInstance);

    if (!User::exists(UserColumns::UUID, $userUuid)) {
        $appInstance->BadRequest('User not found', ['error_code' => 'USER_NOT_FOUND']);

        return;
    }

    if ($session->getInfo(UserColumns::UUID, false) !== $userUuid) {
        $appInstance->BadRequest('User not found', ['error_code' => 'USER_NOT_FOUND']);

        return;
    }

    $instances = MythicalDashInstance::getByUser($userUuid);

    if ($instances === false) {
        $appInstance->InternalServerError('Failed to fetch instances', ['error_code' => 'FAILED_TO_FETCH_INSTANCES']);

        return;
    }

    $appInstance->OK('Instances fetched successfully', ['instances' => $instances]);

});

$router->post('/api/user/mythicaldash/instances/(.*)/upgrade', function (string $instanceId): void {
    App::init();
    $appInstance = App::getInstance(true);
    $appInstance->allowOnlyPOST();

    $session = new MyMythicalID\Chat\User\Session($appInstance);

    if (!MythicalDashInstance::exists((int) $instanceId)) {
        $appInstance->BadRequest('Instance not found', ['error_code' => 'INSTANCE_NOT_FOUND']);

        return;

    }
    $instance = MythicalDashInstance::getById((int) $instanceId);
    $project = Project::getById(5);
    $coins = $session->getInfo(UserColumns::CREDITS, false);

    if ($coins < $project['price']) {
        $appInstance->BadRequest('Insufficient coins', ['error_code' => 'INSUFFICIENT_COINS']);

        return;
    }

    $newCoins = $coins - $project['price'];
    User::updateInfo(User::getTokenFromUUID($session->getInfo(UserColumns::UUID, false)), UserColumns::CREDITS, $newCoins, false);
    if (LicenseKey::exists((int) $instance['license_key'])) {
        $keyInfo = LicenseKey::getById((int) $instance['license_key']);

        LicenseKey::update((int) $instance['license_key'], [
            'status' => 'inactive',
            'context' => 'MythicalDash instance upgraded to premium' . $keyInfo['context'],
        ]);

        LicenseKey::create(
            5,
            $instance['user'],
            $instance['uuid'],
            'MythicalDash Premium License',
            date('Y-m-d H:i:s', strtotime('+1 month')),
            $instance['id'],
            'active',
        );
        $appInstance->OK('MythicalDash instance upgraded successfully', ['instance' => $instance]);

    } else {
        $appInstance->BadRequest('Failed to upgrade MythicalDash instance', ['error_code' => 'FAILED_TO_UPGRADE_INSTANCE']);

        return;
    }

});
