import React from 'react';
import { useStories } from '@/contexts/StoriesContext';
import { Link } from 'react-router-dom';
import { Clock, User, ArrowRight, Loader2, Target, Award, TrendingUp } from 'lucide-react';

const JuniorAthletics: React.FC = () => {
  const { latestStories, latestLoading } = useStories();

  // Filter stories related to junior athletics
  const juniorStories = latestStories.filter(story => 
    story.category?.toLowerCase().includes('junior') || 
    story.category?.toLowerCase().includes('youth') ||
    story.title?.toLowerCase().includes('junior') ||
    story.title?.toLowerCase().includes('youth') ||
    story.title?.toLowerCase().includes('teen') ||
    story.excerpt?.toLowerCase().includes('junior') ||
    story.excerpt?.toLowerCase().includes('youth') ||
    story.excerpt?.toLowerCase().includes('teen')
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
              Rising Stars
            </span>
          </div>
          <h1 className="text-4xl md:text-5xl font-bold mb-4 text-gray-900">
            Junior Athletics
          </h1>
          <p className="text-lg text-gray-600 max-w-2xl mx-auto">
            Developing competitive young athletes through structured training, mentorship, and national competitions
          </p>
        </div>

        {/* Performance Metrics */}
        <div className="grid md:grid-cols-4 gap-4 mb-12">
          <div className="text-center p-4 bg-blue-50 rounded-lg border border-blue-200">
            <div className="text-2xl font-bold text-blue-600 mb-1">500+</div>
            <div className="text-sm text-gray-600">Active Juniors</div>
          </div>
          <div className="text-center p-4 bg-green-50 rounded-lg border border-green-200">
            <div className="text-2xl font-bold text-green-600 mb-1">25</div>
            <div className="text-sm text-gray-600">National Events</div>
          </div>
          <div className="text-center p-4 bg-purple-50 rounded-lg border border-purple-200">
            <div className="text-2xl font-bold text-purple-600 mb-1">15</div>
            <div className="text-sm text-gray-600">Training Centers</div>
          </div>
          <div className="text-center p-4 bg-orange-50 rounded-lg border border-orange-200">
            <div className="text-2xl font-bold text-orange-600 mb-1">50+</div>
            <div className="text-sm text-gray-600">Qualified Coaches</div>
          </div>
        </div>

        {/* Stories Grid */}
        <div className="mb-12">
          <h2 className="text-2xl font-bold text-gray-900 mb-6">Junior Athlete Spotlights</h2>
          {juniorStories.length > 0 ? (
            <div className="grid md:grid-cols-2 lg:grid-cols-3 gap-6">
              {juniorStories.map((story) => (
                <Link to={`/stories/${story.slug}`} key={story.id}>
                  <article className="border border-gray-200 rounded-lg overflow-hidden bg-white h-full hover:shadow-md transition-shadow">
                    <div className="relative h-48 overflow-hidden bg-gray-100">
                      <img
                        src={story.image_url}
                        alt={story.title}
                        className="w-full h-full object-cover"
                      />
                      <div className="absolute top-3 left-3">
                        <span className="inline-block bg-blue-500 text-white text-xs font-semibold px-2.5 py-1 rounded">
                          Junior Star
                        </span>
                      </div>
                    </div>
                    {story.image_attribution && (
                      <p className="px-5 pt-2 text-[11px] italic text-gray-500">
                        {story.image_attribution}
                      </p>
                    )}
                    <div className={`p-5 ${story.image_attribution ? "pt-3" : ""}`}>
                      <h3 className="text-lg font-bold mb-2 text-gray-900 line-clamp-2">
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
                        <span className="text-gray-700 font-semibold inline-flex items-center gap-1">
                          Read Profile
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
              <p className="text-gray-500 text-lg mb-4">No junior athletics stories available</p>
              <p className="text-gray-400 text-sm">Profiles of promising junior athletes coming soon.</p>
            </div>
          )}
        </div>

        {/* Development Pathway */}
        <div className="bg-gray-50 rounded-lg p-8 border border-gray-200">
          <h2 className="text-2xl font-bold text-gray-900 mb-6">Junior Development Pathway</h2>
          <div className="grid md:grid-cols-3 gap-6">
            <div className="text-center">
              <Target className="w-8 h-8 text-blue-600 mx-auto mb-3" />
              <h3 className="font-semibold text-gray-900 mb-2">Skill Development</h3>
              <p className="text-sm text-gray-600">Technical training and competition experience</p>
            </div>
            <div className="text-center">
              <Award className="w-8 h-8 text-green-600 mx-auto mb-3" />
              <h3 className="font-semibold text-gray-900 mb-2">Competition</h3>
              <p className="text-sm text-gray-600">National and regional championship events</p>
            </div>
            <div className="text-center">
              <TrendingUp className="w-8 h-8 text-purple-600 mx-auto mb-3" />
              <h3 className="font-semibold text-gray-900 mb-2">Progression</h3>
              <p className="text-sm text-gray-600">Pathways to senior and elite level competition</p>
            </div>
          </div>
        </div>
      </div>
    </section>
  );
};

export default JuniorAthletics;
