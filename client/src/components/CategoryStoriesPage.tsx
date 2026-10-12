// src/components/CategoryStoriesPage.tsx

import { useState, useEffect, useRef, useCallback } from "react";
import { useNavigate, useSearchParams } from "react-router-dom";
import PageTemplate from "@/components/PageTemplate";
import { Input } from "@/components/ui/input";
import { Button } from "@/components/ui/button";
import { storiesAPI } from "@/services/api";
import type { Story, StoriesResponse } from "@/types/story";
import { useStories } from "@/contexts/StoriesContext";
import {
  Search, Loader2, ArrowRight,
  Clock, User, Heart, BookOpen, X,
} from "lucide-react";

const RED = "#d62835";

interface CategoryStoriesPageProps {
  defaultCategory?: string;
  title?: string;
  description?: string;
  showAllCategories?: boolean;
}

const PER_PAGE = 12;

const CategoryStoriesPage = ({
  defaultCategory,
  title,
  description,
  showAllCategories = false,
}: CategoryStoriesPageProps) => {
  const navigate = useNavigate();
  const [searchParams, setSearchParams] = useSearchParams();
  const { categories: allCategories } = useStories();

  const [stories, setStories]           = useState<Story[]>([]);
  const [loading, setLoading]           = useState(true);   // initial / filter-change load
  const [loadingMore, setLoadingMore]   = useState(false);  // appending pages
  const [hasMore, setHasMore]           = useState(false);
  const [totalStories, setTotalStories] = useState(0);
  const [error, setError]               = useState<string | null>(null);

  const [searchQuery, setSearchQuery]       = useState(searchParams.get("search") || "");
  const [selectedCategory, setSelectedCategory] = useState(
    searchParams.get("category") || defaultCategory || "All"
  );

  // Track current page in a ref so the IntersectionObserver closure stays fresh
  const pageRef    = useRef(1);
  const hasMoreRef = useRef(false);
  const busyRef    = useRef(false);   // prevents concurrent fetches

  const sentinelRef = useRef<HTMLDivElement>(null);

  const categoriesToShow = showAllCategories
    ? ["All", ...allCategories]
    : defaultCategory
    ? [defaultCategory]
    : ["All", ...allCategories];

  /* ─────────────────────────────────────────
     Core fetch — append=false resets the list
  ───────────────────────────────────────── */
  const fetchPage = useCallback(
    async (pageNum: number, append: boolean) => {
      if (busyRef.current) return;
      busyRef.current = true;

      try {
        if (append) setLoadingMore(true);
        else         setLoading(true);
        setError(null);

        const cat = selectedCategory === "All" ? undefined : selectedCategory;
        const response: StoriesResponse = await storiesAPI.getStories({
          category:  cat,
          search:    searchQuery || undefined,
          per_page:  PER_PAGE,
          page:      pageNum,
        });

        if (response.success) {
          const newStories = response.data;
          const lastPage   = response.meta.last_page;

          setStories((prev) => append ? [...prev, ...newStories] : newStories);
          setTotalStories(response.meta.total);

          const more = pageNum < lastPage;
          setHasMore(more);
          hasMoreRef.current = more;
          pageRef.current    = pageNum;
        }
      } catch (err) {
        setError(err instanceof Error ? err.message : "Failed to load stories");
      } finally {
        if (append) setLoadingMore(false);
        else         setLoading(false);
        busyRef.current = false;
      }
    },
    [selectedCategory, searchQuery]
  );

  /* ── Reset + initial fetch when filters change ── */
  useEffect(() => {
    pageRef.current    = 1;
    hasMoreRef.current = false;
    setStories([]);
    setHasMore(false);
    fetchPage(1, false);
  }, [selectedCategory, searchQuery, fetchPage]);   // eslint-disable-line

  /* ── Update URL params ── */
  useEffect(() => {
    const params: Record<string, string> = {};
    if (selectedCategory && selectedCategory !== "All") params.category = selectedCategory;
    if (searchQuery) params.search = searchQuery;
    setSearchParams(params, { replace: true });
  }, [selectedCategory, searchQuery]); // eslint-disable-line

  /* ── IntersectionObserver sentinel ── */
  useEffect(() => {
    const sentinel = sentinelRef.current;
    if (!sentinel) return;

    const observer = new IntersectionObserver(
      (entries) => {
        if (
          entries[0].isIntersecting &&
          hasMoreRef.current &&
          !busyRef.current
        ) {
          fetchPage(pageRef.current + 1, true);
        }
      },
      { rootMargin: "200px" }   // start loading 200px before hitting bottom
    );

    observer.observe(sentinel);
    return () => observer.disconnect();
  }, [fetchPage]);

  /* ── Handlers ── */
  const handleSearch = (e: React.FormEvent) => {
    e.preventDefault();
    // searchQuery state already set; useEffect above will fire
  };

  const handleCategoryChange = (cat: string) => {
    setSelectedCategory(cat);
  };

  const clearFilters = () => {
    setSearchQuery("");
    setSelectedCategory(defaultCategory || "All");
  };

  const pageTitle = title || (defaultCategory && defaultCategory !== "All"
    ? `${defaultCategory} Stories`
    : "All Stories");

  const pageDescription = description || (defaultCategory && defaultCategory !== "All"
    ? `Explore inspiring ${defaultCategory.toLowerCase()} stories.`
    : "Discover inspiring stories from athletes and the community.");

  const hasActiveFilters = searchQuery ||
    (selectedCategory !== "All" && selectedCategory !== defaultCategory);

  return (
    <PageTemplate title={pageTitle} description={pageDescription}>
      <div className="max-w-7xl mx-auto">

        {/* Search */}
        <div className="mb-6">
          <form onSubmit={handleSearch} className="max-w-2xl mx-auto">
            <div className="relative">
              <Search className="absolute left-4 top-1/2 -translate-y-1/2 text-gray-400" size={20} />
              <Input
                type="text"
                placeholder={`Search ${defaultCategory ? defaultCategory.toLowerCase() : ""} stories...`}
                value={searchQuery}
                onChange={(e) => setSearchQuery(e.target.value)}
                className="pl-12 pr-24 py-6 text-base border border-gray-300"
              />
              {searchQuery && (
                <button
                  type="button"
                  onClick={() => setSearchQuery("")}
                  className="absolute right-20 top-1/2 -translate-y-1/2 text-gray-400 hover:text-gray-600"
                >
                  <X size={18} />
                </button>
              )}
              <Button
                type="submit"
                size="sm"
                className="absolute right-2 top-1/2 -translate-y-1/2 text-white"
                style={{ background: RED }}
              >
                Search
              </Button>
            </div>
          </form>
        </div>

        {/* Category filter */}
        {categoriesToShow.length > 1 && (
          <div className="mb-6 flex flex-wrap justify-center gap-2">
            {categoriesToShow.map((cat) => (
              <button
                key={cat}
                onClick={() => handleCategoryChange(cat)}
                disabled={loading}
                style={{
                  padding: "6px 14px",
                  borderRadius: 20,
                  fontSize: 12,
                  fontWeight: 700,
                  border: `1px solid ${selectedCategory === cat ? RED : "#d1d5db"}`,
                  background: selectedCategory === cat ? RED : "#fff",
                  color: selectedCategory === cat ? "#fff" : "#374151",
                  cursor: "pointer",
                  transition: "all .15s",
                }}
              >
                {cat}
              </button>
            ))}
          </div>
        )}

        {/* Count + clear */}
        <div className="flex items-center justify-between mb-6 flex-wrap gap-4">
          <div className="flex items-center gap-2 text-sm text-gray-600">
            <BookOpen size={16} style={{ color: RED }} />
            {!loading && (
              <span>
                <span style={{ color: RED, fontWeight: 700 }}>{stories.length}</span>
                {" "}of{" "}
                <span style={{ color: RED, fontWeight: 700 }}>{totalStories}</span>
                {" "}{totalStories === 1 ? "story" : "stories"}
              </span>
            )}
          </div>
          {hasActiveFilters && (
            <button
              onClick={clearFilters}
              style={{ background: "none", border: "none", cursor: "pointer", color: "#6b7280", fontSize: 13, display: "flex", alignItems: "center", gap: 4 }}
            >
              <X size={14} /> Clear filters
            </button>
          )}
        </div>

        {/* Initial loading */}
        {loading && (
          <div className="flex justify-center items-center min-h-[400px]">
            <div className="text-center">
              <Loader2 className="w-10 h-10 animate-spin mx-auto mb-3" style={{ color: RED }} />
              <p className="text-gray-500 text-sm">Loading stories...</p>
            </div>
          </div>
        )}

        {/* Error */}
        {error && !loading && (
          <div className="text-center py-10 bg-red-50 rounded-lg border border-red-200">
            <p className="text-red-600 mb-4 font-medium text-sm">{error}</p>
            <Button onClick={() => fetchPage(1, false)} style={{ background: RED, color: "#fff" }}>
              Try Again
            </Button>
          </div>
        )}

        {/* Stories grid */}
        {!loading && !error && stories.length > 0 && (
          <div className="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            {stories.map((story) => (
              <div
                key={story.id}
                onClick={() => navigate(`/stories/${story.slug}`)}
                className="story-card"
                style={{
                  border: "1px solid #e5e7eb",
                  borderRadius: 10,
                  overflow: "hidden",
                  background: "#fff",
                  cursor: "pointer",
                  transition: "border-color .15s",
                }}
              >
                {/* Image */}
                <div style={{ position: "relative", height: 210, overflow: "hidden" }}>
                  <img
                    src={story.image_url}
                    alt={story.title}
                    style={{ width: "100%", height: "100%", objectFit: "cover", display: "block" }}
                  />
                  <div style={{ position: "absolute", inset: 0, background: "linear-gradient(to top, rgba(0,0,0,.75) 0%, rgba(0,0,0,.35) 50%, transparent 100%)" }} />

                  {/* Category badge */}
                  <span style={{
                    position: "absolute", top: 10, left: 10,
                    background: RED, color: "#fff",
                    padding: "3px 9px", borderRadius: 20,
                    fontSize: 10, fontWeight: 800, letterSpacing: ".3px",
                  }}>
                    {story.category}
                  </span>

                  {/* Featured badge */}
                  {story.is_featured && (
                    <span style={{
                      position: "absolute", top: 10, right: 10,
                      background: "#f0b429", color: "#1a1a1a",
                      padding: "3px 9px", borderRadius: 20,
                      fontSize: 10, fontWeight: 800,
                    }}>
                      ⭐ Featured
                    </span>
                  )}

                  {/* Title overlay */}
                  <div style={{ position: "absolute", bottom: 0, left: 0, right: 0, padding: "14px 16px" }}>
                    <h3 style={{
                      margin: 0, fontSize: 16, fontWeight: 800, color: "#fff",
                      lineHeight: 1.3,
                      overflow: "hidden", display: "-webkit-box",
                      WebkitLineClamp: 2, WebkitBoxOrient: "vertical",
                    }}>
                      {story.title}
                    </h3>
                  </div>
                </div>

                {story.image_attribution && (
                  <p style={{ padding: "6px 16px 0", fontSize: 11, color: "#9ca3af", fontStyle: "italic", margin: 0 }}>
                    {story.image_attribution}
                  </p>
                )}

                {/* Body */}
                <div style={{ padding: story.image_attribution ? "10px 16px 16px" : "14px 16px 16px" }}>
                  <p style={{
                    margin: "0 0 12px", fontSize: 13, color: "#6b7280", lineHeight: 1.6,
                    overflow: "hidden", display: "-webkit-box",
                    WebkitLineClamp: 3, WebkitBoxOrient: "vertical",
                  }}>
                    {story.excerpt}
                  </p>

                  {/* Meta */}
                  <div style={{
                    display: "flex", alignItems: "center", justifyContent: "space-between",
                    fontSize: 11, color: "#9ca3af",
                    paddingBottom: 12, marginBottom: 12,
                    borderBottom: "1px solid #f3f4f6",
                  }}>
                    <div style={{ display: "flex", gap: 12 }}>
                      <span style={{ display: "flex", alignItems: "center", gap: 4 }}>
                        <User size={12} style={{ color: RED }} />
                        <span style={{ fontWeight: 600, color: "#374151" }}>{story.author_name}</span>
                      </span>
                      <span style={{ display: "flex", alignItems: "center", gap: 4 }}>
                        <Clock size={12} style={{ color: RED }} />
                        {story.read_time} min
                      </span>
                    </div>
                    {story.likes_count > 0 && (
                      <span style={{ display: "flex", alignItems: "center", gap: 3, color: RED, fontWeight: 700 }}>
                        <Heart size={12} fill={RED} />
                        {story.likes_count}
                      </span>
                    )}
                  </div>

                  <button
                    style={{
                      width: "100%", padding: "8px 0", borderRadius: 5,
                      border: `1px solid #e5e7eb`, background: "#fff",
                      color: "#374151", fontWeight: 700, fontSize: 12,
                      cursor: "pointer", display: "flex", alignItems: "center", justifyContent: "center", gap: 6,
                      transition: "border-color .15s",
                    }}
                    className="story-read-btn"
                  >
                    Read Full Story <ArrowRight size={13} />
                  </button>
                </div>
              </div>
            ))}
          </div>
        )}

        {/* Sentinel — IntersectionObserver target */}
        <div ref={sentinelRef} style={{ height: 1 }} />

        {/* Loading more spinner */}
        {loadingMore && (
          <div style={{ display: "flex", justifyContent: "center", padding: "32px 0" }}>
            <Loader2 size={28} style={{ color: RED }} className="animate-spin" />
          </div>
        )}

        {/* End of list */}
        {!loading && !loadingMore && !hasMore && stories.length > 0 && (
          <p style={{ textAlign: "center", padding: "32px 0 8px", fontSize: 12, color: "#9ca3af" }}>
            All {totalStories} {totalStories === 1 ? "story" : "stories"} loaded.
          </p>
        )}

        {/* No results */}
        {!loading && !error && stories.length === 0 && (
          <div className="text-center py-20 bg-gray-50 rounded-lg">
            <BookOpen size={40} style={{ color: RED, margin: "0 auto 16px" }} />
            <h3 className="text-xl font-bold text-gray-900 mb-2">No Stories Found</h3>
            <p className="text-gray-500 text-sm mb-6 max-w-sm mx-auto">
              {searchQuery
                ? `No results for "${searchQuery}". Try a different term or category.`
                : `No stories in "${selectedCategory}" yet.`}
            </p>
            {hasActiveFilters && (
              <Button onClick={clearFilters} style={{ background: RED, color: "#fff" }}>
                View All Stories
              </Button>
            )}
          </div>
        )}

      </div>

      <style>{`
        .story-card:hover { border-color: ${RED} !important; }
        .story-read-btn:hover { border-color: #374151 !important; }
      `}</style>
    </PageTemplate>
  );
};

export default CategoryStoriesPage;
