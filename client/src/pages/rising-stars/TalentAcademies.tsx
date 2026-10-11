import React from 'react';
import { useStories } from '@/contexts/StoriesContext';
import { Link } from 'react-router-dom';
import { Clock, ArrowRight, Loader2, Building, Users, Award } from 'lucide-react';

const TalentAcademies: React.FC = () => {
  const { latestStories, latestLoading } = useStories();

  const academyStories = latestStories.filter(story => 
    story.category?.toLowerCase().includes('academy') || 
    story.title?.toLowerCase().includes('academy') ||
    story.title?.toLowerCase().includes('camp') ||
    story.excerpt?.toLowerCase().includes('academy')
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
          <h1 className="text-4xl font-bold mb-4 text-gray-900">Talent Academies & Camps</h1>
          <p className="text-gray-600 max-w-2xl mx-auto">
            Premier training centers and development programs nurturing future champions
          </p>
        </div>

        <div className="grid md:grid-cols-3 gap-4 mb-8">
          <div className="text-center p-4 bg-blue-50 rounded-lg">
            <Building className="w-6 h-6 text-blue-600 mx-auto mb-2" />
            <h3 className="font-semibold text-gray-900 text-sm">Elite Academies</h3>
          </div>
          <div className="text-center p-4 bg-green-50 rounded-lg">
            <Users className="w-6 h-6 text-green-600 mx-auto mb-2" />
            <h3 className="font-semibold text-gray-900 text-sm">Training Camps</h3>
          </div>
          <div className="text-center p-4 bg-purple-50 rounded-lg">
            <Award className="w-6 h-6 text-purple-600 mx-auto mb-2" />
            <h3 className="font-semibold text-gray-900 text-sm">Scholarships</h3>
          </div>
        </div>

        <h2 className="text-2xl font-bold text-gray-900 mb-6">Featured Programs</h2>
        {academyStories.length > 0 ? (
          <div className="space-y-4">
            {academyStories.map((story) => (
              <Link to={`/stories/${story.slug}`} key={story.id}>
                <article className="border border-gray-200 rounded-lg p-6 hover:shadow-md transition-shadow">
                  <h3 className="text-lg font-bold mb-2 text-gray-900">{story.title}</h3>
                  <p className="text-gray-600 text-sm mb-4">{story.excerpt}</p>
                  <div className="flex items-center justify-between text-xs text-gray-500">
                    <span>{story.read_time} min read</span>
                    <span className="text-gray-700 font-semibold inline-flex items-center gap-1">
                      Learn More
                      <ArrowRight className="w-3.5 h-3.5" />
                    </span>
                  </div>
                </article>
              </Link>
            ))}
          </div>
        ) : (
          <div className="text-center py-12 bg-gray-50 rounded-lg">
            <p className="text-gray-500">No academy stories available yet</p>
          </div>
        )}
      </div>
    </section>
  );
};

export default TalentAcademies;