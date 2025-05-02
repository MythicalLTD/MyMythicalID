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

namespace MyMythicalID\Chat\ZeroTrust;

use MyMythicalID\Chat\Database;

class ZeroTrust
{
    public static function getTableName()
    {
        return 'mymythicalid_zerotrust';
    }

    /**
     * Create a new ZeroTrust record.
     *
     * @param int $project Project ID
     * @param int|null $instance Instance ID
     * @param int|null $license License ID
     * @param string $osInfo OS information in JSON format
     * @param string $trustInfo Trust information in JSON format
     * @param string|null $action Action to take
     *
     * @return int|false The ID of the newly created record or false on failure
     */
    public static function create(int $project, ?int $instance = null, ?int $license = null, string $osInfo = '{}', string $trustInfo = '{}', ?string $action = null): int|false
    {
        try {
            $pdo = Database::getPdoConnection();
            $stmt = $pdo->prepare('INSERT INTO ' . self::getTableName() . ' (project, instance, license, osInfo, trustInfo, action) VALUES (?, ?, ?, ?, ?, ?)');

            $stmt->execute([$project, $instance, $license, $osInfo, $trustInfo, $action]);

            return $pdo->lastInsertId();
        } catch (\Exception $e) {
            Database::db_Error('Failed to create ZeroTrust record: ' . $e->getMessage());

            return false;
        }
    }

    /**
     * Get a ZeroTrust record by ID.
     *
     * @param int $id Record ID
     *
     * @return array|false Record data or false if not found
     */
    public static function getById(int $id): array|false
    {
        try {
            $pdo = Database::getPdoConnection();
            $stmt = $pdo->prepare('SELECT * FROM ' . self::getTableName() . " WHERE id = ? AND deleted = 'false'");
            $stmt->execute([$id]);

            return $stmt->fetch(\PDO::FETCH_ASSOC);
        } catch (\Exception $e) {
            Database::db_Error('Failed to get ZeroTrust record: ' . $e->getMessage());

            return false;
        }
    }

    /**
     * Get all ZeroTrust records.
     *
     * @param bool $includeDeleted Whether to include deleted records
     *
     * @return array Array of records
     */
    public static function getAll(bool $includeDeleted = false): array
    {
        try {
            $pdo = Database::getPdoConnection();
            $sql = 'SELECT * FROM ' . self::getTableName();

            if (!$includeDeleted) {
                $sql .= " WHERE deleted = 'false'";
            }

            $stmt = $pdo->query($sql);

            return $stmt->fetchAll(\PDO::FETCH_ASSOC);
        } catch (\Exception $e) {
            Database::db_Error('Failed to get all ZeroTrust records: ' . $e->getMessage());

            return [];
        }
    }

    /**
     * Get ZeroTrust records by project ID.
     *
     * @param int $projectId Project ID
     * @param bool $includeDeleted Whether to include deleted records
     *
     * @return array Array of records
     */
    public static function getByProject(int $projectId, bool $includeDeleted = false): array
    {
        try {
            $pdo = Database::getPdoConnection();
            $sql = 'SELECT * FROM ' . self::getTableName() . ' WHERE project = ?';

            if (!$includeDeleted) {
                $sql .= " AND deleted = 'false'";
            }

            $stmt = $pdo->prepare($sql);
            $stmt->execute([$projectId]);

            return $stmt->fetchAll(\PDO::FETCH_ASSOC);
        } catch (\Exception $e) {
            Database::db_Error('Failed to get ZeroTrust records by project: ' . $e->getMessage());

            return [];
        }
    }

    /**
     * Get ZeroTrust records by instance ID.
     *
     * @param int $instanceId Instance ID
     * @param bool $includeDeleted Whether to include deleted records
     *
     * @return array Array of records
     */
    public static function getByInstance(int $instanceId, bool $includeDeleted = false): array
    {
        try {
            $pdo = Database::getPdoConnection();
            $sql = 'SELECT * FROM ' . self::getTableName() . ' WHERE instance = ?';

            if (!$includeDeleted) {
                $sql .= " AND deleted = 'false'";
            }

            $stmt = $pdo->prepare($sql);
            $stmt->execute([$instanceId]);

            return $stmt->fetchAll(\PDO::FETCH_ASSOC);
        } catch (\Exception $e) {
            Database::db_Error('Failed to get ZeroTrust records by instance: ' . $e->getMessage());

            return [];
        }
    }

    /**
     * Get ZeroTrust records by license ID.
     *
     * @param int $licenseId License ID
     * @param bool $includeDeleted Whether to include deleted records
     *
     * @return array Array of records
     */
    public static function getByLicense(int $licenseId, bool $includeDeleted = false): array
    {
        try {
            $pdo = Database::getPdoConnection();
            $sql = 'SELECT * FROM ' . self::getTableName() . ' WHERE license = ?';

            if (!$includeDeleted) {
                $sql .= " AND deleted = 'false'";
            }

            $stmt = $pdo->prepare($sql);
            $stmt->execute([$licenseId]);

            return $stmt->fetchAll(\PDO::FETCH_ASSOC);
        } catch (\Exception $e) {
            Database::db_Error('Failed to get ZeroTrust records by license: ' . $e->getMessage());

            return [];
        }
    }

    /**
     * Update a ZeroTrust record.
     *
     * @param int $id Record ID
     * @param array $data Record data to update
     *
     * @return bool True on success, false on failure
     */
    public static function update(int $id, array $data): bool
    {
        try {
            $pdo = Database::getPdoConnection();

            // Build the SET clause
            $setClause = [];
            $params = [];

            foreach ($data as $key => $value) {
                if (in_array($key, ['project', 'instance', 'license', 'osInfo', 'trustInfo', 'action'])) {
                    $setClause[] = "$key = ?";
                    $params[] = $value;
                }
            }

            if (empty($setClause)) {
                return false;
            }

            $params[] = $id;
            $sql = 'UPDATE ' . self::getTableName() . ' SET ' . implode(', ', $setClause) . " WHERE id = ? AND deleted = 'false'";

            $stmt = $pdo->prepare($sql);

            return $stmt->execute($params);
        } catch (\Exception $e) {
            Database::db_Error('Failed to update ZeroTrust record: ' . $e->getMessage());

            return false;
        }
    }

    /**
     * Delete a ZeroTrust record (soft delete).
     *
     * @param int $id Record ID
     *
     * @return bool True on success, false on failure
     */
    public static function delete(int $id): bool
    {
        try {
            return Database::markRecordAsDeleted(self::getTableName(), $id);
        } catch (\Exception $e) {
            Database::db_Error('Failed to delete ZeroTrust record: ' . $e->getMessage());

            return false;
        }
    }

    /**
     * Restore a deleted ZeroTrust record.
     *
     * @param int $id Record ID
     *
     * @return bool True on success, false on failure
     */
    public static function restore(int $id): bool
    {
        try {
            return Database::restoreRecord(self::getTableName(), $id);
        } catch (\Exception $e) {
            Database::db_Error('Failed to restore ZeroTrust record: ' . $e->getMessage());

            return false;
        }
    }

    /**
     * Lock a ZeroTrust record.
     *
     * @param int $id Record ID
     *
     * @return bool True on success, false on failure
     */
    public static function lock(int $id): bool
    {
        try {
            return Database::lockRecord(self::getTableName(), $id);
        } catch (\Exception $e) {
            Database::db_Error('Failed to lock ZeroTrust record: ' . $e->getMessage());

            return false;
        }
    }

    /**
     * Unlock a ZeroTrust record.
     *
     * @param int $id Record ID
     *
     * @return bool True on success, false on failure
     */
    public static function unlock(int $id): bool
    {
        try {
            return Database::unlockRecord(self::getTableName(), $id);
        } catch (\Exception $e) {
            Database::db_Error('Failed to unlock ZeroTrust record: ' . $e->getMessage());

            return false;
        }
    }

    /**
     * Check if a ZeroTrust record is locked.
     *
     * @param int $id Record ID
     *
     * @return bool True if locked, false otherwise
     */
    public static function isLocked(int $id): bool
    {
        try {
            return Database::isLocked(self::getTableName(), $id);
        } catch (\Exception $e) {
            Database::db_Error('Failed to check ZeroTrust record lock status: ' . $e->getMessage());

            return false;
        }
    }

    /**
     * Get the total number of ZeroTrust records.
     *
     * @param bool $includeDeleted Whether to include deleted records
     *
     * @return int Number of records
     */
    public static function getCount(bool $includeDeleted = false): int
    {
        try {
            return Database::getTableRowCount(self::getTableName(), $includeDeleted);
        } catch (\Exception $e) {
            Database::db_Error('Failed to get ZeroTrust record count: ' . $e->getMessage());

            return 0;
        }
    }

    /**
     * Get a ZeroTrust record by filename.
     *
     * @param string $filename The filename to search for
     *
     * @return array|null The record if found, null otherwise
     */
    public static function getByFilename(string $filename): ?array
    {
        try {
            $query = "SELECT * FROM " . self::getTableName() . " WHERE trustInfo LIKE :filename AND action = 'backup'";
            $params = [':filename' => '%"filename":"' . $filename . '"%'];
            
            $result = Database::query($query, $params);
            
            if ($result && count($result) > 0) {
                return $result[0];
            }
            
            return null;
        } catch (\Exception $e) {
            Database::db_Error('Failed to get ZeroTrust record by filename: ' . $e->getMessage());
            
            return null;
        }
    }

}
