<?php

namespace MyMythicalID\Chat\Project;

use MyMythicalID\Chat\Database;
use PDO;

class Project {
	public static function getTableName() {
		return 'mymythicalid_projects';
	}

	/**
	 * Create a new project
	 * 
	 * @param string $name Project name
	 * @param string $description Project description
	 * @param string $type Project type (web, app, plugin, other)
	 * @param int $price Project price
	 * @param string[] $features Project features
	 * @param string $link Project link
	 * 
	 * @return int|false The ID of the newly created project or false on failure
	 */
	public static function create(string $name, string $description, string $type = 'other', int $price = 0, string $features = '{}', string $link = ''): int|false {
		try {
			$pdo = Database::getPdoConnection();
			$stmt = $pdo->prepare("INSERT INTO " . self::getTableName() . " (name, description, uuid, type, price, features, link) VALUES (?, ?, ?, ?, ?, ?, ?)");
			
			$uuid = uniqid('proj_', true);
			$stmt->execute([$name, $description, $uuid, $type, $price, $features, $link]);
			
			return $pdo->lastInsertId();
		} catch (\Exception $e) {
			Database::db_Error('Failed to create project: ' . $e->getMessage());
			return false;
		}
	}

	/**
	 * Get a project by ID
	 * 
	 * @param int $id Project ID
	 * @return array|false Project data or false if not found
	 */
	public static function getById(int $id): array|false {
		try {
			$pdo = Database::getPdoConnection();
			$stmt = $pdo->prepare("SELECT * FROM " . self::getTableName() . " WHERE id = ? AND deleted = 'false'");
			$stmt->execute([$id]);
			
			return $stmt->fetch(PDO::FETCH_ASSOC);
		} catch (\Exception $e) {
			Database::db_Error('Failed to get project: ' . $e->getMessage());
			return false;
		}
	}

	/**
	 * Get a project by UUID
	 * 
	 * @param string $uuid Project UUID
	 * @return array|false Project data or false if not found
	 */
	public static function getByUuid(string $uuid): array|false {
		try {
			$pdo = Database::getPdoConnection();
			$stmt = $pdo->prepare("SELECT * FROM " . self::getTableName() . " WHERE uuid = ? AND deleted = 'false'");
			$stmt->execute([$uuid]);
			
			return $stmt->fetch(PDO::FETCH_ASSOC);
		} catch (\Exception $e) {
			Database::db_Error('Failed to get project by UUID: ' . $e->getMessage());
			return false;
		}
	}

	/**
	 * Get all projects
	 * 
	 * @param bool $includeDeleted Whether to include deleted projects
	 * @return array Array of projects
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
			Database::db_Error('Failed to get all projects: ' . $e->getMessage());
			return [];
		}
	}

	/**
	 * Update a project
	 * 
	 * @param int $id Project ID
	 * @param array $data Project data to update
	 * @return bool True on success, false on failure
	 */
	public static function update(int $id, array $data): bool {
		try {
			$pdo = Database::getPdoConnection();
			
			// Build the SET clause
			$setClause = [];
			$params = [];
			
			foreach ($data as $key => $value) {
				if (in_array($key, ['name', 'description', 'type', 'price', 'features', 'link'])) {
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
			Database::db_Error('Failed to update project: ' . $e->getMessage());
			return false;
		}
	}

	/**
	 * Delete a project (soft delete)
	 * 
	 * @param int $id Project ID
	 * @return bool True on success, false on failure
	 */
	public static function delete(int $id): bool {
		try {
			return Database::markRecordAsDeleted(self::getTableName(), $id);
		} catch (\Exception $e) {
			Database::db_Error('Failed to delete project: ' . $e->getMessage());
			return false;
		}
	}

	/**
	 * Restore a deleted project
	 * 
	 * @param int $id Project ID
	 * @return bool True on success, false on failure
	 */
	public static function restore(int $id): bool {
		try {
			return Database::restoreRecord(self::getTableName(), $id);
		} catch (\Exception $e) {
			Database::db_Error('Failed to restore project: ' . $e->getMessage());
			return false;
		}
	}

	/**
	 * Lock a project
	 * 
	 * @param int $id Project ID
	 * @return bool True on success, false on failure
	 */
	public static function lock(int $id): bool {
		try {
			return Database::lockRecord(self::getTableName(), $id);
		} catch (\Exception $e) {
			Database::db_Error('Failed to lock project: ' . $e->getMessage());
			return false;
		}
	}

	/**
	 * Unlock a project
	 * 
	 * @param int $id Project ID
	 * @return bool True on success, false on failure
	 */
	public static function unlock(int $id): bool {
		try {
			return Database::unlockRecord(self::getTableName(), $id);
		} catch (\Exception $e) {
			Database::db_Error('Failed to unlock project: ' . $e->getMessage());
			return false;
		}
	}

	/**
	 * Check if a project is locked
	 * 
	 * @param int $id Project ID
	 * @return bool True if locked, false otherwise
	 */
	public static function isLocked(int $id): bool {
		try {
			return Database::isLocked(self::getTableName(), $id);
		} catch (\Exception $e) {
			Database::db_Error('Failed to check project lock status: ' . $e->getMessage());
			return false;
		}
	}

	/**
	 * Get the total number of projects
	 * 
	 * @param bool $includeDeleted Whether to include deleted projects
	 * @return int Number of projects
	 */
	public static function getCount(bool $includeDeleted = false): int {
		try {
			return Database::getTableRowCount(self::getTableName(), $includeDeleted);
		} catch (\Exception $e) {
			Database::db_Error('Failed to get project count: ' . $e->getMessage());
			return 0;
		}
	}
}