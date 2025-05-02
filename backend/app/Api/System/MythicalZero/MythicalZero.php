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

/**
 * Format bytes to human readable format
 */
function formatBytes($bytes, $precision = 2) {
    $units = ['B', 'KB', 'MB', 'GB', 'TB'];
    $bytes = max($bytes, 0);
    $pow = floor(($bytes ? log($bytes) : 0) / log(1024));
    $pow = min($pow, count($units) - 1);
    $bytes /= pow(1024, $pow);
    return round($bytes, $precision) . ' ' . $units[$pow];
}

/**
 * Get cloud storage limit from project features
 */
function getCloudStorageLimit($features) {
    foreach ($features as $feature) {
        if (preg_match('/MythicalCloud\s*\((\d+)GB\)/', $feature, $matches)) {
            return (int)$matches[1] * 1024 * 1024 * 1024; // Convert GB to bytes
        }
    }
    return 0; // No MythicalCloud feature found
}

/**
 * Check if project has MythicalCloud feature
 */
function hasMythicalCloud($features) {
    foreach ($features as $feature) {
        if (strpos($feature, 'MythicalCloud') !== false) {
            return true;
        }
    }
    return false;
}

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

$router->put('/api/system/cloud/license/(.*)/backup', function (string $licenseKey): void {
    App::init();
    $appInstance = App::getInstance(true);
    $appInstance->allowOnlyPUT();

    $license_data = $appInstance->addLicenseCheck($licenseKey, $appInstance);
    
    // Check if project has MythicalCloud feature
    if (!hasMythicalCloud($license_data['project_info']['features'])) {
        $appInstance->BadRequest('MythicalCloud not available', [
            'error_code' => 'MYTHICALCLOUD_NOT_AVAILABLE',
            'message' => 'This license does not include MythicalCloud feature'
        ]);
        return;
    }

    // Get cloud storage limit from features
    $storageLimit = getCloudStorageLimit($license_data['project_info']['features']);
    if ($storageLimit === 0) {
        $appInstance->BadRequest('Invalid storage limit', [
            'error_code' => 'INVALID_STORAGE_LIMIT',
            'message' => 'Could not determine storage limit from project features'
        ]);
        return;
    }

    // Debug information
    $debug = [
        'files' => $_FILES,
        'post' => $_POST,
        'request' => $_REQUEST,
        'content_type' => $_SERVER['CONTENT_TYPE'] ?? 'not set',
        'request_method' => $_SERVER['REQUEST_METHOD'] ?? 'not set',
        'php_sapi' => PHP_SAPI,
        'upload_max_filesize' => ini_get('upload_max_filesize'),
        'post_max_size' => ini_get('post_max_size'),
        'raw_input' => file_get_contents('php://input')
    ];
    
    // Try to get file from raw input if not in $_FILES
    $file = null;
    if (empty($_FILES['backup'])) {
        $rawInput = file_get_contents('php://input');
        if (!empty($rawInput)) {
            // Create temporary file
            $tempFile = tempnam(sys_get_temp_dir(), 'backup_');
            file_put_contents($tempFile, $rawInput);
            
            $file = [
                'name' => 'backup.zip',
                'type' => 'application/zip',
                'tmp_name' => $tempFile,
                'error' => 0,
                'size' => filesize($tempFile)
            ];
        }
    } else {
        $file = $_FILES['backup'];
    }
    
    // Check if file was uploaded
    if (!$file) {
        $appInstance->BadRequest('No file uploaded', [
            'error_code' => 'NO_FILE_UPLOADED',
            'message' => 'Please upload a backup file',
            'debug' => $debug
        ]);
        return;
    }
    
    // Security checks
    if ($file['error'] !== 0) {
        $appInstance->BadRequest('File upload error', [
            'error_code' => 'FILE_UPLOAD_ERROR',
            'message' => 'Error code: ' . $file['error'],
            'debug' => $debug
        ]);
        return;
    }

    // Check file size (max 512MB)
    if ($file['size'] > 512 * 1024 * 1024) {
        $appInstance->BadRequest('File too large', [
            'error_code' => 'FILE_TOO_LARGE',
            'message' => 'Maximum file size is 512MB',
            'debug' => $debug
        ]);
        return;
    }

    // Check file type
    $allowedTypes = ['application/octet-stream'];
    $finfo = finfo_open(FILEINFO_MIME_TYPE);
    $mimeType = finfo_file($finfo, $file['tmp_name']);
    finfo_close($finfo);

    if (!in_array($mimeType, $allowedTypes)) {
        $appInstance->BadRequest('Invalid file type', [
            'error_code' => 'INVALID_FILE_TYPE',
            'message' => 'Only MYDB files are allowed',
            'debug' => $debug
        ]);
        return;
    }

    // Create backup directory if it doesn't exist
    $backupDir = APP_STORAGE_DIR . 'backups/' . $license_data['instance']['id'];
    if (!is_dir($backupDir)) {
        mkdir($backupDir, 0755, true);
    }

    // Calculate instance backup size
    $instanceBackupSize = 0;
    $instanceBackupCount = 0;
    if (is_dir($backupDir)) {
        $files = glob($backupDir . '/*.mydb');
        foreach ($files as $existingFile) {
            $instanceBackupSize += filesize($existingFile);
            $instanceBackupCount++;
        }
    }

    // Check if adding this file would exceed storage limit
    if (($instanceBackupSize + $file['size']) > $storageLimit) {
        $appInstance->BadRequest('Instance backup limit exceeded', [
            'error_code' => 'INSTANCE_BACKUP_LIMIT_EXCEEDED',
            'message' => 'Instance backup limit would be exceeded',
            'current_usage' => [
                'used_space' => $instanceBackupSize,
                'used_space_formatted' => formatBytes($instanceBackupSize),
                'backup_count' => $instanceBackupCount,
                'remaining_space' => $storageLimit - $instanceBackupSize,
                'remaining_space_formatted' => formatBytes($storageLimit - $instanceBackupSize),
                'storage_limit' => $storageLimit,
                'storage_limit_formatted' => formatBytes($storageLimit)
            ]
        ]);
        return;
    }

    // Create backup record in database first to get the ID
    $backupId = ZeroTrust::create(
        $license_data['project_info']['id'],
        $license_data['instance']['id'],
        $license_data['license_key_info']['id'],
        json_encode([
            'os_type' => $_POST['os_type'] ?? 'Unknown',
            'kernel_version' => $_POST['kernel_version'] ?? 'Unknown',
            'cpu_architecture' => $_POST['cpu_architecture'] ?? 'Unknown'
        ]),
        json_encode([
            'original_name' => $file['name'],
            'size' => $file['size'],
            'mime_type' => $mimeType
        ]),
        'backup'
    );

    // Generate filename using the backup ID
    $filename = 'backup_' . $backupId . '.mydb';
    $filepath = $backupDir . '/' . $filename;

    // Move uploaded file to backup directory
    if (!move_uploaded_file($file['tmp_name'], $filepath)) {
        // If move_uploaded_file fails, try copy
        if (!copy($file['tmp_name'], $filepath)) {
            $appInstance->InternalServerError('Failed to save backup', [
                'error_code' => 'SAVE_FAILED',
                'message' => 'Could not save the backup file',
                'debug' => $debug
            ]);
            return;
        }
    }

    // Calculate new instance backup size after adding this file
    $newInstanceBackupSize = $instanceBackupSize + $file['size'];

    $appInstance->Ok('Backup received successfully', [
        'id' => $backupId,
        'filename' => $filename,
        'backup_url' => 'https://mymythicalid.mythical.systems/api/system/cloud/license/' . $licenseKey . '/backup/' . $backupId . '/download',
        'info_url' => 'https://mymythicalid.mythical.systems/api/system/cloud/license/' . $licenseKey . '/backup/' . $backupId . '/info',
        'storage_info' => [
            'file_size' => $file['size'],
            'file_size_formatted' => formatBytes($file['size']),
            'instance_usage' => [
                'used_space' => $newInstanceBackupSize,
                'used_space_formatted' => formatBytes($newInstanceBackupSize),
                'backup_count' => $instanceBackupCount + 1,
                'remaining_space' => $storageLimit - $newInstanceBackupSize,
                'remaining_space_formatted' => formatBytes($storageLimit - $newInstanceBackupSize),
                'storage_limit' => $storageLimit,
                'storage_limit_formatted' => formatBytes($storageLimit)
            ]
        ]
    ]);
});

/**
 * List all backups for an instance
 */
$router->get('/api/system/cloud/license/(.*)/backups', function (string $licenseKey): void {
    App::init();
    $appInstance = App::getInstance(true);
    $appInstance->allowOnlyGET();

    $license_data = $appInstance->addLicenseCheck($licenseKey, $appInstance);
    
    // Check if project has MythicalCloud feature
    if (!hasMythicalCloud($license_data['project_info']['features'])) {
        $appInstance->BadRequest('MythicalCloud not available', [
            'error_code' => 'MYTHICALCLOUD_NOT_AVAILABLE',
            'message' => 'This license does not include MythicalCloud feature'
        ]);
        return;
    }

    // Get cloud storage limit from features
    $storageLimit = getCloudStorageLimit($license_data['project_info']['features']);
    if ($storageLimit === 0) {
        $appInstance->BadRequest('Invalid storage limit', [
            'error_code' => 'INVALID_STORAGE_LIMIT',
            'message' => 'Could not determine storage limit from project features'
        ]);
        return;
    }
    
    // Get all backup records for this instance
    $backups = ZeroTrust::getByInstance($license_data['instance']['id']);
    $backupDir = APP_STORAGE_DIR . 'backups/' . $license_data['instance']['id'];
    
    if (empty($backups)) {
        $appInstance->Ok('No backups found', [
            'backups' => [],
            'storage_info' => [
                'used_space' => 0,
                'used_space_formatted' => formatBytes(0),
                'backup_count' => 0,
                'remaining_space' => $storageLimit,
                'remaining_space_formatted' => formatBytes($storageLimit),
                'storage_limit' => $storageLimit,
                'storage_limit_formatted' => formatBytes($storageLimit)
            ]
        ]);
        return;
    }

    $formattedBackups = [];
    $totalSize = 0;

    foreach ($backups as $backup) {
        $filename = 'backup_' . $backup['id'] . '.mydb';
        $filepath = $backupDir . '/' . $filename;
        
        if (file_exists($filepath)) {
            $fileSize = filesize($filepath);
            $totalSize += $fileSize;
            
            $formattedBackups[] = [
                'id' => $backup['id'],
                'filename' => $filename,
                'size' => $fileSize,
                'size_formatted' => formatBytes($fileSize),
                'created_at' => $backup['date'],
                'download_url' => 'https://mymythicalid.mythical.systems/api/system/cloud/license/' . $licenseKey . '/backup/' . $backup['id'] . '/download',
                'info_url' => 'https://mymythicalid.mythical.systems/api/system/cloud/license/' . $licenseKey . '/backup/' . $backup['id'] . '/info'
            ];
        }
    }

    // Sort backups by creation date (newest first)
    usort($formattedBackups, function($a, $b) {
        return strtotime($b['created_at']) - strtotime($a['created_at']);
    });

    $appInstance->Ok('Backups retrieved successfully', [
        'backups' => $formattedBackups,
        'storage_info' => [
            'used_space' => $totalSize,
            'used_space_formatted' => formatBytes($totalSize),
            'backup_count' => count($formattedBackups),
            'remaining_space' => $storageLimit - $totalSize,
            'remaining_space_formatted' => formatBytes($storageLimit - $totalSize),
            'storage_limit' => $storageLimit,
            'storage_limit_formatted' => formatBytes($storageLimit)
        ]
    ]);
});

/**
 * Get backup information
 */
$router->get('/api/system/cloud/license/(.*)/backup/(.*)/info', function (string $licenseKey, string $backupId): void {
    App::init();
    $appInstance = App::getInstance(true);
    $appInstance->allowOnlyGET();

    $license_data = $appInstance->addLicenseCheck($licenseKey, $appInstance);
    
    // Check if project has MythicalCloud feature
    if (!hasMythicalCloud($license_data['project_info']['features'])) {
        $appInstance->BadRequest('MythicalCloud not available', [
            'error_code' => 'MYTHICALCLOUD_NOT_AVAILABLE',
            'message' => 'This license does not include MythicalCloud feature'
        ]);
        return;
    }

    // Get cloud storage limit from features
    $storageLimit = getCloudStorageLimit($license_data['project_info']['features']);
    if ($storageLimit === 0) {
        $appInstance->BadRequest('Invalid storage limit', [
            'error_code' => 'INVALID_STORAGE_LIMIT',
            'message' => 'Could not determine storage limit from project features'
        ]);
        return;
    }
    
    // Get backup record from database
    $backupRecord = ZeroTrust::getById($backupId);
    if (!$backupRecord || $backupRecord['action'] !== 'backup') {
        $appInstance->NotFound('Backup not found', [
            'error_code' => 'BACKUP_NOT_FOUND',
            'message' => 'The requested backup does not exist'
        ]);
        return;
    }

    $backupDir = APP_STORAGE_DIR . 'backups/' . $license_data['instance']['id'];
    $filename = 'backup_' . $backupId . '.mydb';
    $filepath = $backupDir . '/' . $filename;

    if (!file_exists($filepath)) {
        $appInstance->NotFound('Backup file not found', [
            'error_code' => 'BACKUP_FILE_NOT_FOUND',
            'message' => 'The backup record exists but the file is missing'
        ]);
        return;
    }

    // Calculate total instance usage
    $instanceBackupSize = 0;
    $instanceBackupCount = 0;
    if (is_dir($backupDir)) {
        $files = glob($backupDir . '/*.mydb');
        foreach ($files as $existingFile) {
            $instanceBackupSize += filesize($existingFile);
            $instanceBackupCount++;
        }
    }

    $fileInfo = [
        'id' => $backupId,
        'filename' => $filename,
        'size' => filesize($filepath),
        'size_formatted' => formatBytes(filesize($filepath)),
        'created_at' => $backupRecord['date'],
        'download_url' => 'https://mymythicalid.mythical.systems/api/system/cloud/license/' . $licenseKey . '/backup/' . $backupId . '/download',
        'metadata' => json_decode($backupRecord['trustInfo'], true),
        'os_info' => json_decode($backupRecord['osInfo'], true),
        'storage_info' => [
            'used_space' => $instanceBackupSize,
            'used_space_formatted' => formatBytes($instanceBackupSize),
            'backup_count' => $instanceBackupCount,
            'remaining_space' => $storageLimit - $instanceBackupSize,
            'remaining_space_formatted' => formatBytes($storageLimit - $instanceBackupSize),
            'storage_limit' => $storageLimit,
            'storage_limit_formatted' => formatBytes($storageLimit)
        ]
    ];

    $appInstance->Ok('Backup information retrieved successfully', [
        'backup' => $fileInfo
    ]);
});

/**
 * Download a backup
 */
$router->get('/api/system/cloud/license/(.*)/backup/(.*)/download', function (string $licenseKey, string $backupId): void {
    App::init();
    $appInstance = App::getInstance(true);
    $appInstance->allowOnlyGET();

    $license_data = $appInstance->addLicenseCheck($licenseKey, $appInstance);
    
    // Get backup record from database
    $backupRecord = ZeroTrust::getById($backupId);
    if (!$backupRecord || $backupRecord['action'] !== 'backup') {
        $appInstance->NotFound('Backup not found', [
            'error_code' => 'BACKUP_NOT_FOUND',
            'message' => 'The requested backup does not exist'
        ]);
        return;
    }

    $backupDir = APP_STORAGE_DIR . 'backups/' . $license_data['instance']['id'];
    $filename = 'backup_' . $backupId . '.mydb';
    $filepath = $backupDir . '/' . $filename;

    if (!file_exists($filepath)) {
        $appInstance->NotFound('Backup file not found', [
            'error_code' => 'BACKUP_FILE_NOT_FOUND',
            'message' => 'The backup record exists but the file is missing'
        ]);
        return;
    }

    // Set headers for file download
    header('Content-Type: application/octet-stream');
    header('Content-Disposition: attachment; filename="' . $filename . '"');
    header('Content-Length: ' . filesize($filepath));
    header('Cache-Control: no-cache, must-revalidate');
    header('Pragma: no-cache');
    header('Expires: 0');

    // Output file
    readfile($filepath);
    exit;
});

/**
 * Delete a specific backup
 */
$router->delete('/api/system/cloud/license/(.*)/backup/(.*)', function (string $licenseKey, string $backupId): void {
    App::init();
    $appInstance = App::getInstance(true);
    $appInstance->allowOnlyDELETE();

    $license_data = $appInstance->addLicenseCheck($licenseKey, $appInstance);
    
    // Check if project has MythicalCloud feature
    if (!hasMythicalCloud($license_data['project_info']['features'])) {
        $appInstance->BadRequest('MythicalCloud not available', [
            'error_code' => 'MYTHICALCLOUD_NOT_AVAILABLE',
            'message' => 'This license does not include MythicalCloud feature'
        ]);
        return;
    }

    // Get cloud storage limit from features
    $storageLimit = getCloudStorageLimit($license_data['project_info']['features']);
    if ($storageLimit === 0) {
        $appInstance->BadRequest('Invalid storage limit', [
            'error_code' => 'INVALID_STORAGE_LIMIT',
            'message' => 'Could not determine storage limit from project features'
        ]);
        return;
    }
    
    // Get backup record from database
    $backupRecord = ZeroTrust::getById($backupId);
    if (!$backupRecord || $backupRecord['action'] !== 'backup') {
        $appInstance->NotFound('Backup not found', [
            'error_code' => 'BACKUP_NOT_FOUND',
            'message' => 'The requested backup does not exist'
        ]);
        return;
    }

    $backupDir = APP_STORAGE_DIR . 'backups/' . $license_data['instance']['id'];
    $filename = 'backup_' . $backupId . '.mydb';
    $filepath = $backupDir . '/' . $filename;

    if (!file_exists($filepath)) {
        $appInstance->NotFound('Backup file not found', [
            'error_code' => 'BACKUP_FILE_NOT_FOUND',
            'message' => 'The backup record exists but the file is missing'
        ]);
        return;
    }

    // Delete the file
    if (!unlink($filepath)) {
        $appInstance->InternalServerError('Failed to delete backup', [
            'error_code' => 'DELETE_FAILED',
            'message' => 'Could not delete the backup file'
        ]);
        return;
    }

    // Delete the record from database
    if (!ZeroTrust::delete($backupId)) {
        $appInstance->InternalServerError('Failed to delete backup record', [
            'error_code' => 'DELETE_RECORD_FAILED',
            'message' => 'Could not delete the backup record from database'
        ]);
        return;
    }

    // Calculate new instance usage
    $instanceBackupSize = 0;
    $instanceBackupCount = 0;
    if (is_dir($backupDir)) {
        $files = glob($backupDir . '/*.mydb');
        foreach ($files as $existingFile) {
            $instanceBackupSize += filesize($existingFile);
            $instanceBackupCount++;
        }
    }

    $appInstance->Ok('Backup deleted successfully', [
        'storage_info' => [
            'used_space' => $instanceBackupSize,
            'used_space_formatted' => formatBytes($instanceBackupSize),
            'backup_count' => $instanceBackupCount,
            'remaining_space' => $storageLimit - $instanceBackupSize,
            'remaining_space_formatted' => formatBytes($storageLimit - $instanceBackupSize),
            'storage_limit' => $storageLimit,
            'storage_limit_formatted' => formatBytes($storageLimit)
        ]
    ]);
});

/**
 * Purge all backups for an instance
 */
$router->delete('/api/system/cloud/license/(.*)/backups', function (string $licenseKey): void {
    App::init();
    $appInstance = App::getInstance(true);
    $appInstance->allowOnlyDELETE();

    $license_data = $appInstance->addLicenseCheck($licenseKey, $appInstance);
    
    // Check if project has MythicalCloud feature
    if (!hasMythicalCloud($license_data['project_info']['features'])) {
        $appInstance->BadRequest('MythicalCloud not available', [
            'error_code' => 'MYTHICALCLOUD_NOT_AVAILABLE',
            'message' => 'This license does not include MythicalCloud feature'
        ]);
        return;
    }

    // Get cloud storage limit from features
    $storageLimit = getCloudStorageLimit($license_data['project_info']['features']);
    if ($storageLimit === 0) {
        $appInstance->BadRequest('Invalid storage limit', [
            'error_code' => 'INVALID_STORAGE_LIMIT',
            'message' => 'Could not determine storage limit from project features'
        ]);
        return;
    }
    
    // Get all backup records for this instance
    $backups = ZeroTrust::getByInstance($license_data['instance']['id']);
    $backupDir = APP_STORAGE_DIR . 'backups/' . $license_data['instance']['id'];
    
    if (empty($backups)) {
        $appInstance->Ok('No backups to purge', [
            'storage_info' => [
                'used_space' => 0,
                'used_space_formatted' => formatBytes(0),
                'backup_count' => 0,
                'remaining_space' => $storageLimit,
                'remaining_space_formatted' => formatBytes($storageLimit),
                'storage_limit' => $storageLimit,
                'storage_limit_formatted' => formatBytes($storageLimit)
            ]
        ]);
        return;
    }

    // Delete all backup files
    $files = glob($backupDir . '/*.mydb');
    foreach ($files as $file) {
        if (!unlink($file)) {
            $appInstance->InternalServerError('Failed to delete some backup files', [
                'error_code' => 'DELETE_FAILED',
                'message' => 'Could not delete all backup files'
            ]);
            return;
        }
    }

    // Delete all backup records from database
    foreach ($backups as $backup) {
        if (!ZeroTrust::delete($backup['id'])) {
            $appInstance->InternalServerError('Failed to delete some backup records', [
                'error_code' => 'DELETE_RECORD_FAILED',
                'message' => 'Could not delete all backup records from database'
            ]);
            return;
        }
    }

    $appInstance->Ok('All backups purged successfully', [
        'storage_info' => [
            'used_space' => 0,
            'used_space_formatted' => formatBytes(0),
            'backup_count' => 0,
            'remaining_space' => $storageLimit,
            'remaining_space_formatted' => formatBytes($storageLimit),
            'storage_limit' => $storageLimit,
            'storage_limit_formatted' => formatBytes($storageLimit)
        ]
    ]);
});