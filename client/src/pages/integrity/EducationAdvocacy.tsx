import React from 'react';
import { useStories } from '@/contexts/StoriesContext';
import { Link } from 'react-router-dom';
import { Clock, User, ArrowRight, Loader2, Book, Users, Shield } from 'lucide-react';

const EducationAdvocacy: React.FC = () => {
  const { latestStories, latestLoading } = useStories();

  // Filter stories related to education and advocacy
  const educationStories = latestStories.filter(story => 
    story.category?.toLowerCase().includes('education') || 
    story.category?.toLowerCase().includes('advocacy') ||
    story.title?.toLowerCase().includes('education') ||
    story.title?.toLowerCase().includes('advocacy') ||
    story.excerpt?.toLowerCase().includes('education') ||
    story.excerpt?.toLowerCase().includes('advocacy')
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
            Education & Advocacy
          </h1>
          <p className="text-lg text-gray-600 max-w-2xl mx-auto">
            Promoting ethics, clean sport, and fair competition through education and awareness
          </p>
        </div>

        {/* Features */}
        <div className="grid md:grid-cols-3 gap-6 mb-12">
          <div className="text-center p-6 bg-blue-50 rounded-lg border border-blue-200">
            <Book className="w-8 h-8 text-blue-600 mx-auto mb-3" />
            <h3 className="font-semibold text-gray-900 mb-2">Educational Programs</h3>
            <p className="text-sm text-gray-600">Comprehensive anti-doping and ethics education for athletes at all levels</p>
          </div>
          <div className="text-center p-6 bg-green-50 rounded-lg border border-green-200">
            <Users className="w-8 h-8 text-green-600 mx-auto mb-3" />
            <h3 className="font-semibold text-gray-900 mb-2">Community Outreach</h3>
            <p className="text-sm text-gray-600">Engaging with schools, clubs, and communities to promote fair play</p>
          </div>
          <div className="text-center p-6 bg-purple-50 rounded-lg border border-purple-200">
            <Shield className="w-8 h-8 text-purple-600 mx-auto mb-3" />
            <h3 className="font-semibold text-gray-900 mb-2">Advocacy Work</h3>
            <p className="text-sm text-gray-600">Championing policy changes and reforms for cleaner sport</p>
          </div>
        </div>

        {/* Stories Grid */}
        <div className="mb-12">
          <h2 className="text-2xl font-bold text-gray-900 mb-6">Latest Educational Content</h2>
          {educationStories.length > 0 ? (
            <div className="grid md:grid-cols-2 lg:grid-cols-3 gap-6">
              {educationStories.map((story) => (
                <Link to={`/stories/${story.slug}`} key={story.id}>
                  <article className="border border-gray-200 rounded-lg overflow-hidden bg-white h-full hover:shadow-md transition-shadow">
                    <div className="relative h-48 overflow-hidden bg-gray-100">
                      <img
                        src={story.image_url}
                        alt={story.title}
                        className="w-full h-full object-cover"
                      />
                    </div>
                    {story.image_attribution && (
                      <p className="px-5 pt-2 text-xs italic text-gray-500">
                        {story.image_attribution}
                      </p>
                    )}
                    <div className="p-5">
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
              <p className="text-gray-500 text-lg mb-4">No educational content available</p>
              <p className="text-gray-400 text-sm">Educational resources and advocacy updates coming soon.</p>
            </div>
          )}
        </div>

        {/* Resources Section */}
        <div className="bg-gray-50 rounded-lg p-8 border border-gray-200">
          <h2 className="text-2xl font-bold text-gray-900 mb-4">Educational Resources</h2>
          <div className="grid md:grid-cols-2 gap-6">
            <div>
              <h3 className="font-semibold text-gray-900 mb-3">For Athletes</h3>
              <ul className="text-sm text-gray-600 space-y-2">
                <li>• Anti-doping guide</li>
                <li>• Code of conduct</li>
                <li>• Rights & responsibilities</li>
                <li>• Whistleblower protection</li>
              </ul>
            </div>
            <div>
              <h3 className="font-semibold text-gray-900 mb-3">For Coaches & Officials</h3>
              <ul className="text-sm text-gray-600 space-y-2">
                <li>• Ethics training materials</li>
                <li>• Fair play guidelines</li>
                <li>• Case studies</li>
                <li>• Best practices</li>
              </ul>
            </div>
          </div>
        </div>
      </div>
    </section>
  );
};

export default EducationAdvocacy;
