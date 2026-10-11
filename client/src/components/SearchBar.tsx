// components/SearchBar.tsx (updated - removed focus styles)
import React, { useState, useRef, useEffect } from 'react';
import { Search, X, Loader2, Filter } from 'lucide-react';
import { SearchResult, SearchFilters } from '@/types/search';
import { searchService } from '@/services/searchService';
import SearchResults from './SearchResults';
import SearchFiltersPanel from './SearchFiltersPanel';
import { useSearchNavigation } from '@/hooks/useSearchNavigation';

interface SearchBarProps {
  placeholder?: string;
  className?: string;
  onResultClick?: (result: SearchResult) => void;
  filters?: SearchFilters;
  variant?: 'desktop' | 'mobile' | 'mobile-menu';
}

const SearchBar: React.FC<SearchBarProps> = ({
  placeholder = "Search stories, athletes, videos, events...",
  className = "",
  onResultClick,
  filters,
  variant = 'desktop'
}) => {
  const [isOpen, setIsOpen] = useState(variant === 'desktop');
  const [query, setQuery] = useState('');
  const [results, setResults] = useState<SearchResult[]>([]);
  const [isLoading, setIsLoading] = useState(false);
  const [showFilters, setShowFilters] = useState(false);
  const [activeFilters, setActiveFilters] = useState<SearchFilters>(filters || {
    types: ['story', 'athlete', 'video', 'race-event', 'women-running', 'race-result']
  });

  const searchRef = useRef<HTMLDivElement>(null);
  const inputRef = useRef<HTMLInputElement>(null);
  const { handleSearchResultClick } = useSearchNavigation();

  // Close search when clicking outside (for desktop)
  useEffect(() => {
    if (variant !== 'desktop') return;

    const handleClickOutside = (event: MouseEvent) => {
      if (searchRef.current && !searchRef.current.contains(event.target as Node)) {
        setIsOpen(true); // Keep open but clear if needed
        setShowFilters(false);
        if (!query) {
          // Don't clear query, just close filters
        }
      }
    };

    document.addEventListener('mousedown', handleClickOutside);
    return () => document.removeEventListener('mousedown', handleClickOutside);
  }, [variant, query]);

  // Focus input when opened
  useEffect(() => {
    if (isOpen && inputRef.current) {
      inputRef.current.focus();
    }
  }, [isOpen]);

  // Perform search
  useEffect(() => {
    const performSearch = async () => {
      if (!query.trim()) {
        setResults([]);
        return;
      }

      setIsLoading(true);
      try {
        const searchResults = await searchService.debouncedSearch(query, activeFilters);
        setResults(searchResults.results);
      } catch (error) {
        console.error('Search error:', error);
        setResults([]);
      } finally {
        setIsLoading(false);
      }
    };

    performSearch();
  }, [query, activeFilters]);

  const handleSearchToggle = () => {
    if (variant === 'mobile') {
      setIsOpen(!isOpen);
    }
    // For desktop, search is always open
  };

  const handleClose = () => {
    if (variant === 'mobile') {
      setIsOpen(false);
    }
    setShowFilters(false);
    setQuery('');
    setResults([]);
  };

  const handleResultClick = (result: SearchResult) => {
    onResultClick?.(result);
    handleSearchResultClick(result);
    if (variant === 'mobile') {
      handleClose();
    }
  };

  const handleFilterChange = (newFilters: SearchFilters) => {
    setActiveFilters(newFilters);
  };

  const handleKeyDown = (e: React.KeyboardEvent) => {
    if (e.key === 'Escape') {
      if (variant === 'mobile') {
        handleClose();
      } else {
        setShowFilters(false);
      }
    }
  };

  // Desktop integrated search - ALWAYS VISIBLE
  if (variant === 'desktop') {
    return (
      <div ref={searchRef} className={`relative ${className}`}>
        {/* Desktop Search Input - Always Visible */}
        <div className="relative w-full">
          <div className="relative">
            <Search className="w-4 h-4 text-gray-400 absolute left-3 top-1/2 transform -translate-y-1/2" />
            <input
              ref={inputRef}
              type="text"
              value={query}
              onChange={(e) => setQuery(e.target.value)}
              onKeyDown={handleKeyDown}
              placeholder={placeholder}
              className="w-full pl-10 pr-20 py-2.5 bg-white border border-gray-300 rounded-lg focus:outline-none focus:border-gray-400 text-sm transition-all duration-200 placeholder-gray-500"
              aria-label="Search input"
            />

            {/* Filter Button */}
            <button
              onClick={() => setShowFilters(!showFilters)}
              className={`absolute right-8 top-1/2 transform -translate-y-1/2 p-1 transition-colors duration-200 ${
                showFilters ? 'text-gray-600' : 'text-gray-400 hover:text-gray-600'
              }`}
              aria-label="Toggle filters"
            >
              <Filter className="w-4 h-4" />
            </button>

            {/* Clear Button */}
            {query && (
              <button
                onClick={() => setQuery('')}
                className="absolute right-3 top-1/2 transform -translate-y-1/2 p-1 text-gray-400 hover:text-gray-600 transition-colors"
                aria-label="Clear search"
              >
                <X className="w-4 h-4" />
              </button>
            )}
          </div>

          {/* Search Results Dropdown */}
          {query && (
            <div className="absolute top-full left-0 right-0 mt-2 bg-white border border-gray-200 rounded-lg shadow-lg z-50 max-h-96 overflow-hidden">
              {/* Filters Panel */}
              {showFilters && (
                <div className="border-b border-gray-100">
                  <SearchFiltersPanel
                    filters={activeFilters}
                    onChange={handleFilterChange}
                    onClose={() => setShowFilters(false)}
                    compact
                  />
                </div>
              )}

              {/* Results */}
              <div className="max-h-80 overflow-y-auto">
                {isLoading ? (
                  <div className="flex items-center justify-center p-4">
                    <Loader2 className="w-4 h-4 animate-spin text-gray-400 mr-2" />
                    <span className="text-sm text-gray-600">Searching...</span>
                  </div>
                ) : results.length === 0 ? (
                  <div className="p-4 text-center text-sm text-gray-500">
                    No results found for &quot;{query}&quot;
                  </div>
                ) : (
                  <SearchResults
                    results={results}
                    query={query}
                    onResultClick={handleResultClick}
                    compact
                  />
                )}
              </div>
            </div>
          )}
        </div>
      </div>
    );
  }

  // Mobile search in navigation bar
  if (variant === 'mobile') {
    return (
      <div ref={searchRef} className={`relative ${className}`}>
        {isOpen ? (
          // Expanded mobile search
          <div className="relative">
            <div className="relative">
              <Search className="w-4 h-4 text-gray-400 absolute left-3 top-1/2 transform -translate-y-1/2" />
              <input
                ref={inputRef}
                type="text"
                value={query}
                onChange={(e) => setQuery(e.target.value)}
                onKeyDown={handleKeyDown}
                placeholder={placeholder}
                className="w-full pl-10 pr-12 py-2.5 bg-white border border-gray-300 rounded-lg focus:outline-none focus:border-gray-400 text-sm transition-all duration-200"
                aria-label="Search input"
              />
              
              {/* Close Button */}
              <button
                onClick={handleClose}
                className="absolute right-3 top-1/2 transform -translate-y-1/2 p-1 text-gray-400 hover:text-gray-600 transition-colors"
                aria-label="Close search"
              >
                <X className="w-4 h-4" />
              </button>
            </div>

            {/* Search Results Dropdown for Mobile */}
            {query && (
              <div className="absolute top-full left-0 right-0 mt-2 bg-white border border-gray-200 rounded-lg shadow-lg z-50 max-h-64 overflow-hidden">
                <div className="max-h-60 overflow-y-auto">
                  {isLoading ? (
                    <div className="flex items-center justify-center p-3">
                      <Loader2 className="w-4 h-4 animate-spin text-gray-400 mr-2" />
                      <span className="text-sm text-gray-600">Searching...</span>
                    </div>
                  ) : results.length === 0 ? (
                    <div className="p-3 text-center text-sm text-gray-500">
                      No results found
                    </div>
                  ) : (
                    <SearchResults
                      results={results}
                      query={query}
                      onResultClick={handleResultClick}
                      compact
                    />
                  )}
                </div>
              </div>
            )}
          </div>
        ) : (
          // Collapsed mobile search (icon only)
          <button
            onClick={handleSearchToggle}
            className="p-2.5 hover:bg-gray-100 rounded-lg transition-colors duration-200 text-gray-600 hover:text-gray-800 focus:outline-none"
            aria-label="Open search"
          >
            <Search className="w-5 h-5" />
          </button>
        )}
      </div>
    );
  }

  // Mobile menu search (inside the sidebar)
  return (
    <div ref={searchRef} className={`relative ${className}`}>
      <div className="relative">
        <Search className="w-4 h-4 text-gray-400 absolute left-3 top-1/2 transform -translate-y-1/2" />
        <input
          ref={inputRef}
          type="text"
          value={query}
          onChange={(e) => setQuery(e.target.value)}
          onKeyDown={handleKeyDown}
          placeholder={placeholder}
          className="w-full pl-10 pr-4 py-2.5 bg-gray-50 border border-gray-200 rounded-lg focus:outline-none focus:border-gray-400 text-sm transition-all duration-200"
          aria-label="Search input"
        />
      </div>

      {/* Inline Results for Mobile Menu */}
      {query && (
        <div className="absolute top-full left-0 right-0 mt-2 bg-white border border-gray-200 rounded-lg shadow-lg z-10 max-h-64 overflow-hidden">
          <div className="max-h-60 overflow-y-auto">
            {isLoading ? (
              <div className="flex items-center justify-center p-3">
                <Loader2 className="w-4 h-4 animate-spin text-gray-400 mr-2" />
                <span className="text-sm text-gray-600">Searching...</span>
              </div>
            ) : results.length === 0 ? (
              <div className="p-3 text-center text-sm text-gray-500">
                No results found for &quot;{query}&quot;
              </div>
            ) : (
              <SearchResults
                results={results}
                query={query}
                onResultClick={handleResultClick}
                compact
              />
            )}
          </div>
        </div>
      )}
    </div>
  );
};

export default SearchBar;