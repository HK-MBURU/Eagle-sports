import { useState, useEffect } from "react";
import { Helmet } from 'react-helmet-async';
import { useParams, useNavigate, useLocation, Link } from "react-router-dom";
import { useAuth } from "@/contexts/AuthContext";
import StorySEO from "@/components/StorySEO";
import CommentSection from "@/components/CommentSection";
import {
  Clock,
  User,
  Calendar,
  ArrowLeft,
  Share2,
  Facebook,
  Twitter,
  Linkedin,
  Link2,
  Eye,
  Heart,
  MessageCircle,
  BookmarkPlus,
  Check,
  Mail,
} from "lucide-react";
import { Button } from "@/components/ui/button";
import { Badge } from "@/components/ui/badge";
import { storiesAPI } from "@/services/api";
import { commentsAPI } from "@/services/comments";
import type { Story, ShareData } from "@/types/story";
import { useToast } from "@/hooks/use-toast";

const StoryDetail = () => {
  const { slug } = useParams<{ slug: string }>();
  const navigate = useNavigate();
  const location = useLocation();
  const { toast } = useToast();
  const { isAuthenticated } = useAuth();

  const [story, setStory] = useState<Story | null>(null);
  const [shareData, setShareData] = useState<ShareData | null>(null);
  const [relatedStories, setRelatedStories] = useState<Story[]>([]);
  const [loading, setLoading] = useState(true);
  const [relatedLoading, setRelatedLoading] = useState(true);
  const [commentStatsLoading, setCommentStatsLoading] = useState(true);
  const [error, setError] = useState<string | null>(null);
  const [isLiked, setIsLiked] = useState(false);
  const [likesCount, setLikesCount] = useState(0);
  const [isSaved, setIsSaved] = useState(false);
  const [showShareMenu, setShowShareMenu] = useState(false);
  const [copied, setCopied] = useState(false);
  const [commentCount, setCommentCount] = useState(0);
  const [viewsCount, setViewsCount] = useState(0);

  // Function to update meta tags dynamically
  const updateMetaTags = (metaTags: any) => {
    if (!metaTags) return;

    // Update basic meta tags
    document.title = metaTags.title;

    // Update description meta tag
    updateMetaTag('description', metaTags.description);
    updateMetaTag('keywords', metaTags.keywords);

    // Update Open Graph tags
    updateMetaTag('og:title', metaTags.title);
    updateMetaTag('og:description', metaTags.description);
    updateMetaTag('og:image', metaTags.image);
    updateMetaTag('og:url', metaTags.url);
    updateMetaTag('og:type', 'article'); // Always 'article' for stories
    updateMetaTag('og:site_name', 'Beyond the Finish Line');

    // Update article-specific OG tags
    updateMetaTag('article:published_time', metaTags.published_time);
    updateMetaTag('article:modified_time', metaTags.modified_time);
    updateMetaTag('article:author', metaTags.author);
    updateMetaTag('article:section', metaTags.section);

    // Update Twitter tags
    updateMetaTag('twitter:card', 'summary_large_image');
    updateMetaTag('twitter:title', metaTags.title);
    updateMetaTag('twitter:description', metaTags.description);
    updateMetaTag('twitter:image', metaTags.image);
    updateMetaTag('twitter:url', metaTags.url);
    updateMetaTag('twitter:creator', '@beyondfinishline');

    // Update canonical URL
    updateLinkTag('canonical', metaTags.url);

    // Update schema tags
    updateSchemaTags(metaTags);
  };

  const updateSchemaTags = (metaTags: any) => {
    if (!metaTags) return;

    // Create Article schema for story pages
    const articleSchema = {
      "@context": "https://schema.org",
      "@type": "Article",
      "headline": metaTags.title,
      "description": metaTags.description,
      "image": metaTags.image,
      "author": {
        "@type": "Person",
        "name": metaTags.author
      },
      "publisher": {
        "@type": "Organization",
        "name": "Beyond the Finish Line",
        "logo": {
          "@type": "ImageObject",
          "url": "https://beyondfinishline.com/logo.png"
        }
      },
      "datePublished": metaTags.published_time,
      "dateModified": metaTags.modified_time,
      "mainEntityOfPage": {
        "@type": "WebPage",
        "@id": metaTags.url
      },
      "articleSection": metaTags.section
    };

    // Update or create the schema script
    let schemaScript = document.getElementById('website-schema') as HTMLScriptElement;
    if (!schemaScript) {
      schemaScript = document.createElement('script');
      schemaScript.setAttribute('type', 'application/ld+json');
      schemaScript.setAttribute('id', 'website-schema');
      document.head.appendChild(schemaScript);
    }
    schemaScript.textContent = JSON.stringify(articleSchema);
  };

  // Helper function to update meta tags
  const updateMetaTag = (property: string, content: string) => {
    if (!content) return;

    let element = document.querySelector(`meta[property="${property}"]`) as HTMLMetaElement;
    if (!element) {
      element = document.querySelector(`meta[name="${property}"]`) as HTMLMetaElement;
    }

    if (!element) {
      element = document.createElement('meta');
      if (property.startsWith('og:') || property.startsWith('article:') || property.startsWith('twitter:')) {
        element.setAttribute('property', property);
      } else {
        element.setAttribute('name', property);
      }
      document.head.appendChild(element);
    }
    element.setAttribute('content', content);
  };

  // Helper function to update link tags
  const updateLinkTag = (rel: string, href: string) => {
    if (!href) return;

    let element = document.querySelector(`link[rel="${rel}"]`) as HTMLLinkElement;
    if (!element) {
      element = document.createElement('link');
      element.setAttribute('rel', rel);
      document.head.appendChild(element);
    }
    element.setAttribute('href', href);
  };

  useEffect(() => {
    if (slug) {
      fetchStory();
      fetchShareData();
    }
  }, [slug]);

  // Update meta tags when story data is loaded
  useEffect(() => {
    if (story?.meta_tags) {
      updateMetaTags(story.meta_tags);
    }
  }, [story]);

  const fetchStory = async () => {
    try {
      setLoading(true);
      setError(null);

      const response = await storiesAPI.getStory(slug!);

      if (response.success) {
        setStory(response.data);
        setIsLiked(response.data.is_liked);
        setLikesCount(response.data.likes_count);
        setLoading(false);
        fetchCommentStats(response.data.id);
        fetchRelatedStories(response.data.id);
      }
    } catch (err) {
      setError(err instanceof Error ? err.message : "Failed to load story");
      console.error("Error fetching story:", err);
    } finally {
      setLoading(false);
    }
  };

  const fetchShareData = async () => {
    try {
      const response = await storiesAPI.getShareData(slug!);
      if (response.success) {
        setShareData(response.data);
      }
    } catch (err) {
      console.error("Error fetching share data:", err);
    }
  };

  const fetchCommentStats = async (storyId: number) => {
    try {
      setCommentStatsLoading(true);
      const response = await commentsAPI.getStats(storyId);
      if (response.data.success) {
        setCommentCount(response.data.data.total_comments || 0);
      }
    } catch (err) {
      console.error("Error fetching comment stats:", err);
      await fetchCommentsCount(storyId);
    } finally {
      setCommentStatsLoading(false);
    }
  };

  // Fallback method to get comment count
  const fetchCommentsCount = async (storyId: number) => {
    try {
      const response = await commentsAPI.getComments(storyId);
      if (response.data.success) {
        setCommentCount(response.data.data.length || 0);
      }
    } catch (err) {
      console.error("Error fetching comments count:", err);
    } finally {
      setCommentStatsLoading(false);
    }
  };

  const fetchRelatedStories = async (storyId: number) => {
    try {
      setRelatedLoading(true);
      const response = await storiesAPI.getRelatedStories(storyId);
      if (response.success) {
        setRelatedStories(response.data);
      }
    } catch (err) {
      console.error("Error fetching related stories:", err);
    } finally {
      setRelatedLoading(false);
    }
  };

  const handleLike = async () => {
    if (!story) return;

    if (!isAuthenticated) {
      toast({
        title: "Login Required",
        description: "Please log in to like stories",
        variant: "destructive",
      });
      navigate('/login', { state: { from: location.pathname } });
      return;
    }

    try {
      const response = await storiesAPI.toggleLike(story.id);

      if (response.success) {
        setIsLiked(response.data.is_liked);
        setLikesCount(response.data.likes_count);

        toast({
          title: response.data.is_liked ? "Liked!" : "Unliked",
          description: response.message,
        });
      }
    } catch (err) {
      toast({
        title: "Error",
        description: err instanceof Error ? err.message : "Failed to like story",
        variant: "destructive",
      });
    }
  };

  const handleSave = () => {
    setIsSaved(!isSaved);
    toast({
      title: isSaved ? "Removed from saved" : "Saved!",
      description: isSaved ? "" : "Story saved to your collection",
    });
  };

  const handleShare = (platform: string) => {
    if (!shareData) {
      toast({
        title: "Loading...",
        description: "Share data is loading, please try again",
        variant: "default",
      });
      return;
    }

    // Use the backend-generated share links
    const shareUrls = shareData.social_links;

    if (platform === "copy") {
      navigator.clipboard.writeText(shareData.share_url);
      setCopied(true);
      toast({
        title: "Link copied!",
        description: "Story link copied to clipboard",
      });
      setTimeout(() => setCopied(false), 2000);
    } else if (platform === "email") {
      window.location.href = shareUrls.email;
    } else if (platform === "facebook") {
      window.open(shareUrls.facebook, '_blank', 'width=600,height=400');
    } else {
      const url = shareUrls[platform as keyof typeof shareUrls];
      if (url) {
        window.open(url, "_blank", "width=600,height=400");
      }
    }

    // Close the share menu after sharing
    setShowShareMenu(false);
  };

  const handleCommentAdded = () => {
    setCommentCount(prev => prev + 1);
    if (story) {
      setTimeout(() => {
        fetchCommentStats(story.id);
      }, 500);
    }
  };

  const handleCommentDeleted = () => {
    setCommentCount(prev => Math.max(0, prev - 1));
    if (story) {
      setTimeout(() => {
        fetchCommentStats(story.id);
      }, 500);
    }
  };

  if (loading) {
    return (
      <div className="min-h-screen bg-background">
        <div className="animate-pulse">
          <div className="h-[300px] sm:h-[400px] md:h-[500px] bg-muted" />
          <div className="container mx-auto px-4 py-10">
            <div className="max-w-4xl mx-auto space-y-4">
              <div className="h-5 w-32 bg-muted rounded" />
              <div className="h-10 w-3/4 bg-muted rounded" />
              <div className="h-4 w-1/2 bg-muted rounded" />
              <div className="h-4 w-full bg-muted rounded" />
              <div className="h-4 w-5/6 bg-muted rounded" />
              <div className="h-4 w-2/3 bg-muted rounded" />
            </div>
          </div>
        </div>
      </div>
    );
  }

  if (error || !story) {
    return (
      <div className="min-h-screen flex items-center justify-center bg-background px-4">
        <div className="text-center max-w-md">
          <h2 className="text-2xl font-bold mb-4">Story Not Found</h2>
          <p className="text-muted-foreground mb-6">{error || "The story you're looking for doesn't exist."}</p>
          <Button onClick={() => navigate("/")} variant="default">
            <ArrowLeft className="mr-2 w-4 h-4" />
            Back to Home
          </Button>
        </div>
      </div>
    );
  }

  return (
    <div className="min-h-screen bg-background">

      <Helmet>
        <title>{story.meta_title || story.title}</title>
        <meta name="description" content={story.meta_description || story.excerpt} />
        <meta name="keywords" content={story.meta_keywords || ''} />

        {/* Open Graph */}
        <meta property="og:title" content={story.meta_title || story.title} />
        <meta property="og:description" content={story.meta_description || story.excerpt} />
        <meta property="og:image" content={story.og_image_url || story.image_url} />
        <meta property="og:url" content={window.location.href} />
        <meta property="og:type" content="article" />
        <meta property="og:site_name" content="Beyond the Finish Line" />

        {/* Article Meta */}
        <meta property="article:published_time" content={story.published_date} />
        <meta property="article:modified_time" content={story.updated_at || story.created_at} />
        <meta property="article:author" content={story.author_name} />
        <meta property="article:section" content={story.category} />

        {/* Twitter */}
        <meta name="twitter:card" content="summary_large_image" />
        <meta name="twitter:title" content={story.meta_title || story.title} />
        <meta name="twitter:description" content={story.meta_description || story.excerpt} />
        <meta name="twitter:image" content={story.og_image_url || story.image_url} />
        <meta name="twitter:creator" content="@beyondfinishline" />

        {/* Canonical */}
        <link rel="canonical" href={window.location.href} />
      </Helmet>

      {/* Header Navigation */}
      <header className="sticky top-0 z-50 bg-background/95 backdrop-blur border-b">
        <div className="container mx-auto px-4 py-4">
          <div className="flex items-center justify-between">
            <Button variant="ghost" onClick={() => navigate("/")} className="gap-2">
              <ArrowLeft className="w-4 h-4" />
              Back to Stories
            </Button>

            <div className="flex items-center gap-2">
              <div className="relative">
                <Button
                  variant="outline"
                  size="sm"
                  onClick={() => setShowShareMenu(!showShareMenu)}
                  className="gap-2"
                >
                  <Share2 className="w-4 h-4" />
                  Share
                </Button>

                {showShareMenu && (
                  <>
                    {/* Backdrop to close menu when clicking outside */}
                    <div
                      className="fixed inset-0 z-40"
                      onClick={() => setShowShareMenu(false)}
                    />

                    {/* Share Menu */}
                    <div className="absolute right-0 mt-2 w-56 bg-card border rounded-lg p-2 shadow-lg z-50">
                      <div className="px-3 py-2 border-b mb-2">
                        <p className="text-xs font-semibold text-muted-foreground uppercase">
                          Share this story
                        </p>
                      </div>

                      <button
                        onClick={() => handleShare("facebook")}
                        className="w-full flex items-center gap-3 px-3 py-2.5 hover:bg-accent rounded text-sm transition-colors"
                      >
                        <Facebook className="w-4 h-4 text-blue-600" />
                        <span>Facebook</span>
                      </button>

                      <button
                        onClick={() => handleShare("twitter")}
                        className="w-full flex items-center gap-3 px-3 py-2.5 hover:bg-accent rounded text-sm transition-colors"
                      >
                        <Twitter className="w-4 h-4 text-sky-500" />
                        <span>Twitter</span>
                      </button>

                      <button
                        onClick={() => handleShare("whatsapp")}
                        className="w-full flex items-center gap-3 px-3 py-2.5 hover:bg-accent rounded text-sm transition-colors"
                      >
                        <svg
                          className="w-4 h-4 text-green-500"
                          fill="currentColor"
                          viewBox="0 0 24 24"
                        >
                          <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413Z" />
                        </svg>
                        <span>WhatsApp</span>
                      </button>

                      <button
                        onClick={() => handleShare("linkedin")}
                        className="w-full flex items-center gap-3 px-3 py-2.5 hover:bg-accent rounded text-sm transition-colors"
                      >
                        <Linkedin className="w-4 h-4 text-blue-700" />
                        <span>LinkedIn</span>
                      </button>

                      <button
                        onClick={() => handleShare("email")}
                        className="w-full flex items-center gap-3 px-3 py-2.5 hover:bg-accent rounded text-sm transition-colors"
                      >
                        <Mail className="w-4 h-4 text-gray-600" />
                        <span>Email</span>
                      </button>

                      <div className="border-t my-2" />

                      <button
                        onClick={() => handleShare("copy")}
                        className="w-full flex items-center gap-3 px-3 py-2.5 hover:bg-accent rounded text-sm transition-colors"
                      >
                        {copied ? (
                          <>
                            <Check className="w-4 h-4 text-green-600" />
                            <span className="text-green-600">Copied!</span>
                          </>
                        ) : (
                          <>
                            <Link2 className="w-4 h-4" />
                            <span>Copy Link</span>
                          </>
                        )}
                      </button>

                      {/* Share URL Display */}
                      {shareData && (
                        <div className="mt-2 pt-2 border-t">
                          <p className="text-xs text-muted-foreground px-3 py-1 break-all">
                            {shareData.share_url}
                          </p>
                        </div>
                      )}
                    </div>
                  </>
                )}
              </div>
            </div>
          </div>
        </div>
      </header>

      {/* Rest of your component remains exactly the same */}
      {/* Hero Section */}
      <div className="relative">
        {/* Image Container */}
        <div className="relative h-[300px] sm:h-[400px] md:h-[500px] bg-muted overflow-hidden">
          <img
            src={story.image_url}
            alt={story.title}
            className="w-full h-full object-cover"
          />
          {/* Overlay - only visible on md+ screens */}
          <div className="hidden md:block absolute inset-0 bg-gradient-to-t from-black/80 via-black/40 to-transparent" />

          {/* Desktop Title - Inside Image (md+ screens) */}
          <div className="hidden md:block absolute bottom-0 left-0 right-0 p-8 lg:p-12">
            <div className="container mx-auto max-w-4xl">
              <Badge className="mb-4 bg-[#D43028] text-white border-[#D43028]">
                {story.category}
              </Badge>
              <h1 className="text-3xl lg:text-5xl font-bold text-white mb-4 leading-tight">
                {story.title}
              </h1>

              <div className="flex flex-wrap items-center gap-4 text-white/90 text-sm">
                <div className="flex items-center gap-2">
                  <User className="w-4 h-4" />
                  <span className="font-medium">{story.author_name}</span>
                </div>
                <div className="flex items-center gap-2">
                  <Calendar className="w-4 h-4" />
                  <span>{story.formatted_date}</span>
                </div>
                <div className="flex items-center gap-2">
                  <Clock className="w-4 h-4" />
                  <span>{story.read_time} min read</span>
                </div>
                <div className="flex items-center gap-2">
                  <Eye className="w-4 h-4" />
                  <span>{viewsCount || '0'} views</span>
                </div>
              </div>
            </div>
          </div>
        </div>

        {story.image_attribution && (
          <div className="bg-white px-4 sm:px-6 py-2 border-b border-gray-100">
            <p className="text-xs italic text-gray-500">{story.image_attribution}</p>
          </div>
        )}

        {/* Mobile Title - Below Image (< md screens) */}
        <div className="block md:hidden bg-white px-4 sm:px-6 py-6">
          <div className="container mx-auto max-w-4xl">
            <Badge className="mb-3 bg-[#D43028] text-white border-[#D43028]">
              {story.category}
            </Badge>
            <h1 className="text-2xl sm:text-3xl font-bold text-gray-900 mb-4 leading-tight">
              {story.title}
            </h1>

            <div className="flex flex-wrap items-center gap-3 text-gray-600 text-xs sm:text-sm">
              <div className="flex items-center gap-1.5">
                <User className="w-3.5 h-3.5 sm:w-4 sm:h-4" />
                <span className="font-medium">{story.author_name}</span>
              </div>
              <div className="flex items-center gap-1.5">
                <Calendar className="w-3.5 h-3.5 sm:w-4 sm:h-4" />
                <span>{story.formatted_date}</span>
              </div>
              <div className="flex items-center gap-1.5">
                <Clock className="w-3.5 h-3.5 sm:w-4 sm:h-4" />
                <span>{story.read_time} min read</span>
              </div>
              <div className="flex items-center gap-1.5">
                <Eye className="w-3.5 h-3.5 sm:w-4 sm:h-4" />
                <span>{viewsCount || '0'} views</span>
              </div>
            </div>
          </div>
        </div>
      </div>

      {/* Main Content */}
      <div className="container mx-auto px-4 py-12">
        <div className="grid lg:grid-cols-12 gap-8 max-w-6xl mx-auto">
          {/* Sidebar - Actions */}
          <aside className="lg:col-span-2 order-2 lg:order-1">
            <div className="lg:sticky lg:top-24 space-y-4">
              <button
                onClick={handleLike}
                className={`flex flex-col items-center gap-1 p-3 rounded-lg border w-full transition-colors ${isLiked
                  ? "bg-red-50 border-red-200 text-red-600"
                  : "hover:bg-accent"
                  }`}
              >
                <Heart className={`w-5 h-5 ${isLiked ? "fill-current" : ""}`} />
                <span className="text-xs font-medium">{likesCount}</span>
              </button>

              <button className="flex flex-col items-center gap-1 p-3 rounded-lg border w-full hover:bg-accent transition-colors">
                <MessageCircle className="w-5 h-5" />
                <span className="text-xs font-medium">
                  {commentStatsLoading ? "..." : commentCount}
                </span>
              </button>

              <button
                onClick={handleSave}
                className={`flex flex-col items-center gap-1 p-3 rounded-lg border w-full transition-colors ${isSaved
                  ? "bg-[#FFBF00]/10 border-[#FFBF00] text-[#FFBF00]"
                  : "hover:bg-accent"
                  }`}
              >
                <BookmarkPlus
                  className={`w-5 h-5 ${isSaved ? "fill-current" : ""}`}
                />
                <span className="text-xs font-medium">Save</span>
              </button>
            </div>
          </aside>

          {/* Article Content */}
          <article className="lg:col-span-7 order-1 lg:order-2">
            {/* Excerpt */}
            <div className="mb-8 p-6 bg-muted/50 border-l-4 border-[#D43028] rounded-r-lg">
              <p className="text-lg text-gray-700 italic leading-relaxed font-serif">
                {story.excerpt}
              </p>
            </div>

            {/* YouTube Video if available */}
            {story.youtube_embed_url && (
              <div className="mb-8">
                <div className="aspect-video rounded-lg overflow-hidden shadow-lg">
                  <iframe
                    src={story.youtube_embed_url}
                    title={story.title}
                    allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture"
                    allowFullScreen
                    className="w-full h-full"
                  />
                </div>
              </div>
            )}

            {/* Main Content with Rich Text Styling */}
            <div className="story-content">
              <div
                dangerouslySetInnerHTML={{ __html: story.content }}
              />
            </div>

            {/* Tags/Category */}
            <div className="mt-12 pt-8 border-t">
              <h3 className="text-sm font-semibold text-muted-foreground mb-3">
                CATEGORY
              </h3>
              <Badge className="bg-primary/10 text-primary border-primary/20">
                {story.category}
              </Badge>
            </div>

            {/* Comments Section - Now using the component */}
            <CommentSection
              storyId={story.id}
              commentsCount={commentCount}
              onCommentAdded={handleCommentAdded}
              onCommentDeleted={handleCommentDeleted}
            />
          </article>

          {/* Sidebar - Related Stories */}
          <aside className="lg:col-span-3 order-3">
            <div className="lg:sticky lg:top-24">
              <h3 className="text-xl font-bold mb-6 text-gray-900">Related Stories</h3>
              <div className="space-y-4">
                {relatedLoading ? (
                  <div className="space-y-4">
                    {[...Array(3)].map((_, index) => (
                      <div key={index} className="border rounded-lg overflow-hidden animate-pulse">
                        <div className="h-32 bg-muted" />
                        <div className="p-3 space-y-2">
                          <div className="h-4 bg-muted rounded" />
                          <div className="h-3 w-1/2 bg-muted rounded" />
                        </div>
                      </div>
                    ))}
                  </div>
                ) : relatedStories.length > 0 ? (
                  relatedStories.map((related) => (
                    <Link
                      key={related.id}
                      to={`/stories/${related.slug}`}
                      className="block group"
                    >
                      <div className="border rounded-lg overflow-hidden hover:border-primary transition-colors">
                        <img
                          src={related.image_url}
                          alt={related.title}
                          className="w-full h-32 object-cover"
                        />
                        <div className="p-3">
                          <h4 className="font-semibold text-sm line-clamp-2 group-hover:text-primary transition-colors text-gray-900">
                            {related.title}
                          </h4>
                          <p className="text-xs text-muted-foreground mt-1">
                            {related.read_time} min read
                          </p>
                        </div>
                      </div>
                    </Link>
                  ))
                ) : (
                  <p className="text-sm text-muted-foreground">No related stories found</p>
                )}
              </div>

              {/* Hashtags Section */}
              {story.hashtags_array && story.hashtags_array.length > 0 && (
                <div className="mt-8 pt-8 border-t overflow-hidden">
                  <h3 className="text-sm font-semibold text-muted-foreground mb-3">
                    HASHTAGS
                  </h3>
                  <div className="flex flex-wrap gap-2 max-w-full">
                    {story.hashtags_array.map((tag, index) => (
                      <span
                        key={index}
                        className="inline-flex items-center px-4 py-2 rounded-full text-sm font-medium  shadow-sm hover:shadow-md transition-shadow break-all"
                        style={{ maxWidth: '100%' }}
                        title={tag.length > 30 ? tag : undefined}
                      >
                        {tag.length > 30 ? `${tag.substring(0, 60)}...` : tag}
                      </span>
                    ))}
                  </div>
                </div>
              )}
            </div>
          </aside>
        </div>
      </div>
    </div>
  );
};

export default StoryDetail;
