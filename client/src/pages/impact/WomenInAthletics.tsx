import { useState, useEffect } from "react";
import { useNavigate } from "react-router-dom";
import PageTemplate from "@/components/PageTemplate";
import { Card, CardContent } from "@/components/ui/card";
import { Button } from "@/components/ui/button";
import { Input } from "@/components/ui/input";
import { womenRunningApi } from "@/services/womenRunningApi";
import { WomenRunningEntry } from "@/types/womenRunning";
import { getIconComponent } from "@/utils/iconMapper";
import { 
  Search, 
  Loader2, 
  ArrowRight, 
  ChevronLeft, 
  ChevronRight, 
  Sparkles,
  Play,
  Heart
} from "lucide-react";
import femaleAthlete from "@/assets/female-athlete.jpg";
import { VideoModal } from "@/components/VideoModal";

const WomenInAthletics = () => {
  const navigate = useNavigate();
  const [stories, setStories] = useState<WomenRunningEntry[]>([]);
  const [loading, setLoading] = useState(true);
  const [error, setError] = useState<string | null>(null);
  const [searchQuery, setSearchQuery] = useState("");
  const [currentPage, setCurrentPage] = useState(1);
  const [totalPages, setTotalPages] = useState(1);
  const [totalStories, setTotalStories] = useState(0);

  // Video modal state
  const [isVideoModalOpen, setIsVideoModalOpen] = useState(false);
  const [selectedVideo, setSelectedVideo] = useState<{
    url: string;
    title: string;
    name: string;
  } | null>(null);

  useEffect(() => {
    fetchStories();
  }, [currentPage]);

  const fetchStories = async () => {
    try {
      setLoading(true);
      setError(null);

      const response = await womenRunningApi.getAll({
        page: currentPage,
        per_page: 12,
        sort_by: 'order',
        sort_order: 'asc',
      });

      if (response.success) {
        setStories(response.data.data);
        setTotalPages(response.data.last_page);
        setTotalStories(response.data.total);
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
    // For now, client-side filtering
    // You can enhance this with API search if available
  };

  const handlePageChange = (page: number) => {
    setCurrentPage(page);
    window.scrollTo({ top: 0, behavior: "smooth" });
  };

  const handleStoryClick = (storyId: number) => {
    navigate(`/women-stories/${storyId}`);
  };

  const handleWatchVideo = (videoUrl: string, title: string, name: string) => {
    setSelectedVideo({ url: videoUrl, title, name });
    setIsVideoModalOpen(true);
  };

  const closeVideoModal = () => {
    setIsVideoModalOpen(false);
    setTimeout(() => setSelectedVideo(null), 300);
  };

  // Filter stories based on search query
  const filteredStories = searchQuery
    ? stories.filter(
        (story) =>
          story.name.toLowerCase().includes(searchQuery.toLowerCase()) ||
          story.title.toLowerCase().includes(searchQuery.toLowerCase()) ||
          story.description.toLowerCase().includes(searchQuery.toLowerCase())
      )
    : stories;

  return (
    <PageTemplate
      title="Women in Athletics"
      description="Celebrating and empowering women athletes who are breaking barriers and inspiring the next generation."
    >
      <div className="max-w-7xl mx-auto">
        {/* Search Bar */}
        <div className="mb-8">
          <form onSubmit={handleSearch} className="max-w-2xl mx-auto">
            <div className="relative">
              <Search className="absolute left-4 top-1/2 transform -translate-y-1/2 text-gray-400" size={20} />
              <Input
                type="text"
                placeholder="Search women athletes and stories..."
                value={searchQuery}
                onChange={(e) => setSearchQuery(e.target.value)}
                className="pl-12 pr-4 py-6 text-base border border-gray-300"
              />
            </div>
          </form>
        </div>

        {/* Results Count */}
        {!loading && !error && (
          <div className="flex items-center justify-center gap-4 mb-8 text-center">
            <div className="flex items-center gap-2 text-gray-600">
              <Sparkles size={20} className="text-[#FF6600]" />
              <span>
                Showing <span className="font-bold text-[#FF6600]">{filteredStories.length}</span> of{" "}
                <span className="font-bold text-[#FF6600]">{totalStories}</span> inspiring women
              </span>
            </div>
          </div>
        )}

        {/* Loading State */}
        {loading && (
          <div className="flex justify-center items-center min-h-[400px]">
            <div className="text-center">
              <Loader2 className="w-12 h-12 animate-spin text-[#FF6600] mx-auto mb-4" />
              <p className="text-gray-600">Loading inspiring stories...</p>
            </div>
          </div>
        )}

        {/* Error State */}
        {error && !loading && (
          <div className="text-center py-12">
            <p className="text-red-500 mb-4">{error}</p>
            <Button onClick={fetchStories} className="bg-[#FF6600] text-white">
              Try Again
            </Button>
          </div>
        )}

        {/* Stories Grid */}
        {!loading && !error && filteredStories.length > 0 && (
          <>
            <div className="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8 mb-12">
              {filteredStories.map((story) => {
                const Icon = getIconComponent(story.icon);
                return (
                  <Card
                    key={story.id}
                    className="overflow-hidden border border-gray-200 bg-white cursor-pointer"
                    onClick={() => handleStoryClick(story.id)}
                  >
                    {/* Image Container */}
                    <div className="relative h-64 group">
                      <img
                        src={story.image || femaleAthlete}
                        alt={story.name}
                        className="w-full h-full object-cover"
                        onError={(e) => {
                          const target = e.target as HTMLImageElement;
                          target.src = femaleAthlete;
                        }}
                      />
                      <div className="absolute inset-0 bg-gradient-to-t from-black/70 via-black/30 to-transparent" />

                      

             
                      {/* Icon Badge */}
                      <div className="absolute top-4 left-4 w-10 h-10 bg-[#FF6600] flex items-center justify-center text-white">
                        <Icon size={20} />
                      </div>

                      {/* Name Overlay */}
                      <div className="absolute bottom-0 left-0 right-0 p-5">
                        <h3 className="text-xl font-bold text-white line-clamp-1">
                          {story.name}
                        </h3>
                      </div>
                    </div>

                    {/* Content */}
                    <CardContent className="p-6">
                      <h4 className="text-lg font-bold text-gray-900 mb-2 line-clamp-2">
                        {story.title}
                      </h4>
                      <p className="text-sm text-gray-600 mb-4 line-clamp-3 leading-relaxed">
                        {story.description}
                      </p>

                      {/* Actions */}
                      <div className="flex items-center justify-between pt-4 border-t border-gray-100">
                        <Button
                          variant="outline"
                          size="sm"
                          className="text-[#FF6600] border-[#FF6600] font-semibold"
                          onClick={(e) => {
                            e.stopPropagation();
                            handleStoryClick(story.id);
                          }}
                        >
                          Read Story
                          <ArrowRight className="ml-2 h-4 w-4" />
                        </Button>
                        {story.video_url && (
                          <Button
                            variant="ghost"
                            size="sm"
                            className="text-[#FF6600]"
                            onClick={(e) => {
                              e.stopPropagation();
                              handleWatchVideo(story.video_url!, story.title, story.name);
                            }}
                          >
                            <Play size={16} className="mr-1" />
                            Watch
                          </Button>
                        )}
                      </div>
                    </CardContent>
                  </Card>
                );
              })}
            </div>

            {/* Pagination */}
            {totalPages > 1 && (
              <div className="flex justify-center items-center gap-2 mt-12">
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
                            ? "bg-[#FF6600] text-white border-0"
                            : "border-gray-300"
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
        {!loading && !error && filteredStories.length === 0 && (
          <div className="text-center py-16">
            <div className="inline-flex items-center justify-center w-24 h-24 bg-gray-100 mb-6">
              <Sparkles size={48} className="text-[#FF6600]" />
            </div>
            <h3 className="text-2xl font-bold text-gray-900 mb-3">No Stories Found</h3>
            <p className="text-gray-600 mb-6 max-w-md mx-auto">
              {searchQuery
                ? `No results found for "${searchQuery}". Try a different search term.`
                : "No stories available at the moment."}
            </p>
            {searchQuery && (
              <Button
                onClick={() => setSearchQuery("")}
                className="bg-[#FF6600] text-white"
              >
                Clear Search
              </Button>
            )}
          </div>
        )}
      </div>

      {/* Video Modal */}
      {selectedVideo && (
        <VideoModal
          isOpen={isVideoModalOpen}
          onClose={closeVideoModal}
          videoUrl={selectedVideo.url}
          title={selectedVideo.title}
          athleteName={selectedVideo.name}
        />
      )}
    </PageTemplate>
  );
};

export default WomenInAthletics;