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

namespace MyMythicalID\Chat\LicenseKey;

use MyMythicalID\Chat\Database;

class LicenseKey
{
	public static function getTableName()
	{
		return 'mymythicalid_license_keys';
	}

	/**
	 * Create a new license key.
	 *
	 * @param int $projectId Project ID
	 * @param string $userUuid User UUID
	 * @param string $licenseKeyUuid License key UUID
	 * @param string $context License context
	 * @param string $expiresAt Expiration date
	 * @param int $instance Instance ID
	 * @param string $status License status (active, inactive, expired)
	 *
	 * @return int|false The ID of the newly created license key or false on failure
	 */
	public static function create(
		int $projectId,
		string $userUuid,
		string $licenseKeyUuid,
		string $context,
		string $expiresAt,
		int $instance = 0,
		string $status = 'active',
	): int|false {
		try {
			$pdo = Database::getPdoConnection();
			$stmt = $pdo->prepare('INSERT INTO ' . self::getTableName() .
				' (project, uuid, license_key_uuid, context, status, expires_at, instance) VALUES (?, ?, ?, ?, ?, ?, ?)');

			$stmt->execute([
				$projectId,
				$userUuid,
				$licenseKeyUuid,
				$context,
				$status,
				$expiresAt,
				$instance === 0 ? null : $instance
			]);

			return $pdo->lastInsertId();
		} catch (\Exception $e) {
			Database::db_Error('Failed to create license key: ' . $e->getMessage());

			return false;
		}
	}

	/**
	 * Get a license key by ID.
	 *
	 * @param int $id License key ID
	 *
	 * @return array|false License key data or false if not found
	 */
	public static function getById(int $id): array|false
	{
		try {
			$pdo = Database::getPdoConnection();
			$stmt = $pdo->prepare('SELECT * FROM ' . self::getTableName() . " WHERE id = ? AND deleted = 'false'");
			$stmt->execute([$id]);

			return $stmt->fetch(\PDO::FETCH_ASSOC);
		} catch (\Exception $e) {
			Database::db_Error('Failed to get license key: ' . $e->getMessage());

			return false;
		}
	}

	/**
	 * Get license keys by project ID.
	 *
	 * @param int $projectId Project ID
	 * @param bool $includeDeleted Whether to include deleted license keys
	 *
	 * @return array Array of license keys
	 */
	public static function getByProjectId(int $projectId, bool $includeDeleted = false): array
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
			Database::db_Error('Failed to get license keys by project: ' . $e->getMessage());

			return [];
		}
	}

	/**
	 * Get license keys by user UUID.
	 *
	 * @param string $userUuid User UUID
	 * @param bool $includeDeleted Whether to include deleted license keys
	 *
	 * @return array Array of license keys
	 */
	public static function getByUserUuid(string $userUuid, bool $includeDeleted = false): array
	{
		try {
			$pdo = Database::getPdoConnection();
			$sql = 'SELECT * FROM ' . self::getTableName() . ' WHERE uuid = ?';

			if (!$includeDeleted) {
				$sql .= " AND deleted = 'false'";
			}

			$stmt = $pdo->prepare($sql);
			$stmt->execute([$userUuid]);

			return $stmt->fetchAll(\PDO::FETCH_ASSOC);
		} catch (\Exception $e) {
			Database::db_Error('Failed to get license keys by user: ' . $e->getMessage());

			return [];
		}
	}

	/**
	 * Get license key by license key UUID.
	 *
	 * @param string $licenseKeyUuid License key UUID
	 *
	 * @return array|false License key data or false if not found
	 */
	public static function getByLicenseKeyUuid(string $licenseKeyUuid): array|false
	{
		try {
			$pdo = Database::getPdoConnection();
			$stmt = $pdo->prepare('SELECT * FROM ' . self::getTableName() . " WHERE license_key_uuid = ? AND deleted = 'false'");
			$stmt->execute([$licenseKeyUuid]);

			return $stmt->fetch(\PDO::FETCH_ASSOC);
		} catch (\Exception $e) {
			Database::db_Error('Failed to get license key by UUID: ' . $e->getMessage());

			return false;
		}
	}

	/**
	 * Get all license keys.
	 *
	 * @param bool $includeDeleted Whether to include deleted license keys
	 *
	 * @return array Array of license keys
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
			Database::db_Error('Failed to get all license keys: ' . $e->getMessage());

			return [];
		}
	}

	/**
	 * Update a license key.
	 *
	 * @param int $id License key ID
	 * @param array $data License key data to update
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

			$allowedFields = ['context', 'status', 'expires_at', 'instance'];
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
			$sql = 'UPDATE ' . self::getTableName() . ' SET ' . implode(', ', $setClause) . " WHERE id = ? AND deleted = 'false'";

			$stmt = $pdo->prepare($sql);

			return $stmt->execute($params);
		} catch (\Exception $e) {
			Database::db_Error('Failed to update license key: ' . $e->getMessage());

			return false;
		}
	}

	/**
	 * Delete a license key (soft delete).
	 *
	 * @param int $id License key ID
	 *
	 * @return bool True on success, false on failure
	 */
	public static function delete(int $id): bool
	{
		try {
			return Database::markRecordAsDeleted(self::getTableName(), $id);
		} catch (\Exception $e) {
			Database::db_Error('Failed to delete license key: ' . $e->getMessage());

			return false;
		}
	}

	/**
	 * Restore a deleted license key.
	 *
	 * @param int $id License key ID
	 *
	 * @return bool True on success, false on failure
	 */
	public static function restore(int $id): bool
	{
		try {
			return Database::restoreRecord(self::getTableName(), $id);
		} catch (\Exception $e) {
			Database::db_Error('Failed to restore license key: ' . $e->getMessage());

			return false;
		}
	}

	/**
	 * Lock a license key.
	 *
	 * @param int $id License key ID
	 *
	 * @return bool True on success, false on failure
	 */
	public static function lock(int $id): bool
	{
		try {
			return Database::lockRecord(self::getTableName(), $id);
		} catch (\Exception $e) {
			Database::db_Error('Failed to lock license key: ' . $e->getMessage());

			return false;
		}
	}

	/**
	 * Unlock a license key.
	 *
	 * @param int $id License key ID
	 *
	 * @return bool True on success, false on failure
	 */
	public static function unlock(int $id): bool
	{
		try {
			return Database::unlockRecord(self::getTableName(), $id);
		} catch (\Exception $e) {
			Database::db_Error('Failed to unlock license key: ' . $e->getMessage());

			return false;
		}
	}

	/**
	 * Check if a license key is locked.
	 *
	 * @param int $id License key ID
	 *
	 * @return bool True if locked, false otherwise
	 */
	public static function isLocked(int $id): bool
	{
		try {
			return Database::isLocked(self::getTableName(), $id);
		} catch (\Exception $e) {
			Database::db_Error('Failed to check license key lock status: ' . $e->getMessage());

			return false;
		}
	}

	/**
	 * Get the total number of license keys.
	 *
	 * @param bool $includeDeleted Whether to include deleted license keys
	 *
	 * @return int Number of license keys
	 */
	public static function getCount(bool $includeDeleted = false): int
	{
		try {
			return Database::getTableRowCount(self::getTableName(), $includeDeleted);
		} catch (\Exception $e) {
			Database::db_Error('Failed to get license key count: ' . $e->getMessage());

			return 0;
		}
	}

	/**
	 * Get active license keys.
	 *
	 * @param bool $includeDeleted Whether to include deleted license keys
	 *
	 * @return array Array of active license keys
	 */
	public static function getActive(bool $includeDeleted = false): array
	{
		try {
			$pdo = Database::getPdoConnection();
			$sql = 'SELECT * FROM ' . self::getTableName() . " WHERE status = 'active'";

			if (!$includeDeleted) {
				$sql .= " AND deleted = 'false'";
			}

			$stmt = $pdo->query($sql);

			return $stmt->fetchAll(\PDO::FETCH_ASSOC);
		} catch (\Exception $e) {
			Database::db_Error('Failed to get active license keys: ' . $e->getMessage());

			return [];
		}
	}

	/**
	 * Get expired license keys.
	 *
	 * @param bool $includeDeleted Whether to include deleted license keys
	 *
	 * @return array Array of expired license keys
	 */
	public static function getExpired(bool $includeDeleted = false): array
	{
		try {
			$pdo = Database::getPdoConnection();
			$sql = 'SELECT * FROM ' . self::getTableName() . " WHERE status = 'expired'";

			if (!$includeDeleted) {
				$sql .= " AND deleted = 'false'";
			}

			$stmt = $pdo->query($sql);

			return $stmt->fetchAll(\PDO::FETCH_ASSOC);
		} catch (\Exception $e) {
			Database::db_Error('Failed to get expired license keys: ' . $e->getMessage());

			return [];
		}
	}

	/**
	 * Check if a license key is expired.
	 *
	 * @param int $id License key ID
	 *
	 * @return bool True if expired, false otherwise
	 */
	public static function isExpired(int $id): bool
	{
		try {
			$pdo = Database::getPdoConnection();
			$stmt = $pdo->prepare('SELECT expires_at FROM ' . self::getTableName() . " WHERE id = ? AND deleted = 'false'");
			$stmt->execute([$id]);

			$expiresAt = $stmt->fetchColumn();
			if (!$expiresAt) {
				return false;
			}

			return strtotime($expiresAt) < time();
		} catch (\Exception $e) {
			Database::db_Error('Failed to check license key expiration: ' . $e->getMessage());

			return false;
		}
	}

	public static function exists(int $id): bool
	{
		return self::getById($id) !== false;
	}

	/**
	 * Get a license key by license key UUID.
	 *
	 * @param string $licenseKey License key UUID
	 *
	 * @return array|false License key data or false if not found
	 */
	public static function getByLicenseKey(string $licenseKey): array|false
	{
		try {
			$pdo = Database::getPdoConnection();
			$stmt = $pdo->prepare('SELECT * FROM ' . self::getTableName() . " WHERE license_key_uuid = ? AND deleted = 'false'");
			$stmt->execute([$licenseKey]);

			$result = $stmt->fetch(\PDO::FETCH_ASSOC);
			if ($result === false) {
				error_log('License key not found: ' . $licenseKey);

				return false;
			}

			return $result;
		} catch (\Exception $e) {
			error_log('Failed to get license key by license key: ' . $e->getMessage());
			Database::db_Error('Failed to get license key by license key: ' . $e->getMessage());

			return false;
		}
	}

	/**
	 * Check if a license key exists by license key UUID.
	 *
	 * @param string $licenseKey License key UUID
	 *
	 * @return bool True if exists, false otherwise
	 */
	public static function existsByLicenseKey(string $licenseKey): bool
	{
		return self::getByLicenseKey($licenseKey) !== false;
	}
}
