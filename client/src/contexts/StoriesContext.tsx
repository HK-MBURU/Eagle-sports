// src/contexts/StoriesContext.tsx

import { createContext, useContext, useState, useEffect, ReactNode } from 'react';
import { storiesAPI } from '@/services/api';
import { Story } from '@/types/story';

interface StoriesContextType {
  // Top stories for hero section (can be featured or latest)
  heroStories: Story[];
  heroLoading: boolean;
  
  // Featured stories
  featuredStories: Story[];
  featuredLoading: boolean;
  
  // Latest stories
  latestStories: Story[];
  latestLoading: boolean;
  
  // All categories
  categories: string[];
  categoriesLoading: boolean;
  
  // Refresh functions
  refreshHero: () => Promise<void>;
  refreshFeatured: () => Promise<void>;
  refreshLatest: () => Promise<void>;
  refreshCategories: () => Promise<void>;
}

const StoriesContext = createContext<StoriesContextType | undefined>(undefined);

export const StoriesProvider = ({ children }: { children: ReactNode }) => {
  const [heroStories, setHeroStories] = useState<Story[]>([]);
  const [heroLoading, setHeroLoading] = useState(true);
  
  const [featuredStories, setFeaturedStories] = useState<Story[]>([]);
  const [featuredLoading, setFeaturedLoading] = useState(true);
  
  const [latestStories, setLatestStories] = useState<Story[]>([]);
  const [latestLoading, setLatestLoading] = useState(true);
  
  const [categories, setCategories] = useState<string[]>([]);
  const [categoriesLoading, setCategoriesLoading] = useState(true);

  // Fetch top stories for hero section in two phases:
  // 1) load one story quickly, 2) fill in the rest after
  const fetchHeroStories = async () => {
    try {
      setHeroLoading(true);
      const response = await storiesAPI.getStories({ per_page: 1 });
      if (response.success) {
        setHeroStories(response.data);
      }
    } catch (error) {
      console.error('Error fetching hero story:', error);
    } finally {
      setHeroLoading(false);
    }

    try {
      const response = await storiesAPI.getStories({ per_page: 7 });
      if (response.success) {
        setHeroStories(response.data);
      }
    } catch (error) {
      console.error('Error fetching hero stories:', error);
    }
  };

  // Fetch featured stories
  const fetchFeaturedStories = async () => {
    try {
      setFeaturedLoading(true);
      const response = await storiesAPI.getFeaturedStories();
      if (response.success) {
        setFeaturedStories(response.data);
      }
    } catch (error) {
      console.error('Error fetching featured stories:', error);
    } finally {
      setFeaturedLoading(false);
    }
  };

  // Fetch latest stories
  const fetchLatestStories = async () => {
    try {
      setLatestLoading(true);
      const response = await storiesAPI.getLatestStories();
      if (response.success) {
        setLatestStories(response.data);
      }
    } catch (error) {
      console.error('Error fetching latest stories:', error);
    } finally {
      setLatestLoading(false);
    }
  };

  // Fetch categories
  const fetchCategories = async () => {
    try {
      setCategoriesLoading(true);
      const response = await storiesAPI.getCategories();
      if (response.success) {
        // Extract just the category names
        const categoryNames = response.data.map((cat: any) => cat.name || cat);
        setCategories(categoryNames);
      }
    } catch (error) {
      console.error('Error fetching categories:', error);
    } finally {
      setCategoriesLoading(false);
    }
  };

  // Load data on mount
  useEffect(() => {
    fetchHeroStories();
    fetchFeaturedStories();
    fetchLatestStories();
    fetchCategories();
  }, []);

  const value = {
    heroStories,
    heroLoading,
    featuredStories,
    featuredLoading,
    latestStories,
    latestLoading,
    categories,
    categoriesLoading,
    refreshHero: fetchHeroStories,
    refreshFeatured: fetchFeaturedStories,
    refreshLatest: fetchLatestStories,
    refreshCategories: fetchCategories,
  };

  return (
    <StoriesContext.Provider value={value}>
      {children}
    </StoriesContext.Provider>
  );
};

// Custom hook to use the stories context
export const useStories = () => {
  const context = useContext(StoriesContext);
  if (context === undefined) {
    throw new Error('useStories must be used within a StoriesProvider');
  }
  return context;
};
