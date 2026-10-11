// components/SearchResults.tsx (updated - removed orange accents)
import React from 'react';
import { SearchResult } from '@/types/search';
import { 
  FileText, 
  User, 
  Video, 
  Calendar, 
  Award, 
  Users, 
  Trophy,
  Clock
} from 'lucide-react';

interface SearchResultsProps {
  results: SearchResult[];
  query: string;
  onResultClick: (result: SearchResult) => void;
  compact?: boolean;
}

const SearchResults: React.FC<SearchResultsProps> = ({
  results,
  query,
  onResultClick,
  compact = false
}) => {
  const getIcon = (type: SearchResult['type']) => {
    switch (type) {
      case 'story': return <FileText className="w-4 h-4" />;
      case 'athlete': return <User className="w-4 h-4" />;
      case 'video': return <Video className="w-4 h-4" />;
      case 'race-event': return <Calendar className="w-4 h-4" />;
      case 'women-running': return <Users className="w-4 h-4" />;
      case 'race-result': return <Trophy className="w-4 h-4" />;
      case 'partner': return <Award className="w-4 h-4" />;
      case 'quote': return <FileText className="w-4 h-4" />;
      default: return <FileText className="w-4 h-4" />;
    }
  };

  const getTypeLabel = (type: SearchResult['type']) => {
    switch (type) {
      case 'story': return 'Story';
      case 'athlete': return 'Athlete';
      case 'video': return 'Video';
      case 'race-event': return 'Event';
      case 'women-running': return 'Women Running';
      case 'race-result': return 'Race Result';
      case 'partner': return 'Partner';
      case 'quote': return 'Quote';
      default: return type;
    }
  };

  const highlightText = (text: string, highlight: string) => {
    if (!text || !highlight) return text;
    
    const regex = new RegExp(`(${highlight})`, 'gi');
    const parts = text.split(regex);
    
    return parts.map((part, index) =>
      regex.test(part) ? (
        <mark key={index} className="bg-yellow-100 font-medium px-0.5 rounded">
          {part}
        </mark>
      ) : (
        part
      )
    );
  };

  if (results.length === 0) {
    return null;
  }

  if (compact) {
    return (
      <div className="p-2">
        <div className="text-xs text-gray-500 px-2 py-1 mb-1">
          {results.length} results
        </div>
        
        <div className="space-y-1">
          {results.map((result) => (
            <button
              key={`${result.type}-${result.id}`}
              onClick={() => onResultClick(result)}
              className="w-full text-left p-2 rounded-md hover:bg-gray-50 transition-colors duration-150 focus:outline-none focus:bg-gray-50"
            >
              <div className="flex items-center space-x-2">
                <div className="flex-shrink-0 w-6 h-6 bg-gray-100 rounded flex items-center justify-center text-gray-600">
                  {getIcon(result.type)}
                </div>

                <div className="flex-1 min-w-0">
                  <div className="flex items-center space-x-2 mb-0.5">
                    <span className="text-xs font-medium text-gray-600">
                      {getTypeLabel(result.type)}
                    </span>
                  </div>

                  <h3 className="text-sm font-medium text-gray-900 truncate">
                    {highlightText(result.title, query)}
                  </h3>
                </div>
              </div>
            </button>
          ))}
        </div>
      </div>
    );
  }

  return (
    <div className="p-4">
      <div className="mb-4 text-sm text-gray-600">
        Found {results.length} results for "{query}"
      </div>
      
      <div className="space-y-3">
        {results.map((result) => (
          <button
            key={`${result.type}-${result.id}`}
            onClick={() => onResultClick(result)}
            className="w-full text-left p-4 rounded-lg border border-gray-200 hover:border-gray-300 hover:bg-gray-50 transition-all duration-200 focus:outline-none focus:bg-gray-50"
          >
            <div className="flex items-start space-x-3">
              {/* Icon */}
              <div className="flex-shrink-0 w-8 h-8 bg-gray-100 rounded-full flex items-center justify-center text-gray-600">
                {getIcon(result.type)}
              </div>

              {/* Content */}
              <div className="flex-1 min-w-0">
                <div className="flex items-center space-x-2 mb-1">
                  <span className="text-xs font-medium text-gray-600 bg-gray-100 px-2 py-1 rounded">
                    {getTypeLabel(result.type)}
                  </span>
                  {result.date && (
                    <div className="flex items-center text-xs text-gray-500">
                      <Clock className="w-3 h-3 mr-1" />
                      {new Date(result.date).toLocaleDateString()}
                    </div>
                  )}
                </div>

                <h3 className="font-semibold text-gray-900 mb-1 truncate">
                  {highlightText(result.title, query)}
                </h3>

                {result.description && (
                  <p className="text-sm text-gray-600 line-clamp-2">
                    {highlightText(result.description, query)}
                  </p>
                )}

                {result.category && (
                  <div className="mt-2 text-xs text-gray-500">
                    Category: {result.category}
                  </div>
                )}
              </div>

              {/* Image */}
              {result.image && (
                <div className="flex-shrink-0 w-16 h-16 rounded-lg overflow-hidden">
                  <img
                    src={result.image}
                    alt={result.title}
                    className="w-full h-full object-cover"
                  />
                </div>
              )}
            </div>
          </button>
        ))}
      </div>
    </div>
  );
};

export default SearchResults;