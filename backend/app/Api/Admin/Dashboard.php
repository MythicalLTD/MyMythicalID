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
use MyMythicalID\Hooks\GitHub;
use MyMythicalID\Chat\Database;
use MyMythicalID\Chat\User\Can;
use MyMythicalID\Chat\User\Session;
use MyMythicalID\Chat\columns\UserColumns;
use MyMythicalID\Chat\User\UserActivities;

$router->get('/api/admin', function (): void {
    App::init();
    $appInstance = App::getInstance(true);
    $appInstance->allowOnlyGET();
    $session = new Session($appInstance);
    if (Can::canAccessAdminUI($session->getInfo(UserColumns::ROLE_ID, false))) {
        try {
            $github_data = new GitHub();
            $github_data = $github_data->getRepoData();
            $activity = UserActivities::getAll(150);

            // Get counts for dashboard metrics
            $userCount = Database::getTableRowCount('mymythicalid_users');
            $instanceCount = Database::getTableRowCount('mymythicalid_mythicaldash_instances');
            $licenseCount = Database::getTableRowCount('mymythicalid_license_keys');
            $projectCount = Database::getTableRowCount('mymythicalid_projects');
            $ticketCount = Database::getTableRowCount('mymythicalid_tickets');
            $instanceCount = Database::getTableRowCount('mymythicalid_mythicaldash_instances');
            $departmentCount = Database::getTableRowCount('mymythicalid_departments');
            $announcementCount = Database::getTableRowCount('mymythicalid_announcements');
            $mailTemplateCount = Database::getTableRowCount('mymythicalid_mail_templates');
            $settingsCount = Database::getTableRowCount('mymythicalid_settings');

            $appInstance->OK('Dashboard data retrieved successfully.', [
                'core' => [
                    'github_data' => $github_data,
                ],
                'count' => [
                    'user_count' => $userCount,
                    'instance_count' => $instanceCount,
                    'license_keys_count' => $licenseCount,
                    'projects_count' => $projectCount,
                    'tickets_count' => $ticketCount,
                    'departments_count' => $departmentCount,
                    'announcements_count' => $announcementCount,
                    'mail_templates_count' => $mailTemplateCount,
                    'settings_count' => $settingsCount,
                    'instances_count' => $instanceCount,
                ],
                'etc' => [
                    'activity' => $activity,
                ],
            ]);

        } catch (Exception $e) {
            $appInstance->InternalServerError($e->getMessage(), ['error_code' => 'SERVICE_UNAVAILABLE']);
        }
    } else {
        $appInstance->Unauthorized('Unauthorized', ['error_code' => 'INVALID_SESSION']);
    }

});
