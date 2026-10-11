import React from 'react';
import { useStories } from '@/contexts/StoriesContext';
import { Link } from 'react-router-dom';
import { Clock, ArrowRight, Loader2, User, Mic } from 'lucide-react';

const ProfilesInterviews: React.FC = () => {
  const { latestStories, latestLoading } = useStories();

  const profileStories = latestStories.filter(story => 
    story.category?.toLowerCase().includes('profile') || 
    story.category?.toLowerCase().includes('interview') ||
    story.title?.toLowerCase().includes('profile') ||
    story.title?.toLowerCase().includes('interview')
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
        <div className="text-center mb-12">
          <span className="text-xs font-semibold text-gray-600 uppercase tracking-wider bg-gray-100 px-3 py-1 rounded mb-4 inline-block">
            Rising Stars
          </span>
          <h1 className="text-4xl font-bold mb-4 text-gray-900">Profiles & Interviews</h1>
          <p className="text-gray-600 max-w-2xl mx-auto">
            In-depth features and conversations with promising young athletes
          </p>
        </div>

        <div className="grid md:grid-cols-2 gap-6 mb-8">
          <div className="text-center p-6 bg-blue-50 rounded-lg">
            <User className="w-8 h-8 text-blue-600 mx-auto mb-3" />
            <h3 className="font-semibold text-gray-900 mb-2">Athlete Profiles</h3>
            <p className="text-sm text-gray-600">Detailed background and journey of young talents</p>
          </div>
          <div className="text-center p-6 bg-green-50 rounded-lg">
            <Mic className="w-8 h-8 text-green-600 mx-auto mb-3" />
            <h3 className="font-semibold text-gray-900 mb-2">Exclusive Interviews</h3>
            <p className="text-sm text-gray-600">Personal insights and future aspirations</p>
          </div>
        </div>

        <h2 className="text-2xl font-bold text-gray-900 mb-6">Featured Profiles</h2>
        {profileStories.length > 0 ? (
          <div className="grid md:grid-cols-2 lg:grid-cols-3 gap-6">
            {profileStories.map((story) => (
              <Link to={`/stories/${story.slug}`} key={story.id}>
                <article className="border border-gray-200 rounded-lg p-6 hover:shadow-md transition-shadow h-full">
                  <h3 className="text-lg font-bold mb-3 text-gray-900 line-clamp-2">{story.title}</h3>
                  <p className="text-gray-600 text-sm mb-4 line-clamp-3">{story.excerpt}</p>
                  <div className="flex items-center justify-between text-xs text-gray-500">
                    <div className="flex items-center gap-1.5">
                      <Clock className="w-3.5 h-3.5" />
                      <span>{story.read_time} min</span>
                    </div>
                    <span className="text-gray-700 font-semibold inline-flex items-center gap-1">
                      Read Profile
                      <ArrowRight className="w-3.5 h-3.5" />
                    </span>
                  </div>
                </article>
              </Link>
            ))}
          </div>
        ) : (
          <div className="text-center py-12 bg-gray-50 rounded-lg">
            <p className="text-gray-500">No profile stories available yet</p>
          </div>
        )}
      </div>
    </section>
  );
};

export default ProfilesInterviews;