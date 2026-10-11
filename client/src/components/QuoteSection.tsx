import { useState, useEffect } from 'react';
import { quotesAPI } from '../services/api';
import type { Quote } from '../types/quote';

const QuoteSection = () => {
  const [quote, setQuote] = useState<Quote | null>(null);
  const [loading, setLoading] = useState(true);
  const [error, setError] = useState<string | null>(null);

  const fetchRandomQuote = async () => {
    try {
      setLoading(true);
      setError(null);
      const response = await quotesAPI.getRandomQuote();
      
      if (response.success && response.data) {
        setQuote(response.data);
      } else {
        setError('Failed to load quote');
      }
    } catch (err) {
      console.error('Error fetching quote:', err);
      setError(err instanceof Error ? err.message : 'Failed to load quote');
    } finally {
      setLoading(false);
    }
  };

  useEffect(() => {
    fetchRandomQuote();
  }, []);

  if (loading) {
    return (
      <div className="max-w-4xl mx-auto text-center bg-gradient-to-r from-primary/5 to-accent-blue/5 p-12 rounded-2xl">
        <div className="text-6xl text-primary mb-4">"</div>
        <div className="text-2xl font-serif text-foreground mb-6 leading-relaxed">
          Loading inspiring quote...
        </div>
        <div className="text-lg text-muted-foreground animate-pulse">
          — Loading...
        </div>
      </div>
    );
  }

  if (error || !quote) {
    return (
      <div className="max-w-4xl mx-auto text-center bg-gradient-to-r from-primary/5 to-accent-blue/5 p-12 rounded-2xl">
        <div className="text-6xl text-primary mb-4">"</div>
        <blockquote className="text-2xl font-serif text-foreground mb-6 leading-relaxed">
          The same determination that drives us to win races must now drive us to protect our environment. Our training grounds are disappearing, and we have a responsibility to preserve them for future generations.
        </blockquote>
        <cite className="text-lg text-muted-foreground">— Eliud Kipchoge, Marathon Champion</cite>
        <div className="mt-4">
          <button
            onClick={fetchRandomQuote}
            className="text-sm text-primary hover:text-primary/80 transition-colors"
          >
            Try again
          </button>
        </div>
      </div>
    );
  }

  return (
    <div className="max-w-4xl mx-auto text-center bg-gradient-to-r from-primary/5 to-accent-blue/5 p-12 rounded-2xl">
      <div className="text-6xl text-primary mb-4">"</div>
      <blockquote className="text-2xl font-serif text-foreground mb-6 leading-relaxed">
        {quote.content}
      </blockquote>
      <cite className="text-lg text-muted-foreground">
        — {quote.owner}
        {quote.owner_title && `, ${quote.owner_title}`}
      </cite>
      <div className="mt-4">
        <button
          onClick={fetchRandomQuote}
          className="text-sm text-muted-foreground hover:text-foreground transition-colors"
        >
          Show another quote
        </button>
      </div>
    </div>
  );
};

export default QuoteSection;