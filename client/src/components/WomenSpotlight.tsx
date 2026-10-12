import { Card, CardContent } from "@/components/ui/card";
import { Button } from "@/components/ui/button";
import { Sparkles, Loader2, Play, ArrowRight } from "lucide-react";
import femaleAthlete from "@/assets/female-athlete.jpg";
import { useWomenRunning } from "@/hooks/useWomenRunning";
import { getIconComponent } from "@/utils/iconMapper";
import { useNavigate } from "react-router-dom";
import { useState } from "react";
import { VideoModal } from "./VideoModal";

const WomenSpotlight = () => {
  const { entries, featuredEntry, loading, error } = useWomenRunning();
  const navigate = useNavigate();

  // Video modal state
  const [isVideoModalOpen, setIsVideoModalOpen] = useState(false);
  const [selectedVideo, setSelectedVideo] = useState<{
    url: string;
    title: string;
    name: string;
  } | null>(null);

  const handleStoryClick = (storyId: number) => {
    navigate(`/women-stories/${storyId}`);
  };

  const handleWatchVideo = (videoUrl: string, title: string, name: string) => {
    setSelectedVideo({ url: videoUrl, title, name });
    setIsVideoModalOpen(true);
  };

  const closeVideoModal = () => {
    setIsVideoModalOpen(false);
    // Small delay before clearing to allow smooth closing animation
    setTimeout(() => setSelectedVideo(null), 300);
  };

  // Show loading state
  if (loading) {
    return (
      <section id="women" className="py-24 bg-gradient-to-b from-accent to-background">
        <div className="container mx-auto px-4">
          <div className="flex items-center justify-center min-h-[400px]">
            <div className="text-center">
              <Loader2 className="w-12 h-12 animate-spin text-accent-orange mx-auto mb-4" />
              <p className="text-muted-foreground">Loading inspiring stories...</p>
            </div>
          </div>
        </div>
      </section>
    );
  }

  // Show error state
  if (error) {
    return (
      <section id="women" className="py-24 bg-gradient-to-b from-accent to-background">
        <div className="container mx-auto px-4">
          <div className="flex items-center justify-center min-h-[400px]">
            <div className="text-center">
              <p className="text-red-500 mb-4">Failed to load content</p>
              <Button
                onClick={() => window.location.reload()}
                className="bg-accent-orange text-accent-orange-foreground hover:bg-accent-terracotta"
              >
                Retry
              </Button>
            </div>
          </div>
        </div>
      </section>
    );
  }

  return (
    <section id="women" className="pt-24 pb-10 bg-gradient-to-b from-accent to-background">
      <div className="container mx-auto px-4">
        {/* Section Header */}
        <div className="text-center mb-16 animate-fade-up">
          <h2 className="text-4xl md:text-5xl font-bold text-foreground mb-4">
            Women <span className="text-accent-orange">Breaking Barriers</span>
          </h2>
          <p className="text-lg text-muted-foreground max-w-2xl mx-auto">
            Celebrating female athletes who are reshaping the landscape of Kenyan athletics
          </p>
        </div>

        {/* Featured Story */}
        {featuredEntry && (
          <div className="max-w-6xl mx-auto mb-16">
            <Card className="overflow-hidden border-0 shadow-strong hover:shadow-2xl transition-shadow duration-300">
              <div className="grid md:grid-cols-2 gap-0">
                <div className="relative h-80 md:h-auto group overflow-hidden">
                  <img
                    src={featuredEntry.image || femaleAthlete}
                    alt={featuredEntry.name}
                    className="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500"
                    onError={(e) => {
                      const target = e.target as HTMLImageElement;
                      target.src = femaleAthlete;
                    }}
                  />
                  <div className="absolute inset-0 bg-gradient-to-r from-accent-orange/20 to-transparent" />

                  {/* Play button overlay for featured video */}
                  {featuredEntry.video_url && (
                    <div className="absolute inset-0 flex items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity duration-300">
                      <button
                        onClick={(e) => {
                          e.stopPropagation();
                          handleWatchVideo(featuredEntry.video_url!, featuredEntry.title, featuredEntry.name);
                        }}
                        className="w-20 h-20 rounded-full bg-accent-orange/90 hover:bg-accent-orange flex items-center justify-center text-white shadow-xl transform hover:scale-110 transition-all duration-300"
                        aria-label="Play video"
                      >
                        <Play size={32} className="ml-1" fill="currentColor" />
                      </button>
                    </div>
                  )}
                </div>
                <CardContent className="p-8 md:p-12 flex flex-col justify-center bg-card">
                  <div className="inline-block bg-accent-orange/10 text-accent-orange px-4 py-2 rounded-full text-sm font-semibold mb-4 w-fit">
                    Featured Story
                  </div>
                  <h3 className="text-3xl font-bold text-foreground mb-4">
                    {featuredEntry.title}
                  </h3>
                  <p className="text-muted-foreground mb-6 text-lg leading-relaxed line-clamp-4">
                    {featuredEntry.description}
                  </p>
                  <div className="flex gap-4 flex-wrap">
                    <Button
                      className="bg-accent-orange text-accent-orange-foreground hover:bg-accent-terracotta"
                      onClick={() => handleStoryClick(featuredEntry.id)}
                    >
                      Read Full Story
                    </Button>
                    {featuredEntry.video_url && (
                      <Button
                        variant="outline"
                        className="border-accent-orange text-accent-orange hover:bg-accent-orange hover:text-accent-orange-foreground"
                        onClick={(e) => {
                          e.stopPropagation();
                          handleWatchVideo(featuredEntry.video_url!, featuredEntry.title, featuredEntry.name);
                        }}
                      >
                        <Play size={18} className="mr-2" />
                        Watch Interview
                      </Button>
                    )}
                  </div>
                </CardContent>
              </div>
            </Card>
          </div>
        )}

        {/* Stories Grid */}
        {entries.length > 0 && (
          <div className="grid grid-cols-1 md:grid-cols-3 gap-8 max-w-6xl mx-auto">
            {entries.map((entry, index) => {
              const Icon = getIconComponent(entry.icon);
              return (
                <Card
                  key={entry.id}
                  className="group hover:shadow-medium transition-all duration-300 hover:-translate-y-1 border-border animate-fade-up cursor-pointer relative overflow-hidden"
                  style={{ animationDelay: `${index * 100}ms` }}
                  onClick={() => handleStoryClick(entry.id)}
                >


                  <CardContent className="p-6">
                    <div className="inline-flex items-center justify-center w-12 h-12 rounded-full bg-accent-orange/10 text-accent-orange mb-4 group-hover:bg-accent-orange group-hover:text-accent-orange-foreground transition-colors">
                      <Icon size={24} />
                    </div>
                    <h3 className="text-xl font-bold text-foreground mb-2 group-hover:text-accent-orange transition-colors">
                      {entry.title}
                    </h3>
                    <p className="text-sm text-accent-orange font-semibold mb-3">{entry.name}</p>
                    <p className="text-muted-foreground line-clamp-3">{entry.description}</p>
                  </CardContent>
                </Card>
              );
            })}
          </div>
        )}

        {/* Empty State */}
        {!loading && entries.length === 0 && !featuredEntry && (
          <div className="text-center py-12">
            <p className="text-muted-foreground text-lg">
              No stories available at the moment. Check back soon!
            </p>
          </div>
        )}

        {/* Call to Action */}
        <div className="text-center mt-8">
          <p className="text-lg text-muted-foreground mb-6">
            More Stories about women in athletics
          </p>
          <Button size="lg" className="bg-accent-orange text-accent-orange-foreground hover:bg-accent-terracotta px-8"
          onClick={() => window.location.href = '/impact/women'}
          >
            Explore
            <ArrowRight className="ml-2 h-5 w-5" />
          </Button>
        </div>
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
    </section>
  );
};

export default WomenSpotlight;
