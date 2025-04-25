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
use MyMythicalID\Chat\Mails\MailTemplates;
use MyMythicalID\Chat\User\UserActivities;
use MyMythicalID\CloudFlare\CloudFlareRealIP;
use MyMythicalID\Chat\interface\UserActivitiesTypes;

$router->get('/api/admin/mail/mail-templates', function (): void {
    App::init();
    $appInstance = App::getInstance(true);
    $appInstance->allowOnlyGET();
    $session = new MyMythicalID\Chat\User\Session($appInstance);

    if (Can::canAccessAdminUI($session->getInfo(UserColumns::ROLE_ID, false))) {
        $mailTemplates = MailTemplates::getAll();
        $appInstance->OK('Mail templates retrieved successfully.', ['mail_templates' => $mailTemplates]);
    } else {
        $appInstance->Unauthorized('Unauthorized', ['error_code' => 'INVALID_SESSION']);
    }
});

$router->post('/api/admin/mail/mail-templates/create', function (): void {
    App::init();
    $appInstance = App::getInstance(true);
    $appInstance->allowOnlyPOST();
    $session = new MyMythicalID\Chat\User\Session($appInstance);

    if (Can::canAccessAdminUI($session->getInfo(UserColumns::ROLE_ID, false))) {
        if (isset($_POST['name']) && isset($_POST['content']) && isset($_POST['active'])) {
            $name = $_POST['name'];
            $content = $_POST['content'];
            $active = $_POST['active'];
            $active = strtolower($active);
            if (!in_array($active, ['true', 'false'])) {
                $appInstance->BadRequest('Invalid active value', ['error_code' => 'INVALID_ACTIVE_VALUE']);

                return;
            }

            if (strlen($name) > 255) {
                $appInstance->BadRequest('Name is too long', ['error_code' => 'NAME_TOO_LONG']);

                return;
            }

            if (strlen($content) > 65535) {
                $appInstance->BadRequest('Content is too long', ['error_code' => 'CONTENT_TOO_LONG']);

                return;
            }

            if (strlen($name) < 1) {
                $appInstance->BadRequest('Name is too short', ['error_code' => 'NAME_TOO_SHORT']);

                return;
            }

            if (strlen($content) < 1) {
                $appInstance->BadRequest('Content is too short', ['error_code' => 'CONTENT_TOO_SHORT']);

                return;
            }

            if (MailTemplates::existsByName($name)) {
                $appInstance->BadRequest('Mail template already exists', ['error_code' => 'MAIL_TEMPLATE_ALREADY_EXISTS']);

                return;
            }

            $mailTemplates = MailTemplates::create($name, $content, $active);
            if ($mailTemplates) {
                UserActivities::add(
                    $session->getInfo(UserColumns::UUID, false),
                    UserActivitiesTypes::$mail_template_create,
                    CloudFlareRealIP::getRealIP(),
                    "Created mail template $name"
                );
                $appInstance->OK('Mail template created successfully.', ['mail_template' => $mailTemplates]);
            } else {
                $appInstance->BadRequest('Failed to create mail template', ['error_code' => 'FAILED_TO_CREATE_MAIL_TEMPLATE']);
            }
        } else {
            $appInstance->BadRequest('Missing required fields', ['error_code' => 'MISSING_REQUIRED_FIELDS']);
        }
    } else {
        $appInstance->Unauthorized('Unauthorized', ['error_code' => 'INVALID_SESSION']);
    }
});

$router->post('/api/admin/mail/mail-templates/(.*)/update', function (string $id): void {
    App::init();
    $appInstance = App::getInstance(true);
    $appInstance->allowOnlyPOST();
    $session = new MyMythicalID\Chat\User\Session($appInstance);

    if (Can::canAccessAdminUI($session->getInfo(UserColumns::ROLE_ID, false))) {
        if (isset($_POST['name']) && isset($_POST['content']) && isset($_POST['active'])) {
            $name = $_POST['name'];
            $content = $_POST['content'];
            $active = $_POST['active'];
            if (!MailTemplates::exists($id)) {
                $appInstance->BadRequest('Mail template does not exist', ['error_code' => 'MAIL_TEMPLATE_DOES_NOT_EXIST']);

                return;
            }
            $active = strtolower($active);
            if (!in_array($active, ['true', 'false'])) {
                $appInstance->BadRequest('Invalid active value', ['error_code' => 'INVALID_ACTIVE_VALUE']);

                return;
            }

            if (strlen($name) > 255) {
                $appInstance->BadRequest('Name is too long', ['error_code' => 'NAME_TOO_LONG']);

                return;
            }

            if (strlen($content) > 65535) {
                $appInstance->BadRequest('Content is too long', ['error_code' => 'CONTENT_TOO_LONG']);

                return;
            }

            if (strlen($name) < 1) {
                $appInstance->BadRequest('Name is too short', ['error_code' => 'NAME_TOO_SHORT']);

                return;
            }

            if (strlen($content) < 1) {
                $appInstance->BadRequest('Content is too short', ['error_code' => 'CONTENT_TOO_SHORT']);

                return;
            }
            $info = MailTemplates::get($id);
            if ($info['name'] !== $name) {
                if (MailTemplates::existsByName($name)) {
                    $appInstance->BadRequest('Mail template already exists: ' . $name . ' with id: ' . $id . ' and name: ' . $info['name'], ['error_code' => 'MAIL_TEMPLATE_ALREADY_EXISTS']);

                    return;
                }
            }

            $mailTemplates = MailTemplates::update($id, $name, $content, $active);
            if ($mailTemplates) {
                UserActivities::add(
                    $session->getInfo(UserColumns::UUID, false),
                    UserActivitiesTypes::$mail_template_update,
                    CloudFlareRealIP::getRealIP(),
                    "Updated mail template $name"
                );
                $appInstance->OK('Mail template updated successfully.', ['mail_template' => $mailTemplates]);
            } else {
                $appInstance->BadRequest('Failed to update mail template', ['error_code' => 'FAILED_TO_UPDATE_MAIL_TEMPLATE']);
            }
        } else {
            $appInstance->BadRequest('Missing required fields', ['error_code' => 'MISSING_REQUIRED_FIELDS']);
        }
    } else {
        $appInstance->Unauthorized('Unauthorized', ['error_code' => 'INVALID_SESSION']);
    }
});

$router->post('/api/admin/mail/mail-templates/(.*)/delete', function (string $id): void {
    App::init();
    $appInstance = App::getInstance(true);
    $appInstance->allowOnlyPOST();
    $session = new MyMythicalID\Chat\User\Session($appInstance);

    if (Can::canAccessAdminUI($session->getInfo(UserColumns::ROLE_ID, false))) {
        if (!MailTemplates::exists($id)) {
            $appInstance->BadRequest('Mail template does not exist', ['error_code' => 'MAIL_TEMPLATE_DOES_NOT_EXIST']);

            return;
        }

        UserActivities::add(
            $session->getInfo(UserColumns::UUID, false),
            UserActivitiesTypes::$mail_template_delete,
            CloudFlareRealIP::getRealIP(),
            "Deleted mail template $id"
        );

        if (MailTemplates::delete($id)) {
            $appInstance->OK('Mail template deleted successfully.', ['mail_template' => $id]);
        } else {
            $appInstance->BadRequest('Failed to delete mail template', ['error_code' => 'FAILED_TO_DELETE_MAIL_TEMPLATE']);
        }
    } else {
        $appInstance->Unauthorized('Unauthorized', ['error_code' => 'INVALID_SESSION']);
    }
});
