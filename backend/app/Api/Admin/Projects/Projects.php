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
use MyMythicalID\Chat\Project\Project;
use MyMythicalID\Chat\columns\UserColumns;

$router->get('/api/admin/projects', function (): void {
    App::init();
    $appInstance = App::getInstance(true);
    $appInstance->allowOnlyGET();
    $session = new MyMythicalID\Chat\User\Session($appInstance);

    if (Can::canAccessAdminUI($session->getInfo(UserColumns::ROLE_ID, false))) {
        $projects = Project::getAll();

        $appInstance->OK('Projects fetched successfully', ['projects' => $projects]);
    } else {
        $appInstance->Unauthorized('Unauthorized', ['error_code' => 'INVALID_SESSION']);
    }
});

$router->post('/api/admin/project/create', function (): void {
    App::init();
    $appInstance = App::getInstance(true);
    $appInstance->allowOnlyPOST();
    $session = new MyMythicalID\Chat\User\Session($appInstance);

    if (Can::canAccessAdminUI($session->getInfo(UserColumns::ROLE_ID, false))) {

        if (isset($_POST['name']) && isset($_POST['description']) && isset($_POST['type']) && isset($_POST['price']) && isset($_POST['features']) && isset($_POST['link'])) {
            $name = $_POST['name'];
            $description = $_POST['description'];
            $type = $_POST['type'];
            $price = $_POST['price'];
            $features = $_POST['features'];
            $link = $_POST['link'];

            $project = Project::create($name, $description, $type, $price, $features, $link);

            if ($project) {
                $appInstance->OK('Project created successfully', ['project' => $project]);
            } else {
                $appInstance->BadRequest('Failed to create project', ['error_code' => 'FAILED_TO_CREATE_PROJECT']);
            }
        } else {
            $appInstance->BadRequest('Missing required fields', ['error_code' => 'MISSING_REQUIRED_FIELDS']);
        }
    } else {
        $appInstance->Unauthorized('Unauthorized', ['error_code' => 'INVALID_SESSION']);
    }
});

$router->post('/api/admin/project/(.*)/update', function (string $projectId): void {
    App::init();
    $appInstance = App::getInstance(true);
    $appInstance->allowOnlyPOST();
    $session = new MyMythicalID\Chat\User\Session($appInstance);

    if (Can::canAccessAdminUI($session->getInfo(UserColumns::ROLE_ID, false))) {

        if (isset($_POST['name']) && isset($_POST['description']) && isset($_POST['type']) && isset($_POST['price']) && isset($_POST['features']) && isset($_POST['link'])) {
            $name = $_POST['name'];
            $description = $_POST['description'];
            $type = $_POST['type'];
            $price = $_POST['price'];
            $features = $_POST['features'];
            $link = $_POST['link'];

            $array = [
                'name' => $name,
                'description' => $description,
                'type' => $type,
                'price' => $price,
                'features' => $features,
                'link' => $link,
            ];
            $project = Project::update($projectId, $array);

            if ($project) {
                $appInstance->OK('Project updated successfully', ['project' => $project]);
            } else {
                $appInstance->BadRequest('Failed to update project', ['error_code' => 'FAILED_TO_UPDATE_PROJECT']);
            }
        }
    } else {
        $appInstance->Unauthorized('Unauthorized', ['error_code' => 'INVALID_SESSION']);
    }
});

$router->post('/api/admin/project/(.*)/delete', function (string $projectId): void {
    App::init();
    $appInstance = App::getInstance(true);
    $appInstance->allowOnlyPOST();
    $session = new MyMythicalID\Chat\User\Session($appInstance);

    if (Can::canAccessAdminUI($session->getInfo(UserColumns::ROLE_ID, false))) {
        $project = Project::delete($projectId);

        if ($project) {
            $appInstance->OK('Project deleted successfully', ['project' => $project]);
        } else {
            $appInstance->BadRequest('Failed to delete project', ['error_code' => 'FAILED_TO_DELETE_PROJECT']);
        }
    } else {
        $appInstance->Unauthorized('Unauthorized', ['error_code' => 'INVALID_SESSION']);
    }
});

$router->get('/api/admin/project/(.*)/info', function (string $projectId): void {
    App::init();
    $appInstance = App::getInstance(true);
    $appInstance->allowOnlyGET();
    $session = new MyMythicalID\Chat\User\Session($appInstance);

    if (Can::canAccessAdminUI($session->getInfo(UserColumns::ROLE_ID, false))) {
        $project = Project::getById($projectId);
        $appInstance->OK('Project fetched successfully', ['project' => $project]);
    } else {
        $appInstance->Unauthorized('Unauthorized', ['error_code' => 'INVALID_SESSION']);
    }
});
