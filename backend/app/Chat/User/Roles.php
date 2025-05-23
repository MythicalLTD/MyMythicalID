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

namespace MyMythicalID\Chat\User;

use MyMythicalID\Chat\Database;
use MyMythicalID\Chat\columns\UserColumns;
use MyMythicalID\Chat\columns\RolesColumns;
use MyMythicalID\Chat\interface\RolesInterface;

class Roles extends Database
{
    public const TABLE_NAME = 'mymythicalid_roles';

    /**
     * Get the list of roles.
     */
    public static function getList(): array
    {
        try {
            $con = self::getPdoConnection();
            $stmt = $con->prepare('SELECT * FROM ' . self::TABLE_NAME . ' WHERE deleted = \'false\'');
            $stmt->execute();

            return $stmt->fetchAll(\PDO::FETCH_ASSOC);
        } catch (\Exception $e) {
            self::db_Error('Failed to get list of roles: ' . $e->getMessage());

            return [];
        }
    }

    /**
     * Get the role info.
     *
     * @param RolesInterface|string $real_name The role name
     * @param RolesColumns|string $info The column name
     *
     * @throws \InvalidArgumentException If the column name is invalid
     *
     * @return string|null The value of the column
     */
    public static function getInfo(RolesInterface|string $real_name, RolesColumns|string $info): ?string
    {
        try {
            if (!in_array($info, RolesColumns::getColumns())) {
                throw new \InvalidArgumentException('Invalid column name: ' . $info);
            }
            $con = self::getPdoConnection();
            $stmt = $con->prepare('SELECT ' . $info . ' FROM ' . self::TABLE_NAME . ' WHERE real_name = :real_name');
            $stmt->bindParam(':real_name', $real_name);
            $stmt->execute();

            return $stmt->fetchColumn();
        } catch (\Exception $e) {
            self::db_Error('Failed to grab the info about the role: ' . $e->getMessage());

            return null;
        }
    }

    /**
     * Update the role info.
     *
     * @param RolesInterface|string $real_name The role name
     * @param RolesColumns|string $info The column name
     * @param string $value The new value
     *
     * @throws \InvalidArgumentException If the column name is invalid
     */
    public static function updateInfo(RolesInterface|string $real_name, RolesColumns|string $info, string $value): bool
    {
        try {
            if (!in_array($info, RolesColumns::getColumns())) {
                throw new \InvalidArgumentException('Invalid column name: ' . $info);
            }
            $con = self::getPdoConnection();
            $stmt = $con->prepare('UPDATE ' . self::TABLE_NAME . ' SET ' . $info . ' = :value WHERE real_name = :real_name');
            $stmt->bindParam(':real_name', $real_name);
            $stmt->bindParam(':value', $value);

            return $stmt->execute();
        } catch (\Exception $e) {
            self::db_Error('Failed to update the role info: ' . $e->getMessage());

            return false;
        }
    }

    /**
     * Get the role name.
     *
     * @param string $uuid The user UUID
     *
     * @return string|null The role name
     */
    public static function getUserRoleName(string $uuid): ?string
    {
        try {
            $con = self::getPdoConnection();
            $id = User::getInfo(User::getTokenFromUUID($uuid), UserColumns::ROLE_ID, false);
            $stmt = $con->prepare('SELECT name FROM ' . self::TABLE_NAME . ' WHERE id = :id AND deleted = \'false\'');
            $stmt->bindParam(':id', $id);
            $stmt->execute();

            return $stmt->fetchColumn();
        } catch (\Exception $e) {
            self::db_Error('Failed to get role name: ' . $e->getMessage());

            return null;
        }
    }

    /**
     * Get the role real name.
     *
     * @param string $uuid The user UUID
     *
     * @return string|null The role real name
     */
    public static function getUserRealName(string $uuid): ?string
    {
        try {
            $con = self::getPdoConnection();
            $id = User::getInfo(User::getTokenFromUUID($uuid), UserColumns::ROLE_ID, false);
            $stmt = $con->prepare('SELECT real_name FROM ' . self::TABLE_NAME . ' WHERE id = :id AND deleted = \'false\'');
            $stmt->bindParam(':id', $id);
            $stmt->execute();

            return $stmt->fetchColumn();
        } catch (\Exception $e) {
            self::db_Error('Failed to get real role name: ' . $e->getMessage());

            return null;
        }
    }

    /**
     * Get the role name from role ID.
     *
     * @param int $roleId The role ID
     * @param bool $useRealName Whether to return the real_name instead of name
     *
     * @return string|null The role name or real name
     */
    public static function getRoleNameById(int $roleId, bool $useRealName = false): ?string
    {
        try {
            $con = self::getPdoConnection();
            $column = $useRealName ? 'real_name' : 'name';
            $stmt = $con->prepare('SELECT ' . $column . ' FROM ' . self::TABLE_NAME . ' WHERE id = :id AND deleted = \'false\'');
            $stmt->bindParam(':id', $roleId, \PDO::PARAM_INT);
            $stmt->execute();

            return $stmt->fetchColumn();
        } catch (\Exception $e) {
            self::db_Error('Failed to get role name by ID: ' . $e->getMessage());

            return null;
        }
    }
}
