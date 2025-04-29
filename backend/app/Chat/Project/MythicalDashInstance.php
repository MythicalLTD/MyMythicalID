<?php

namespace MyMythicalID\Chat\Project;

use MyMythicalID\Chat\Database;
use PDO;

class MythicalDashInstance {
    public static function getTableName() {
        return 'mymythicalid_mythicaldash_instances';
    }

    /**
     * Create a new MythicalDash instance
     * 
     * @param string $uuid Instance UUID
     * @param string $user User UUID
     * @param int $project Project ID
     * @param int $licenseKey License key ID
     * @param string $companyName Company name
     * @param string $companyWebsite Company website
     * @param string $businessDescription Business description
     * @param string $hostingType Hosting type (free, paid, both)
     * @param int $currentUsers Current number of users
     * @param int $expectedUsers Expected number of users
     * @param string $instanceUrl Instance URL
     * @param string $serverType Server type (vps, dedicated, docker, other)
     * @param int $serverCount Number of servers
     * @param string $primaryEmail Primary email
     * @param string $abuseEmail Abuse email
     * @param string $supportEmail Support email
     * @param string $ownerFirstName Owner's first name
     * @param string $ownerLastName Owner's last name
     * @param string $ownerBirthDate Owner's birth date
     * 
     * @return int|false The ID of the newly created instance or false on failure
     */
    public static function create(
        string $uuid,
        string $user,
        int $project,
        int $licenseKey,
        string $companyName,
        string $companyWebsite,
        string $businessDescription,
        string $hostingType,
        int $currentUsers,
        int $expectedUsers,
        string $instanceUrl,
        string $serverType,
        int $serverCount,
        string $primaryEmail,
        string $abuseEmail,
        string $supportEmail,
        string $ownerFirstName,
        string $ownerLastName,
        string $ownerBirthDate
    ): int|false {
        try {
            $pdo = Database::getPdoConnection();
            $stmt = $pdo->prepare("INSERT INTO " . self::getTableName() . " (
                uuid, user, project, license_key, companyName, companyWebsite, 
                businessDescription, hostingType, currentUsers, expectedUsers, 
                instanceUrl, serverType, serverCount, primaryEmail, abuseEmail, 
                supportEmail, ownerFirstName, ownerLastName, ownerBirthDate, 
                deleted, locked
            ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, 'false', 'false')");
            
            $stmt->execute([
                $uuid, $user, $project, $licenseKey, $companyName, $companyWebsite,
                $businessDescription, $hostingType, $currentUsers, $expectedUsers,
                $instanceUrl, $serverType, $serverCount, $primaryEmail, $abuseEmail,
                $supportEmail, $ownerFirstName, $ownerLastName, $ownerBirthDate
            ]);
            
            return $pdo->lastInsertId();
        } catch (\Exception $e) {
            Database::db_Error('Failed to create MythicalDash instance: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Get an instance by ID
     * 
     * @param int $id Instance ID
     * @return array|false Instance data or false if not found
     */
    public static function getById(int $id): array|false {
        try {
            $pdo = Database::getPdoConnection();
            $stmt = $pdo->prepare("SELECT * FROM " . self::getTableName() . " WHERE id = ? AND deleted = 'false'");
            $stmt->execute([$id]);
            
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (\Exception $e) {
            Database::db_Error('Failed to get MythicalDash instance: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Get an instance by UUID
     * 
     * @param string $uuid Instance UUID
     * @return array|false Instance data or false if not found
     */
    public static function getByUuid(string $uuid): array|false {
        try {
            $pdo = Database::getPdoConnection();
            $stmt = $pdo->prepare("SELECT * FROM " . self::getTableName() . " WHERE uuid = ? AND deleted = 'false'");
            $stmt->execute([$uuid]);
            
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (\Exception $e) {
            Database::db_Error('Failed to get MythicalDash instance by UUID: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Get all instances
     * 
     * @param bool $includeDeleted Whether to include deleted instances
     * @return array Array of instances
     */
    public static function getAll(bool $includeDeleted = false): array {
        try {
            $pdo = Database::getPdoConnection();
            $sql = "SELECT * FROM " . self::getTableName();
            
            if (!$includeDeleted) {
                $sql .= " WHERE deleted = 'false'";
            }
            
            $stmt = $pdo->query($sql);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (\Exception $e) {
            Database::db_Error('Failed to get all MythicalDash instances: ' . $e->getMessage());
            return [];
        }
    }

    /**
     * Update an instance
     * 
     * @param int $id Instance ID
     * @param array $data Instance data to update
     * @return bool True on success, false on failure
     */
    public static function update(int $id, array $data): bool {
        try {
            $pdo = Database::getPdoConnection();
            
            // Build the SET clause
            $setClause = [];
            $params = [];
            
            $allowedFields = [
                'companyName', 'companyWebsite', 'businessDescription', 'hostingType',
                'currentUsers', 'expectedUsers', 'instanceUrl', 'serverType', 'serverCount',
                'primaryEmail', 'abuseEmail', 'supportEmail', 'ownerFirstName', 'ownerLastName',
                'ownerBirthDate'
            ];
            
            foreach ($data as $key => $value) {
                if (in_array($key, $allowedFields)) {
                    $setClause[] = "$key = ?";
                    $params[] = $value;
                }
            }
            
            if (empty($setClause)) {
                return false;
            }
            
            $params[] = $id;
            $sql = "UPDATE " . self::getTableName() . " SET " . implode(', ', $setClause) . " WHERE id = ? AND deleted = 'false'";
            
            $stmt = $pdo->prepare($sql);
            return $stmt->execute($params);
        } catch (\Exception $e) {
            Database::db_Error('Failed to update MythicalDash instance: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Delete an instance (soft delete)
     * 
     * @param int $id Instance ID
     * @return bool True on success, false on failure
     */
    public static function delete(int $id): bool {
        try {
            return Database::markRecordAsDeleted(self::getTableName(), $id);
        } catch (\Exception $e) {
            Database::db_Error('Failed to delete MythicalDash instance: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Restore a deleted instance
     * 
     * @param int $id Instance ID
     * @return bool True on success, false on failure
     */
    public static function restore(int $id): bool {
        try {
            return Database::restoreRecord(self::getTableName(), $id);
        } catch (\Exception $e) {
            Database::db_Error('Failed to restore MythicalDash instance: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Lock an instance
     * 
     * @param int $id Instance ID
     * @return bool True on success, false on failure
     */
    public static function lock(int $id): bool {
        try {
            return Database::lockRecord(self::getTableName(), $id);
        } catch (\Exception $e) {
            Database::db_Error('Failed to lock MythicalDash instance: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Unlock an instance
     * 
     * @param int $id Instance ID
     * @return bool True on success, false on failure
     */
    public static function unlock(int $id): bool {
        try {
            return Database::unlockRecord(self::getTableName(), $id);
        } catch (\Exception $e) {
            Database::db_Error('Failed to unlock MythicalDash instance: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Check if an instance is locked
     * 
     * @param int $id Instance ID
     * @return bool True if locked, false otherwise
     */
    public static function isLocked(int $id): bool {
        try {
            return Database::isLocked(self::getTableName(), $id);
        } catch (\Exception $e) {
            Database::db_Error('Failed to check MythicalDash instance lock status: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Get the total number of instances
     * 
     * @param bool $includeDeleted Whether to include deleted instances
     * @return int Number of instances
     */
    public static function getCount(bool $includeDeleted = false): int {
        try {
            return Database::getTableRowCount(self::getTableName(), $includeDeleted);
        } catch (\Exception $e) {
            Database::db_Error('Failed to get MythicalDash instance count: ' . $e->getMessage());
            return 0;
        }
    }

    /**
     * Get instances by user UUID
     * 
     * @param string $userUuid User UUID
     * @param bool $includeDeleted Whether to include deleted instances
     * @return array Array of instances
     */
    public static function getByUser(string $userUuid, bool $includeDeleted = false): array {
        try {
            $pdo = Database::getPdoConnection();
            $sql = "SELECT * FROM " . self::getTableName() . " WHERE user = ?";
            
            if (!$includeDeleted) {
                $sql .= " AND deleted = 'false'";
            }
            
            $stmt = $pdo->prepare($sql);
            $stmt->execute([$userUuid]);
            
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (\Exception $e) {
            Database::db_Error('Failed to get MythicalDash instances by user: ' . $e->getMessage());
            return [];
        }
    }

    /**
     * Get instances by project ID
     * 
     * @param int $projectId Project ID
     * @param bool $includeDeleted Whether to include deleted instances
     * @return array Array of instances
     */
    public static function getByProject(int $projectId, bool $includeDeleted = false): array {
        try {
            $pdo = Database::getPdoConnection();
            $sql = "SELECT * FROM " . self::getTableName() . " WHERE project = ?";
            
            if (!$includeDeleted) {
                $sql .= " AND deleted = 'false'";
            }
            
            $stmt = $pdo->prepare($sql);
            $stmt->execute([$projectId]);
            
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (\Exception $e) {
            Database::db_Error('Failed to get MythicalDash instances by project: ' . $e->getMessage());
            return [];
        }
    }

    /**
     * Get instances by license key ID
     * 
     * @param int $licenseKeyId License key ID
     * @param bool $includeDeleted Whether to include deleted instances
     * @return array Array of instances
     */
    public static function getByLicenseKey(int $licenseKeyId, bool $includeDeleted = false): array {
        try {
            $pdo = Database::getPdoConnection();
            $sql = "SELECT * FROM " . self::getTableName() . " WHERE license_key = ?";
            
            if (!$includeDeleted) {
                $sql .= " AND deleted = 'false'";
            }
            
            $stmt = $pdo->prepare($sql);
            $stmt->execute([$licenseKeyId]);
            
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (\Exception $e) {
            Database::db_Error('Failed to get MythicalDash instances by license key: ' . $e->getMessage());
            return [];
        }
    }
	/**
	 * Does the instance exist?
	 * 
	 * @param int $id
	 * @return bool
	 */
	public static function exists(int $id): bool {
		return self::getById($id) !== false;
	}
} 