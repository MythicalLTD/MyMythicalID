class Projects {
    /**
     * Get all projects
     *
     * @returns Promise with all projects
     */
    public static async getProjects() {
        const response = await fetch('/api/admin/projects', {
            method: 'GET',
        });
        return await response.json();
    }

    /**
     * Create a new project
     *
     * @param name Project name
     * @param description Project description
     * @param type Project type
     * @param price Project price
     * @param features Project features
     * @param link Project link
     * @returns Promise with the created project
     */
    public static async createProject(
        name: string,
        description: string,
        type: string,
        price: number,
        features: string[],
        link: string,
    ) {
        const formData = new FormData();
        formData.append('name', name);
        formData.append('description', description);
        formData.append('type', type);
        formData.append('price', price.toString());
        formData.append('features', JSON.stringify(features));
        formData.append('link', link);
        const response = await fetch('/api/admin/project/create', {
            method: 'POST',
            body: formData,
        });
        return await response.json();
    }

    /**
     * Update a project
     *
     * @param projectId The ID of the project to update
     * @param name Project name
     * @param description Project description
     * @param type Project type
     * @param price Project price
     * @param features Project features
     * @param link Project link
     * @returns Promise with the updated project
     */
    public static async updateProject(
        projectId: string,
        name: string,
        description: string,
        type: string,
        price: number,
        features: string[],
        link: string,
    ) {
        const formData = new FormData();
        formData.append('name', name);
        formData.append('description', description);
        formData.append('type', type);
        formData.append('price', price.toString());
        formData.append('features', JSON.stringify(features));
        formData.append('link', link);

        const response = await fetch(`/api/admin/project/${projectId}/update`, {
            method: 'POST',
            body: formData,
        });
        return await response.json();
    }

    /**
     * Delete a project
     *
     * @param projectId The ID of the project to delete
     * @returns Promise with the response
     */
    public static async deleteProject(projectId: string) {
        const response = await fetch(`/api/admin/project/${projectId}/delete`, {
            method: 'POST',
        });
        return await response.json();
    }

    /**
     * Get a project by ID
     *
     * @param projectId The ID of the project to get
     * @returns Promise with the project
     */
    public static async getProject(projectId: string) {
        const response = await fetch(`/api/admin/project/${projectId}/info`, {
            method: 'GET',
        });
        return await response.json();
    }
}

export default Projects;
