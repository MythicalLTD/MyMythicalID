import { computed, type Ref } from 'vue';

export function useSearchResults(searchQuery: Ref<string>) {
    const adminBaseUri = '/mc-admin';

    const searchResults = [
        { id: 1, name: 'Dashboard', path: `${adminBaseUri}` },
        { id: 6, name: 'Tickets', path: `${adminBaseUri}/tickets` },
        { id: 7, name: 'Users', path: `${adminBaseUri}/users` },
        { id: 8, name: 'Departments', path: `${adminBaseUri}/departments` },
        { id: 9, name: 'Create Department', path: `${adminBaseUri}/departments/create` },
    ];

    const filteredResults = computed(() => {
        if (!searchQuery.value) return [];
        const query = searchQuery.value.toLowerCase();
        return searchResults.filter((result) => result.name.toLowerCase().includes(query));
    });

    return {
        searchResults,
        filteredResults,
    };
}
