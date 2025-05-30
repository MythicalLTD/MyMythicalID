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

namespace MyMythicalID\Mail\templates;

use MyMythicalID\App;
use MyMythicalID\Mail\Mail;
use MyMythicalID\Chat\Database;
use MyMythicalID\Chat\User\User;
use MyMythicalID\Chat\User\Mails;
use MyMythicalID\Chat\columns\UserColumns;

class Verify extends Mail
{
    public static function sendMail(string $uuid, string $verifyToken): void
    {
        try {
            $template = self::getFinalTemplate($uuid);
            $template = str_replace('${token}', $verifyToken, $template);
            $email = User::getInfo(User::getTokenFromUUID($uuid), UserColumns::EMAIL, false);
            Mails::add('Verify your email', $template, $uuid);
            try {
                self::send($email, 'Verify your email', $template);
            } catch (\Exception $e) {
                App::getInstance(true)->getLogger()->error('(' . APP_SOURCECODE_DIR . '/Mail/templates/Verify.php) [sendMail] Failed to send email: ' . $e->getMessage());
            }
        } catch (\Exception $e) {
            App::getInstance(true)->getLogger()->error('(' . APP_SOURCECODE_DIR . '/Mail/templates/Verify.php) [sendMail] Failed to send email: ' . $e->getMessage());
        }
    }

    private static function getFinalTemplate(string $uuid): string
    {
        return self::processTemplate(self::getTemplate(), $uuid);
    }

    private static function getTemplate(): ?string
    {
        try {
            $conn = Database::getPdoConnection();
            $query = $conn->prepare('SELECT content FROM mymythicalid_mail_templates WHERE name = :name');
            $query->execute(['name' => 'verify']);
            $template = $query->fetchColumn();

            return $template;
        } catch (\Exception $e) {
            App::getInstance(true)->getLogger()->error('(' . APP_SOURCECODE_DIR . '/Mail/templates/Verify.php) [sendMail] Failed to process template: ' . $e->getMessage());

            return null;
        }
    }

    private static function processTemplate(string $template, string $uuid): string
    {
        try {
            $template = self::getTemplate();
            $template = User::processTemplate($template, $uuid);
            $template = Mail::processEmailTemplateGlobal($template);

            return $template;
        } catch (\Exception $e) {
            App::getInstance(true)->getLogger()->error('(' . APP_SOURCECODE_DIR . '/Mail/templates/Verify.php) [sendMail] Failed to process template: ' . $e->getMessage());

            return '';
        }
    }
}
