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
use MyMythicalID\Chat\LicenseKey\LicenseKey;

$router->get('/api/admin/license-keys', function (): void {
    App::init();
    $appInstance = App::getInstance(true);
    $appInstance->allowOnlyGET();
    $session = new MyMythicalID\Chat\User\Session($appInstance);

    if (Can::canAccessAdminUI($session->getInfo(UserColumns::ROLE_ID, false))) {
        $licenses = LicenseKey::getAll();
        $appInstance->OK('License keys fetched successfully', ['licenses' => $licenses]);
    } else {
        $appInstance->Unauthorized('Unauthorized', ['error_code' => 'INVALID_SESSION']);
    }
});

$router->post('/api/admin/license-key/create', function (): void {
    App::init();
    $appInstance = App::getInstance(true);
    $appInstance->allowOnlyPOST();
    $session = new MyMythicalID\Chat\User\Session($appInstance);

    if (Can::canAccessAdminUI($session->getInfo(UserColumns::ROLE_ID, false))) {
        if (isset($_POST['project']) && isset($_POST['uuid']) && isset($_POST['license_key_uuid'])
            && isset($_POST['context']) && isset($_POST['status']) && isset($_POST['expires_at']) && isset($_POST['instance'])) {

            $projectId = (int) $_POST['project'];
            $userUuid = $_POST['uuid'];
            $licenseKeyUuid = $_POST['license_key_uuid'];
            $context = $_POST['context'];
            $status = $_POST['status'];
            $instance = $_POST['instance'];
            $expiresAt = $_POST['expires_at'];

            $license = LicenseKey::create($projectId, $userUuid, $licenseKeyUuid, $context, $status, $instance, $expiresAt);

            if ($license) {
                $licenseData = LicenseKey::getById($license);
                $appInstance->OK('License key created successfully', ['license_key' => $licenseData]);
            } else {
                $appInstance->BadRequest('Failed to create license key', ['error_code' => 'FAILED_TO_CREATE_LICENSE']);
            }
        } else {
            $appInstance->BadRequest('Missing required fields', ['error_code' => 'MISSING_REQUIRED_FIELDS']);
        }
    } else {
        $appInstance->Unauthorized('Unauthorized', ['error_code' => 'INVALID_SESSION']);
    }
});

$router->post('/api/admin/license-key/(.*)/update', function (string $licenseKeyId): void {
    App::init();
    $appInstance = App::getInstance(true);
    $appInstance->allowOnlyPOST();
    $session = new MyMythicalID\Chat\User\Session($appInstance);

    if (Can::canAccessAdminUI($session->getInfo(UserColumns::ROLE_ID, false))) {
        if (isset($_POST['project']) && isset($_POST['uuid']) && isset($_POST['license_key_uuid'])
            && isset($_POST['context']) && isset($_POST['status']) && isset($_POST['expires_at']) && isset($_POST['instance'])) {

            $data = [
                'project' => (int) $_POST['project'],
                'uuid' => $_POST['uuid'],
                'license_key_uuid' => $_POST['license_key_uuid'],
                'context' => $_POST['context'],
                'status' => $_POST['status'],
                'expires_at' => $_POST['expires_at'],
                'instance' => $_POST['instance'],
            ];

            $license = LicenseKey::update((int) $licenseKeyId, $data);

            if ($license) {
                $licenseData = LicenseKey::getById((int) $licenseKeyId);
                $appInstance->OK('License key updated successfully', ['license_key' => $licenseData]);
            } else {
                $appInstance->BadRequest('Failed to update license key', ['error_code' => 'FAILED_TO_UPDATE_LICENSE']);
            }
        } else {
            $appInstance->BadRequest('Missing required fields', ['error_code' => 'MISSING_REQUIRED_FIELDS']);
        }
    } else {
        $appInstance->Unauthorized('Unauthorized', ['error_code' => 'INVALID_SESSION']);
    }
});

$router->post('/api/admin/license-key/(.*)/delete', function (string $licenseKeyId): void {
    App::init();
    $appInstance = App::getInstance(true);
    $appInstance->allowOnlyPOST();
    $session = new MyMythicalID\Chat\User\Session($appInstance);

    if (Can::canAccessAdminUI($session->getInfo(UserColumns::ROLE_ID, false))) {
        $license = LicenseKey::delete((int) $licenseKeyId);

        if ($license) {
            $appInstance->OK('License key deleted successfully', ['license_key' => $license]);
        } else {
            $appInstance->BadRequest('Failed to delete license key', ['error_code' => 'FAILED_TO_DELETE_LICENSE']);
        }
    } else {
        $appInstance->Unauthorized('Unauthorized', ['error_code' => 'INVALID_SESSION']);
    }
});

$router->get('/api/admin/license-key/(.*)/info', function (string $licenseKeyId): void {
    App::init();
    $appInstance = App::getInstance(true);
    $appInstance->allowOnlyGET();
    $session = new MyMythicalID\Chat\User\Session($appInstance);

    if (Can::canAccessAdminUI($session->getInfo(UserColumns::ROLE_ID, false))) {
        $license = LicenseKey::getById((int) $licenseKeyId);
        if ($license) {
            $appInstance->OK('License key fetched successfully', ['license_key' => $license]);
        } else {
            $appInstance->NotFound('License key not found', ['error_code' => 'LICENSE_NOT_FOUND']);
        }
    } else {
        $appInstance->Unauthorized('Unauthorized', ['error_code' => 'INVALID_SESSION']);
    }
});

$router->get('/api/admin/license/project/(.*)', function (string $projectId): void {
    App::init();
    $appInstance = App::getInstance(true);
    $appInstance->allowOnlyGET();
    $session = new MyMythicalID\Chat\User\Session($appInstance);

    if (Can::canAccessAdminUI($session->getInfo(UserColumns::ROLE_ID, false))) {
        $licenses = LicenseKey::getByProjectId((int) $projectId);
        $appInstance->OK('Project licenses fetched successfully', ['licenses' => $licenses]);
    } else {
        $appInstance->Unauthorized('Unauthorized', ['error_code' => 'INVALID_SESSION']);
    }
});

$router->get('/api/admin/license/user/(.*)', function (string $userUuid): void {
    App::init();
    $appInstance = App::getInstance(true);
    $appInstance->allowOnlyGET();
    $session = new MyMythicalID\Chat\User\Session($appInstance);

    if (Can::canAccessAdminUI($session->getInfo(UserColumns::ROLE_ID, false))) {
        $licenses = LicenseKey::getByUserUuid($userUuid);
        $appInstance->OK('User licenses fetched successfully', ['licenses' => $licenses]);
    } else {
        $appInstance->Unauthorized('Unauthorized', ['error_code' => 'INVALID_SESSION']);
    }
});

$router->get('/api/admin/license/active', function (): void {
    App::init();
    $appInstance = App::getInstance(true);
    $appInstance->allowOnlyGET();
    $session = new MyMythicalID\Chat\User\Session($appInstance);

    if (Can::canAccessAdminUI($session->getInfo(UserColumns::ROLE_ID, false))) {
        $licenses = LicenseKey::getActive();
        $appInstance->OK('Active licenses fetched successfully', ['licenses' => $licenses]);
    } else {
        $appInstance->Unauthorized('Unauthorized', ['error_code' => 'INVALID_SESSION']);
    }
});

$router->get('/api/admin/license/expired', function (): void {
    App::init();
    $appInstance = App::getInstance(true);
    $appInstance->allowOnlyGET();
    $session = new MyMythicalID\Chat\User\Session($appInstance);

    if (Can::canAccessAdminUI($session->getInfo(UserColumns::ROLE_ID, false))) {
        $licenses = LicenseKey::getExpired();
        $appInstance->OK('Expired licenses fetched successfully', ['licenses' => $licenses]);
    } else {
        $appInstance->Unauthorized('Unauthorized', ['error_code' => 'INVALID_SESSION']);
    }
});

$router->post('/api/admin/license/(.*)/restore', function (string $licenseId): void {
    App::init();
    $appInstance = App::getInstance(true);
    $appInstance->allowOnlyPOST();
    $session = new MyMythicalID\Chat\User\Session($appInstance);

    if (Can::canAccessAdminUI($session->getInfo(UserColumns::ROLE_ID, false))) {
        $license = LicenseKey::restore((int) $licenseId);

        if ($license) {
            $appInstance->OK('License restored successfully', ['license' => $license]);
        } else {
            $appInstance->BadRequest('Failed to restore license', ['error_code' => 'FAILED_TO_RESTORE_LICENSE']);
        }
    } else {
        $appInstance->Unauthorized('Unauthorized', ['error_code' => 'INVALID_SESSION']);
    }
});

$router->post('/api/admin/license/(.*)/lock', function (string $licenseId): void {
    App::init();
    $appInstance = App::getInstance(true);
    $appInstance->allowOnlyPOST();
    $session = new MyMythicalID\Chat\User\Session($appInstance);

    if (Can::canAccessAdminUI($session->getInfo(UserColumns::ROLE_ID, false))) {
        $license = LicenseKey::lock((int) $licenseId);

        if ($license) {
            $appInstance->OK('License locked successfully', ['license' => $license]);
        } else {
            $appInstance->BadRequest('Failed to lock license', ['error_code' => 'FAILED_TO_LOCK_LICENSE']);
        }
    } else {
        $appInstance->Unauthorized('Unauthorized', ['error_code' => 'INVALID_SESSION']);
    }
});

$router->post('/api/admin/license/(.*)/unlock', function (string $licenseId): void {
    App::init();
    $appInstance = App::getInstance(true);
    $appInstance->allowOnlyPOST();
    $session = new MyMythicalID\Chat\User\Session($appInstance);

    if (Can::canAccessAdminUI($session->getInfo(UserColumns::ROLE_ID, false))) {
        $license = LicenseKey::unlock((int) $licenseId);

        if ($license) {
            $appInstance->OK('License unlocked successfully', ['license' => $license]);
        } else {
            $appInstance->BadRequest('Failed to unlock license', ['error_code' => 'FAILED_TO_UNLOCK_LICENSE']);
        }
    } else {
        $appInstance->Unauthorized('Unauthorized', ['error_code' => 'INVALID_SESSION']);
    }
});
