import { useState, useEffect } from 'react';
import { womenRunningApi } from '@/services/womenRunningApi';
import { WomenRunningEntry } from '@/types/womenRunning';

export const useWomenRunning = () => {
    const [entries, setEntries] = useState<WomenRunningEntry[]>([]);
    const [featuredEntry, setFeaturedEntry] = useState<WomenRunningEntry | null>(null);
    const [loading, setLoading] = useState(true);
    const [error, setError] = useState<string | null>(null);

    useEffect(() => {
        fetchData();
    }, []);

    const fetchData = async () => {
        try {
            setLoading(true);
            setError(null);

            // Fetch entries with enough buffer to account for featured entry
            const entriesResponse = await womenRunningApi.getAll({
                per_page: 10,
                sort_by: 'order',
                sort_order: 'asc',
            });

            if (entriesResponse.success) {
                const allEntries = entriesResponse.data.data;

                // Find and set the first featured entry
                const featured = allEntries.find((entry) => entry.featured === true);
                setFeaturedEntry(featured || null);

                // Filter out the featured entry and get the first 3 regular entries
                const regularEntries = allEntries
                    .filter((entry) => entry.featured !== true)
                    .slice(0, 3);

                setEntries(regularEntries);
            }
        } catch (err: any) {
            console.error('Error fetching women running data:', err);
            setError(err.message || 'Failed to fetch data');
        } finally {
            setLoading(false);
        }
    };

    return {
        entries,
        featuredEntry,
        loading,
        error,
        refetch: fetchData,
    };
};