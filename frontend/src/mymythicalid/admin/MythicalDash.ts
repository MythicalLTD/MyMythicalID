interface MythicalDashInstance {
    id: number;
    uuid: string;
    user: string;
    project: number;
    license_key: number;
    companyName: string;
    companyWebsite: string;
    businessDescription: string;
    hostingType: 'free' | 'paid' | 'both';
    currentUsers: number;
    expectedUsers: number;
    instanceUrl: string;
    serverType: 'vps' | 'dedicated' | 'docker' | 'other';
    serverCount: number;
    primaryEmail: string;
    abuseEmail: string;
    supportEmail: string;
    ownerFirstName: string;
    ownerLastName: string;
    ownerBirthDate: string;
    deleted: 'false' | 'true';
    locked: 'false' | 'true';
    updated_at: string;
    created_at: string;
}

class MythicalDash {
    /**
     * Get all MythicalDash instances
     *
     * @returns Promise with all instances
     */
    public static async getInstances() {
        const response = await fetch('/api/admin/mythicaldash/instances', {
            method: 'GET',
        });
        return await response.json();
    }

    /**
     * Create a new MythicalDash instance
     *
     * @param user User UUID
     * @param project Project ID
     * @param companyName Company name
     * @param companyWebsite Company website
     * @param businessDescription Business description
     * @param hostingType Hosting type
     * @param currentUsers Current number of users
     * @param expectedUsers Expected number of users
     * @param instanceUrl Instance URL
     * @param serverType Server type
     * @param serverCount Number of servers
     * @param primaryEmail Primary email
     * @param abuseEmail Abuse email
     * @param supportEmail Support email
     * @param ownerFirstName Owner's first name
     * @param ownerLastName Owner's last name
     * @param ownerBirthDate Owner's birth date
     * @returns Promise with the created instance
     */
    public static async createInstance(
        user: string,
        project: number,
        companyName: string,
        companyWebsite: string,
        businessDescription: string,
        hostingType: 'free' | 'paid' | 'both',
        currentUsers: number,
        expectedUsers: number,
        instanceUrl: string,
        serverType: 'vps' | 'dedicated' | 'docker' | 'other',
        serverCount: number,
        primaryEmail: string,
        abuseEmail: string,
        supportEmail: string,
        ownerFirstName: string,
        ownerLastName: string,
        ownerBirthDate: string,
    ) {
        const formData = new FormData();
        formData.append('user', user);
        formData.append('project', project.toString());
        formData.append('companyName', companyName);
        formData.append('companyWebsite', companyWebsite);
        formData.append('businessDescription', businessDescription);
        formData.append('hostingType', hostingType);
        formData.append('currentUsers', currentUsers.toString());
        formData.append('expectedUsers', expectedUsers.toString());
        formData.append('instanceUrl', instanceUrl);
        formData.append('serverType', serverType);
        formData.append('serverCount', serverCount.toString());
        formData.append('primaryEmail', primaryEmail);
        formData.append('abuseEmail', abuseEmail);
        formData.append('supportEmail', supportEmail);
        formData.append('ownerFirstName', ownerFirstName);
        formData.append('ownerLastName', ownerLastName);
        formData.append('ownerBirthDate', ownerBirthDate);

        const response = await fetch('/api/admin/mythicaldash/instance/create', {
            method: 'POST',
            body: formData,
        });
        return await response.json();
    }

    /**
     * Update a MythicalDash instance
     *
     * @param instanceId The ID of the instance to update
     * @param data The data to update
     * @returns Promise with the updated instance
     */
    public static async updateInstance(instanceId: string, data: Partial<MythicalDashInstance>) {
        const formData = new FormData();
        Object.entries(data).forEach(([key, value]) => {
            if (value !== undefined) {
                formData.append(key, value.toString());
            }
        });

        const response = await fetch(`/api/admin/mythicaldash/instance/${instanceId}/update`, {
            method: 'POST',
            body: formData,
        });
        return await response.json();
    }

    /**
     * Delete a MythicalDash instance
     *
     * @param instanceId The ID of the instance to delete
     * @returns Promise with the response
     */
    public static async deleteInstance(instanceId: number) {
        const response = await fetch(`/api/admin/mythicaldash/instance/${instanceId}/delete`, {
            method: 'POST',
        });
        return await response.json();
    }

    /**
     * Get a MythicalDash instance by ID
     *
     * @param instanceId The ID of the instance to get
     * @returns Promise with the instance
     */
    public static async getInstance(instanceId: string) {
        const response = await fetch(`/api/admin/mythicaldash/instance/${instanceId}/info`, {
            method: 'GET',
        });
        return await response.json();
    }

    /**
     * Restore a deleted MythicalDash instance
     *
     * @param instanceId The ID of the instance to restore
     * @returns Promise with the response
     */
    public static async restoreInstance(instanceId: string) {
        const response = await fetch(`/api/admin/mythicaldash/instance/${instanceId}/restore`, {
            method: 'POST',
        });
        return await response.json();
    }

    /**
     * Lock a MythicalDash instance
     *
     * @param instanceId The ID of the instance to lock
     * @returns Promise with the response
     */
    public static async lockInstance(instanceId: string) {
        const response = await fetch(`/api/admin/mythicaldash/instance/${instanceId}/lock`, {
            method: 'POST',
        });
        return await response.json();
    }

    /**
     * Unlock a MythicalDash instance
     *
     * @param instanceId The ID of the instance to unlock
     * @returns Promise with the response
     */
    public static async unlockInstance(instanceId: string) {
        const response = await fetch(`/api/admin/mythicaldash/instance/${instanceId}/unlock`, {
            method: 'POST',
        });
        return await response.json();
    }

    /**
     * Get MythicalDash instances by user
     *
     * @param userUuid The UUID of the user
     * @returns Promise with the instances
     */
    public static async getInstancesByUser(userUuid: string) {
        const response = await fetch(`/api/admin/mythicaldash/instance/user/${userUuid}`, {
            method: 'GET',
        });
        return await response.json();
    }

    /**
     * Get MythicalDash instances by project
     *
     * @param projectId The ID of the project
     * @returns Promise with the instances
     */
    public static async getInstancesByProject(projectId: string) {
        const response = await fetch(`/api/admin/mythicaldash/instance/project/${projectId}`, {
            method: 'GET',
        });
        return await response.json();
    }

    /**
     * Get MythicalDash instances by license key
     *
     * @param licenseKeyId The ID of the license key
     * @returns Promise with the instances
     */
    public static async getInstancesByLicenseKey(licenseKeyId: string) {
        const response = await fetch(`/api/admin/mythicaldash/instance/license/${licenseKeyId}`, {
            method: 'GET',
        });
        return await response.json();
    }
}

export default MythicalDash;
