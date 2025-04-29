-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: nayskutzu
-- Generation Time: Apr 25, 2025 at 08:19 AM
-- Server version: 11.7.2-MariaDB-deb12
-- PHP Version: 8.2.28
SET
	SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";

START TRANSACTION;

SET
	time_zone = "+00:00";

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;

/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;

/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;

/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `mymythicalid`
--
-- --------------------------------------------------------
--
-- Table structure for table `mymythicalid_announcements`
--
CREATE TABLE
	`mymythicalid_announcements` (
		`id` int (11) NOT NULL,
		`title` text NOT NULL,
		`shortDescription` text NOT NULL,
		`description` text NOT NULL,
		`deleted` enum ('false', 'true') NOT NULL DEFAULT 'false',
		`locked` enum ('false', 'true') NOT NULL DEFAULT 'false',
		`date` datetime NOT NULL DEFAULT current_timestamp()
	) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_general_ci;

-- --------------------------------------------------------
--
-- Table structure for table `mymythicalid_announcements_assets`
--
CREATE TABLE
	`mymythicalid_announcements_assets` (
		`id` int (11) NOT NULL,
		`announcements` int (16) NOT NULL,
		`images` text NOT NULL,
		`deleted` enum ('false', 'true') NOT NULL DEFAULT 'false',
		`locked` enum ('false', 'true') NOT NULL DEFAULT 'false',
		`date` datetime NOT NULL DEFAULT current_timestamp()
	) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_general_ci;

-- --------------------------------------------------------
--
-- Table structure for table `mymythicalid_announcements_tags`
--
CREATE TABLE
	`mymythicalid_announcements_tags` (
		`id` int (11) NOT NULL,
		`announcements` int (16) NOT NULL,
		`tag` text NOT NULL,
		`deleted` enum ('false', 'true') NOT NULL DEFAULT 'false',
		`locked` enum ('false', 'true') NOT NULL DEFAULT 'false',
		`date` datetime NOT NULL DEFAULT current_timestamp()
	) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_general_ci;

-- --------------------------------------------------------
--
-- Table structure for table `mymythicalid_departments`
--
CREATE TABLE
	`mymythicalid_departments` (
		`id` int (11) NOT NULL,
		`name` text NOT NULL,
		`description` text NOT NULL,
		`time_open` text NOT NULL,
		`time_close` text NOT NULL,
		`enabled` enum ('false', 'true') NOT NULL DEFAULT 'false',
		`deleted` enum ('false', 'true') NOT NULL DEFAULT 'false',
		`locked` enum ('false', 'true') NOT NULL DEFAULT 'false',
		`date` datetime NOT NULL DEFAULT current_timestamp()
	) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_general_ci;

-- --------------------------------------------------------
--
-- Table structure for table `mymythicalid_mail_templates`
--
CREATE TABLE
	`mymythicalid_mail_templates` (
		`id` int (11) NOT NULL,
		`name` text NOT NULL,
		`content` text NOT NULL,
		`deleted` enum ('false', 'true') NOT NULL DEFAULT 'false',
		`locked` enum ('false', 'true') NOT NULL DEFAULT 'false',
		`active` enum ('false', 'true') NOT NULL DEFAULT 'false',
		`date` datetime NOT NULL DEFAULT current_timestamp()
	) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_general_ci;

--
-- Dumping data for table `mymythicalid_mail_templates`
--
INSERT INTO
	`mymythicalid_mail_templates` (
		`id`,
		`name`,
		`content`,
		`deleted`,
		`locked`,
		`active`,
		`date`
	)
VALUES
	(
		1,
		'verify',
		'<!doctypehtml><html lang=en><meta charset=UTF-8><meta content=\"width=device-width,initial-scale=1\"name=viewport><title>Verify your ${app_name} account</title><style>body{font-family:Arial,sans-serif;line-height:1.6;color:#333;background-color:#1a103c;margin:0;padding:0}.container{max-width:600px;margin:20px auto;background-color:#fff;border-radius:8px;overflow:hidden}.header{background-color:#1a103c;color:#fff;text-align:center;padding:20px}.content{padding:20px}.button{display:inline-block;background-color:#6366f1;color:#fff;text-decoration:none;padding:10px 20px;border-radius:5px;margin-top:20px}.footer{background-color:#f4f4f4;text-align:center;padding:10px;font-size:12px;color:#666}</style><div class=container><div class=header><h1>${app_name}</h1></div><div class=content><h2>Verify your email address</h2><p>Dear Sir or Madam,</p><p>Hi ${first_name} ${last_name},</p><p>Thanks for signing up for ${app_name}. Please verify your email address by clicking the button below.</p><a class=button href=\"${app_url}/api/user/auth/verify?code=${token}\">Verify Email Address</a><p>If you did not create an account with ${app_name}, you can safely ignore this email.</p></div><div class=footer><p>© 2025 ${app_name}. All rights reserved.</p></div></div>',
		'false',
		'false',
		'true',
		'2025-04-25 08:16:00'
	),
	(
		2,
		'reset_password',
		'<!doctypehtml><html lang=en><meta charset=UTF-8><meta content=\"width=device-width,initial-scale=1\"name=viewport><title>Reset your ${app_name} password</title><style>body{font-family:Arial,sans-serif;line-height:1.6;color:#333;background-color:#1a103c;margin:0;padding:0}.container{max-width:600px;margin:20px auto;background-color:#fff;border-radius:8px;overflow:hidden}.header{background-color:#1a103c;color:#fff;text-align:center;padding:20px}.content{padding:20px}.button{display:inline-block;background-color:#6366f1;color:#fff;text-decoration:none;padding:10px 20px;border-radius:5px;margin-top:20px}.footer{background-color:#f4f4f4;text-align:center;padding:10px;font-size:12px;color:#666}</style><div class=container><div class=header><h1>${app_name}</h1></div><div class=content><h2>Reset your password</h2><p>Dear Sir or Madam,</p><p>Hi ${first_name} ${last_name},</p><p>We received a request to reset your password for your ${app_name} account. Click the button below to reset it.</p><a class=button href=\"${app_url}/auth/reset-password?token=${token}\">Reset Password</a><p>If you did not request a password reset, you can safely ignore this email.</p></div><div class=footer><p>© 2025 ${app_name}. All rights reserved.</p></div></div>',
		'false',
		'false',
		'true',
		'2025-04-25 08:16:00'
	),
	(
		3,
		'new_login',
		'<!doctypehtml><html lang=en><meta charset=UTF-8><meta content=\"width=device-width,initial-scale=1\"name=viewport><title>New login to your ${app_name} account</title><style>body{font-family:Arial,sans-serif;line-height:1.6;color:#333;background-color:#1a103c;margin:0;padding:0}.container{max-width:600px;margin:20px auto;background-color:#fff;border-radius:8px;overflow:hidden}.header{background-color:#1a103c;color:#fff;text-align:center;padding:20px}.content{padding:20px}.button{display:inline-block;background-color:#6366f1;color:#fff;text-decoration:none;padding:10px 20px;border-radius:5px;margin-top:20px}.footer{background-color:#f4f4f4;text-align:center;padding:10px;font-size:12px;color:#666}</style><div class=container><div class=header><h1>${app_name}</h1></div><div class=content><h2>New login detected</h2><p>Dear Sir or Madam,</p><p>Hi ${first_name} ${last_name},</p><p>We detected a new login to your ${app_name} account from a new device. If this was you, you can safely ignore this email. If this was not you, please secure your account immediately.</p><a class=button href=\"${app_url}/auth/reset-password\">Secure Account</a></div><div class=footer><p>© 2025 ${app_name}. All rights reserved.</p></div></div>',
		'false',
		'false',
		'true',
		'2025-04-25 08:16:00'
	),
	(
		4,
		'new_invoice',
		'<!doctypehtml><html lang=en><meta charset=UTF-8><meta content=\"width=device-width,initial-scale=1\"name=viewport><title>New Invoice from ${app_name}</title><style>body{font-family:Arial,sans-serif;line-height:1.6;color:#333;background-color:#1a103c;margin:0;padding:0}.container{max-width:600px;margin:20px auto;background-color:#fff;border-radius:8px;overflow:hidden}.header{background-color:#1a103c;color:#fff;text-align:center;padding:20px}.content{padding:20px}.button{display:inline-block;background-color:#6366f1;color:#fff;text-decoration:none;padding:10px 20px;border-radius:5px;margin-top:20px}.footer{background-color:#f4f4f4;text-align:center;padding:10px;font-size:12px;color:#666}</style><div class=container><div class=header><h1>${app_name}</h1></div><div class=content><h2>New Invoice</h2><p>Dear Sir or Madam,</p><p>Hi ${first_name} ${last_name},</p><p>You have a new invoice from ${app_name}. Please find the details below:</p><p>Invoice Number: ${invoice_number}</p><p>Amount: ${invoice_amount}</p><a class=button href=\"${app_url}/invoices/${invoice_id}/view\">View Invoice</a><p>If you have any questions, please contact our support team.</p></div><div class=footer><p>© 2025 ${app_name}. All rights reserved.</p></div></div>',
		'false',
		'false',
		'true',
		'2025-04-25 08:16:00'
	),
	(
		5,
		'product_ready',
		'<!doctypehtml><html lang=en><meta charset=UTF-8><meta content=\"width=device-width,initial-scale=1\"name=viewport><title>Your ${app_name} product is ready</title><style>body{font-family:Arial,sans-serif;line-height:1.6;color:#333;background-color:#1a103c;margin:0;padding:0}.container{max-width:600px;margin:20px auto;background-color:#fff;border-radius:8px;overflow:hidden}.header{background-color:#1a103c;color:#fff;text-align:center;padding:20px}.content{padding:20px}.button{display:inline-block;background-color:#6366f1;color:#fff;text-decoration:none;padding:10px 20px;border-radius:5px;margin-top:20px}.footer{background-color:#f4f4f4;text-align:center;padding:10px;font-size:12px;color:#666}</style><div class=container><div class=header><h1>${app_name}</h1></div><div class=content><h2>Your product is ready</h2><p>Dear Sir or Madam,</p><p>Hi ${first_name} ${last_name},</p><p>Your ${app_name} product is ready. You can access it using the following details:</p><p>Location: ${product_location}</p><p>Username: ${username}</p><p>Password: ${password}</p><p>Additional Info: ${additional_info}</p><a class=button href=\"${product_location}\">Access Product</a></div><div class=footer><p>© 2025 ${app_name}. All rights reserved.</p></div></div>',
		'false',
		'false',
		'true',
		'2025-04-25 08:16:00'
	),
	(
		6,
		'product_suspended',
		'<!doctypehtml><html lang=en><meta charset=UTF-8><meta content=\"width=device-width,initial-scale=1\"name=viewport><title>Your ${app_name} product was suspended</title><style>body{font-family:Arial,sans-serif;line-height:1.6;color:#333;background-color:#1a103c;margin:0;padding:0}.container{max-width:600px;margin:20px auto;background-color:#fff;border-radius:8px;overflow:hidden}.header{background-color:#1a103c;color:#fff;text-align:center;padding:20px}.content{padding:20px}.button{display:inline-block;background-color:#6366f1;color:#fff;text-decoration:none;padding:10px 20px;border-radius:5px;margin-top:20px}.footer{background-color:#f4f4f4;text-align:center;padding:10px;font-size:12px;color:#666}</style><div class=container><div class=header><h1>${app_name}</h1></div><div class=content><h2>Product Suspended</h2><p>Dear Sir or Madam,</p><p>Hi ${first_name} ${last_name},</p><p>Your ${app_name} product was suspended. Please contact support for more information.</p></div><div class=footer><p>© 2025 ${app_name}. All rights reserved.</p></div></div>',
		'false',
		'false',
		'true',
		'2025-04-25 08:16:00'
	),
	(
		7,
		'invoice_paid',
		'<!doctypehtml><html lang=en><meta charset=UTF-8><meta content=\"width=device-width,initial-scale=1\"name=viewport><title>Invoice Paid - ${app_name}</title><style>body{font-family:Arial,sans-serif;line-height:1.6;color:#333;background-color:#1a103c;margin:0;padding:0}.container{max-width:600px;margin:20px auto;background-color:#fff;border-radius:8px;overflow:hidden}.header{background-color:#1a103c;color:#fff;text-align:center;padding:20px}.content{padding:20px}.button{display:inline-block;background-color:#6366f1;color:#fff;text-decoration:none;padding:10px 20px;border-radius:5px;margin-top:20px}.footer{background-color:#f4f4f4;text-align:center;padding:10px;font-size:12px;color:#666}</style><div class=container><div class=header><h1>${app_name}</h1></div><div class=content><h2>Invoice Paid</h2><p>Dear Sir or Madam,</p><p>Hi ${first_name} ${last_name},</p><p>Thank you for your payment. Your invoice ${invoice_number} has been paid successfully.</p><p>Amount: ${invoice_amount}</p><a class=button href=\"${app_url}/invoices/${invoice_id}/view\">View Invoice</a></div><div class=footer><p>© 2025 ${app_name}. All rights reserved.</p></div></div>',
		'false',
		'false',
		'true',
		'2025-04-25 08:16:00'
	);

-- --------------------------------------------------------
--
-- Table structure for table `mymythicalid_paypal_payments`
--
CREATE TABLE
	`mymythicalid_paypal_payments` (
		`id` int (11) NOT NULL,
		`code` text NOT NULL,
		`coins` int (11) NOT NULL,
		`user` varchar(36) NOT NULL,
		`status` enum ('processing', 'processed', 'failed') NOT NULL DEFAULT 'processing',
		`deleted` enum ('false', 'true') NOT NULL DEFAULT 'false',
		`locked` enum ('false', 'true') NOT NULL DEFAULT 'false',
		`date` datetime NOT NULL DEFAULT current_timestamp()
	) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_general_ci;

-- --------------------------------------------------------
--
-- Table structure for table `mymythicalid_roles`
--
CREATE TABLE
	`mymythicalid_roles` (
		`id` int (11) NOT NULL,
		`name` text NOT NULL,
		`real_name` text NOT NULL,
		`deleted` enum ('false', 'true') NOT NULL DEFAULT 'false',
		`locked` enum ('false', 'true') NOT NULL DEFAULT 'false',
		`date` datetime NOT NULL DEFAULT current_timestamp()
	) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_general_ci;

--
-- Dumping data for table `mymythicalid_roles`
--
INSERT INTO
	`mymythicalid_roles` (
		`id`,
		`name`,
		`real_name`,
		`deleted`,
		`locked`,
		`date`
	)
VALUES
	(
		1,
		'Default',
		'default',
		'false',
		'false',
		'2025-04-25 08:16:00'
	),
	(
		2,
		'VIP',
		'vip',
		'false',
		'false',
		'2025-04-25 08:16:00'
	),
	(
		3,
		'Support Buddy',
		'supportbuddy',
		'false',
		'false',
		'2025-04-25 08:16:00'
	),
	(
		4,
		'Support',
		'support',
		'false',
		'false',
		'2025-04-25 08:16:00'
	),
	(
		5,
		'Support LVL 3',
		'supportlvl3',
		'false',
		'false',
		'2025-04-25 08:16:00'
	),
	(
		6,
		'Support LVL 4',
		'supportlvl4',
		'false',
		'false',
		'2025-04-25 08:16:00'
	),
	(
		7,
		'Admin',
		'admin',
		'false',
		'false',
		'2025-04-25 08:16:00'
	),
	(
		8,
		'Administrator',
		'administrator',
		'false',
		'false',
		'2025-04-25 08:16:00'
	);

-- --------------------------------------------------------
--
-- Table structure for table `mymythicalid_settings`
--
CREATE TABLE
	`mymythicalid_settings` (
		`id` int (11) NOT NULL COMMENT 'The id of the setting!',
		`name` text NOT NULL COMMENT 'The name of the setting',
		`value` text NOT NULL COMMENT 'The value of the setting!',
		`date` datetime NOT NULL DEFAULT current_timestamp() COMMENT 'The date of the last modifed!'
	) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_general_ci COMMENT = 'The settings table where we store the settings of the dash!';

-- --------------------------------------------------------
--
-- Table structure for table `mymythicalid_stripe_payments`
--
CREATE TABLE
	`mymythicalid_stripe_payments` (
		`id` int (11) NOT NULL,
		`code` text NOT NULL,
		`coins` int (11) NOT NULL,
		`user` varchar(36) NOT NULL,
		`status` enum ('processing', 'processed', 'failed') NOT NULL DEFAULT 'processing',
		`deleted` enum ('false', 'true') NOT NULL DEFAULT 'false',
		`locked` enum ('false', 'true') NOT NULL DEFAULT 'false',
		`date` datetime NOT NULL DEFAULT current_timestamp()
	) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_general_ci;

-- --------------------------------------------------------
--
-- Table structure for table `mymythicalid_tickets`
--
CREATE TABLE
	`mymythicalid_tickets` (
		`id` int (11) NOT NULL,
		`user` varchar(36) NOT NULL,
		`department` int (16) NOT NULL,
		`priority` enum ('low', 'medium', 'high', 'urgent') NOT NULL DEFAULT 'low',
		`status` enum (
			'open',
			'closed',
			'waiting',
			'replied',
			'inprogress'
		) NOT NULL DEFAULT 'open',
		`title` text NOT NULL,
		`description` text NOT NULL,
		`deleted` enum ('false', 'true') NOT NULL DEFAULT 'false',
		`locked` enum ('false', 'true') NOT NULL DEFAULT 'false',
		`date` datetime NOT NULL DEFAULT current_timestamp()
	) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_general_ci;

-- --------------------------------------------------------
--
-- Table structure for table `mymythicalid_tickets_attachments`
--
CREATE TABLE
	`mymythicalid_tickets_attachments` (
		`id` int (11) NOT NULL,
		`ticket` int (16) NOT NULL,
		`file` text NOT NULL,
		`deleted` enum ('false', 'true') NOT NULL DEFAULT 'false',
		`locked` enum ('false', 'true') NOT NULL DEFAULT 'false',
		`date` datetime NOT NULL DEFAULT current_timestamp()
	) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_general_ci;

-- --------------------------------------------------------
--
-- Table structure for table `mymythicalid_tickets_messages`
--
CREATE TABLE
	`mymythicalid_tickets_messages` (
		`id` int (11) NOT NULL,
		`ticket` int (16) NOT NULL,
		`user` varchar(36) NOT NULL,
		`message` text NOT NULL,
		`deleted` enum ('false', 'true') NOT NULL DEFAULT 'false',
		`locked` enum ('false', 'true') NOT NULL DEFAULT 'false',
		`date` datetime NOT NULL DEFAULT current_timestamp()
	) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_general_ci;

-- --------------------------------------------------------
--
-- Table structure for table `mymythicalid_users`
--
CREATE TABLE
	`mymythicalid_users` (
		`id` int (11) NOT NULL,
		`username` text NOT NULL,
		`first_name` text NOT NULL,
		`last_name` text NOT NULL,
		`email` text NOT NULL,
		`password` text NOT NULL,
		`avatar` text DEFAULT 'https://www.gravatar.com/avatar',
		`credits` int (16) NOT NULL DEFAULT 0,
		`background` text NOT NULL DEFAULT 'https://cdn.mythical.systems/background.gif',
		`uuid` varchar(36) NOT NULL,
		`token` text NOT NULL,
		`role` int (11) NOT NULL DEFAULT 1,
		`first_ip` text NOT NULL,
		`last_ip` text NOT NULL,
		`banned` text DEFAULT 'NO',
		`verified` enum ('false', 'true') NOT NULL DEFAULT 'false',
		`support_pin` text DEFAULT NULL,
		`2fa_enabled` enum ('false', 'true') NOT NULL DEFAULT 'false',
		`2fa_key` text DEFAULT NULL,
		`2fa_blocked` enum ('false', 'true') NOT NULL DEFAULT 'false',
		`discord_id` text DEFAULT NULL,
		`github_id` int (11) DEFAULT NULL,
		`github_username` text DEFAULT NULL,
		`github_email` text DEFAULT NULL,
		`github_linked` enum ('true', 'false') DEFAULT 'false',
		`discord_username` text DEFAULT NULL,
		`discord_global_name` text DEFAULT NULL,
		`discord_email` text DEFAULT NULL,
		`discord_linked` enum ('true', 'false') DEFAULT 'false',
		`deleted` enum ('false', 'true') NOT NULL DEFAULT 'false',
		`locked` enum ('false', 'true') NOT NULL DEFAULT 'false',
		`last_seen` datetime NOT NULL DEFAULT current_timestamp(),
		`first_seen` datetime NOT NULL DEFAULT current_timestamp()
	) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_general_ci;

-- --------------------------------------------------------
--
-- Table structure for table `mymythicalid_users_activities`
--
CREATE TABLE
	`mymythicalid_users_activities` (
		`id` int (11) NOT NULL,
		`user` varchar(36) NOT NULL,
		`action` text NOT NULL,
		`ip_address` text NOT NULL,
		`deleted` enum ('false', 'true') NOT NULL DEFAULT 'false',
		`locked` enum ('false', 'true') NOT NULL DEFAULT 'false',
		`date` datetime NOT NULL DEFAULT current_timestamp(),
		`context` text NOT NULL DEFAULT 'None'
	) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_general_ci;

-- --------------------------------------------------------
--
-- Table structure for table `mymythicalid_users_apikeys`
--
CREATE TABLE
	`mymythicalid_users_apikeys` (
		`id` int (11) NOT NULL,
		`name` text NOT NULL,
		`user` varchar(36) NOT NULL,
		`type` enum ('r', 'rw') NOT NULL DEFAULT 'r',
		`value` text NOT NULL,
		`deleted` enum ('false', 'true') NOT NULL DEFAULT 'false',
		`locked` enum ('false', 'true') NOT NULL DEFAULT 'false',
		`date` datetime NOT NULL DEFAULT current_timestamp()
	) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_general_ci;

-- --------------------------------------------------------
--
-- Table structure for table `mymythicalid_users_email_verification`
--
CREATE TABLE
	`mymythicalid_users_email_verification` (
		`id` int (11) NOT NULL,
		`code` text NOT NULL,
		`user` varchar(36) NOT NULL,
		`type` enum ('password', 'verify') NOT NULL DEFAULT 'verify',
		`date` datetime NOT NULL DEFAULT current_timestamp()
	) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_general_ci;

-- --------------------------------------------------------
--
-- Table structure for table `mymythicalid_users_mails`
--
CREATE TABLE
	`mymythicalid_users_mails` (
		`id` int (11) NOT NULL,
		`subject` text NOT NULL,
		`body` longtext NOT NULL,
		`from` text NOT NULL DEFAULT 'app@mythical.systems',
		`user` varchar(36) NOT NULL,
		`read` int (11) NOT NULL DEFAULT 0,
		`deleted` enum ('false', 'true') NOT NULL DEFAULT 'false',
		`locked` enum ('false', 'true') NOT NULL DEFAULT 'false',
		`date` datetime NOT NULL DEFAULT current_timestamp()
	) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_general_ci;

-- --------------------------------------------------------
--
-- Table structure for table `mymythicalid_users_notifications`
--
CREATE TABLE
	`mymythicalid_users_notifications` (
		`id` int (11) NOT NULL,
		`user` varchar(36) NOT NULL,
		`name` text NOT NULL,
		`description` text NOT NULL,
		`deleted` enum ('false', 'true') NOT NULL DEFAULT 'false',
		`locked` enum ('false', 'true') NOT NULL DEFAULT 'false',
		`date` datetime NOT NULL DEFAULT current_timestamp()
	) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_general_ci;

CREATE TABLE
	`mymythicalid_projects` (
		`id` INT NOT NULL AUTO_INCREMENT,
		`name` TEXT NOT NULL,
		`description` TEXT NOT NULL,
		`uuid` TEXT NOT NULL,
		`type` ENUM ('web', 'app', 'plugin', 'other') NOT NULL DEFAULT 'other',
		`deleted` enum ('false', 'true') NOT NULL DEFAULT 'false',
		`locked` enum ('false', 'true') NOT NULL DEFAULT 'false',
		`date` datetime NOT NULL DEFAULT current_timestamp(),
		PRIMARY KEY (`id`)
	) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_general_ci;

CREATE TABLE
	`mymythicalid_mythicaldash_instances` (
		`id` INT NOT NULL AUTO_INCREMENT,
		`uuid` TEXT NOT NULL,
		`user` varchar(36) NOT NULL,
		`project` INT (32) NOT NULL,
		`license_key` INT (32) NOT NULL,
		`companyName` TEXT NOT NULL,
		`companyWebsite` TEXT NOT NULL,
		`businessDescription` TEXT NOT NULL,
		`hostingType` ENUM ('free', 'paid', 'both') NOT NULL,
		`currentUsers` INT NOT NULL,
		`expectedUsers` INT NOT NULL,
		`instanceUrl` TEXT NOT NULL,
		`serverType` ENUM ('vps', 'dedicated', 'docker', 'other') NULL DEFAULT 'other',
		`serverCount` INT NOT NULL,
		`primaryEmail` TEXT NOT NULL,
		`abuseEmail` TEXT NOT NULL,
		`supportEmail` TEXT NOT NULL,
		`ownerFirstName` TEXT NOT NULL,
		`ownerLastName` TEXT NOT NULL,
		`ownerBirthDate` DATETIME NOT NULL,
		`deleted` ENUM ('false', 'true') NOT NULL,
		`locked` ENUM ('false', 'true') NOT NULL,
		`updated_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
		`created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT 'The date of the creation!' ON UPDATE CURRENT_TIMESTAMP,
		PRIMARY KEY (`id`),
		FOREIGN KEY (`user`) REFERENCES `mymythicalid_users` (`uuid`),
		FOREIGN KEY (`project`) REFERENCES `mymythicalid_projects` (`id`),
		FOREIGN KEY (`license_key`) REFERENCES `mymythicalid_license_keys` (`id`)
	) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_general_ci;

CREATE TABLE
	`mymythicalid_license_keys` (
		`id` INT NOT NULL AUTO_INCREMENT,
		`project` INT (16) NOT NULL,
		`uuid` varchar(36) NOT NULL,
		`license_key_uuid` TEXT NOT NULL,
		`context` TEXT NOT NULL,
		`status` ENUM ('active', 'inactive', 'expired') NOT NULL DEFAULT 'active',
		`deleted` enum ('false', 'true') NOT NULL DEFAULT 'false',
		`locked` enum ('false', 'true') NOT NULL DEFAULT 'false',
		`expires_at` datetime NOT NULL,
		`date` datetime NOT NULL DEFAULT current_timestamp(),
		FOREIGN KEY (`project`) REFERENCES `mymythicalid_projects` (`id`),
		FOREIGN KEY (`uuid`) REFERENCES `mymythicalid_users` (`uuid`),
		PRIMARY KEY (`id`)
	) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_general_ci;

CREATE TABLE
	`mymythicalid_zerotrust` (
		`id` INT NOT NULL AUTO_INCREMENT,
		`project` INT (36) NOT NULL,
		`instance` INT (36) NULL DEFAULT NULL,
		`license` INT (36) NULL DEFAULT NULL,
		`osInfo` TEXT NOT NULL DEFAULT '{}',
		`trustInfo` TEXT NOT NULL DEFAULT '{}',
		`action` TEXT NULL DEFAULT NULL,
		`deleted` enum ('false', 'true') NOT NULL DEFAULT 'false',
		`locked` enum ('false', 'true') NOT NULL DEFAULT 'false',
		`date` datetime NOT NULL DEFAULT current_timestamp(),
		PRIMARY KEY (`id`),
		FOREIGN KEY (`project`) REFERENCES `mymythicalid_projects` (`id`),
		FOREIGN KEY (`instance`) REFERENCES `mymythicalid_mythicaldash_instances` (`id`),
		FOREIGN KEY (`license`) REFERENCES `mymythicalid_license_keys` (`id`)
	) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_general_ci;

--
-- Indexes for dumped tables
--
--
-- Indexes for table `mymythicalid_announcements`
--
ALTER TABLE `mymythicalid_announcements` ADD PRIMARY KEY (`id`);

--
-- Indexes for table `mymythicalid_announcements_assets`
--
ALTER TABLE `mymythicalid_announcements_assets` ADD PRIMARY KEY (`id`),
ADD KEY `announcements` (`announcements`);

--
-- Indexes for table `mymythicalid_announcements_tags`
--
ALTER TABLE `mymythicalid_announcements_tags` ADD PRIMARY KEY (`id`),
ADD KEY `announcements` (`announcements`);

--
-- Indexes for table `mymythicalid_departments`
--
ALTER TABLE `mymythicalid_departments` ADD PRIMARY KEY (`id`);

--
-- Indexes for table `mymythicalid_mail_templates`
--
ALTER TABLE `mymythicalid_mail_templates` ADD PRIMARY KEY (`id`);

--
-- Indexes for table `mymythicalid_paypal_payments`
--
ALTER TABLE `mymythicalid_paypal_payments` ADD PRIMARY KEY (`id`),
ADD KEY `user` (`user`);

--
-- Indexes for table `mymythicalid_roles`
--
ALTER TABLE `mymythicalid_roles` ADD PRIMARY KEY (`id`);

--
-- Indexes for table `mymythicalid_settings`
--
ALTER TABLE `mymythicalid_settings` ADD PRIMARY KEY (`id`);

--
-- Indexes for table `mymythicalid_stripe_payments`
--
ALTER TABLE `mymythicalid_stripe_payments` ADD PRIMARY KEY (`id`),
ADD KEY `user` (`user`);

--
-- Indexes for table `mymythicalid_tickets`
--
ALTER TABLE `mymythicalid_tickets` ADD PRIMARY KEY (`id`),
ADD KEY `user` (`user`),
ADD KEY `department` (`department`);

--
-- Indexes for table `mymythicalid_tickets_attachments`
--
ALTER TABLE `mymythicalid_tickets_attachments` ADD PRIMARY KEY (`id`),
ADD KEY `ticket` (`ticket`);

--
-- Indexes for table `mymythicalid_tickets_messages`
--
ALTER TABLE `mymythicalid_tickets_messages` ADD PRIMARY KEY (`id`),
ADD KEY `ticket` (`ticket`),
ADD KEY `user` (`user`);

--
-- Indexes for table `mymythicalid_users`
--
ALTER TABLE `mymythicalid_users` ADD PRIMARY KEY (`id`),
ADD UNIQUE KEY `uuid` (`uuid`),
ADD KEY `role` (`role`);

--
-- Indexes for table `mymythicalid_users_activities`
--
ALTER TABLE `mymythicalid_users_activities` ADD PRIMARY KEY (`id`),
ADD KEY `user` (`user`);

--
-- Indexes for table `mymythicalid_users_apikeys`
--
ALTER TABLE `mymythicalid_users_apikeys` ADD PRIMARY KEY (`id`),
ADD KEY `user` (`user`);

--
-- Indexes for table `mymythicalid_users_email_verification`
--
ALTER TABLE `mymythicalid_users_email_verification` ADD PRIMARY KEY (`id`),
ADD KEY `user` (`user`);

--
-- Indexes for table `mymythicalid_users_mails`
--
ALTER TABLE `mymythicalid_users_mails` ADD PRIMARY KEY (`id`),
ADD KEY `user` (`user`);

--
-- Indexes for table `mymythicalid_users_notifications`
--
ALTER TABLE `mymythicalid_users_notifications` ADD PRIMARY KEY (`id`),
ADD KEY `user` (`user`);

--
-- AUTO_INCREMENT for dumped tables
--
--
-- AUTO_INCREMENT for table `mymythicalid_announcements`
--
ALTER TABLE `mymythicalid_announcements` MODIFY `id` int (11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `mymythicalid_announcements_assets`
--
ALTER TABLE `mymythicalid_announcements_assets` MODIFY `id` int (11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `mymythicalid_announcements_tags`
--
ALTER TABLE `mymythicalid_announcements_tags` MODIFY `id` int (11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `mymythicalid_departments`
--
ALTER TABLE `mymythicalid_departments` MODIFY `id` int (11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `mymythicalid_mail_templates`
--
ALTER TABLE `mymythicalid_mail_templates` MODIFY `id` int (11) NOT NULL AUTO_INCREMENT,
AUTO_INCREMENT = 12;

--
-- AUTO_INCREMENT for table `mymythicalid_paypal_payments`
--
ALTER TABLE `mymythicalid_paypal_payments` MODIFY `id` int (11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `mymythicalid_roles`
--
ALTER TABLE `mymythicalid_roles` MODIFY `id` int (11) NOT NULL AUTO_INCREMENT,
AUTO_INCREMENT = 9;

--
-- AUTO_INCREMENT for table `mymythicalid_settings`
--
ALTER TABLE `mymythicalid_settings` MODIFY `id` int (11) NOT NULL AUTO_INCREMENT COMMENT 'The id of the setting!';

--
-- AUTO_INCREMENT for table `mymythicalid_stripe_payments`
--
ALTER TABLE `mymythicalid_stripe_payments` MODIFY `id` int (11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `mymythicalid_tickets`
--
ALTER TABLE `mymythicalid_tickets` MODIFY `id` int (11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `mymythicalid_tickets_attachments`
--
ALTER TABLE `mymythicalid_tickets_attachments` MODIFY `id` int (11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `mymythicalid_tickets_messages`
--
ALTER TABLE `mymythicalid_tickets_messages` MODIFY `id` int (11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `mymythicalid_users`
--
ALTER TABLE `mymythicalid_users` MODIFY `id` int (11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `mymythicalid_users_activities`
--
ALTER TABLE `mymythicalid_users_activities` MODIFY `id` int (11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `mymythicalid_users_apikeys`
--
ALTER TABLE `mymythicalid_users_apikeys` MODIFY `id` int (11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `mymythicalid_users_email_verification`
--
ALTER TABLE `mymythicalid_users_email_verification` MODIFY `id` int (11) NOT NULL AUTO_INCREMENT,
AUTO_INCREMENT = 2;

--
-- AUTO_INCREMENT for table `mymythicalid_users_mails`
--
ALTER TABLE `mymythicalid_users_mails` MODIFY `id` int (11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `mymythicalid_users_notifications`
--
ALTER TABLE `mymythicalid_users_notifications` MODIFY `id` int (11) NOT NULL AUTO_INCREMENT;

--
-- Constraints for dumped tables
--
--
-- Constraints for table `mymythicalid_announcements_assets`
--
ALTER TABLE `mymythicalid_announcements_assets` ADD CONSTRAINT `mymythicalid_announcements_assets_ibfk_1` FOREIGN KEY (`announcements`) REFERENCES `mymythicalid_announcements` (`id`);

--
-- Constraints for table `mymythicalid_announcements_tags`
--
ALTER TABLE `mymythicalid_announcements_tags` ADD CONSTRAINT `mymythicalid_announcements_tags_ibfk_1` FOREIGN KEY (`announcements`) REFERENCES `mymythicalid_announcements` (`id`);

--
-- Constraints for table `mymythicalid_paypal_payments`
--
ALTER TABLE `mymythicalid_paypal_payments` ADD CONSTRAINT `mymythicalid_paypal_payments_ibfk_1` FOREIGN KEY (`user`) REFERENCES `mymythicalid_users` (`uuid`);

--
-- Constraints for table `mymythicalid_stripe_payments`
--
ALTER TABLE `mymythicalid_stripe_payments` ADD CONSTRAINT `mymythicalid_stripe_payments_ibfk_1` FOREIGN KEY (`user`) REFERENCES `mymythicalid_users` (`uuid`);

--
-- Constraints for table `mymythicalid_tickets`
--
ALTER TABLE `mymythicalid_tickets` ADD CONSTRAINT `mymythicalid_tickets_ibfk_1` FOREIGN KEY (`user`) REFERENCES `mymythicalid_users` (`uuid`),
ADD CONSTRAINT `mymythicalid_tickets_ibfk_2` FOREIGN KEY (`department`) REFERENCES `mymythicalid_departments` (`id`);

--
-- Constraints for table `mymythicalid_tickets_attachments`
--
ALTER TABLE `mymythicalid_tickets_attachments` ADD CONSTRAINT `mymythicalid_tickets_attachments_ibfk_1` FOREIGN KEY (`ticket`) REFERENCES `mymythicalid_tickets` (`id`);

--
-- Constraints for table `mymythicalid_tickets_messages`
--
ALTER TABLE `mymythicalid_tickets_messages` ADD CONSTRAINT `mymythicalid_tickets_messages_ibfk_1` FOREIGN KEY (`ticket`) REFERENCES `mymythicalid_tickets` (`id`),
ADD CONSTRAINT `mymythicalid_tickets_messages_ibfk_2` FOREIGN KEY (`user`) REFERENCES `mymythicalid_users` (`uuid`);

--
-- Constraints for table `mymythicalid_users`
--
ALTER TABLE `mymythicalid_users` ADD CONSTRAINT `mymythicalid_users_ibfk_1` FOREIGN KEY (`role`) REFERENCES `mymythicalid_roles` (`id`);

--
-- Constraints for table `mymythicalid_users_activities`
--
ALTER TABLE `mymythicalid_users_activities` ADD CONSTRAINT `mymythicalid_users_activities_ibfk_1` FOREIGN KEY (`user`) REFERENCES `mymythicalid_users` (`uuid`);

--
-- Constraints for table `mymythicalid_users_apikeys`
--
ALTER TABLE `mymythicalid_users_apikeys` ADD CONSTRAINT `mymythicalid_users_apikeys_ibfk_1` FOREIGN KEY (`user`) REFERENCES `mymythicalid_users` (`uuid`);

--
-- Constraints for table `mymythicalid_users_email_verification`
--
ALTER TABLE `mymythicalid_users_email_verification` ADD CONSTRAINT `mymythicalid_users_email_verification_ibfk_1` FOREIGN KEY (`user`) REFERENCES `mymythicalid_users` (`uuid`);

--
-- Constraints for table `mymythicalid_users_mails`
--
ALTER TABLE `mymythicalid_users_mails` ADD CONSTRAINT `mymythicalid_users_mails_ibfk_1` FOREIGN KEY (`user`) REFERENCES `mymythicalid_users` (`uuid`);

--
-- Constraints for table `mymythicalid_users_notifications`
--
ALTER TABLE `mymythicalid_users_notifications` ADD CONSTRAINT `mymythicalid_users_notifications_ibfk_1` FOREIGN KEY (`user`) REFERENCES `mymythicalid_users` (`uuid`);

COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;

/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;

/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

ALTER TABLE `mymythicalid_projects` ADD `price` INT NOT NULL DEFAULT '0' AFTER `description`;

ALTER TABLE `mymythicalid_projects` ADD `features` TEXT NULL DEFAULT NULL AFTER `price`,
ADD `link` TEXT NULL DEFAULT NULL AFTER `features`;

ALTER TABLE `mymythicalid_license_keys` ADD `instance` INT (36) NOT NULL DEFAULT '0' AFTER `context`,
ADD CONSTRAINT `mymythicalid_license_keys_ibfk_3` FOREIGN KEY (`instance`) REFERENCES `mymythicalid_mythicaldash_instances` (`id`);

ALTER TABLE `mymythicalid_license_keys` ADD `other1` TEXT NULL DEFAULT NULL AFTER `context`;