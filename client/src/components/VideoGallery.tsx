import { useState, useEffect } from "react";
import { Card, CardContent } from "@/components/ui/card";
import { Button } from "@/components/ui/button";
import { Play, Youtube, Loader2, Eye, Clock } from "lucide-react";
import { videosAPI } from "@/services/api";
import type { Video } from "@/types/video";
import { VideoModal } from "./VideoModal";

const VideoGallery = () => {
  const [videos, setVideos] = useState<Video[]>([]);
  const [featuredVideo, setFeaturedVideo] = useState<Video | null>(null);
  const [loading, setLoading] = useState(true);
  const [error, setError] = useState<string | null>(null);
  const [isModalOpen, setIsModalOpen] = useState(false);
  const [selectedVideo, setSelectedVideo] = useState<Video | null>(null);

  const fetchVideos = async () => {
    try {
      setLoading(true);
      setError(null);
      
      // Fetch featured videos (first one will be our main featured video)
      const featuredResponse = await videosAPI.getFeaturedVideos(5);
      
      if (featuredResponse.success && featuredResponse.data.length > 0) {
        setFeaturedVideo(featuredResponse.data[0]);
        // Use the remaining featured videos for the grid
        setVideos(featuredResponse.data.slice(1));
      } else {
        // If no featured videos, fetch regular videos
        const regularResponse = await videosAPI.getVideos({ per_page: 4 });
        if (regularResponse.success) {
          setVideos(regularResponse.data.videos.slice(0, 4));
        }
      }
    } catch (err) {
      console.error('Error fetching videos:', err);
      setError(err instanceof Error ? err.message : 'Failed to load videos');
    } finally {
      setLoading(false);
    }
  };

  const extractYouTubeId = (url: string): string | null => {
    const pattern = /(?:youtube\.com\/(?:[^\/]+\/.+\/|(?:v|e(?:mbed)?)\/|.*[?&]v=)|youtu\.be\/)([^"&?\/\s]{11})/;
    const matches = url.match(pattern);
    return matches ? matches[1] : null;
  };

  const getVideoThumbnail = (video: Video): string => {
    // Use the YouTube thumbnail from API, or generate one from URL
    if (video.youtube_thumbnail) {
      return video.youtube_thumbnail;
    }
    
    const videoId = extractYouTubeId(video.youtube_url);
    return videoId ? `https://img.youtube.com/vi/${videoId}/hqdefault.jpg` : '/placeholder-video.jpg';
  };

  const handleVideoPlay = (video: Video) => {
    setSelectedVideo(video);
    setIsModalOpen(true);
  };

  const closeModal = () => {
    setIsModalOpen(false);
    setTimeout(() => setSelectedVideo(null), 250);
  };

  const formatViewCount = (views: number): string => {
    if (views >= 1000000) {
      return `${(views / 1000000).toFixed(1)}M`;
    } else if (views >= 1000) {
      return `${(views / 1000).toFixed(1)}K`;
    }
    return views.toString();
  };

  // Mock view counts (since your API might not have actual view counts)
  const getMockViewCount = (video: Video): number => {
    // Generate a semi-random view count based on video ID and creation date
    const base = (video.id * 12345) % 1000000 + 50000;
    const daysSinceCreation = Math.floor((new Date().getTime() - new Date(video.created_at).getTime()) / (1000 * 60 * 60 * 24));
    return Math.min(base + (daysSinceCreation * 1000), 2500000);
  };

  useEffect(() => {
    fetchVideos();
  }, []);

  if (loading) {
    return (
      <section id="videos" className="pt-24 pb-6 bg-background">
        <div className="container mx-auto px-4">
          <div className="text-center mb-16">
            <div className="inline-flex items-center justify-center w-16 h-16 rounded-full bg-accent-terracotta/10 text-accent-terracotta mb-6">
              <Youtube size={32} />
            </div>
            <h2 className="text-4xl md:text-5xl font-bold text-foreground mb-4">
              Video <span className="text-gradient-gold">Gallery</span>
            </h2>
            <p className="text-lg text-muted-foreground max-w-2xl mx-auto">
              Loading inspiring videos...
            </p>
          </div>
          <div className="flex justify-center items-center py-12">
            <Loader2 className="h-8 w-8 animate-spin text-primary" />
          </div>
        </div>
      </section>
    );
  }

  if (error) {
    return (
      <section id="videos" className="pt-24 pb-6 bg-background">
        <div className="container mx-auto px-4">
          <div className="text-center mb-16">
            <div className="inline-flex items-center justify-center w-16 h-16 rounded-full bg-accent-terracotta/10 text-accent-terracotta mb-6">
              <Youtube size={32} />
            </div>
            <h2 className="text-4xl md:text-5xl font-bold text-foreground mb-4">
              Video <span className="text-gradient-gold">Gallery</span>
            </h2>
            <p className="text-lg text-muted-foreground max-w-2xl mx-auto mb-8">
              {error}
            </p>
            <Button 
              onClick={fetchVideos}
              className="bg-accent-terracotta text-accent-terracotta-foreground hover:bg-accent-terracotta/90"
            >
              Try Again
            </Button>
          </div>
        </div>
      </section>
    );
  }

  return (
    <section id="videos" className="pt-24 pb-6 bg-background">
      <div className="container mx-auto px-4">
        {selectedVideo && (
          <VideoModal
            isOpen={isModalOpen}
            onClose={closeModal}
            videoUrl={selectedVideo.youtube_embed_url || selectedVideo.youtube_url}
            title={selectedVideo.title}
            athleteName={selectedVideo.creator?.name || selectedVideo.category_name}
          />
        )}

        {/* Section Header */}
        <div className="text-center mb-16 animate-fade-up">
          <div className="inline-flex items-center justify-center w-16 h-16 rounded-full bg-accent-terracotta/10 text-accent-terracotta mb-6">
            <Youtube size={32} />
          </div>
          <h2 className="text-4xl md:text-5xl font-bold text-foreground mb-4">
            Video <span className="text-gradient-gold">Gallery</span>
          </h2>
          <p className="text-lg text-muted-foreground max-w-2xl mx-auto">
            Watch inspiring stories, training insights, and athlete interviews
          </p>
        </div>

        {/* Featured Video */}
        {featuredVideo && (
          <div className="max-w-5xl mx-auto mb-16">
            <Card className="overflow-hidden border-0 shadow-strong">
              <div 
                className="relative aspect-video bg-cover bg-center group cursor-pointer"
                style={{ backgroundImage: `url(${getVideoThumbnail(featuredVideo)})` }}
                onClick={() => handleVideoPlay(featuredVideo)}
              >
                <div className="absolute inset-0 ">
                  <div className="absolute inset-0 flex items-center justify-center">
                    <div className="w-24 h-24 rounded-full bg-accent-gold/90 flex items-center justify-center group-hover:bg-accent-orange transition-all group-hover:scale-110">
                      <Play size={40} className="text-accent-gold-foreground ml-2" fill="currentColor" />
                    </div>
                  </div>
                </div>
                <div className="absolute inset-0 bg-black/30 group-hover:bg-black/40 transition-colors" />
              </div>
              <CardContent className="p-6 bg-card">
                <div className="flex items-start justify-between gap-4">
                  <div>
                    <h3 className="text-2xl font-bold text-foreground mb-2">
                      {featuredVideo.title}
                    </h3>
                    <p className="text-muted-foreground mb-3">
                      {featuredVideo.description || "Watch this inspiring video from our collection."}
                    </p>
                    <div className="flex items-center gap-4 text-sm text-muted-foreground">
                      <span className="bg-accent-terracotta/10 text-accent-terracotta px-3 py-1 rounded-full font-semibold">
                        {featuredVideo.category_name}
                      </span>
                      <span className="flex items-center gap-1">
                        <Eye className="h-4 w-4" />
                        {formatViewCount(getMockViewCount(featuredVideo))} views
                      </span>
                      <span className="flex items-center gap-1">
                        <Clock className="h-4 w-4" />
                        {new Date(featuredVideo.created_at).toLocaleDateString('en-US', { 
                          year: 'numeric', 
                          month: 'long', 
                          day: 'numeric' 
                        })}
                      </span>
                    </div>
                  </div>
                </div>
              </CardContent>
            </Card>
          </div>
        )}

        {/* Video Grid */}
        <div className="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8 max-w-6xl mx-auto mb-12">
          {videos.map((video, index) => {
            const viewCount = getMockViewCount(video);
            
            return (
              <Card 
                key={video.id}
                className="group overflow-hidden hover:shadow-medium transition-all duration-300 hover:-translate-y-1 cursor-pointer border-border animate-fade-up"
                style={{ animationDelay: `${index * 100}ms` }}
                onClick={() => handleVideoPlay(video)}
              >
                <div 
                  className="relative aspect-video bg-cover bg-center"
                  style={{ backgroundImage: `url(${getVideoThumbnail(video)})` }}
                >
                  <div className="absolute inset-0 bg-black/40 group-hover:bg-black/20 transition-colors">
                    <div className="absolute inset-0 flex items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity">
                      <div className="w-14 h-14 rounded-full bg-primary-foreground/90 flex items-center justify-center group-hover:bg-accent-gold transition-all group-hover:scale-110">
                        <Play size={24} className="text-primary ml-1" fill="currentColor" />
                      </div>
                    </div>
                  </div>
                  <div className="absolute bottom-2 right-2 bg-primary-foreground/90 text-primary px-2 py-1 rounded text-xs font-semibold">
                    {/* You could extract duration from YouTube API if needed */}
                    {/* For now, using a placeholder */}
                    {index % 2 === 0 ? "8:45" : "12:30"}
                  </div>
                </div>
                <CardContent className="p-4">
                  <div className="inline-block bg-accent px-2 py-1 rounded text-xs font-semibold text-primary mb-2">
                    {video.category_name}
                  </div>
                  <h3 className="text-base font-bold text-foreground mb-2 line-clamp-2 group-hover:text-primary transition-colors">
                    {video.title}
                  </h3>
                  <p className="text-sm text-muted-foreground flex items-center gap-1">
                    <Eye className="h-3 w-3" />
                    {formatViewCount(viewCount)} views
                  </p>
                </CardContent>
              </Card>
            );
          })}
        </div>

        {/* Call to Action */}
        <div className="text-center">
          <Button 
            size="lg" 
            variant="outline" 
            className="border-accent-terracotta text-accent-terracotta hover:bg-accent-terracotta hover:text-white hover:border-accent-terracotta px-8"
            onClick={() => {
              window.open('https://www.youtube.com/@BeyondthefinishlineTV', '_blank', 'noopener,noreferrer');
            }}
          >
            <Youtube className="mr-2 h-5 w-5" />
            Visit Our YouTube Channel
          </Button>
        </div>
      </div>
    </section>
  );
};

export default VideoGallery;
