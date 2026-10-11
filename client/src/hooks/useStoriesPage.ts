// src/hooks/useStoriesPage.ts

import { useState, useEffect, useCallback, useRef } from 'react';
import { storiesAPI } from '@/services/api';
import { Story, StoriesResponse } from '@/types/story';

interface UseStoriesPageParams {
  category?: string;
  featured?: boolean;
  search?: string;
  per_page?: number;
  initialPage?: number;
  enabled?: boolean;
}

export const useStoriesPage = (params: UseStoriesPageParams = {}) => {
  const [stories, setStories] = useState<Story[]>([]);
  const [loading, setLoading] = useState(true);
  const [error, setError] = useState<string | null>(null);
  const [currentPage, setCurrentPage] = useState(params.initialPage || 1);
  const [totalPages, setTotalPages] = useState(1);
  const [total, setTotal] = useState(0);
  
  const abortControllerRef = useRef<AbortController | null>(null);

  const fetchStories = useCallback(async (page: number = currentPage) => {
    // Cancel previous request
    if (abortControllerRef.current) {
      abortControllerRef.current.abort();
    }

    // Create new abort controller
    abortControllerRef.current = new AbortController();

    try {
      setLoading(true);
      setError(null);
      
      const response: StoriesResponse = await storiesAPI.getStories({
        ...params,
        page,
      });

      if (response.success) {
        setStories(response.data);
        setCurrentPage(response.meta.current_page);
        setTotalPages(response.meta.last_page);
        setTotal(response.meta.total);
      }
    } catch (err: any) {
      if (err.name !== 'AbortError') {
        setError(err instanceof Error ? err.message : 'Failed to fetch stories');
        console.error('Error fetching stories:', err);
      }
    } finally {
      setLoading(false);
    }
  }, [params.category, params.featured, params.search, params.per_page, currentPage]);

  // Debounced effect
  useEffect(() => {
    if (params.enabled === false) return;

    const timeoutId = setTimeout(() => {
      fetchStories(1);
    }, 400); // 400ms debounce

    return () => {
      clearTimeout(timeoutId);
      if (abortControllerRef.current) {
        abortControllerRef.current.abort();
      }
    };
  }, [fetchStories, params.enabled]);

  const goToPage = (page: number) => {
    if (page >= 1 && page <= totalPages) {
      setCurrentPage(page);
      fetchStories(page);
      // Smooth scroll to top
      requestAnimationFrame(() => {
        window.scrollTo({ top: 0, behavior: 'smooth' });
      });
    }
  };

  const nextPage = () => goToPage(currentPage + 1);
  const prevPage = () => goToPage(currentPage - 1);
  const refresh = () => fetchStories(currentPage);

  return {
    stories,
    loading,
    error,
    currentPage,
    totalPages,
    total,
    goToPage,
    nextPage,
    prevPage,
    refresh,
    hasNextPage: currentPage < totalPages,
    hasPrevPage: currentPage > 1,
  };
};