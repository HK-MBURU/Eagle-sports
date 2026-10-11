import React from 'react';
import { useStories } from '@/contexts/StoriesContext';
import { Link } from 'react-router-dom';
import { Clock, User, ArrowRight, Loader2, Heart, Star, Award } from 'lucide-react';

const RedemptionStories: React.FC = () => {
  const { latestStories, latestLoading } = useStories();

  // Filter stories related to redemption and second chances
  const redemptionStories = latestStories.filter(story => 
    story.category?.toLowerCase().includes('redemption') || 
    story.category?.toLowerCase().includes('comeback') ||
    story.title?.toLowerCase().includes('redemption') ||
    story.title?.toLowerCase().includes('comeback') ||
    story.title?.toLowerCase().includes('second chance') ||
    story.excerpt?.toLowerCase().includes('redemption') ||
    story.excerpt?.toLowerCase().includes('comeback') ||
    story.excerpt?.toLowerCase().includes('second chance')
  );

  if (latestLoading) {
    return (
      <section className="min-h-screen bg-gray-50 pt-20">
        <div className="container mx-auto px-4 py-16">
          <div className="flex justify-center items-center min-h-[400px]">
            <Loader2 className="w-10 h-10 animate-spin text-gray-400" />
          </div>
        </div>
      </section>
    );
  }

  return (
    <section className="min-h-screen bg-white pt-20">
      <div className="container mx-auto px-4 py-16 max-w-6xl">
        {/* Header */}
        <div className="text-center mb-12">
          <div className="inline-block mb-4">
            <span className="text-xs font-semibold text-gray-600 uppercase tracking-wider bg-gray-100 px-3 py-1 rounded">
              Integrity & Fair Play
            </span>
          </div>
          <h1 className="text-4xl md:text-5xl font-bold mb-4 text-gray-900">
            Redemption Stories
          </h1>
          <p className="text-lg text-gray-600 max-w-2xl mx-auto">
            Inspiring journeys of athletes who turned setbacks into comebacks and found redemption through perseverance
          </p>
        </div>

        {/* Hero Message */}
        <div className="bg-primary/5 rounded-xl p-8 mb-12 border border-primary/20">
          <div className="text-center max-w-3xl mx-auto">
            <Heart className="w-12 h-12 text-accent-orange mx-auto mb-4" />
            <h2 className="text-2xl font-bold text-gray-900 mb-4">Second Chances Done Right</h2>
            <p className="text-gray-700 leading-relaxed">
              Every athlete deserves a path to redemption. These stories showcase the power of resilience, 
              personal growth, and the transformative journey from adversity to triumph in the world of athletics.
            </p>
          </div>
        </div>

        {/* Stories Grid */}
        {redemptionStories.length > 0 ? (
          <div className="grid md:grid-cols-2 lg:grid-cols-3 gap-8 mb-12">
            {redemptionStories.map((story) => (
              <Link to={`/stories/${story.slug}`} key={story.id}>
                <article className="border border-gray-200 rounded-lg overflow-hidden bg-white h-full hover:shadow-lg transition-all duration-300 group">
                  <div className="relative h-48 overflow-hidden bg-gray-100">
                    <img
                      src={story.image_url}
                      alt={story.title}
                      className="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300"
                    />
                    <div className="absolute top-3 left-3">
                      <span className="inline-block bg-green-600 text-white text-xs font-semibold px-2.5 py-1 rounded">
                        Redemption Story
                      </span>
                    </div>
                  </div>
                  {story.image_attribution && (
                    <p className="px-6 pt-2 text-[11px] italic text-gray-500">
                      {story.image_attribution}
                    </p>
                  )}
                  <div className={`p-6 ${story.image_attribution ? "pt-3" : ""}`}>
                    <h3 className="text-lg font-bold mb-3 text-gray-900 line-clamp-2 group-hover:text-green-700 transition-colors">
                      {story.title}
                    </h3>
                    <p className="text-gray-600 mb-4 line-clamp-3 text-sm leading-relaxed">
                      {story.excerpt}
                    </p>
                    <div className="flex items-center justify-between text-xs text-gray-500 pt-3 border-t border-gray-100">
                      <div className="flex items-center gap-1.5">
                        <Clock className="w-3.5 h-3.5" />
                        <span>{story.read_time} min</span>
                      </div>
                      <span className="text-green-600 font-semibold inline-flex items-center gap-1 group-hover:gap-2 transition-all">
                        Read Journey
                        <ArrowRight className="w-3.5 h-3.5" />
                      </span>
                    </div>
                  </div>
                </article>
              </Link>
            ))}
          </div>
        ) : (
          <div className="text-center py-16 bg-gray-50 rounded-lg border border-gray-200 mb-12">
            <Star className="w-16 h-16 text-gray-300 mx-auto mb-4" />
            <p className="text-gray-500 text-lg mb-2">No redemption stories available yet</p>
            <p className="text-gray-400 text-sm">Inspiring comeback stories will be featured here soon.</p>
          </div>
        )}

        {/* Values Section */}
        <div className="grid md:grid-cols-3 gap-6">
          <div className="text-center p-6 bg-yellow-50 rounded-lg border border-yellow-200">
            <Award className="w-8 h-8 text-yellow-600 mx-auto mb-3" />
            <h3 className="font-semibold text-gray-900 mb-2">Restoration</h3>
            <p className="text-sm text-gray-600">Rebuilding careers and reputations through dedication</p>
          </div>
          <div className="text-center p-6 bg-blue-50 rounded-lg border border-blue-200">
            <Heart className="w-8 h-8 text-blue-600 mx-auto mb-3" />
            <h3 className="font-semibold text-gray-900 mb-2">Growth</h3>
            <p className="text-sm text-gray-600">Personal development and learning from past experiences</p>
          </div>
          <div className="text-center p-6 bg-green-50 rounded-lg border border-green-200">
            <Star className="w-8 h-8 text-green-600 mx-auto mb-3" />
            <h3 className="font-semibold text-gray-900 mb-2">Triumph</h3>
            <p className="text-sm text-gray-600">Achieving success after overcoming significant challenges</p>
          </div>
        </div>
      </div>
    </section>
  );
};

export default RedemptionStories;
