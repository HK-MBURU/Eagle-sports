// src/services/api.ts

const API_BASE_URL = import.meta.env.VITE_API_URL || 'https://eaglesports.co.ke/api';

// Simple in-memory cache
const cache = new Map();
const CACHE_DURATION = 5 * 60 * 1000; // 5 minutes

// Get auth token from localStorage
const getAuthToken = () => {
  return localStorage.getItem('auth_token');
};

// Add auth header to requests
const getAuthHeaders = () => {
  const token = getAuthToken();
  return {
    'Content-Type': 'application/json',
    'Accept': 'application/json',
    ...(token && { 'Authorization': `Bearer ${token}` }),
  };
};

// Enhanced fetch with timeout and caching
const enhancedFetch = async (url: string, options: RequestInit = {}, useCache = true) => {
  const cacheKey = `${url}-${JSON.stringify(options)}`;

  // Check cache first
  if (useCache) {
    const cached = cache.get(cacheKey);
    if (cached && Date.now() - cached.timestamp < CACHE_DURATION) {
      return cached.data;
    }
  }

  const controller = new AbortController();
  const timeoutId = setTimeout(() => controller.abort(), 15000); // 15 second timeout

  try {
    const response = await fetch(url, {
      ...options,
      signal: controller.signal,
    });

    clearTimeout(timeoutId);

    if (!response.ok) {
      throw new Error(`HTTP ${response.status}: ${response.statusText}`);
    }

    const data = await response.json();

    // Cache successful responses
    if (useCache) {
      cache.set(cacheKey, {
        data,
        timestamp: Date.now()
      });
    }

    return data;
  } catch (error) {
    clearTimeout(timeoutId);
    throw error;
  }
};

// Cache management
export const cacheManager = {
  clear: () => cache.clear(),
  remove: (key: string) => cache.delete(key),
  getSize: () => cache.size,
};

// Helper function to safely append values to FormData
const appendToFormData = (formData: FormData, key: string, value: any): void => {
  if (value === undefined || value === null) {
    return;
  }

  if (value instanceof File) {
    formData.append(key, value);
  } else if (typeof value === 'boolean') {
    formData.append(key, value ? '1' : '0');
  } else {
    formData.append(key, String(value));
  }
};

export const storiesAPI = {

  // Get share data for a story
  getShareData: async (slug: string) => {
    const url = `${API_BASE_URL}/stories/${slug}/share-data`;
    return enhancedFetch(url, {
      headers: getAuthHeaders(),
    }, true); // Cache share data
  },

  // Get SEO meta data for a story
  getSEOData: async (slug: string) => {
    const url = `${API_BASE_URL}/stories/${slug}/seo`;
    return enhancedFetch(url, {
      headers: getAuthHeaders(),
    }, true);
  },

  // Get optimized homepage stories
  getHomepageStories: async () => {
    const url = `${API_BASE_URL}/stories/homepage-featured`;
    return enhancedFetch(url, {
      headers: getAuthHeaders(),
    }, true); // Cache aggressively since homepage doesn't change often
  },

  // Get all stories with filters
  getStories: async (params?: {
    category?: string;
    featured?: boolean;
    search?: string;
    per_page?: number;
    page?: number;
  }) => {
    const queryParams = new URLSearchParams();

    if (params?.category && params.category !== 'All') {
      queryParams.append('category', params.category);
    }
    if (params?.featured) {
      queryParams.append('featured', '1');
    }
    if (params?.search) {
      queryParams.append('search', params.search);
    }
    if (params?.per_page) {
      queryParams.append('per_page', params.per_page.toString());
    }
    if (params?.page) {
      queryParams.append('page', params.page.toString());
    }

    const url = `${API_BASE_URL}/stories${queryParams.toString() ? `?${queryParams}` : ''}`;
    return enhancedFetch(url, {
      headers: getAuthHeaders(),
    });
  },

  // Get featured stories - cache aggressively
  getFeaturedStories: async () => {
    const url = `${API_BASE_URL}/stories/featured`;
    return enhancedFetch(url, {
      headers: getAuthHeaders(),
    }, true); // Use cache
  },

  // Get related stories by story ID
  getRelatedStories: async (storyId: number) => {
    const url = `${API_BASE_URL}/stories/${storyId}/related`;
    return enhancedFetch(url, {
      headers: getAuthHeaders(),
    }, true);
  },

  // Toggle like on a story
  toggleLike: async (storyId: number) => {
    const url = `${API_BASE_URL}/stories/${storyId}/like`;
    return enhancedFetch(url, {
      method: 'POST',
      headers: getAuthHeaders(),
    }, false); // Don't cache likes
  },


  // Get latest stories
  getLatestStories: async () => {
    const url = `${API_BASE_URL}/stories/latest`;
    return enhancedFetch(url, {
      headers: getAuthHeaders(),
    }, true);
  },

  // Get single story - cache by slug/id
  getStory: async (identifier: string | number) => {
    const url = `${API_BASE_URL}/stories/${identifier}`;
    return enhancedFetch(url, {
      headers: getAuthHeaders(),
    }, true);
  },

  // Get categories with counts - cache for longer
  getCategories: async () => {
    const url = `${API_BASE_URL}/stories/categories`;
    return enhancedFetch(url, {
      headers: getAuthHeaders(),
    }, true);
  },

  // Clear cache for specific endpoints
  clearCache: (endpoint?: string) => {
    if (endpoint) {
      // Clear specific endpoint cache
      for (const key of cache.keys()) {
        if (key.includes(endpoint)) {
          cache.delete(key);
        }
      }
    } else {
      cacheManager.clear();
    }
  },
};


export const raceEventsAPI = {
  // Get all race events with filters
  getEvents: async (params?: {
    per_page?: number;
    page?: number;
    featured?: boolean;
    active?: boolean;
    upcoming?: boolean;
    search?: string;
  }) => {
    const queryParams = new URLSearchParams();

    if (params?.per_page) {
      queryParams.append('per_page', params.per_page.toString());
    }
    if (params?.page) {
      queryParams.append('page', params.page.toString());
    }
    if (params?.featured !== undefined) {
      queryParams.append('featured', params.featured.toString());
    }
    if (params?.active !== undefined) {
      queryParams.append('active', params.active.toString());
    }
    if (params?.upcoming !== undefined) {
      queryParams.append('upcoming', params.upcoming.toString());
    }
    if (params?.search) {
      queryParams.append('search', params.search);
    }

    const url = `${API_BASE_URL}/race-events${queryParams.toString() ? `?${queryParams}` : ''}`;
    const response = await fetch(url, {
      headers: getAuthHeaders(),
    });

    if (!response.ok) {
      throw new Error('Failed to fetch race events');
    }

    return response.json();
  },

  // Get the flagship/coverage event (e.g. Dakar 2026)
  getFlagshipEvent: async () => {
    const response = await fetch(`${API_BASE_URL}/race-events/flagship/current`, {
      headers: getAuthHeaders(),
    });
    if (!response.ok) throw new Error('No flagship event found');
    return response.json();
  },

  // Get featured events
  getFeaturedEvents: async () => {
    const response = await fetch(`${API_BASE_URL}/race-events/featured/all`, {
      headers: getAuthHeaders(),
    });

    if (!response.ok) {
      throw new Error('Failed to fetch featured events');
    }

    return response.json();
  },

  // Get upcoming events
  getUpcomingEvents: async (limit?: number) => {
    const queryParams = limit ? `?limit=${limit}` : '';
    const response = await fetch(`${API_BASE_URL}/race-events/upcoming/list${queryParams}`, {
      headers: getAuthHeaders(),
    });

    if (!response.ok) {
      throw new Error('Failed to fetch upcoming events');
    }

    return response.json();
  },

  // Get events by date range
  getEventsByDateRange: async (startDate: string, endDate: string) => {
    const queryParams = new URLSearchParams({
      start_date: startDate,
      end_date: endDate,
    });

    const response = await fetch(`${API_BASE_URL}/race-events/date-range/filter?${queryParams}`, {
      headers: getAuthHeaders(),
    });

    if (!response.ok) {
      throw new Error('Failed to fetch events by date range');
    }

    return response.json();
  },

  // Get single event
  getEvent: async (id: number) => {
    const response = await fetch(`${API_BASE_URL}/race-events/${id}`, {
      headers: getAuthHeaders(),
    });

    if (!response.ok) {
      throw new Error('Failed to fetch race event');
    }

    return response.json();
  },

  // Create event (requires authentication)
  createEvent: async (eventData: {
    title: string;
    event_date: string;
    location: string;
    start_time: string;
    expected_runners?: string;
    distance_options: string;
    image?: File;
    description?: string;
    registration_link?: string;
    is_featured?: boolean;
    is_active?: boolean;
  }) => {
    const formData = new FormData();

    // Append all fields to FormData using the helper function
    Object.entries(eventData).forEach(([key, value]) => {
      appendToFormData(formData, key, value);
    });

    const response = await fetch(`${API_BASE_URL}/race-events`, {
      method: 'POST',
      headers: {
        'Accept': 'application/json',
        'Authorization': getAuthHeaders().Authorization || '',
      },
      body: formData,
    });

    if (!response.ok) {
      const errorData = await response.json().catch(() => ({}));
      throw new Error(errorData.message || 'Failed to create race event');
    }

    return response.json();
  },

  // Update event (requires authentication)
  updateEvent: async (id: number, eventData: {
    title?: string;
    event_date?: string;
    location?: string;
    start_time?: string;
    expected_runners?: string;
    distance_options?: string;
    image?: File;
    description?: string;
    registration_link?: string;
    is_featured?: boolean;
    is_active?: boolean;
  }) => {
    const formData = new FormData();

    // Append all fields to FormData using the helper function
    Object.entries(eventData).forEach(([key, value]) => {
      appendToFormData(formData, key, value);
    });

    formData.append('_method', 'PUT');

    const response = await fetch(`${API_BASE_URL}/race-events/${id}`, {
      method: 'POST',
      headers: {
        'Accept': 'application/json',
        'Authorization': getAuthHeaders().Authorization || '',
      },
      body: formData,
    });

    if (!response.ok) {
      throw new Error('Failed to update race event');
    }

    return response.json();
  },

  // Delete event (requires authentication)
  deleteEvent: async (id: number) => {
    const response = await fetch(`${API_BASE_URL}/race-events/${id}`, {
      method: 'DELETE',
      headers: getAuthHeaders(),
    });

    if (!response.ok) {
      throw new Error('Failed to delete race event');
    }

    return response.json();
  },
};


export const videosAPI = {
  // Get all videos with filters
  getVideos: async (params?: {
    per_page?: number;
    page?: number;
    featured?: boolean;
    category?: string;
    search?: string;
  }) => {
    const queryParams = new URLSearchParams();

    if (params?.per_page) {
      queryParams.append('per_page', params.per_page.toString());
    }
    if (params?.page) {
      queryParams.append('page', params.page.toString());
    }
    if (params?.featured !== undefined) {
      queryParams.append('featured', params.featured.toString());
    }
    if (params?.category) {
      queryParams.append('category', params.category);
    }
    if (params?.search) {
      queryParams.append('search', params.search);
    }

    const url = `${API_BASE_URL}/videos${queryParams.toString() ? `?${queryParams}` : ''}`;
    const response = await fetch(url, {
      headers: getAuthHeaders(),
    });

    if (!response.ok) {
      throw new Error('Failed to fetch videos');
    }

    return response.json();
  },

  // Get featured videos
  getFeaturedVideos: async (limit?: number) => {
    const queryParams = limit ? `?limit=${limit}` : '';
    const response = await fetch(`${API_BASE_URL}/videos/featured/all${queryParams}`, {
      headers: getAuthHeaders(),
    });

    if (!response.ok) {
      throw new Error('Failed to fetch featured videos');
    }

    return response.json();
  },

  // Get videos by category
  getVideosByCategory: async (category: string) => {
    const response = await fetch(`${API_BASE_URL}/videos/category/${encodeURIComponent(category)}`, {
      headers: getAuthHeaders(),
    });

    if (!response.ok) {
      throw new Error('Failed to fetch videos by category');
    }

    return response.json();
  },

  // Get video categories
  getCategories: async () => {
    const response = await fetch(`${API_BASE_URL}/videos/categories/list`, {
      headers: getAuthHeaders(),
    });

    if (!response.ok) {
      throw new Error('Failed to fetch video categories');
    }

    return response.json();
  },

  // Get single video
  getVideo: async (id: number) => {
    const response = await fetch(`${API_BASE_URL}/videos/${id}`, {
      headers: getAuthHeaders(),
    });

    if (!response.ok) {
      throw new Error('Failed to fetch video');
    }

    return response.json();
  },

  // Search videos
  searchVideos: async (searchTerm: string, params?: {
    per_page?: number;
    page?: number;
  }) => {
    const queryParams = new URLSearchParams({ search: searchTerm });

    if (params?.per_page) {
      queryParams.append('per_page', params.per_page.toString());
    }
    if (params?.page) {
      queryParams.append('page', params.page.toString());
    }

    const response = await fetch(`${API_BASE_URL}/videos?${queryParams}`, {
      headers: getAuthHeaders(),
    });

    if (!response.ok) {
      throw new Error('Failed to search videos');
    }

    return response.json();
  },

  // Create video (requires authentication)
  createVideo: async (videoData: {
    youtube_url: string;
    title: string;
    description?: string;
    category: string;
    is_featured?: boolean;
  }) => {
    const response = await fetch(`${API_BASE_URL}/videos`, {
      method: 'POST',
      headers: getAuthHeaders(),
      body: JSON.stringify(videoData),
    });

    if (!response.ok) {
      throw new Error('Failed to create video');
    }

    return response.json();
  },

  // Update video (requires authentication)
  updateVideo: async (id: number, videoData: {
    youtube_url?: string;
    title?: string;
    description?: string;
    category?: string;
    is_featured?: boolean;
  }) => {
    const response = await fetch(`${API_BASE_URL}/videos/${id}`, {
      method: 'PUT',
      headers: getAuthHeaders(),
      body: JSON.stringify(videoData),
    });

    if (!response.ok) {
      throw new Error('Failed to update video');
    }

    return response.json();
  },

  // Delete video (requires authentication)
  deleteVideo: async (id: number) => {
    const response = await fetch(`${API_BASE_URL}/videos/${id}`, {
      method: 'DELETE',
      headers: getAuthHeaders(),
    });

    if (!response.ok) {
      throw new Error('Failed to delete video');
    }

    return response.json();
  },

  // Toggle featured status (requires authentication)
  toggleFeatured: async (id: number) => {
    const response = await fetch(`${API_BASE_URL}/videos/${id}/toggle-featured`, {
      method: 'POST',
      headers: getAuthHeaders(),
    });

    if (!response.ok) {
      throw new Error('Failed to toggle featured status');
    }

    return response.json();
  },
};


export const partnersAPI = {
  // Get all partners with filters
  getPartners: async (params?: {
    per_page?: number;
    page?: number;
    featured?: boolean;
    search?: string;
  }) => {
    const queryParams = new URLSearchParams();

    if (params?.per_page) {
      queryParams.append('per_page', params.per_page.toString());
    }
    if (params?.page) {
      queryParams.append('page', params.page.toString());
    }
    if (params?.featured !== undefined) {
      queryParams.append('featured', params.featured.toString());
    }
    if (params?.search) {
      queryParams.append('search', params.search);
    }

    const url = `${API_BASE_URL}/partners${queryParams.toString() ? `?${queryParams}` : ''}`;
    const response = await fetch(url, {
      headers: getAuthHeaders(),
    });

    if (!response.ok) {
      throw new Error('Failed to fetch partners');
    }

    return response.json();
  },

  // Get featured partners
  getFeaturedPartners: async (limit?: number) => {
    const queryParams = limit ? `?limit=${limit}` : '';
    const response = await fetch(`${API_BASE_URL}/partners/featured/all${queryParams}`, {
      headers: getAuthHeaders(),
    });

    if (!response.ok) {
      throw new Error('Failed to fetch featured partners');
    }

    return response.json();
  },

  // Get partners in alphabetical order
  getAlphabeticalPartners: async () => {
    const response = await fetch(`${API_BASE_URL}/partners/alphabetical/list`, {
      headers: getAuthHeaders(),
    });

    if (!response.ok) {
      throw new Error('Failed to fetch partners');
    }

    return response.json();
  },

  // Get single partner
  getPartner: async (id: number) => {
    const response = await fetch(`${API_BASE_URL}/partners/${id}`, {
      headers: getAuthHeaders(),
    });

    if (!response.ok) {
      throw new Error('Failed to fetch partner');
    }

    return response.json();
  },

  // Search partners
  searchPartners: async (searchTerm: string, params?: {
    per_page?: number;
    page?: number;
  }) => {
    const queryParams = new URLSearchParams({ search: searchTerm });

    if (params?.per_page) {
      queryParams.append('per_page', params.per_page.toString());
    }
    if (params?.page) {
      queryParams.append('page', params.page.toString());
    }

    const response = await fetch(`${API_BASE_URL}/partners/search/query?${queryParams}`, {
      method: 'POST',
      headers: getAuthHeaders(),
    });

    if (!response.ok) {
      throw new Error('Failed to search partners');
    }

    return response.json();
  },

  // Create partner (requires authentication)
  createPartner: async (partnerData: {
    name: string;
    logo?: File;
    is_featured?: boolean;
  }) => {
    const formData = new FormData();

    // Append all fields to FormData using the helper function
    Object.entries(partnerData).forEach(([key, value]) => {
      appendToFormData(formData, key, value);
    });

    const response = await fetch(`${API_BASE_URL}/partners`, {
      method: 'POST',
      headers: {
        'Accept': 'application/json',
        'Authorization': getAuthHeaders().Authorization || '',
      },
      body: formData,
    });

    if (!response.ok) {
      throw new Error('Failed to create partner');
    }

    return response.json();
  },

  // Update partner (requires authentication)
  updatePartner: async (id: number, partnerData: {
    name?: string;
    logo?: File;
    is_featured?: boolean;
  }) => {
    const formData = new FormData();

    // Append all fields to FormData using the helper function
    Object.entries(partnerData).forEach(([key, value]) => {
      appendToFormData(formData, key, value);
    });

    formData.append('_method', 'PUT');

    const response = await fetch(`${API_BASE_URL}/partners/${id}`, {
      method: 'POST',
      headers: {
        'Accept': 'application/json',
        'Authorization': getAuthHeaders().Authorization || '',
      },
      body: formData,
    });

    if (!response.ok) {
      throw new Error('Failed to update partner');
    }

    return response.json();
  },

  // Delete partner (requires authentication)
  deletePartner: async (id: number) => {
    const response = await fetch(`${API_BASE_URL}/partners/${id}`, {
      method: 'DELETE',
      headers: getAuthHeaders(),
    });

    if (!response.ok) {
      throw new Error('Failed to delete partner');
    }

    return response.json();
  },

  // Toggle featured status (requires authentication)
  toggleFeatured: async (id: number) => {
    const response = await fetch(`${API_BASE_URL}/partners/${id}/toggle-featured`, {
      method: 'POST',
      headers: getAuthHeaders(),
    });

    if (!response.ok) {
      throw new Error('Failed to toggle featured status');
    }

    return response.json();
  },
};



// Add this to your existing api.ts file

export const quotesAPI = {
  // Get all quotes with pagination
  getQuotes: async (params?: {
    per_page?: number;
    page?: number;
  }) => {
    const queryParams = new URLSearchParams();

    if (params?.per_page) {
      queryParams.append('per_page', params.per_page.toString());
    }
    if (params?.page) {
      queryParams.append('page', params.page.toString());
    }

    const url = `${API_BASE_URL}/quotes${queryParams.toString() ? `?${queryParams}` : ''}`;
    const response = await fetch(url, {
      headers: getAuthHeaders(),
    });

    if (!response.ok) {
      throw new Error('Failed to fetch quotes');
    }

    return response.json();
  },

  // Get a random quote
  getRandomQuote: async () => {
    const response = await fetch(`${API_BASE_URL}/quotes/random/one`, {
      headers: getAuthHeaders(),
    });

    if (!response.ok) {
      throw new Error('Failed to fetch random quote');
    }

    return response.json();
  },

  // Get specific quote
  getQuote: async (id: number) => {
    const response = await fetch(`${API_BASE_URL}/quotes/${id}`, {
      headers: getAuthHeaders(),
    });

    if (!response.ok) {
      throw new Error('Failed to fetch quote');
    }

    return response.json();
  },

  // Create new quote (requires authentication)
  createQuote: async (quoteData: {
    content: string;
    owner: string;
    owner_title?: string;
  }) => {
    const response = await fetch(`${API_BASE_URL}/quotes`, {
      method: 'POST',
      headers: getAuthHeaders(),
      body: JSON.stringify(quoteData),
    });

    if (!response.ok) {
      throw new Error('Failed to create quote');
    }

    return response.json();
  },

  // Update quote (requires authentication)
  updateQuote: async (id: number, quoteData: {
    content?: string;
    owner?: string;
    owner_title?: string;
  }) => {
    const response = await fetch(`${API_BASE_URL}/quotes/${id}`, {
      method: 'PUT',
      headers: getAuthHeaders(),
      body: JSON.stringify(quoteData),
    });

    if (!response.ok) {
      throw new Error('Failed to update quote');
    }

    return response.json();
  },

  // Delete quote (requires authentication)
  deleteQuote: async (id: number) => {
    const response = await fetch(`${API_BASE_URL}/quotes/${id}`, {
      method: 'DELETE',
      headers: getAuthHeaders(),
    });

    if (!response.ok) {
      throw new Error('Failed to delete quote');
    }

    return response.json();
  },
};
export const athletesAPI = {
  // Get all athletes with pagination
  getAllAthletes: async (params?: {
    page?: number;
    per_page?: number;
    search?: string;
  }) => {
    const queryParams = new URLSearchParams();

    if (params?.page) {
      queryParams.append('page', params.page.toString());
    }
    if (params?.per_page) {
      queryParams.append('per_page', params.per_page.toString());
    }
    if (params?.search) {
      queryParams.append('search', params.search);
    }

    const url = `${API_BASE_URL}/athletes${queryParams.toString() ? `?${queryParams}` : ''}`;
    const response = await fetch(url);

    if (!response.ok) {
      throw new Error('Failed to fetch athletes');
    }

    return response.json();
  },

  // Get featured athletes
  getFeaturedAthletes: async () => {
    const response = await fetch(`${API_BASE_URL}/athletes/featured`);

    if (!response.ok) {
      throw new Error('Failed to fetch featured athletes');
    }

    return response.json();
  },

  // Get athlete statistics
  getAthleteStats: async () => {
    const response = await fetch(`${API_BASE_URL}/athletes/stats`);

    if (!response.ok) {
      throw new Error('Failed to fetch athlete stats');
    }

    return response.json();
  },

  // Get single athlete
  getAthlete: async (id: number) => {
    const response = await fetch(`${API_BASE_URL}/athletes/${id}`);

    if (!response.ok) {
      throw new Error('Failed to fetch athlete');
    }

    return response.json();
  },
};

// ===== ADD THIS NEW RACE RESULTS API =====
export const raceResultsAPI = {
  // Get all race results with filters
  getResults: async (params?: {
    category?: string;
    featured?: boolean;
    search?: string;
    date_from?: string;
    date_to?: string;
    per_page?: number;
    page?: number;
    sort_by?: string;
    sort_order?: 'asc' | 'desc';
  }) => {
    const queryParams = new URLSearchParams();

    if (params?.category) {
      queryParams.append('category', params.category);
    }
    if (params?.featured) {
      queryParams.append('featured', '1');
    }
    if (params?.search) {
      queryParams.append('search', params.search);
    }
    if (params?.date_from) {
      queryParams.append('date_from', params.date_from);
    }
    if (params?.date_to) {
      queryParams.append('date_to', params.date_to);
    }
    if (params?.per_page) {
      queryParams.append('per_page', params.per_page.toString());
    }
    if (params?.page) {
      queryParams.append('page', params.page.toString());
    }
    if (params?.sort_by) {
      queryParams.append('sort_by', params.sort_by);
    }
    if (params?.sort_order) {
      queryParams.append('sort_order', params.sort_order);
    }

    const url = `${API_BASE_URL}/race-results${queryParams.toString() ? `?${queryParams}` : ''}`;
    const response = await fetch(url, {
      headers: getAuthHeaders(),
    });

    if (!response.ok) {
      throw new Error('Failed to fetch race results');
    }

    return response.json();
  },

  // Get featured race results
  getFeaturedResults: async () => {
    const response = await fetch(`${API_BASE_URL}/race-results/featured`, {
      headers: getAuthHeaders(),
    });

    if (!response.ok) {
      throw new Error('Failed to fetch featured results');
    }

    return response.json();
  },

  // Get latest race results
  getLatestResults: async (limit?: number) => {
    const queryParams = limit ? `?limit=${limit}` : '';
    const response = await fetch(`${API_BASE_URL}/race-results/latest${queryParams}`, {
      headers: getAuthHeaders(),
    });

    if (!response.ok) {
      throw new Error('Failed to fetch latest results');
    }

    return response.json();
  },

  // Get single race result
  getResult: async (id: number) => {
    const response = await fetch(`${API_BASE_URL}/race-results/${id}`, {
      headers: getAuthHeaders(),
    });

    if (!response.ok) {
      throw new Error('Failed to fetch race result');
    }

    return response.json();
  },

  // Get results by category
  getResultsByCategory: async (category: string, page?: number) => {
    const queryParams = page ? `?page=${page}` : '';
    const response = await fetch(
      `${API_BASE_URL}/race-results/category/${encodeURIComponent(category)}${queryParams}`,
      {
        headers: getAuthHeaders(),
      }
    );

    if (!response.ok) {
      throw new Error('Failed to fetch results by category');
    }

    return response.json();
  },

  // Get available categories
  getCategories: async () => {
    const response = await fetch(`${API_BASE_URL}/race-results/categories`, {
      headers: getAuthHeaders(),
    });

    if (!response.ok) {
      throw new Error('Failed to fetch categories');
    }

    return response.json();
  },

  // Get statistics
  getStats: async () => {
    const response = await fetch(`${API_BASE_URL}/race-results/stats`, {
      headers: getAuthHeaders(),
    });

    if (!response.ok) {
      throw new Error('Failed to fetch statistics');
    }

    return response.json();
  },

  // Get download link
  getDownloadLink: async (id: number) => {
    const response = await fetch(`${API_BASE_URL}/race-results/${id}/download`, {
      headers: getAuthHeaders(),
    });

    if (!response.ok) {
      throw new Error('Failed to get download link');
    }

    return response.json();
  },
};