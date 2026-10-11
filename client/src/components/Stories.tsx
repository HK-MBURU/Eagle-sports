import { useState, useEffect } from "react";
import { Clock, User, ArrowRight, Loader2 } from "lucide-react";
import { Button } from "@/components/ui/button";
import { storiesAPI } from "@/services/api";
import type { Story } from "@/types/story";
import { Link, useNavigate } from "react-router-dom";

interface HomepageStoriesResponse {
  success: boolean;
  data: {
    featured: Story | null;
    regular: Story[];
    total_stories: number;
  };
}

const Stories = () => {
  const navigate = useNavigate();
  const [homepageStories, setHomepageStories] = useState<{
    featured: Story | null;
    regular: Story[];
  } | null>(null);
  const [loading, setLoading] = useState(true);
  const [error, setError] = useState<string | null>(null);

  // Fetch optimized homepage stories
  useEffect(() => {
    const fetchHomepageStories = async () => {
      try {
        setLoading(true);
        setError(null);

        const response: HomepageStoriesResponse = await storiesAPI.getHomepageStories();

        if (response.success) {
          setHomepageStories(response.data);
        }
      } catch (err) {
        setError(err instanceof Error ? err.message : 'Failed to load stories');
        console.error('Error fetching homepage stories:', err);
      } finally {
        setLoading(false);
      }
    };

    fetchHomepageStories();
  }, []);

  // Loading state with skeleton
  if (loading) {
    return (
      <section id="stories" className="py-16 bg-gray-50">
        <div className="container mx-auto px-4 max-w-7xl">
          {/* Skeleton Header */}
          <div className="mb-4">
            <div className="inline-block mb-4">
              <div className="h-6 w-32 bg-gray-200 rounded animate-pulse"></div>
            </div>
            <div className="h-8 w-64 bg-gray-200 rounded animate-pulse mb-3"></div>
            <div className="h-4 w-96 bg-gray-200 rounded animate-pulse"></div>
          </div>

          {/* Skeleton Featured Story */}
          <div className="mb-16">
            <div className="border border-gray-200 rounded-lg overflow-hidden bg-white">
              <div className="grid md:grid-cols-5 gap-0">
                <div className="relative md:col-span-2 h-[280px] md:h-[400px] bg-gray-200 animate-pulse"></div>
                <div className="md:col-span-3 p-6 md:p-8 lg:p-10">
                  <div className="space-y-4">
                    <div className="h-6 w-3/4 bg-gray-200 rounded animate-pulse"></div>
                    <div className="h-4 w-full bg-gray-200 rounded animate-pulse"></div>
                    <div className="h-4 w-2/3 bg-gray-200 rounded animate-pulse"></div>
                  </div>
                </div>
              </div>
            </div>
          </div>

          {/* Skeleton Grid */}
          <div className="grid md:grid-cols-2 lg:grid-cols-3 gap-6 mb-12">
            {[...Array(3)].map((_, i) => (
              <div key={i} className="border border-gray-200 rounded-lg overflow-hidden bg-white h-full">
                <div className="h-48 bg-gray-200 animate-pulse"></div>
                <div className="p-5">
                  <div className="h-5 w-full bg-gray-200 rounded animate-pulse mb-2"></div>
                  <div className="h-4 w-3/4 bg-gray-200 rounded animate-pulse mb-4"></div>
                  <div className="flex items-center justify-between">
                    <div className="h-3 w-16 bg-gray-200 rounded animate-pulse"></div>
                    <div className="h-3 w-20 bg-gray-200 rounded animate-pulse"></div>
                  </div>
                </div>
              </div>
            ))}
          </div>
        </div>
      </section>
    );
  }

  // Error state
  if (error) {
    return (
      <section id="stories" className="py-16 bg-gray-50">
        <div className="container mx-auto px-4">
          <div className="text-center py-12">
            <p className="text-red-600 mb-4 text-sm">{error}</p>
            <Button onClick={() => window.location.reload()} className="bg-gray-900 text-white">
              Try Again
            </Button>
          </div>
        </div>
      </section>
    );
  }

  const { featured, regular } = homepageStories || {};

  return (
    <section id="stories" className="py-16 bg-white">
      <div className="container mx-auto px-4 max-w-7xl">
        {/* Header */}
        <div className="mb-4">
          <div className="inline-block">
            <span className="text-xs font-semibold text-[#FF6600] uppercase tracking-wider bg-orange-50 px-3 py-1 rounded">
              Beyond The Track
            </span>
          </div>
          <h2 className="text-3xl md:text-4xl font-bold mb-3 text-gray-900">
            Stories That Inspire
          </h2>
          <p className="text-base text-gray-600 max-w-2xl">
            Real journeys of athletes who transformed their victories into lasting impact
          </p>
        </div>

        {/* Featured Story */}
        {featured && (
          <div className="mb-16">
            <Link to={`/stories/${featured.slug}`}>
              <article className="border border-gray-200 rounded-lg overflow-hidden bg-white hover:shadow-lg transition-shadow duration-300">
                <div className="grid md:grid-cols-5 gap-0">
                <div className="md:col-span-2">
                  <div className="relative h-[280px] md:h-[400px] overflow-hidden bg-gray-100">
                    <img
                      src={featured.image_url}
                      alt={featured.title}
                      className="w-full h-full object-cover hover:scale-105 transition-transform duration-300"
                      loading="lazy"
                    />
                    <div className="absolute top-4 left-4">
                      <span className="inline-block bg-[#FF6600] text-white text-xs font-semibold px-3 py-1.5 rounded">
                        {featured.category}
                      </span>
                    </div>
                  </div>
                  {featured.image_attribution && (
                    <p className="px-4 py-2 text-xs italic text-gray-500">
                      {featured.image_attribution}
                    </p>
                  )}
                </div>

                  <div className="md:col-span-3 p-6 md:p-8 lg:p-10 flex flex-col justify-center">
                    <div className="space-y-4">
                      <h3 className="text-xl md:text-2xl lg:text-3xl font-bold leading-tight text-gray-900">
                        {featured.title}
                      </h3>

                      <p className="text-gray-600 text-sm md:text-base leading-relaxed line-clamp-3">
                        {featured.excerpt}
                      </p>

                      <div className="flex flex-wrap items-center gap-x-6 gap-y-2 text-xs text-gray-500 pt-3 border-t border-gray-100">
                        <div className="flex items-center gap-1.5">
                          <User className="w-3.5 h-3.5" />
                          <span>{featured.author_name}</span>
                        </div>
                        <div className="flex items-center gap-1.5">
                          <Clock className="w-3.5 h-3.5" />
                          <span>{featured.read_time} min read</span>
                        </div>
                        <span>{featured.formatted_date}</span>
                      </div>

                      <div className="pt-2">
                        <span className="inline-flex items-center gap-2 text-[#FF6600] text-sm font-semibold group">
                          Read Full Story
                          <ArrowRight className="w-4 h-4 group-hover:translate-x-1 transition-transform" />
                        </span>
                      </div>
                    </div>
                  </div>
                </div>
              </article>
            </Link>
          </div>
        )}

        {/* Story Grid */}
        {regular && regular.length > 0 ? (
          <div className="grid md:grid-cols-2 lg:grid-cols-3 gap-6 mb-12">
            {regular.map((story) => (
              <Link to={`/stories/${story.slug}`} key={story.id}>
                <article className="border border-gray-200 rounded-lg overflow-hidden bg-white h-full hover:shadow-md transition-shadow duration-300 group">
                  <div className="relative h-48 overflow-hidden bg-gray-100">
                    <img
                      src={story.image_url}
                      alt={story.title}
                      className="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300"
                      loading="lazy"
                    />
                    <span className="absolute top-3 left-3 inline-block bg-[#FF6600] text-white text-xs font-semibold px-2.5 py-1 rounded">
                      {story.category}
                    </span>
                  </div>
                  {story.image_attribution && (
                    <p className="px-5 pt-2 text-[11px] italic text-gray-500">
                      {story.image_attribution}
                    </p>
                  )}

                  <div className={`p-5 ${story.image_attribution ? "pt-3" : ""}`}>
                    <h3 className="text-lg font-bold mb-2 text-gray-900 line-clamp-2 min-h-[3.5rem] group-hover:text-[#FF6600] transition-colors">
                      {story.title}
                    </h3>
                    <p className="text-gray-600 mb-4 line-clamp-3 text-sm leading-relaxed min-h-[4rem]">
                      {story.excerpt}
                    </p>

                    <div className="flex items-center justify-between text-xs text-gray-500 pt-3 border-t border-gray-100">
                      <div className="flex items-center gap-1.5">
                        <Clock className="w-3.5 h-3.5" />
                        <span>{story.read_time} min</span>
                      </div>
                      <span className="text-[#FF6600] font-semibold inline-flex items-center gap-1 group-hover:gap-2 transition-all">
                        Read More
                        <ArrowRight className="w-3.5 h-3.5" />
                      </span>
                    </div>
                  </div>
                </article>
              </Link>
            ))}
          </div>
        ) : (
          <div className="text-center py-12 bg-gray-50 rounded-lg border border-gray-200">
            <p className="text-gray-500 text-sm">No stories found.</p>
          </div>
        )}

        {/* Explore More Button */}
        <div className="flex justify-center">
          <Button
            onClick={() => navigate('/stories/inspire')}
            className="bg-[#FF6600] text-white font-semibold px-4 py-2 text-base rounded-sm hover:bg-[#E55A00] transition-colors"
          >
            Explore More Inspiring Stories
            <ArrowRight className="ml-2 h-5 w-5" />
          </Button>
        </div>
      </div>
    </section>
  );
};

export default Stories;
