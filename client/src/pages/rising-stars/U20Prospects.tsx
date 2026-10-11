import React from 'react';
import { useStories } from '@/contexts/StoriesContext';
import { Link } from 'react-router-dom';
import { Clock, ArrowRight, Loader2, TrendingUp, Target } from 'lucide-react';

const U20Prospects: React.FC = () => {
  const { latestStories, latestLoading } = useStories();

  const u20Stories = latestStories.filter(story => 
    story.category?.toLowerCase().includes('u20') || 
    story.title?.toLowerCase().includes('u20') ||
    story.title?.toLowerCase().includes('under-20') ||
    story.excerpt?.toLowerCase().includes('u20')
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
          <h1 className="text-4xl font-bold mb-4 text-gray-900">U-20 Prospects</h1>
          <p className="text-gray-600 max-w-2xl mx-auto">
            Elite young athletes on the pathway to senior international competition
          </p>
        </div>

        <div className="grid md:grid-cols-2 gap-6 mb-8">
          <div className="text-center p-6 bg-blue-50 rounded-lg">
            <Target className="w-8 h-8 text-blue-600 mx-auto mb-3" />
            <h3 className="font-semibold text-gray-900 mb-2">World Juniors</h3>
            <p className="text-sm text-gray-600">Athletes preparing for international junior championships</p>
          </div>
          <div className="text-center p-6 bg-green-50 rounded-lg">
            <TrendingUp className="w-8 h-8 text-green-600 mx-auto mb-3" />
            <h3 className="font-semibold text-gray-900 mb-2">Rapid Development</h3>
            <p className="text-sm text-gray-600">Monitoring progress and breakthrough performances</p>
          </div>
        </div>

        <h2 className="text-2xl font-bold text-gray-900 mb-6">Top U-20 Prospects</h2>
        {u20Stories.length > 0 ? (
          <div className="grid md:grid-cols-2 gap-6">
            {u20Stories.map((story) => (
              <Link to={`/stories/${story.slug}`} key={story.id}>
                <article className="border border-gray-200 rounded-lg p-6 hover:shadow-md transition-shadow">
                  <h3 className="text-lg font-bold mb-2 text-gray-900">{story.title}</h3>
                  <p className="text-gray-600 text-sm mb-4 line-clamp-2">{story.excerpt}</p>
                  <div className="flex items-center justify-between text-xs text-gray-500">
                    <div className="flex items-center gap-1.5">
                      <Clock className="w-3.5 h-3.5" />
                      <span>{story.read_time} min</span>
                    </div>
                    <span className="text-gray-700 font-semibold inline-flex items-center gap-1">
                      Read More
                      <ArrowRight className="w-3.5 h-3.5" />
                    </span>
                  </div>
                </article>
              </Link>
            ))}
          </div>
        ) : (
          <div className="text-center py-12 bg-gray-50 rounded-lg">
            <p className="text-gray-500">No U-20 prospect stories available yet</p>
          </div>
        )}
      </div>
    </section>
  );
};

export default U20Prospects;