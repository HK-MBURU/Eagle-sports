import React from 'react';
import { useStories } from '@/contexts/StoriesContext';
import { Link } from 'react-router-dom';
import { Clock, User, ArrowRight, Loader2 } from 'lucide-react';

const SanctionsBans: React.FC = () => {
  const { latestStories, latestLoading } = useStories();

  // Filter stories related to sanctions and bans
  const sanctionsStories = latestStories.filter(story => 
    story.category?.toLowerCase().includes('sanction') || 
    story.category?.toLowerCase().includes('ban') ||
    story.title?.toLowerCase().includes('sanction') ||
    story.title?.toLowerCase().includes('ban') ||
    story.excerpt?.toLowerCase().includes('sanction') ||
    story.excerpt?.toLowerCase().includes('ban')
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
            Sanctions & Bans
          </h1>
          <p className="text-lg text-gray-600 max-w-2xl mx-auto">
            Tracking violations, disciplinary actions, and important lessons in athletics integrity
          </p>
        </div>

        {/* Content */}
        <div className="grid lg:grid-cols-3 gap-8">
          {/* Main Content */}
          <div className="lg:col-span-2">
            {sanctionsStories.length > 0 ? (
              <div className="space-y-6">
                {sanctionsStories.map((story) => (
                  <article key={story.id} className="border border-gray-200 rounded-lg overflow-hidden bg-white">
                    <Link to={`/stories/${story.slug}`}>
                      <div className="grid md:grid-cols-3 gap-4 p-6">
                        <div className="md:col-span-1">
                          <div className="relative h-48 rounded-lg overflow-hidden bg-gray-100">
                            <img
                              src={story.image_url}
                              alt={story.title}
                              className="w-full h-full object-cover"
                            />
                          </div>
                          {story.image_attribution && (
                            <p className="pt-2 text-xs italic text-gray-500">
                              {story.image_attribution}
                            </p>
                          )}
                        </div>
                        <div className="md:col-span-2">
                          <div className="space-y-3">
                            <div className="flex items-center gap-2">
                              <span className="inline-block bg-red-100 text-red-800 text-xs font-semibold px-2.5 py-1 rounded">
                                {story.category || 'Sanction'}
                              </span>
                            </div>
                            <h3 className="text-xl font-bold text-gray-900 leading-tight">
                              {story.title}
                            </h3>
                            <p className="text-gray-600 text-sm leading-relaxed line-clamp-2">
                              {story.excerpt}
                            </p>
                            <div className="flex flex-wrap items-center gap-x-4 gap-y-2 text-xs text-gray-500 pt-2">
                              <div className="flex items-center gap-1.5">
                                <User className="w-3.5 h-3.5" />
                                <span>{story.author_name}</span>
                              </div>
                              <div className="flex items-center gap-1.5">
                                <Clock className="w-3.5 h-3.5" />
                                <span>{story.read_time} min read</span>
                              </div>
                              <span>{story.formatted_date}</span>
                            </div>
                            <div className="pt-1">
                              <span className="inline-flex items-center gap-2 text-gray-700 text-sm font-semibold">
                                Read Full Report
                                <ArrowRight className="w-4 h-4" />
                              </span>
                            </div>
                          </div>
                        </div>
                      </div>
                    </Link>
                  </article>
                ))}
              </div>
            ) : (
              <div className="text-center py-12 bg-gray-50 rounded-lg border border-gray-200">
                <p className="text-gray-500 text-lg mb-4">No sanctions-related stories found</p>
                <p className="text-gray-400 text-sm">Check back later for updates on disciplinary actions and integrity cases.</p>
              </div>
            )}
          </div>

          {/* Sidebar */}
          <div className="space-y-6">
            <div className="bg-gray-50 rounded-lg p-6 border border-gray-200">
              <h3 className="font-semibold text-gray-900 mb-3">About This Section</h3>
              <p className="text-sm text-gray-600 mb-4">
                This section tracks disciplinary actions, sanctions, and bans in athletics to promote transparency and fair play.
              </p>
              <ul className="text-sm text-gray-600 space-y-2">
                <li>• Anti-doping violations</li>
                <li>• Code of conduct breaches</li>
                <li>• Competition manipulation cases</li>
                <li>• Eligibility violations</li>
              </ul>
            </div>

            <div className="bg-blue-50 rounded-lg p-6 border border-blue-200">
              <h3 className="font-semibold text-blue-900 mb-2">Report Integrity Issues</h3>
              <p className="text-sm text-blue-700 mb-3">
                Concerned about integrity in athletics?
              </p>
              <button className="w-full bg-blue-600 text-white py-2 px-4 rounded text-sm font-medium hover:bg-blue-700 transition-colors">
                Report Anonymously
              </button>
            </div>
          </div>
        </div>
      </div>
    </section>
  );
};

export default SanctionsBans;
