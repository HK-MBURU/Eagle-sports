import { useState, useEffect } from "react";
import { Card, CardContent } from "@/components/ui/card";
import { Button } from "@/components/ui/button";
import { Calendar, MapPin, Users, Clock, Loader2 } from "lucide-react";
import { raceEventsAPI } from "@/services/api";
import type { RaceEvent } from "@/types/race-event";
import { useNavigate } from "react-router-dom";

const UpcomingRaces = () => {
  const [races, setRaces] = useState<RaceEvent[]>([]);
  const [loading, setLoading] = useState(true);
  const [error, setError] = useState<string | null>(null);
  const navigate = useNavigate();

  const fetchUpcomingRaces = async () => {
    try {
      setLoading(true);
      setError(null);
      const response = await raceEventsAPI.getUpcomingEvents(3); // Get 3 upcoming events
      
      if (response.success) {
        setRaces(response.data);
      } else {
        setError('Failed to load upcoming races');
      }
    } catch (err) {
      console.error('Error fetching races:', err);
      setError(err instanceof Error ? err.message : 'Failed to load upcoming races');
    } finally {
      setLoading(false);
    }
  };

  useEffect(() => {
    fetchUpcomingRaces();
  }, []);

  const formatParticipants = (expectedRunners: string | null): string => {
    if (!expectedRunners) return "TBD";
    
    // Handle different formats of expected_runners
    if (expectedRunners.includes('+')) {
      return expectedRunners;
    }
    
    // Add "+" if it's a numeric value
    if (/^\d+$/.test(expectedRunners)) {
      return `${expectedRunners}+ runners`;
    }
    
    return expectedRunners;
  };

  const formatTime = (startTime: string): string => {
    if (!startTime) return "TBD";
    
    try {
      // Convert 24h time to 12h format with timezone
      const [hours, minutes] = startTime.split(':');
      const hour = parseInt(hours);
      const ampm = hour >= 12 ? 'PM' : 'AM';
      const formattedHour = hour % 12 || 12;
      return `${formattedHour}:${minutes} ${ampm} EAT`;
    } catch {
      return startTime;
    }
  };

  const handleRegister = (registrationLink: string | null) => {
    if (registrationLink) {
      window.open(registrationLink, '_blank', 'noopener,noreferrer');
    } else {
      // Fallback action if no registration link
      alert('Registration details coming soon!');
    }
  };

  const handleDetails = (eventId: number) => {
    navigate(`/races/events/${eventId}`);
  };

  if (loading) {
    return (
      <section id="races" className="py-24 bg-accent">
        <div className="container mx-auto px-4">
          <div className="text-center mb-16">
            <div className="inline-flex items-center justify-center w-16 h-16 rounded-full bg-accent-orange/10 text-accent-orange mb-6">
              <Calendar size={32} />
            </div>
            <h2 className="text-4xl md:text-5xl font-bold text-foreground mb-4">
              Upcoming <span className="text-gradient-gold">Races</span>
            </h2>
            <p className="text-lg text-muted-foreground max-w-2xl mx-auto">
              Loading exciting race events across Kenya...
            </p>
          </div>
          <div className="flex justify-center items-center py-12">
            <Loader2 className="h-8 w-8 animate-spin text-primary" />
          </div>
        </div>
      </section>
    );
  }

  if (error) {
    return (
      <section id="races" className="py-24 bg-accent">
        <div className="container mx-auto px-4">
          <div className="text-center mb-16">
            <div className="inline-flex items-center justify-center w-16 h-16 rounded-full bg-accent-orange/10 text-accent-orange mb-6">
              <Calendar size={32} />
            </div>
            <h2 className="text-4xl md:text-5xl font-bold text-foreground mb-4">
              Upcoming <span className="text-gradient-gold">Races</span>
            </h2>
            <p className="text-lg text-muted-foreground max-w-2xl mx-auto mb-8">
              {error}
            </p>
            <Button 
              onClick={fetchUpcomingRaces}
              className="bg-primary text-primary-foreground hover:bg-primary-dark"
            >
              Try Again
            </Button>
          </div>
        </div>
      </section>
    );
  }

  if (races.length === 0) {
    return (
      <section id="races" className="py-24 bg-accent">
        <div className="container mx-auto px-4">
          <div className="text-center mb-16">
            <div className="inline-flex items-center justify-center w-16 h-16 rounded-full bg-accent-orange/10 text-accent-orange mb-6">
              <Calendar size={32} />
            </div>
            <h2 className="text-4xl md:text-5xl font-bold text-foreground mb-4">
              Upcoming <span className="text-gradient-gold">Races</span>
            </h2>
            <p className="text-lg text-muted-foreground max-w-2xl mx-auto mb-8">
              No upcoming races at the moment. Check back soon for new events!
            </p>
          </div>
        </div>
      </section>
    );
  }

  return (
    <section id="races" className="py-24 bg-accent">
      <div className="container mx-auto px-4">
        {/* Section Header */}
        <div className="text-center mb-16 animate-fade-up">
          <div className="inline-flex items-center justify-center w-16 h-16 rounded-full bg-accent-orange/10 text-accent-orange mb-6">
            <Calendar size={32} />
          </div>
          <h2 className="text-4xl md:text-5xl font-bold text-foreground mb-4">
            Upcoming <span className="text-gradient-gold">Races</span>
          </h2>
          <p className="text-lg text-muted-foreground max-w-2xl mx-auto">
            Join the running community at these exciting events across Kenya
          </p>
        </div>

        {/* Races Grid */}
        <div className="grid grid-cols-1 md:grid-cols-3 gap-8 max-w-6xl mx-auto mb-12">
          {races.map((race, index) => (
            <Card 
              key={race.id}
              className="group overflow-hidden hover:shadow-strong transition-all duration-300 hover:-translate-y-2 border-border animate-fade-up"
              style={{ animationDelay: `${index * 100}ms` }}
            >
              <div className="h-2 bg-gradient-to-r from-accent-gold to-accent-orange" />
              <CardContent className="p-6">
                <h3 className="text-2xl font-bold text-foreground mb-4 group-hover:text-primary transition-colors">
                  {race.title}
                </h3>
                
                <div className="space-y-3 mb-6">
                  <div className="flex items-start gap-3 text-muted-foreground">
                    <Calendar className="h-5 w-5 text-primary flex-shrink-0 mt-0.5" />
                    <span>{race.event_date_formatted}</span>
                  </div>
                  <div className="flex items-start gap-3 text-muted-foreground">
                    <MapPin className="h-5 w-5 text-primary flex-shrink-0 mt-0.5" />
                    <span>{race.location}</span>
                  </div>
                  <div className="flex items-start gap-3 text-muted-foreground">
                    <Clock className="h-5 w-5 text-primary flex-shrink-0 mt-0.5" />
                    <span>{formatTime(race.start_time)}</span>
                  </div>
                  <div className="flex items-start gap-3 text-muted-foreground">
                    <Users className="h-5 w-5 text-primary flex-shrink-0 mt-0.5" />
                    <span>{formatParticipants(race.expected_runners)}</span>
                  </div>
                </div>

                <div className="bg-accent p-4 rounded-lg mb-6">
                  <div className="text-sm font-semibold text-primary mb-1">Distance Options</div>
                  <div className="text-foreground">{race.distance_options}</div>
                </div>

                <div className="flex gap-3">
                  <Button 
                    onClick={() => handleRegister(race.registration_link)}
                    className="flex-1 bg-primary text-primary-foreground hover:bg-primary-dark"
                  >
                    Register Now
                  </Button>
                  <Button 
                    onClick={() => handleDetails(race.id)}
                    variant="outline" 
                    className="flex-1 border-primary text-primary hover:bg-primary hover:text-primary-foreground"
                  >
                    Details
                  </Button>
                </div>
              </CardContent>
            </Card>
          ))}
        </div>

        {/* Call to Action */}
        <div className="text-center">
          <p className="text-lg text-muted-foreground mb-6">
            Want to see all upcoming events?
          </p>
          <Button 
            size="lg" 
            variant="outline" 
            className="border-primary text-primary hover:bg-primary hover:text-primary-foreground px-8"
            onClick={() => navigate("/races/calendar")}
          >
            View Full Race Calendar
          </Button>
        </div>
      </div>
    </section>
  );
};

export default UpcomingRaces;
