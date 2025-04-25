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
            $userCount = Database::getTableRowCount('mymythicalid_users');
            $addonsCount = Database::getTableRowCount('mymythicalid_addons');
            $rolesCount = Database::getTableRowCount('mymythicalid_roles');
            $locationsCount = Database::getTableRowCount('mymythicalid_locations');
            $ticketsCount = Database::getTableRowCount('mymythicalid_tickets');
            $eggsCount = Database::getTableRowCount('mymythicalid_eggs');
            $departmentsCount = Database::getTableRowCount('mymythicalid_departments');
            $announcementsCount = Database::getTableRowCount('mymythicalid_announcements');
            $serverQueueCount = Database::getTableRowCount('mymythicalid_servers_queue');
            $mailTemplatesCount = Database::getTableRowCount('mymythicalid_mail_templates');
            $settingsCount = Database::getTableRowCount('mymythicalid_settings', false);
            $redeemCodesCount = Database::getTableRowCount('mymythicalid_redeem_codes');
            $serversCount = Database::getTableRowCount('mymythicalid_servers');
            $pluginsCount = Database::getTableRowCount('mymythicalid_addons');

            $appInstance->OK('Dashboard data retrieved successfully.', [
                'core' => [
                    'github_data' => $github_data,
                ],
                'count' => [
                    'user_count' => $userCount,
                    'addons_count' => $addonsCount,
                    'locations_count' => $locationsCount,
                    'roles_count' => $rolesCount,
                    'tickets_count' => $ticketsCount,
                    'eggs_count' => $eggsCount,
                    'departments_count' => $departmentsCount,
                    'announcements_count' => $announcementsCount,
                    'server_queue_count' => $serverQueueCount,
                    'mail_templates_count' => $mailTemplatesCount,
                    'settings_count' => $settingsCount,
                    'redeem_codes_count' => $redeemCodesCount,
                    'servers_count' => $serversCount,
                    'plugins_count' => $pluginsCount,
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
