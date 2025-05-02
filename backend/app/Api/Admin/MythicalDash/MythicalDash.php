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
use MyMythicalID\Chat\User\User;
use MyMythicalID\Chat\Project\Project;
use MyMythicalID\Chat\columns\UserColumns;
use MyMythicalID\Chat\LicenseKey\LicenseKey;
use MyMythicalID\Chat\Project\MythicalDashInstance;

$router->get('/api/admin/mythicaldash/instances', function (): void {
	App::init();
	$appInstance = App::getInstance(true);
	$appInstance->allowOnlyGET();
	$session = new MyMythicalID\Chat\User\Session($appInstance);

	if (Can::canAccessAdminUI($session->getInfo(UserColumns::ROLE_ID, false))) {
		$instances = MythicalDashInstance::getAll();
		$appInstance->OK('MythicalDash instances fetched successfully', ['instances' => $instances]);
	} else {
		$appInstance->Unauthorized('Unauthorized', ['error_code' => 'INVALID_SESSION']);
	}
});

$router->post('/api/admin/mythicaldash/instance/create', function (): void {
	App::init();
	$appInstance = App::getInstance(true);
	$appInstance->allowOnlyPOST();
	$session = new MyMythicalID\Chat\User\Session($appInstance);

	if (Can::canAccessAdminUI($session->getInfo(UserColumns::ROLE_ID, false))) {
		$requiredFields = [
			'user',
			'project',
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

		$missingFields = [];
		foreach ($requiredFields as $field) {
			if (!isset($_POST[$field])) {
				$missingFields[] = $field;
			}
		}

		if (empty($missingFields)) {
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
				date('Y-m-d H:i:s', strtotime('+5 year')),
				0,
				'active'
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
					'instance' => $instance
				]);
				
				$appInstance->OK('MythicalDash instance created successfully', ['instance' => $instance]);
			} else {
				// If instance creation fails, delete the license key
				LicenseKey::delete($licenseKey);
				$appInstance->BadRequest('Failed to create MythicalDash instance', ['error_code' => 'FAILED_TO_CREATE_INSTANCE']);
			}
		} else {
			$appInstance->BadRequest('Missing required fields: ' . implode(', ', $missingFields), ['error_code' => 'MISSING_REQUIRED_FIELDS']);
		}
	} else {
		$appInstance->Unauthorized('Unauthorized', ['error_code' => 'INVALID_SESSION']);
	}
});

$router->post('/api/admin/mythicaldash/instance/(.*)/update', function (string $instanceId): void {
	App::init();
	$appInstance = App::getInstance(true);
	$appInstance->allowOnlyPOST();
	$session = new MyMythicalID\Chat\User\Session($appInstance);

	if (Can::canAccessAdminUI($session->getInfo(UserColumns::ROLE_ID, false))) {
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
			$success = MythicalDashInstance::update($instanceId, $updateData);

			if ($success) {
				$instance = MythicalDashInstance::getById($instanceId);
				$appInstance->OK('MythicalDash instance updated successfully', ['instance' => $instance]);
			} else {
				$appInstance->BadRequest('Failed to update MythicalDash instance', ['error_code' => 'FAILED_TO_UPDATE_INSTANCE']);
			}
		} else {
			$appInstance->BadRequest('No valid fields to update', ['error_code' => 'NO_VALID_FIELDS']);
		}
	} else {
		$appInstance->Unauthorized('Unauthorized', ['error_code' => 'INVALID_SESSION']);
	}
});

$router->post('/api/admin/mythicaldash/instance/(.*)/delete', function (string $instanceId): void {
	App::init();
	$appInstance = App::getInstance(true);
	$appInstance->allowOnlyPOST();
	$session = new MyMythicalID\Chat\User\Session($appInstance);

	if (Can::canAccessAdminUI($session->getInfo(UserColumns::ROLE_ID, false))) {

		$instance = MythicalDashInstance::getById((int) $instanceId);
		if ($instance) {
			LicenseKey::delete((int) $instance['license_key']);
		}
		$success = MythicalDashInstance::delete((int) $instanceId);
		if ($success) {

			$appInstance->OK('MythicalDash instance deleted successfully');
		} else {
			$appInstance->BadRequest('Failed to delete MythicalDash instance', ['error_code' => 'FAILED_TO_DELETE_INSTANCE']);
		}
	} else {
		$appInstance->Unauthorized('Unauthorized', ['error_code' => 'INVALID_SESSION']);
	}
});

$router->get('/api/admin/mythicaldash/instance/(.*)/info', function (string $instanceId): void {
	App::init();
	$appInstance = App::getInstance(true);
	$appInstance->allowOnlyGET();
	$session = new MyMythicalID\Chat\User\Session($appInstance);

	if (Can::canAccessAdminUI($session->getInfo(UserColumns::ROLE_ID, false))) {
		$instance = MythicalDashInstance::getById((int) $instanceId);

		if ($instance) {
			$appInstance->OK('MythicalDash instance fetched successfully', ['instance' => $instance]);
		} else {
			$appInstance->NotFound('MythicalDash instance not found', ['error_code' => 'INSTANCE_NOT_FOUND']);
		}
	} else {
		$appInstance->Unauthorized('Unauthorized', ['error_code' => 'INVALID_SESSION']);
	}
});

$router->post('/api/admin/mythicaldash/instance/(.*)/restore', function (string $instanceId): void {
	App::init();
	$appInstance = App::getInstance(true);
	$appInstance->allowOnlyPOST();
	$session = new MyMythicalID\Chat\User\Session($appInstance);

	if (Can::canAccessAdminUI($session->getInfo(UserColumns::ROLE_ID, false))) {
		$success = MythicalDashInstance::restore($instanceId);

		if ($success) {
			$appInstance->OK('MythicalDash instance restored successfully', []);
		} else {
			$appInstance->BadRequest('Failed to restore MythicalDash instance', ['error_code' => 'FAILED_TO_RESTORE_INSTANCE']);
		}
	} else {
		$appInstance->Unauthorized('Unauthorized', ['error_code' => 'INVALID_SESSION']);
	}
});

$router->post('/api/admin/mythicaldash/instance/(.*)/lock', function (string $instanceId): void {
	App::init();
	$appInstance = App::getInstance(true);
	$appInstance->allowOnlyPOST();
	$session = new MyMythicalID\Chat\User\Session($appInstance);

	if (Can::canAccessAdminUI($session->getInfo(UserColumns::ROLE_ID, false))) {
		$success = MythicalDashInstance::lock($instanceId);

		if ($success) {
			$appInstance->OK('MythicalDash instance locked successfully');
		} else {
			$appInstance->BadRequest('Failed to lock MythicalDash instance', ['error_code' => 'FAILED_TO_LOCK_INSTANCE']);
		}
	} else {
		$appInstance->Unauthorized('Unauthorized', ['error_code' => 'INVALID_SESSION']);
	}
});

$router->post('/api/admin/mythicaldash/instance/(.*)/unlock', function (string $instanceId): void {
	App::init();
	$appInstance = App::getInstance(true);
	$appInstance->allowOnlyPOST();
	$session = new MyMythicalID\Chat\User\Session($appInstance);

	if (Can::canAccessAdminUI($session->getInfo(UserColumns::ROLE_ID, false))) {
		$success = MythicalDashInstance::unlock($instanceId);

		if ($success) {
			$appInstance->OK('MythicalDash instance unlocked successfully', []);
		} else {
			$appInstance->BadRequest('Failed to unlock MythicalDash instance', ['error_code' => 'FAILED_TO_UNLOCK_INSTANCE']);
		}
	} else {
		$appInstance->Unauthorized('Unauthorized', ['error_code' => 'INVALID_SESSION']);
	}
});

$router->get('/api/admin/mythicaldash/instance/user/(.*)', function (string $userUuid): void {
	App::init();
	$appInstance = App::getInstance(true);
	$appInstance->allowOnlyGET();
	$session = new MyMythicalID\Chat\User\Session($appInstance);

	if (Can::canAccessAdminUI($session->getInfo(UserColumns::ROLE_ID, false))) {
		$instances = MythicalDashInstance::getByUser($userUuid);
		$appInstance->OK('MythicalDash instances fetched successfully', ['instances' => $instances]);
	} else {
		$appInstance->Unauthorized('Unauthorized', ['error_code' => 'INVALID_SESSION']);
	}
});

$router->get('/api/admin/mythicaldash/instance/project/(.*)', function (string $projectId): void {
	App::init();
	$appInstance = App::getInstance(true);
	$appInstance->allowOnlyGET();
	$session = new MyMythicalID\Chat\User\Session($appInstance);

	if (Can::canAccessAdminUI($session->getInfo(UserColumns::ROLE_ID, false))) {
		$instances = MythicalDashInstance::getByProject($projectId);
		$appInstance->OK('MythicalDash instances fetched successfully', ['instances' => $instances]);
	} else {
		$appInstance->Unauthorized('Unauthorized', ['error_code' => 'INVALID_SESSION']);
	}
});

$router->get('/api/admin/mythicaldash/instance/license/(.*)', function (string $licenseKeyId): void {
	App::init();
	$appInstance = App::getInstance(true);
	$appInstance->allowOnlyGET();
	$session = new MyMythicalID\Chat\User\Session($appInstance);

	if (Can::canAccessAdminUI($session->getInfo(UserColumns::ROLE_ID, false))) {
		$instances = MythicalDashInstance::getByLicenseKey($licenseKeyId);
		$appInstance->OK('MythicalDash instances fetched successfully', ['instances' => $instances]);
	} else {
		$appInstance->Unauthorized('Unauthorized', ['error_code' => 'INVALID_SESSION']);
	}
});
