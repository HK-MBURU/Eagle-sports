// services/searchService.ts
import {
    storiesAPI,
    athletesAPI,
    videosAPI,
    raceEventsAPI,
    partnersAPI,
    quotesAPI,
    raceResultsAPI
} from './api';
import { womenRunningApi } from './womenRunningApi';
import { SearchResult, SearchResponse, SearchFilters } from '@/types/search';

class SearchService {
    private async searchStories(query: string, filters?: SearchFilters): Promise<SearchResult[]> {
        try {
            // Use GET method instead of POST for stories search
            const response = await storiesAPI.getStories({
                search: query,
                per_page: 10,
                page: 1
            });

            // Handle different response formats
            const data = response.data || response;
            const stories = Array.isArray(data) ? data : data.data || [];

            return stories.map((story: any) => ({
                id: story.id,
                type: 'story',
                title: story.title,
                description: story.excerpt || story.content?.substring(0, 150),
                image: story.featured_image,
                url: `/stories/${story.slug || story.id}`,
                date: story.published_at,
                category: story.category,
                relevance: this.calculateRelevance(story.title, story.content, query)
            }));
        } catch (error) {
            console.error('Error searching stories:', error);
            return [];
        }
    }

    private async searchAthletes(query: string): Promise<SearchResult[]> {
        try {
            const response = await athletesAPI.getAllAthletes({
                search: query,
                per_page: 10
            });

            // Handle different response formats
            const data = response.data || response;
            const athletes = Array.isArray(data) ? data : data.data || [];

            return athletes.map((athlete: any) => ({
                id: athlete.id,
                type: 'athlete',
                title: athlete.name,
                description: athlete.bio?.substring(0, 150),
                image: athlete.profile_image,
                url: `/athletes/${athlete.id}`,
                relevance: this.calculateRelevance(athlete.name, athlete.bio, query)
            }));
        } catch (error) {
            console.error('Error searching athletes:', error);
            return [];
        }
    }

    private async searchVideos(query: string): Promise<SearchResult[]> {
        try {
            // Use GET method for videos search
            const response = await videosAPI.getVideos({
                search: query,
                per_page: 10
            });

            // Handle different response formats
            const data = response.data || response;
            const videos = Array.isArray(data) ? data : data.data || [];

            return videos.map((video: any) => ({
                id: video.id,
                type: 'video',
                title: video.title,
                description: video.description?.substring(0, 150),
                image: video.thumbnail,
                url: `/videos/${video.id}`,
                date: video.created_at,
                category: video.category,
                relevance: this.calculateRelevance(video.title, video.description, query)
            }));
        } catch (error) {
            console.error('Error searching videos:', error);
            return [];
        }
    }

    private async searchRaceEvents(query: string): Promise<SearchResult[]> {
        try {
            const response = await raceEventsAPI.getEvents({
                search: query,
                per_page: 10
            });

            // Handle different response formats
            const data = response.data || response;
            const events = Array.isArray(data) ? data : data.data || [];

            return events.map((event: any) => ({
                id: event.id,
                type: 'race-event',
                title: event.title,
                description: event.description?.substring(0, 150),
                image: event.image,
                url: `/events/${event.id}`,
                date: event.event_date,
                category: event.location,
                relevance: this.calculateRelevance(event.title, event.description, query)
            }));
        } catch (error) {
            console.error('Error searching race events:', error);
            return [];
        }
    }

    private async searchWomenRunning(query: string): Promise<SearchResult[]> {
        try {
            const response = await womenRunningApi.getAll({
                search: query,
                per_page: 10
            });

            // Handle different response formats more specifically
            let entries: any[] = [];

            if (Array.isArray(response)) {
                entries = response;
            } else if (response && typeof response === 'object') {
                // Handle ApiResponse<PaginatedResponse<WomenRunningEntry>> format
                if ('data' in response) {
                    const responseData = response.data;
                    if (Array.isArray(responseData)) {
                        entries = responseData;
                    } else if (responseData && 'data' in responseData) {
                        // Handle PaginatedResponse format with nested data property
                        entries = Array.isArray(responseData.data) ? responseData.data : [];
                    }
                }
            }

            return entries.map((entry: any) => ({
                id: entry.id,
                type: 'women-running',
                title: entry.title,
                description: entry.content?.substring(0, 150),
                image: entry.featured_image,
                url: `/women-running/${entry.id}`,
                date: entry.created_at,
                category: entry.category,
                relevance: this.calculateRelevance(entry.title, entry.content, query)
            }));
        } catch (error) {
            console.error('Error searching women running:', error);
            return [];
        }
    }

    private async searchRaceResults(query: string): Promise<SearchResult[]> {
        try {
            const response = await raceResultsAPI.getResults({
                search: query,
                per_page: 10
            });

            // Handle different response formats
            const data = response.data || response;
            const results = Array.isArray(data) ? data : data.data || [];

            return results.map((result: any) => ({
                id: result.id,
                type: 'race-result',
                title: result.title,
                description: result.description?.substring(0, 150),
                url: `/results/${result.id}`,
                date: result.race_date,
                category: result.category,
                relevance: this.calculateRelevance(result.title, result.description, query)
            }));
        } catch (error) {
            console.error('Error searching race results:', error);
            return [];
        }
    }

    private calculateRelevance(title: string = '', content: string = '', query: string): number {
        const queryTerms = query.toLowerCase().split(' ');
        const titleText = title?.toLowerCase() || '';
        const contentText = content?.toLowerCase() || '';

        let score = 0;

        queryTerms.forEach(term => {
            if (titleText.includes(term)) score += 3;
            if (contentText.includes(term)) score += 1;
        });

        return Math.min(score, 10);
    }

    async search(
        query: string,
        filters?: SearchFilters,
        limit: number = 20
    ): Promise<SearchResponse> {
        if (!query.trim()) {
            return { results: [], total: 0, hasMore: false };
        }

        try {
            const searchPromises = [];

            // Determine which types to search based on filters
            const typesToSearch = filters?.types || [
                'story', 'athlete', 'video', 'race-event', 'women-running', 'race-result'
            ];

            if (typesToSearch.includes('story')) {
                searchPromises.push(this.searchStories(query, filters));
            }
            if (typesToSearch.includes('athlete')) {
                searchPromises.push(this.searchAthletes(query));
            }
            if (typesToSearch.includes('video')) {
                searchPromises.push(this.searchVideos(query));
            }
            if (typesToSearch.includes('race-event')) {
                searchPromises.push(this.searchRaceEvents(query));
            }
            if (typesToSearch.includes('women-running')) {
                searchPromises.push(this.searchWomenRunning(query));
            }
            if (typesToSearch.includes('race-result')) {
                searchPromises.push(this.searchRaceResults(query));
            }

            const results = await Promise.allSettled(searchPromises);

            // Extract successful results
            const allResults = results
                .filter((result): result is PromiseFulfilledResult<SearchResult[]> =>
                    result.status === 'fulfilled'
                )
                .flatMap(result => result.value);

            // Sort by relevance and limit results
            const sortedResults = allResults
                .sort((a, b) => b.relevance - a.relevance)
                .slice(0, limit);

            return {
                results: sortedResults,
                total: sortedResults.length,
                hasMore: allResults.length > limit,
                suggestions: this.generateSuggestions(query, sortedResults)
            };
        } catch (error) {
            console.error('Search error:', error);
            throw new Error('Search failed. Please try again.');
        }
    }

    private generateSuggestions(query: string, results: SearchResult[]): string[] {
        const suggestions = new Set<string>();

        // Add related terms from results
        results.forEach(result => {
            if (result.title.toLowerCase().includes(query.toLowerCase())) {
                const words = result.title.split(' ');
                words.forEach(word => {
                    if (word.length > 3 && !word.toLowerCase().includes(query.toLowerCase())) {
                        suggestions.add(word);
                    }
                });
            }
        });

        return Array.from(suggestions).slice(0, 5);
    }

    // Debounced search for real-time suggestions
    private searchTimeout: NodeJS.Timeout | null = null;

    async debouncedSearch(
        query: string,
        filters?: SearchFilters,
        delay: number = 300
    ): Promise<SearchResponse> {
        return new Promise((resolve) => {
            if (this.searchTimeout) {
                clearTimeout(this.searchTimeout);
            }

            this.searchTimeout = setTimeout(async () => {
                try {
                    const result = await this.search(query, filters, 10); // Fewer results for suggestions
                    resolve(result);
                } catch (error) {
                    console.error('Debounced search error:', error);
                    resolve({ results: [], total: 0, hasMore: false });
                }
            }, delay);
        });
    }
}

export const searchService = new SearchService();