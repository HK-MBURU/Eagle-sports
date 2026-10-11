import { useEffect, useState } from "react";
import { useParams, useNavigate } from "react-router-dom";
import { Card, CardContent } from "@/components/ui/card";
import { Button } from "@/components/ui/button";
import { Loader2, ArrowLeft, Calendar, Share2, ChevronRight } from "lucide-react";
import { womenRunningApi } from "@/services/womenRunningApi";
import { WomenRunningEntry } from "@/types/womenRunning";
import { getIconComponent } from "@/utils/iconMapper";
import femaleAthlete from "@/assets/female-athlete.jpg";

const WomenStoryDetail = () => {
    const { id } = useParams<{ id: string }>();
    const navigate = useNavigate();

    const [currentStory, setCurrentStory] = useState<WomenRunningEntry | null>(null);
    const [relatedStories, setRelatedStories] = useState<WomenRunningEntry[]>([]);
    const [loading, setLoading] = useState(true);
    const [error, setError] = useState<string | null>(null);

    useEffect(() => {
        if (id) {
            fetchStoryData(parseInt(id));
        }
    }, [id]);

    const fetchStoryData = async (storyId: number) => {
        try {
            setLoading(true);
            setError(null);

            // Fetch current story
            const storyResponse = await womenRunningApi.getById(storyId);
            if (storyResponse.success) {
                setCurrentStory(storyResponse.data);
            }

            // Fetch related stories (all stories except current one)
            const allStoriesResponse = await womenRunningApi.getAll({
                per_page: 100,
            });

            if (allStoriesResponse.success) {
                const related = allStoriesResponse.data.data
                    .filter(story => story.id !== storyId)
                    .slice(0, 6); // Get up to 6 related stories
                setRelatedStories(related);
            }

        } catch (err: any) {
            console.error('Error fetching story:', err);
            setError(err.message || 'Failed to load story');
        } finally {
            setLoading(false);
        }
    };

    const extractYouTubeVideoId = (url: string): string | null => {
        if (!url) return null;

        // Match various YouTube URL formats
        const patterns = [
            /(?:youtube\.com\/watch\?v=|youtu\.be\/|youtube\.com\/embed\/)([^&\n?#]+)/,
            /youtube\.com\/watch\?.*v=([^&\n?#]+)/
        ];

        for (const pattern of patterns) {
            const match = url.match(pattern);
            if (match && match[1]) {
                return match[1];
            }
        }
        return null;
    };

    const handleStoryClick = (storyId: number) => {
        navigate(`/women-stories/${storyId}`);
        window.scrollTo({ top: 0, behavior: 'smooth' });
    };

    const handleShare = async () => {
        const shareData = {
            title: currentStory?.title || 'Women Breaking Barriers',
            text: currentStory?.description || '',
            url: window.location.href,
        };

        try {
            if (navigator.share) {
                await navigator.share(shareData);
            } else {
                // Fallback: copy to clipboard
                await navigator.clipboard.writeText(window.location.href);
                alert('Link copied to clipboard!');
            }
        } catch (err) {
            console.error('Error sharing:', err);
        }
    };

    if (loading) {
        return (
            <div className="min-h-screen bg-gradient-to-b from-accent to-background flex items-center justify-center">
                <div className="text-center">
                    <Loader2 className="w-12 h-12 animate-spin text-accent-orange mx-auto mb-4" />
                    <p className="text-muted-foreground">Loading story...</p>
                </div>
            </div>
        );
    }

    if (error || !currentStory) {
        return (
            <div className="min-h-screen bg-gradient-to-b from-accent to-background flex items-center justify-center">
                <div className="text-center">
                    <p className="text-red-500 mb-4">Failed to load story</p>
                    <Button onClick={() => navigate('/women-stories')}>
                        Back to Stories
                    </Button>
                </div>
            </div>
        );
    }

    const videoId = currentStory.video_url ? extractYouTubeVideoId(currentStory.video_url) : null;
    const Icon = getIconComponent(currentStory.icon);

    return (
        <div className="min-h-screen bg-gradient-to-b from-accent to-background">
            {/* Hero Section */}
            <div className="relative h-[60vh] md:h-[70vh] overflow-hidden">
                <img
                    src={currentStory.image || femaleAthlete}
                    alt={currentStory.name}
                    className="w-full h-full object-cover"
                    onError={(e) => {
                        const target = e.target as HTMLImageElement;
                        target.src = femaleAthlete;
                    }}
                />
                <div className="absolute inset-0 bg-gradient-to-t from-background via-background/10 to-transparent" />

                {/* Back Button */}
                <div className="absolute top-8 left-8 z-10">
                    <Button
                        variant="outline"
                        className="bg-white/90 backdrop-blur-sm hover:bg-white"
                        onClick={() => navigate(-1)}
                    >
                        <ArrowLeft className="mr-2" size={18} />
                        Back
                    </Button>
                </div>

                {/* Story Title Overlay */}
                <div className="absolute bottom-0 left-0 right-0 p-8 md:p-12 bg-gradient-to-t from-black/70 via-black/40 to-transparent">
                    <div className="container mx-auto max-w-6xl">
                        <div className="flex items-center gap-3 mb-4 flex-wrap">
                            <div className="inline-flex items-center justify-center w-12 h-12 rounded-full bg-accent-orange/90 text-white shadow-xl">
                                <Icon size={24} />
                            </div>
                            <span className="inline-block bg-accent-orange/90 text-white px-4 py-2 rounded-full text-sm font-semibold shadow-lg">
                                {currentStory.category_label}
                            </span>
                            {currentStory.featured && (
                                <span className="inline-block bg-yellow-500/90 text-white px-4 py-2 rounded-full text-sm font-semibold shadow-lg">
                                    Featured
                                </span>
                            )}
                        </div>
                        <h1
                            className="text-4xl md:text-6xl font-bold text-white mb-4"
                            style={{
                                textShadow: '0 4px 6px rgba(0, 0, 0, 0.8), 0 2px 4px rgba(0, 0, 0, 0.6), 0 8px 16px rgba(0, 0, 0, 0.4)'
                            }}
                        >
                            {currentStory.title}
                        </h1>
                        <p
                            className="text-xl md:text-2xl text-white font-semibold"
                            style={{
                                textShadow: '0 2px 4px rgba(0, 0, 0, 0.8), 0 4px 8px rgba(0, 0, 0, 0.6)'
                            }}
                        >
                            {currentStory.name}
                        </p>
                    </div>
                </div>
            </div>

            {/* Main Content */}
            <div className="container mx-auto px-4 py-12 max-w-7xl">
                <div className="grid lg:grid-cols-3 gap-8">
                    {/* Main Story Content */}
                    <div className="lg:col-span-2 space-y-8">
                        {/* Story Info Card */}
                        <Card className="border-0 shadow-lg">
                            <CardContent className="p-8">
                                <div className="flex items-center justify-between mb-6 pb-6 border-b">
                                    <div className="flex items-center gap-4 text-muted-foreground">
                                        <div className="flex items-center gap-2">
                                            <Calendar size={18} />
                                            <span className="text-sm">{currentStory.created_at_human}</span>
                                        </div>
                                    </div>
                                    <Button
                                        variant="outline"
                                        size="sm"
                                        onClick={handleShare}
                                        className="gap-2"
                                    >
                                        <Share2 size={16} />
                                        Share
                                    </Button>
                                </div>

                                {/* Description */}
                                <div className="prose prose-lg max-w-none mb-8">
                                    <p className="text-xl text-muted-foreground leading-relaxed">
                                        {currentStory.description}
                                    </p>
                                </div>

                                {/* Full Story Content */}
                                {currentStory.story_content && (
                                    <div className="prose prose-lg max-w-none">
                                        <h2 className="text-2xl font-bold text-foreground mb-4">The Full Story</h2>
                                        <div className="text-muted-foreground leading-relaxed whitespace-pre-wrap">
                                            {currentStory.story_content}
                                        </div>
                                    </div>
                                )}
                            </CardContent>
                        </Card>

                        {/* Video Section */}
                        {videoId && (
                            <Card className="border-0 shadow-lg">
                                <CardContent className="p-8">
                                    <h2 className="text-2xl font-bold text-foreground mb-6 flex items-center gap-2">
                                        <span className="inline-flex items-center justify-center w-10 h-10 rounded-full bg-accent-orange/10 text-accent-orange">
                                            <svg className="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                                <path d="M10 18a8 8 0 100-16 8 8 0 000 16zM9.555 7.168A1 1 0 008 8v4a1 1 0 001.555.832l3-2a1 1 0 000-1.664l-3-2z" />
                                            </svg>
                                        </span>
                                        Watch Interview
                                    </h2>

                                    {/* Embedded YouTube Video */}
                                    <div className="relative w-full" style={{ paddingBottom: '56.25%' }}>
                                        <iframe
                                            className="absolute top-0 left-0 w-full h-full rounded-lg"
                                            src={`https://www.youtube.com/embed/${videoId}?rel=0`}
                                            title={currentStory.title}
                                            frameBorder="0"
                                            allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture"
                                            allowFullScreen
                                        ></iframe>
                                    </div>

                                    <div className="mt-4 flex items-center justify-between text-sm text-muted-foreground">
                                        <p>Watch the full interview with {currentStory.name}</p>
                                        <a
                                            href={currentStory.video_url}
                                            target="_blank"
                                            rel="noopener noreferrer"
                                            className="text-accent-orange hover:underline flex items-center gap-1"
                                        >
                                            Open in YouTube
                                            <ChevronRight size={16} />
                                        </a>
                                    </div>
                                </CardContent>
                            </Card>
                        )}
                    </div>

                    {/* Sidebar - Related Stories */}
                    <div className="lg:col-span-1">
                        <div className="sticky top-8 space-y-6">
                            <h2 className="text-2xl font-bold text-foreground">More Stories</h2>

                            <div className="space-y-4">
                                {relatedStories.map((story) => {
                                    const StoryIcon = getIconComponent(story.icon);
                                    return (
                                        <Card
                                            key={story.id}
                                            className="group cursor-pointer hover:shadow-lg transition-all duration-300 border-border hover:border-accent-orange"
                                            onClick={() => handleStoryClick(story.id)}
                                        >
                                            <CardContent className="p-0">
                                                <div className="relative h-40 overflow-hidden">
                                                    <img
                                                        src={story.image || femaleAthlete}
                                                        alt={story.name}
                                                        className="w-full h-full object-cover group-hover:scale-110 transition-transform duration-300"
                                                        onError={(e) => {
                                                            const target = e.target as HTMLImageElement;
                                                            target.src = femaleAthlete;
                                                        }}
                                                    />
                                                    <div className="absolute inset-0 bg-gradient-to-t from-black/60 to-transparent" />
                                                    <div className="absolute bottom-3 left-3">
                                                        <span className="inline-block bg-accent-orange/90 text-white px-3 py-1 rounded-full text-xs font-semibold">
                                                            {story.category_label}
                                                        </span>
                                                    </div>
                                                </div>
                                                <div className="p-4">
                                                    <div className="flex items-start gap-3">
                                                        <div className="inline-flex items-center justify-center w-8 h-8 rounded-full bg-accent-orange/10 text-accent-orange flex-shrink-0 mt-1">
                                                            <StoryIcon size={16} />
                                                        </div>
                                                        <div className="flex-1 min-w-0">
                                                            <h3 className="font-bold text-foreground mb-1 line-clamp-2 group-hover:text-accent-orange transition-colors">
                                                                {story.title}
                                                            </h3>
                                                            <p className="text-sm text-accent-orange font-semibold mb-2">
                                                                {story.name}
                                                            </p>
                                                            <p className="text-xs text-muted-foreground line-clamp-2">
                                                                {story.description}
                                                            </p>
                                                        </div>
                                                    </div>
                                                </div>
                                            </CardContent>
                                        </Card>
                                    );
                                })}
                            </div>

                            {relatedStories.length === 0 && (
                                <Card className="border-0 shadow-sm">
                                    <CardContent className="p-6 text-center">
                                        <p className="text-muted-foreground">No more stories available</p>
                                    </CardContent>
                                </Card>
                            )}

                            {/* Call to Action */}
                            <Card className="border-0 shadow-lg bg-gradient-to-br from-accent-orange/10 to-accent-terracotta/10">
                                <CardContent className="p-6 text-center">
                                    <h3 className="text-lg font-bold text-foreground mb-2">
                                        Inspired by these stories?
                                    </h3>
                                    <p className="text-sm text-muted-foreground mb-4">
                                        Join our mentorship program and be part of the change
                                    </p>
                                    <Button
                                        className="w-full bg-accent-orange text-accent-orange-foreground hover:bg-accent-terracotta"
                                        onClick={() => navigate('/get-involved')}
                                    >
                                        Get Involved
                                    </Button>
                                </CardContent>
                            </Card>
                        </div>
                    </div>
                </div>
            </div>

            {/* Navigation to Next Story */}
            {relatedStories.length > 0 && (
                <div className="container mx-auto px-4 pb-12 max-w-7xl">
                    <Card
                        className="border-0 shadow-lg cursor-pointer hover:shadow-xl transition-all duration-300 bg-gradient-to-r from-accent-orange/5 to-accent-terracotta/5 hover:from-accent-orange/10 hover:to-accent-terracotta/10"
                        onClick={() => handleStoryClick(relatedStories[0].id)}
                    >
                        <CardContent className="p-8">
                            <div className="flex items-center justify-between">
                                <div className="flex-1">
                                    <p className="text-sm text-accent-orange font-semibold mb-2">Next Story</p>
                                    <h3 className="text-2xl font-bold text-foreground mb-2">
                                        {relatedStories[0].title}
                                    </h3>
                                    <p className="text-muted-foreground">
                                        {relatedStories[0].name}
                                    </p>
                                </div>
                                <Button
                                    size="lg"
                                    className="bg-accent-orange text-accent-orange-foreground hover:bg-accent-terracotta ml-4"
                                >
                                    Read Next
                                    <ChevronRight className="ml-2" size={20} />
                                </Button>
                            </div>
                        </CardContent>
                    </Card>
                </div>
            )}
        </div>
    );
};

export default WomenStoryDetail;