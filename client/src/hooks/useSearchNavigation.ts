// hooks/useSearchNavigation.ts
import { useNavigate } from 'react-router-dom';
import { SearchResult } from '@/types/search';

export const useSearchNavigation = () => {
  const navigate = useNavigate();

  const handleSearchResultClick = (result: SearchResult) => {
    // Generate proper route based on result type
    let route = '';
    
    switch (result.type) {
      case 'story':
        route = `/stories/${result.id}`;
        break;
      case 'athlete':
        route = `/athletes/${result.id}`;
        break;
      case 'video':
        route = `/media/videos#video-${result.id}`;
        break;
      case 'race-event':
        route = `/races/upcoming#event-${result.id}`;
        break;
      case 'women-running':
        route = `/women-stories/${result.id}`;
        break;
      case 'race-result':
        route = `/race-results/${result.id}`;
        break;
      case 'partner':
        route = `/about#partner-${result.id}`;
        break;
      case 'quote':
        route = `/stories/inspire#quote-${result.id}`;
        break;
      default:
        // Fallback to the URL from the result
        route = result.url || '/';
    }

    // Use React Router for navigation
    navigate(route);
    
    // Optional: Track search result clicks in analytics
    if (typeof window !== 'undefined' && window.gtag) {
      window.gtag('event', 'search_result_click', {
        search_term: '', // You can pass the query if available
        result_type: result.type,
        result_id: result.id,
        result_title: result.title
      });
    }
  };

  return { handleSearchResultClick };
};