import { useState, useEffect } from "react";
import { useNavigate } from "react-router-dom";
import PageTemplate from "@/components/PageTemplate";
import { Card, CardContent } from "@/components/ui/card";
import { Button } from "@/components/ui/button";
import { Input } from "@/components/ui/input";
import { athletesAPI } from "@/services/api";
import type { Athlete } from "@/types/athlete";
import { Award, Search, Loader2, ArrowRight, ChevronLeft, ChevronRight } from "lucide-react";

const AthleteProfiles = () => {
  const navigate = useNavigate();
  const [athletes, setAthletes] = useState<Athlete[]>([]);
  const [loading, setLoading] = useState(true);
  const [error, setError] = useState<string | null>(null);
  const [searchQuery, setSearchQuery] = useState("");
  const [currentPage, setCurrentPage] = useState(1);
  const [totalPages, setTotalPages] = useState(1);
  const [totalAthletes, setTotalAthletes] = useState(0);

  useEffect(() => {
    fetchAthletes();
  }, [currentPage, searchQuery]);

  const fetchAthletes = async () => {
    try {
      setLoading(true);
      setError(null);

      const response = await athletesAPI.getAllAthletes({
        page: currentPage,
        per_page: 12,
        search: searchQuery || undefined,
      });

      if (response.success) {
        setAthletes(response.data);
        setTotalPages(response.meta.last_page);
        setTotalAthletes(response.meta.total);
      }
    } catch (err) {
      setError(err instanceof Error ? err.message : "Failed to load athletes");
      console.error("Error fetching athletes:", err);
    } finally {
      setLoading(false);
    }
  };

  const handleSearch = (e: React.FormEvent) => {
    e.preventDefault();
    setCurrentPage(1);
    fetchAthletes();
  };

  const handlePageChange = (page: number) => {
    setCurrentPage(page);
    window.scrollTo({ top: 0, behavior: "smooth" });
  };

  return (
    <PageTemplate
      title="Athlete Profiles"
      description="Get to know the athletes who inspire us with their dedication and achievements."
    >
      <div className="max-w-7xl mx-auto">
        {/* Search Bar */}
        <div className="mb-12">
          <form onSubmit={handleSearch} className="max-w-2xl mx-auto">
            <div className="relative">
              <Search className="absolute left-4 top-1/2 transform -translate-y-1/2 text-gray-400" size={20} />
              <Input
                type="text"
                placeholder="Search athletes by name or achievement..."
                value={searchQuery}
                onChange={(e) => setSearchQuery(e.target.value)}
                className="pl-12 pr-4 py-6 text-base rounded-full border-2 border-gray-200 focus:border-[#D43028] transition-colors"
              />
            </div>
          </form>
        </div>

        {/* Results Count */}
        {!loading && !error && (
          <div className="text-center mb-8">
            <p className="text-gray-600">
              Showing <span className="font-semibold text-[#D43028]">{athletes.length}</span> of{" "}
              <span className="font-semibold text-[#D43028]">{totalAthletes}</span> athletes
            </p>
          </div>
        )}

        {/* Loading State */}
        {loading && (
          <div className="flex justify-center items-center min-h-[400px]">
            <Loader2 className="w-12 h-12 animate-spin text-[#D43028]" />
          </div>
        )}

        {/* Error State */}
        {error && !loading && (
          <div className="text-center py-12">
            <p className="text-red-500 mb-4">{error}</p>
            <Button onClick={fetchAthletes} className="bg-[#D43028] hover:bg-[#B52820]">
              Try Again
            </Button>
          </div>
        )}

        {/* Athletes Grid */}
        {!loading && !error && athletes.length > 0 && (
          <>
            <div className="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6 mb-12">
              {athletes.map((athlete, index) => (
                <Card
                  key={athlete.id}
                  className="group overflow-hidden border border-gray-200 bg-white hover:shadow-2xl transition-all duration-300 cursor-pointer transform hover:-translate-y-2"
                  onClick={() => navigate(`/athletes/${athlete.id}`)}
                  style={{
                    animationDelay: `${index * 50}ms`,
                  }}
                >
                  {/* Image Container */}
                  <div className="relative h-80 overflow-hidden">
                    <img
                      src={athlete.image_url}
                      alt={athlete.name}
                      className="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500"
                    />
                    <div className="absolute inset-0 bg-gradient-to-t from-black/80 via-black/40 to-transparent" />

                    {/* Badge */}
                    {athlete.badge && (
                      <div
                        className="absolute top-4 right-4 px-3 py-1.5 rounded-full text-xs font-bold flex items-center gap-1.5 backdrop-blur-sm"
                        style={{
                          background: `${athlete.badge_color}20`,
                          border: `2px solid ${athlete.badge_color}`,
                          color: athlete.badge_color,
                        }}
                      >
                        <Award size={14} />
                        {athlete.badge}
                      </div>
                    )}

                    {/* Name Overlay */}
                    <div className="absolute bottom-0 left-0 right-0 p-6">
                      <h3 className="text-2xl font-bold text-white mb-2 group-hover:text-[#FFBF00] transition-colors">
                        {athlete.name}
                      </h3>
                      <p className="text-sm text-[#FFBF00] font-semibold">
                        {athlete.title}
                      </p>
                    </div>
                  </div>

                  {/* Content */}
                  <CardContent className="p-5">
                    <p className="text-sm text-gray-600 mb-4 line-clamp-3">
                      {athlete.description}
                    </p>
                    <Button
                      variant="ghost"
                      size="sm"
                      className="w-full text-[#D43028] hover:bg-[#D43028] hover:text-white transition-all duration-200 font-semibold group/btn"
                    >
                      View Full Profile
                      <ArrowRight className="ml-2 h-4 w-4 group-hover/btn:translate-x-1 transition-transform" />
                    </Button>
                  </CardContent>
                </Card>
              ))}
            </div>

            {/* Pagination */}
            {totalPages > 1 && (
              <div className="flex justify-center items-center gap-2 mt-12">
                <Button
                  variant="outline"
                  size="sm"
                  onClick={() => handlePageChange(currentPage - 1)}
                  disabled={currentPage === 1}
                  className="disabled:opacity-50"
                >
                  <ChevronLeft size={18} />
                  Previous
                </Button>

                <div className="flex gap-2">
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
                            ? "bg-[#D43028] hover:bg-[#B52820] text-white"
                            : ""
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
                  className="disabled:opacity-50"
                >
                  Next
                  <ChevronRight size={18} />
                </Button>
              </div>
            )}
          </>
        )}

        {/* No Results */}
        {!loading && !error && athletes.length === 0 && (
          <div className="text-center py-16">
            <div className="inline-flex items-center justify-center w-20 h-20 rounded-full bg-gray-100 mb-4">
              <Search size={40} className="text-gray-400" />
            </div>
            <h3 className="text-2xl font-bold text-gray-900 mb-2">No Athletes Found</h3>
            <p className="text-gray-600 mb-6">
              {searchQuery
                ? `No results found for "${searchQuery}". Try a different search term.`
                : "No athletes available at the moment."}
            </p>
            {searchQuery && (
              <Button
                onClick={() => {
                  setSearchQuery("");
                  setCurrentPage(1);
                }}
                className="bg-[#D43028] hover:bg-[#B52820]"
              >
                Clear Search
              </Button>
            )}
          </div>
        )}
      </div>
    </PageTemplate>
  );
};

export default AthleteProfiles;