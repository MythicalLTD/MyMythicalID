class LicenseKeys {
    /**
     * Get all license keys
     *
     * @returns Promise with all license keys
     */
    public static async getLicenseKeys() {
        const response = await fetch('/api/admin/license-keys', {
            method: 'GET',
        });
        return await response.json();
    }

    /**
     * Create a new license key
     *
     * @param projectId Project ID
     * @param userId User UUID
     * @param licenseKeyUuid License key UUID
     * @param context Context
     * @param status Status (active, inactive, expired)
     * @param expiresAt Expiration date
     * @returns Promise with the created license key
     */
    public static async createLicenseKey(
        projectId: string,
        userId: string,
        licenseKeyUuid: string,
        context: string,
        status: 'active' | 'inactive' | 'expired',
        instance: string,
        expiresAt: string,
    ) {
        const formData = new FormData();
        formData.append('project', projectId);
        formData.append('uuid', userId);
        formData.append('license_key_uuid', licenseKeyUuid);
        formData.append('context', context);
        formData.append('status', status);
        formData.append('expires_at', expiresAt);
        formData.append('instance', instance);
        const response = await fetch('/api/admin/license-key/create', {
            method: 'POST',
            body: formData,
        });
        return await response.json();
    }

    /**
     * Update a license key
     *
     * @param licenseKeyId The ID of the license key to update
     * @param projectId Project ID
     * @param userId User UUID
     * @param licenseKeyUuid License key UUID
     * @param context Context
     * @param status Status (active, inactive, expired)
     * @param expiresAt Expiration date
     * @returns Promise with the updated license key
     */
    public static async updateLicenseKey(
        licenseKeyId: string,
        projectId: string,
        userId: string,
        licenseKeyUuid: string,
        context: string,
        status: 'active' | 'inactive' | 'expired',
        instance: string,
        expiresAt: string,
    ) {
        const formData = new FormData();
        formData.append('project', projectId);
        formData.append('uuid', userId);
        formData.append('license_key_uuid', licenseKeyUuid);
        formData.append('context', context);
        formData.append('status', status);
        formData.append('instance', instance);
        formData.append('expires_at', expiresAt);

        const response = await fetch(`/api/admin/license-key/${licenseKeyId}/update`, {
            method: 'POST',
            body: formData,
        });
        return await response.json();
    }

    /**
     * Delete a license key
     *
     * @param licenseKeyId The ID of the license key to delete
     * @returns Promise with the response
     */
    public static async deleteLicenseKey(licenseKeyId: string) {
        const response = await fetch(`/api/admin/license-key/${licenseKeyId}/delete`, {
            method: 'POST',
        });
        return await response.json();
    }

    /**
     * Get a license key by ID
     *
     * @param licenseKeyId The ID of the license key to get
     * @returns Promise with the license key
     */
    public static async getLicenseKey(licenseKeyId: string) {
        const response = await fetch(`/api/admin/license-key/${licenseKeyId}/info`, {
            method: 'GET',
        });
        return await response.json();
    }
}

export default LicenseKeys;
