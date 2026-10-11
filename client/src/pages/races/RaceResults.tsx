// src/pages/races/RaceResults.tsx

import { useState, useEffect } from 'react';
import { useNavigate } from 'react-router-dom';
import { raceResultsAPI } from '@/services/api';
import { RaceResult, RaceResultsResponse } from '@/types/raceResult';
import {
  Calendar,
  MapPin,
  Search,
  Filter,
  Loader2,
  ChevronRight,
  FileText,
  Star
} from 'lucide-react';
import { useToast } from '@/hooks/use-toast';
import PageTemplate from "@/components/PageTemplate";

const RaceResults = () => {
  const navigate = useNavigate();
  const [results, setResults] = useState<RaceResult[]>([]);
  const [loading, setLoading] = useState(true);
  const [searchQuery, setSearchQuery] = useState('');
  const [selectedCategory, setSelectedCategory] = useState<string>('');
  const [categories, setCategories] = useState<{ category: string; count: number }[]>([]);
  const [currentPage, setCurrentPage] = useState(1);
  const [totalPages, setTotalPages] = useState(1);
  const [totalResults, setTotalResults] = useState(0);
  const { toast } = useToast();

  // Fetch results
  const fetchResults = async (page = 1) => {
    try {
      setLoading(true);
      const response: RaceResultsResponse = await raceResultsAPI.getResults({
        page,
        per_page: 15,
        search: searchQuery || undefined,
        category: selectedCategory || undefined,
      });

      if (response.success) {
        setResults(response.data);
        setCurrentPage(response.meta.current_page);
        setTotalPages(response.meta.last_page);
        setTotalResults(response.meta.total);
      }
    } catch (error) {
      console.error('Error fetching results:', error);
      toast({
        title: 'Error',
        description: 'Failed to load race results',
        variant: 'destructive',
      });
    } finally {
      setLoading(false);
    }
  };

  // Fetch categories
  const fetchCategories = async () => {
    try {
      const response = await raceResultsAPI.getCategories();
      if (response.success) {
        setCategories(response.data);
      }
    } catch (error) {
      console.error('Error fetching categories:', error);
    }
  };

  // Initial load
  useEffect(() => {
    fetchResults();
    fetchCategories();
  }, []); // eslint-disable-line react-hooks/exhaustive-deps

  // Search/filter effect
  useEffect(() => {
    const timer = setTimeout(() => {
      fetchResults(1);
    }, 500);

    return () => clearTimeout(timer);
  }, [searchQuery, selectedCategory]); // eslint-disable-line react-hooks/exhaustive-deps

  // Navigate to detail page
  const handleViewResult = (id: number) => {
    navigate(`/race-results/${id}`);
  };

  if (loading && results.length === 0) {
    return (
      <PageTemplate title="Race Results" description="Loading...">
        <div className="min-h-screen flex items-center justify-center">
          <Loader2 className="w-8 h-8 animate-spin text-primary" />
        </div>
      </PageTemplate>
    );
  }

  return (
    <PageTemplate
      title="Race Results"
      description="View and download official race results and documents"
    >
      <div className="container mx-auto px-4 py-6 max-w-6xl">
        {/* Search and Filters */}
        <div className="bg-white rounded-lg shadow-sm border border-gray-100 p-4 mb-6">
          <div className="grid grid-cols-1 sm:grid-cols-2 gap-3">
            {/* Search */}
            <div className="relative">
              <Search className="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-gray-400" />
              <input
                type="text"
                placeholder="Search results..."
                value={searchQuery}
                onChange={(e) => setSearchQuery(e.target.value)}
                className="w-full pl-10 pr-4 py-2.5 text-sm border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary/20 focus:border-primary transition-all"
              />
            </div>

            {/* Category Filter */}
            <div className="relative">
              <Filter className="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-gray-400" />
              <select
                value={selectedCategory}
                onChange={(e) => setSelectedCategory(e.target.value)}
                className="w-full pl-10 pr-4 py-2.5 text-sm border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary/20 focus:border-primary appearance-none transition-all"
              >
                <option value="">All Categories</option>
                {categories.map((cat) => (
                  <option key={cat.category} value={cat.category}>
                    {cat.category} ({cat.count})
                  </option>
                ))}
              </select>
            </div>
          </div>

          {/* Results count */}
          <div className="mt-3 text-xs text-gray-500">
            <span className="font-semibold text-gray-700">{totalResults}</span> results found
          </div>
        </div>

        {/* Results List - Simple Rows */}
        {results.length > 0 ? (
          <>
            <div className="space-y-3 mb-6">
              {results.map((result) => (
                <div
                  key={result.id}
                  onClick={() => handleViewResult(result.id)}
                  className="bg-white rounded-lg border border-gray-100 hover:border-gray-300 hover:shadow-md transition-all duration-200 cursor-pointer group"
                >
                  <div className="p-4 sm:p-5">
                    <div className="flex items-start justify-between gap-4">
                      {/* Left Side - Content */}
                      <div className="flex-1 min-w-0">
                        {/* Title and Badge */}
                        <div className="flex items-start gap-3 mb-2">
                          <h3 className="text-base sm:text-lg font-semibold text-gray-900 group-hover:text-primary transition-colors line-clamp-1 flex-1">
                            {result.title}
                          </h3>
                          {result.is_featured && (
                            <Star className="w-4 h-4 text-yellow-500 fill-current flex-shrink-0" />
                          )}
                        </div>

                        {/* Meta Information */}
                        <div className="flex flex-wrap items-center gap-x-4 gap-y-2 text-xs sm:text-sm text-gray-600">
                          <span className="inline-flex items-center px-2 py-0.5 rounded-full bg-primary/10 text-primary font-medium text-xs">
                            {result.category}
                          </span>
                          
                          {result.race_date && (
                            <div className="flex items-center gap-1.5">
                              <Calendar className="w-3.5 h-3.5 flex-shrink-0" />
                              <span className="hidden sm:inline">{result.formatted_date}</span>
                              <span className="sm:hidden">{result.formatted_date?.split(',')[0]}</span>
                            </div>
                          )}
                          
                          {result.location && (
                            <div className="flex items-center gap-1.5">
                              <MapPin className="w-3.5 h-3.5 flex-shrink-0" />
                              <span className="truncate max-w-[150px] sm:max-w-none">{result.location}</span>
                            </div>
                          )}

                          <div className="flex items-center gap-1.5">
                            <FileText className="w-3.5 h-3.5 flex-shrink-0" />
                            <span>{result.file_type.toUpperCase()}</span>
                          </div>
                        </div>
                      </div>

                      {/* Right Side - Arrow Icon */}
                      <ChevronRight className="w-5 h-5 text-gray-400 group-hover:text-primary group-hover:translate-x-1 transition-all flex-shrink-0" />
                    </div>
                  </div>
                </div>
              ))}
            </div>

            {/* Pagination */}
            {totalPages > 1 && (
              <div className="flex items-center justify-center gap-2">
                <button
                  onClick={() => fetchResults(currentPage - 1)}
                  disabled={currentPage === 1}
                  className="px-4 py-2 text-sm border border-gray-200 rounded-lg hover:bg-gray-50 disabled:opacity-40 disabled:cursor-not-allowed transition-all"
                >
                  Previous
                </button>

                <span className="text-sm text-gray-600">
                  Page {currentPage} of {totalPages}
                </span>

                <button
                  onClick={() => fetchResults(currentPage + 1)}
                  disabled={currentPage === totalPages}
                  className="px-4 py-2 text-sm border border-gray-200 rounded-lg hover:bg-gray-50 disabled:opacity-40 disabled:cursor-not-allowed transition-all"
                >
                  Next
                </button>
              </div>
            )}
          </>
        ) : (
          <div className="text-center py-16 bg-white rounded-lg border border-gray-100">
            <FileText className="w-12 h-12 text-gray-300 mx-auto mb-3" />
            <h3 className="text-lg font-semibold text-gray-900 mb-1">No results found</h3>
            <p className="text-gray-500 text-sm">
              {searchQuery || selectedCategory
                ? 'Try adjusting your search or filters'
                : 'No race results available yet'}
            </p>
          </div>
        )}
      </div>
    </PageTemplate>
  );
};

export default RaceResults;