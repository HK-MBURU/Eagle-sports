// src/components/CategoryStoriesPage.tsx

import { useState, useEffect } from "react";
import { useNavigate, useSearchParams } from "react-router-dom";
import PageTemplate from "@/components/PageTemplate";
import { Card, CardContent } from "@/components/ui/card";
import { Button } from "@/components/ui/button";
import { Input } from "@/components/ui/input";
import { Badge } from "@/components/ui/badge";
import { storiesAPI } from "@/services/api";
import type { Story, StoriesResponse } from "@/types/story";
import { useStories } from "@/contexts/StoriesContext";
import { 
  Search, 
  Loader2, 
  ArrowRight, 
  ChevronLeft, 
  ChevronRight, 
  Clock,
  User,
  Heart,
  BookOpen,
  Filter,
  X
} from "lucide-react";

interface CategoryStoriesPageProps {
  defaultCategory?: string;
  title?: string;
  description?: string;
  showAllCategories?: boolean;
}

const CategoryStoriesPage = ({ 
  defaultCategory, 
  title,
  description,
  showAllCategories = false 
}: CategoryStoriesPageProps) => {
  const navigate = useNavigate();
  const [searchParams, setSearchParams] = useSearchParams();
  const { categories: allCategories } = useStories();
  
  const [stories, setStories] = useState<Story[]>([]);
  const [loading, setLoading] = useState(true);
  const [error, setError] = useState<string | null>(null);
  const [searchQuery, setSearchQuery] = useState(searchParams.get('search') || "");
  const [selectedCategory, setSelectedCategory] = useState(
    searchParams.get('category') || defaultCategory || "All"
  );
  const [currentPage, setCurrentPage] = useState(
    parseInt(searchParams.get('page') || '1')
  );
  const [totalPages, setTotalPages] = useState(1);
  const [totalStories, setTotalStories] = useState(0);

  // Determine which categories to show
  const categoriesToShow = showAllCategories 
    ? ["All", ...allCategories]
    : defaultCategory 
    ? [defaultCategory]
    : ["All", ...allCategories];

  useEffect(() => {
    fetchStories();
  }, [currentPage, selectedCategory]);

  useEffect(() => {
    // Update URL params when filters change
    const params: Record<string, string> = {};
    if (selectedCategory && selectedCategory !== "All") params.category = selectedCategory;
    if (searchQuery) params.search = searchQuery;
    if (currentPage > 1) params.page = currentPage.toString();
    setSearchParams(params);
  }, [selectedCategory, searchQuery, currentPage]);

  const fetchStories = async () => {
    try {
      setLoading(true);
      setError(null);

      const categoryParam = selectedCategory === "All" ? undefined : selectedCategory;

      const response: StoriesResponse = await storiesAPI.getStories({
        category: categoryParam,
        search: searchQuery || undefined,
        per_page: 12,
        page: currentPage,
      });

      if (response.success) {
        setStories(response.data);
        setTotalPages(response.meta.last_page);
        setTotalStories(response.meta.total);
      }
    } catch (err) {
      setError(err instanceof Error ? err.message : "Failed to load stories");
      console.error("Error fetching stories:", err);
    } finally {
      setLoading(false);
    }
  };

  const handleSearch = (e: React.FormEvent) => {
    e.preventDefault();
    setCurrentPage(1);
    fetchStories();
  };

  const handleCategoryChange = (category: string) => {
    setSelectedCategory(category);
    setCurrentPage(1);
  };

  const handlePageChange = (page: number) => {
    setCurrentPage(page);
    window.scrollTo({ top: 0, behavior: "smooth" });
  };

  const clearFilters = () => {
    setSearchQuery("");
    setSelectedCategory(defaultCategory || "All");
    setCurrentPage(1);
  };

  // Auto-generate title and description if not provided
  const pageTitle = title || (
    defaultCategory && defaultCategory !== "All" 
      ? `${defaultCategory} Stories` 
      : "All Stories"
  );

  const pageDescription = description || (
    defaultCategory && defaultCategory !== "All"
      ? `Explore inspiring ${defaultCategory.toLowerCase()} stories from athletes and the community.`
      : "Discover inspiring stories from athletes, communities, and changemakers in athletics."
  );

  const hasActiveFilters = searchQuery || (selectedCategory !== "All" && selectedCategory !== defaultCategory);

  return (
    <PageTemplate title={pageTitle} description={pageDescription}>
      <div className="max-w-7xl mx-auto">
        {/* Search Bar */}
        <div className="mb-6">
          <form onSubmit={handleSearch} className="max-w-2xl mx-auto">
            <div className="relative">
              <Search className="absolute left-4 top-1/2 transform -translate-y-1/2 text-gray-400" size={20} />
              <Input
                type="text"
                placeholder={`Search ${defaultCategory ? defaultCategory.toLowerCase() : ''} stories...`}
                value={searchQuery}
                onChange={(e) => setSearchQuery(e.target.value)}
                className="pl-12 pr-24 py-6 text-base border border-gray-300"
              />
              {searchQuery && (
                <button
                  type="button"
                  onClick={() => {
                    setSearchQuery("");
                    setCurrentPage(1);
                  }}
                  className="absolute right-20 top-1/2 transform -translate-y-1/2 text-gray-400 hover:text-gray-600"
                >
                  <X size={18} />
                </button>
              )}
              <Button
                type="submit"
                size="sm"
                className="absolute right-2 top-1/2 transform -translate-y-1/2 bg-[#FF6600] text-white"
              >
                Search
              </Button>
            </div>
          </form>
        </div>

        {/* Category Filter - Only show if multiple categories available */}
        {categoriesToShow.length > 1 && (
          <div className="mb-6">
            <div className="flex items-center justify-center gap-2 mb-3">
              <Filter size={18} className="text-gray-600" />
              <span className="text-sm font-medium text-gray-600">Filter by category:</span>
            </div>
            <div className="flex flex-wrap justify-center gap-2">
              {categoriesToShow.map((category) => (
                <Button
                  key={category}
                  variant={selectedCategory === category ? "default" : "outline"}
                  size="sm"
                  onClick={() => handleCategoryChange(category)}
                  disabled={loading}
                  className={
                    selectedCategory === category
                      ? "bg-[#FF6600] text-white border-0"
                      : "bg-white text-gray-700 border border-gray-300 hover:border-[#FF6600]"
                  }
                >
                  {category}
                </Button>
              ))}
            </div>
          </div>
        )}

        {/* Active Filters & Results Count */}
        <div className="flex items-center justify-between mb-6 flex-wrap gap-4">
          <div className="flex items-center gap-2 text-sm text-gray-600">
            <BookOpen size={18} className="text-[#FF6600]" />
            {!loading && (
              <span>
                Showing <span className="font-bold text-[#FF6600]">{stories.length}</span> of{" "}
                <span className="font-bold text-[#FF6600]">{totalStories}</span> {totalStories === 1 ? 'story' : 'stories'}
              </span>
            )}
          </div>

          {hasActiveFilters && (
            <Button
              variant="ghost"
              size="sm"
              onClick={clearFilters}
              className="text-gray-600 hover:text-[#FF6600]"
            >
              <X size={16} className="mr-1" />
              Clear filters
            </Button>
          )}
        </div>

        {/* Loading State */}
        {loading && (
          <div className="flex justify-center items-center min-h-[400px]">
            <div className="text-center">
              <Loader2 className="w-12 h-12 animate-spin text-[#FF6600] mx-auto mb-4" />
              <p className="text-gray-600">Loading stories...</p>
            </div>
          </div>
        )}

        {/* Error State */}
        {error && !loading && (
          <div className="text-center py-12 bg-red-50 rounded-lg border border-red-200">
            <p className="text-red-600 mb-4 font-medium">{error}</p>
            <Button onClick={fetchStories} className="bg-[#FF6600] text-white hover:bg-[#E55A00]">
              Try Again
            </Button>
          </div>
        )}

        {/* Stories Grid */}
        {!loading && !error && stories.length > 0 && (
          <>
            <div className="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-12">
              {stories.map((story) => (
                <Card
                  key={story.id}
                  className="group overflow-hidden border border-gray-200 bg-white hover:shadow-xl transition-all duration-300 cursor-pointer"
                  onClick={() => navigate(`/stories/${story.slug}`)}
                >
                  {/* Image Container */}
                  <div className="relative h-56 overflow-hidden">
                    <img
                      src={story.image_url}
                      alt={story.title}
                      className="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500"
                    />
                    <div className="absolute inset-0 bg-gradient-to-t from-black/80 via-black/40 to-transparent" />

                    {/* Category Badge */}
                    <Badge className="absolute top-3 left-3 bg-[#FF6600] text-white border-0 text-xs">
                      {story.category}
                    </Badge>

                    {/* Featured Badge */}
                    {story.is_featured && (
                      <Badge className="absolute top-3 right-3 bg-[#FFBF00] text-gray-900 border-0 text-xs">
                        ⭐ Featured
                      </Badge>
                    )}

                    {/* Title Overlay */}
                    <div className="absolute bottom-0 left-0 right-0 p-4">
                      <h3 className="text-lg font-bold text-white line-clamp-2 group-hover:text-[#FFBF00] transition-colors">
                        {story.title}
                      </h3>
                    </div>
                  </div>

                  {story.image_attribution && (
                    <p className="px-5 pt-2 text-[11px] italic text-gray-500">
                      {story.image_attribution}
                    </p>
                  )}

                  {/* Content */}
                  <CardContent className={`p-5 ${story.image_attribution ? "pt-3" : ""}`}>
                    {/* Excerpt */}
                    <p className="text-gray-600 text-sm mb-4 line-clamp-3 leading-relaxed">
                      {story.excerpt}
                    </p>

                    {/* Meta Info */}
                    <div className="flex items-center justify-between text-xs text-gray-500 mb-4 pb-4 border-b border-gray-100">
                      <div className="flex items-center gap-3">
                        <div className="flex items-center gap-1">
                          <User size={14} className="text-[#FF6600]" />
                          <span className="font-medium">{story.author_name}</span>
                        </div>
                        <div className="flex items-center gap-1">
                          <Clock size={14} className="text-[#FF6600]" />
                          <span>{story.read_time} min</span>
                        </div>
                      </div>
                      {story.likes_count > 0 && (
                        <div className="flex items-center gap-1 text-[#FF6600]">
                          <Heart size={14} fill="currentColor" />
                          <span className="font-semibold">{story.likes_count}</span>
                        </div>
                      )}
                    </div>

                    {/* CTA Button */}
                    <Button
                      variant="outline"
                      size="sm"
                      className="w-full text-[#FF6600] border-[#FF6600] hover:bg-[#FF6600] hover:text-white font-semibold transition-colors"
                    >
                      Read Full Story
                      <ArrowRight className="ml-2 h-4 w-4" />
                    </Button>
                  </CardContent>
                </Card>
              ))}
            </div>

            {/* Pagination */}
            {totalPages > 1 && (
              <div className="flex justify-center items-center gap-2">
                <Button
                  variant="outline"
                  size="sm"
                  onClick={() => handlePageChange(currentPage - 1)}
                  disabled={currentPage === 1}
                  className="disabled:opacity-50 border-gray-300"
                >
                  <ChevronLeft size={18} />
                  Previous
                </Button>

                <div className="flex gap-1">
                  {Array.from({ length: Math.min(5, totalPages) }, (_, i) => {
                    let pageNum;
                    if (totalPages <= 5) {
                      pageNum = i + 1;
                    } else if (currentPage <= 3) {
                      pageNum = i + 1;
                    } else if (currentPage >= totalPages - 2) {
                      pageNum = totalPages - 4 + i;
                    } else {
                      pageNum = currentPage - 2 + i;
                    }

                    return (
                      <Button
                        key={pageNum}
                        variant={currentPage === pageNum ? "default" : "outline"}
                        size="sm"
                        onClick={() => handlePageChange(pageNum)}
                        className={
                          currentPage === pageNum
                            ? "bg-[#FF6600] text-white border-0 min-w-[40px]"
                            : "border-gray-300 hover:border-[#FF6600] min-w-[40px]"
                        }
                      >
                        {pageNum}
                      </Button>
                    );
                  })}
                </div>

                <Button
                  variant="outline"
                  size="sm"
                  onClick={() => handlePageChange(currentPage + 1)}
                  disabled={currentPage === totalPages}
                  className="disabled:opacity-50 border-gray-300"
                >
                  Next
                  <ChevronRight size={18} />
                </Button>
              </div>
            )}
          </>
        )}

        {/* No Results */}
        {!loading && !error && stories.length === 0 && (
          <div className="text-center py-20 bg-gray-50 rounded-lg">
            <div className="inline-flex items-center justify-center w-24 h-24 rounded-full bg-white shadow-sm mb-6">
              <BookOpen size={48} className="text-[#FF6600]" />
            </div>
            <h3 className="text-2xl font-bold text-gray-900 mb-3">No Stories Found</h3>
            <p className="text-gray-600 mb-6 max-w-md mx-auto">
              {searchQuery
                ? `No results found for "${searchQuery}". Try a different search term or category.`
                : selectedCategory !== "All"
                ? `No stories available in the "${selectedCategory}" category yet.`
                : "No stories available at the moment."}
            </p>
            {hasActiveFilters && (
              <Button
                onClick={clearFilters}
                className="bg-[#FF6600] text-white hover:bg-[#E55A00]"
              >
                View All Stories
              </Button>
            )}
          </div>
        )}
      </div>
    </PageTemplate>
  );
};

export default CategoryStoriesPage;
