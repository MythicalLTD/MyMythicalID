class Licenses {
    static async getLicenses() {
        const response = await fetch('/api/user/licenses', {
            method: 'GET',
        });
        const data = await response.json();
        return data;
    }
}

export default Licenses;
