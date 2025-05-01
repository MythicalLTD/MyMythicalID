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
use MyMythicalID\Chat\ZeroTrust\ZeroTrust;

$router->put('/api/system/license/(.*)/mythicalzero/user/register', function (string $licenseKey): void {
    App::init();
    $appInstance = App::getInstance(true);
    $appInstance->allowOnlyPUT();
    $license_data = $appInstance->addLicenseCheck($licenseKey, $appInstance);
    $body = $appInstance->getBody();

    if (isset($body['email']) && isset($body['username']) && isset($body['first_name']) && isset($body['last_name']) && isset($body['ip'])) {
        $email = $body['email'];
        $username = $body['username'];
        $firstName = $body['first_name'];
        $lastName = $body['last_name'];
        $ip = $body['ip'];

        if (isset($body['os_type'])) {
            $osType = $body['os_type'];
        } else {
            $osType = 'Unknown';
        }

        if (isset($body['kernel_version'])) {
            $kernelVersion = $body['kernel_version'];
        } else {
            $kernelVersion = 'Unknown';
        }

        if (isset($body['cpu_architecture'])) {
            $cpu_architecture = $body['cpu_architecture'];
        } else {
            $cpu_architecture = 'Unknown';
        }

        $osInfo = [
            'os_type' => $osType,
            'kernel_version' => $kernelVersion,
            'cpu_architecture' => $cpu_architecture,
        ];

        $data = [
            'email' => $email,
            'username' => $username,
            'first_name' => $firstName,
            'last_name' => $lastName,
            'ip' => $ip,
        ];

        ZeroTrust::create(
            $license_data['project_info']['id'],
            $license_data['instance']['id'],
            $license_data['license_key_info']['id'],
            json_encode($osInfo),
            json_encode($data),
            'register'
        );
        $appInstance->Ok('Telemetry received', []);
    } else {
        $appInstance->BadRequest('Invalid request', [
            'error_code' => 'INVALID_REQUEST',
            'body' => $body,
        ]);
    }
});

$router->put('/api/system/license/(.*)/mythicalzero/user/login', function (string $licenseKey): void {
    App::init();
    $appInstance = App::getInstance(true);
    $appInstance->allowOnlyPUT();

    $license_data = $appInstance->addLicenseCheck($licenseKey, $appInstance);
    $body = $appInstance->getBody();

    if (
        isset($body['username'])
        && isset($body['first_name'])
        && isset($body['last_name'])
        && isset($body['email'])
        && isset($body['credits'])
        && isset($body['uuid'])
        && isset($body['ip'])
        && isset($body['banned'])
        && isset($body['verified'])
        && isset($body['discord_id'])
        && isset($body['github_id'])
    ) {
        $email = $body['email'];
        $username = $body['username'];
        $firstName = $body['first_name'];
        $lastName = $body['last_name'];
        $ip = $body['ip'];
        $credits = $body['credits'];
        $uuid = $body['uuid'];
        $banned = $body['banned'];
        $verified = $body['verified'];
        $discord_id = $body['discord_id'];
        $github_id = $body['github_id'];

        if (isset($body['os_type'])) {
            $osType = $body['os_type'];
        } else {
            $osType = 'Unknown';
        }

        if (isset($body['kernel_version'])) {
            $kernelVersion = $body['kernel_version'];
        } else {
            $kernelVersion = 'Unknown';
        }

        if (isset($body['cpu_architecture'])) {
            $cpu_architecture = $body['cpu_architecture'];
        } else {
            $cpu_architecture = 'Unknown';
        }

        $osInfo = [
            'os_type' => $osType,
            'kernel_version' => $kernelVersion,
            'cpu_architecture' => $cpu_architecture,
        ];

        $data = [
            'email' => $email,
            'username' => $username,
            'first_name' => $firstName,
            'last_name' => $lastName,
            'ip' => $ip,
            'credits' => $credits,
            'uuid' => $uuid,
            'banned' => $banned,
            'verified' => $verified,
            'discord_id' => $discord_id,
            'github_id' => $github_id,
        ];

        ZeroTrust::create(
            $license_data['project_info']['id'],
            $license_data['instance']['id'],
            $license_data['license_key_info']['id'],
            json_encode($osInfo),
            json_encode($data),
            'login'
        );

        $appInstance->Ok('Telemetry received', []);
    } else {
        $appInstance->BadRequest('Invalid request', [
            'error_code' => 'INVALID_REQUEST',
            'body' => $body,
        ]);
    }
});

$router->put('/api/system/license/(.*)/telemetry', function (string $licenseKey): void {
    App::init();
    $appInstance = App::getInstance(true);
    $appInstance->allowOnlyPUT();

    $license_data = $appInstance->addLicenseCheck($licenseKey, $appInstance);
    $body = $appInstance->getBody();

    if (isset($body['os_type'])) {
        $osType = $body['os_type'];
    } else {
        $osType = 'Unknown';
    }

    if (isset($body['kernel_version'])) {
        $kernelVersion = $body['kernel_version'];
    } else {
        $kernelVersion = 'Unknown';
    }

    if (isset($body['cpu_architecture'])) {
        $cpu_architecture = $body['cpu_architecture'];
    } else {
        $cpu_architecture = 'Unknown';
    }

    $osInfo = [
        'os_type' => $osType,
        'kernel_version' => $kernelVersion,
        'cpu_architecture' => $cpu_architecture,
    ];

    $id = ZeroTrust::create(
        $license_data['project_info']['id'],
        $license_data['instance']['id'],
        $license_data['license_key_info']['id'],
        json_encode($osInfo),
        json_encode($body),
        'telemetry'
    );
    $appInstance->Ok('Telemetry received', ['id' => $id]);

});

$router->put('/api/system/license/(.*)/logs', function (string $licenseKey): void {
    App::init();
    $appInstance = App::getInstance(true);
    $appInstance->allowOnlyPUT();

    $license_data = $appInstance->addLicenseCheck($licenseKey, $appInstance);
    $body = $appInstance->getBody();
    if (isset($body['os_type'])) {
        $osType = $body['os_type'];
    } else {
        $osType = 'Unknown';
    }

    if (isset($body['kernel_version'])) {
        $kernelVersion = $body['kernel_version'];
    } else {
        $kernelVersion = 'Unknown';
    }

    if (isset($body['cpu_architecture'])) {
        $cpu_architecture = $body['cpu_architecture'];
    } else {
        $cpu_architecture = 'Unknown';
    }

    $osInfo = [
        'os_type' => $osType,
        'kernel_version' => $kernelVersion,
        'cpu_architecture' => $cpu_architecture,
    ];

    if (isset($body['logs'])) {
        $id = ZeroTrust::create(
            $license_data['project_info']['id'],
            $license_data['instance']['id'],
            $license_data['license_key_info']['id'],
            json_encode($osInfo),
            json_encode($body),
            'logs',
        );
        $appInstance->Ok('Logs received', ['id' => $id, 'logs' => 'https://mymythicalid.mythical.systems/logs/' . $id]);
    } else {
        $appInstance->BadRequest('Invalid request', [
            'error_code' => 'INVALID_REQUEST',
            'body' => $body,
        ]);
    }

});

$router->get('/api/system/logs/(.*)', function (string $logId): void {
    App::init();
    $appInstance = App::getInstance(true);
    $appInstance->allowOnlyGET();

    $log = ZeroTrust::getById($logId);
    if ($log) {
        if ($log['action'] == 'logs') {
            // Parse the JSON strings
            $osInfo = json_decode($log['osInfo'], true);
            $trustInfo = json_decode($log['trustInfo'], true);

            // Create a formatted response
            $formattedResponse = [
                'id' => $log['id'],
                'project' => $log['project'],
                'instance' => $log['instance'],
                'license' => $log['license'],
                'os_info' => $osInfo,
                'logs' => $trustInfo,
                'action' => $log['action'],
                'date' => $log['date'],
            ];

            $appInstance->Ok('Logs received', ['log' => $formattedResponse]);
        } else {
            $appInstance->NotFound('Logs not found', ['error_code' => 'LOGS_NOT_FOUND']);
        }
    } else {
        $appInstance->NotFound('Logs not found', ['error_code' => 'LOGS_NOT_FOUND']);
    }
});
