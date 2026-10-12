import axios from 'axios';
import { WomenRunningEntry, ApiResponse, PaginatedResponse } from '@/types/womenRunning';

const API_BASE_URL = import.meta.env.VITE_API_URL || 'https://eaglesports.co.ke/api';

const api = axios.create({
  baseURL: API_BASE_URL,
  headers: {
    'Content-Type': 'application/json',
    'Accept': 'application/json',
  },
});

// Add request interceptor for authentication if needed
api.interceptors.request.use(
  (config) => {
    const token = localStorage.getItem('auth_token');
    if (token) {
      config.headers.Authorization = `Bearer ${token}`;
    }
    return config;
  },
  (error) => {
    return Promise.reject(error);
  }
);

// Add response interceptor for error handling
api.interceptors.response.use(
  (response) => response,
  (error) => {
    if (error.response?.status === 401) {
      // Handle unauthorized access
      localStorage.removeItem('auth_token');
      // Optionally redirect to login
    }
    return Promise.reject(error);
  }
);

export const womenRunningApi = {
  // Get all entries with filters
  getAll: async (params?: {
    category?: string;
    status?: boolean;
    featured?: boolean;
    search?: string;
    sort_by?: string;
    sort_order?: 'asc' | 'desc';
    per_page?: number;
    page?: number;
  }) => {
    const response = await api.get<ApiResponse<PaginatedResponse<WomenRunningEntry>>>(
      '/women-running',
      { params }
    );
    return response.data;
  },

  // Get single entry
  getById: async (id: number) => {
    const response = await api.get<ApiResponse<WomenRunningEntry>>(
      `/women-running/${id}`
    );
    return response.data;
  },

  // Get featured entries
  getFeatured: async (per_page: number = 10) => {
    const response = await api.get<ApiResponse<PaginatedResponse<WomenRunningEntry>>>(
      '/women-running/filter/featured',
      { params: { per_page } }
    );
    return response.data;
  },

  // Get entries by category
  getByCategory: async (category: string, per_page: number = 15) => {
    const response = await api.get<ApiResponse<PaginatedResponse<WomenRunningEntry>>>(
      `/women-running/category/${category}`,
      { params: { per_page } }
    );
    return response.data;
  },

  // Get statistics
  getStatistics: async () => {
    const response = await api.get<ApiResponse<any>>('/women-running/stats/all');
    return response.data;
  },

  // Create entry (protected)
  create: async (data: FormData) => {
    const response = await api.post<ApiResponse<WomenRunningEntry>>(
      '/women-running',
      data,
      {
        headers: {
          'Content-Type': 'multipart/form-data',
        },
      }
    );
    return response.data;
  },

  // Update entry (protected)
  update: async (id: number, data: FormData) => {
    const response = await api.post<ApiResponse<WomenRunningEntry>>(
      `/women-running/${id}`,
      data,
      {
        headers: {
          'Content-Type': 'multipart/form-data',
          'X-HTTP-Method-Override': 'PUT',
        },
      }
    );
    return response.data;
  },

  // Delete entry (protected)
  delete: async (id: number) => {
    const response = await api.delete<ApiResponse<null>>(`/women-running/${id}`);
    return response.data;
  },

  // Toggle status (protected)
  toggleStatus: async (id: number) => {
    const response = await api.patch<ApiResponse<WomenRunningEntry>>(
      `/women-running/${id}/toggle-status`
    );
    return response.data;
  },

  // Toggle featured (protected)
  toggleFeatured: async (id: number) => {
    const response = await api.patch<ApiResponse<WomenRunningEntry>>(
      `/women-running/${id}/toggle-featured`
    );
    return response.data;
  },
};