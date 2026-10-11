import React from 'react';
import { useStories } from '@/contexts/StoriesContext';
import { Link } from 'react-router-dom';
import { Clock, User, ArrowRight, Loader2, Star, Trophy, Users } from 'lucide-react';

const KidsAthletics: React.FC = () => {
  const { latestStories, latestLoading } = useStories();

  // Filter stories related to kids athletics
  const kidsStories = latestStories.filter(story => 
    story.category?.toLowerCase().includes('kids') || 
    story.category?.toLowerCase().includes('children') ||
    story.category?.toLowerCase().includes('youth') ||
    story.title?.toLowerCase().includes('kids') ||
    story.title?.toLowerCase().includes('children') ||
    story.title?.toLowerCase().includes('youth') ||
    story.excerpt?.toLowerCase().includes('kids') ||
    story.excerpt?.toLowerCase().includes('children') ||
    story.excerpt?.toLowerCase().includes('youth')
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
            Kids Athletics
          </h1>
          <p className="text-lg text-gray-600 max-w-2xl mx-auto">
            Nurturing the next generation of athletes through fun, development, and early talent identification
          </p>
        </div>

        {/* Features */}
        <div className="grid md:grid-cols-3 gap-6 mb-12">
          <div className="text-center p-6 bg-yellow-50 rounded-lg border border-yellow-200">
            <Star className="w-8 h-8 text-yellow-600 mx-auto mb-3" />
            <h3 className="font-semibold text-gray-900 mb-2">Early Development</h3>
            <p className="text-sm text-gray-600">Building fundamental skills and love for the sport</p>
          </div>
          <div className="text-center p-6 bg-blue-50 rounded-lg border border-blue-200">
            <Trophy className="w-8 h-8 text-blue-600 mx-auto mb-3" />
            <h3 className="font-semibold text-gray-900 mb-2">Fun Competitions</h3>
            <p className="text-sm text-gray-600">Age-appropriate events focusing on participation</p>
          </div>
          <div className="text-center p-6 bg-green-50 rounded-lg border border-green-200">
            <Users className="w-8 h-8 text-green-600 mx-auto mb-3" />
            <h3 className="font-semibold text-gray-900 mb-2">Community Building</h3>
            <p className="text-sm text-gray-600">Creating friendships and team spirit from early ages</p>
          </div>
        </div>

        {/* Stories Grid */}
        <div className="mb-12">
          <h2 className="text-2xl font-bold text-gray-900 mb-6">Young Talent Stories</h2>
          {kidsStories.length > 0 ? (
            <div className="grid md:grid-cols-2 lg:grid-cols-3 gap-6">
              {kidsStories.map((story) => (
                <Link to={`/stories/${story.slug}`} key={story.id}>
                  <article className="border border-gray-200 rounded-lg overflow-hidden bg-white h-full hover:shadow-md transition-shadow">
                    <div className="relative h-48 overflow-hidden bg-gray-100">
                      <img
                        src={story.image_url}
                        alt={story.title}
                        className="w-full h-full object-cover"
                      />
                      <div className="absolute top-3 left-3">
                        <span className="inline-block bg-yellow-500 text-white text-xs font-semibold px-2.5 py-1 rounded">
                          Young Talent
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
              <p className="text-gray-500 text-lg mb-4">No kids athletics stories available</p>
              <p className="text-gray-400 text-sm">Stories about young rising stars will be featured here soon.</p>
            </div>
          )}
        </div>

        {/* Programs Info */}
        <div className="bg-gray-50 rounded-lg p-8 border border-gray-200">
          <h2 className="text-2xl font-bold text-gray-900 mb-6">Kids Athletics Programs</h2>
          <div className="grid md:grid-cols-2 gap-8">
            <div>
              <h3 className="font-semibold text-gray-900 mb-3">Ages 6-12</h3>
              <ul className="text-sm text-gray-600 space-y-2">
                <li>• Fundamental movement skills</li>
                <li>• Introduction to multiple sports</li>
                <li>• Fun-based learning approach</li>
                <li>• School partnership programs</li>
                <li>• Weekend development clinics</li>
              </ul>
            </div>
            <div>
              <h3 className="font-semibold text-gray-900 mb-3">Benefits</h3>
              <ul className="text-sm text-gray-600 space-y-2">
                <li>• Physical literacy development</li>
                <li>• Social skills and teamwork</li>
                <li>• Confidence building</li>
                <li>• Healthy lifestyle habits</li>
                <li>• Talent identification pathways</li>
              </ul>
            </div>
          </div>
        </div>
      </div>
    </section>
  );
};

export default KidsAthletics;
