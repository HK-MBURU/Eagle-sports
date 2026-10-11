// types/search.ts
export interface SearchResult {
  id: number;
  type: 'story' | 'athlete' | 'video' | 'race-event' | 'partner' | 'quote' | 'race-result' | 'women-running';
  title: string;
  description?: string;
  image?: string;
  url: string;
  date?: string;
  category?: string;
  relevance: number;
}

export interface SearchFilters {
  types: string[];
  category?: string;
  dateRange?: {
    from: string;
    to: string;
  };
}

export interface SearchResponse {
  results: SearchResult[];
  total: number;
  hasMore: boolean;
  suggestions?: string[];
}