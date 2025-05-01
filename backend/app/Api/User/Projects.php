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

$router->add('/api/user/projects', function () {
    try {
        App::init();
        $appInstance = App::getInstance(true);
        $appInstance->allowOnlyGET();

        // Verify user session
        $session = new Session($appInstance);

        // Get projects
        $projects = Project::getAll();
        if ($projects === false) {
            $appInstance->InternalServerError('Failed to fetch projects', ['error_code' => 'FAILED_TO_FETCH_PROJECTS']);

            return;
        }

        // Parse features for each project
        foreach ($projects as &$project) {
            if (isset($project['features'])) {
                $project['features'] = json_decode($project['features'], true);
                if (json_last_error() !== JSON_ERROR_NONE) {
                    $project['features'] = [];
                }
            }
        }

        $appInstance->OK('Projects fetched successfully', [
            'projects' => $projects,
            'total' => count($projects),
            'user_balance' => $session->getInfo(UserColumns::CREDITS, false),
        ]);
    } catch (Exception $e) {
        $appInstance->InternalServerError('An error occurred while fetching projects', [
            'error_code' => 'INTERNAL_SERVER_ERROR',
            'error_message' => $e->getMessage(),
        ]);
    }
});
