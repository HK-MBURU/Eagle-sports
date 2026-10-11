import { useEffect, useMemo, useState } from "react";
import { useNavigate, useParams } from "react-router-dom";
import PageTemplate from "@/components/PageTemplate";
import { Card, CardContent } from "@/components/ui/card";
import { Badge } from "@/components/ui/badge";
import { Button } from "@/components/ui/button";
import { raceEventsAPI } from "@/services/api";
import type { RaceEvent } from "@/types/race-event";
import { Calendar, MapPin, Clock, Users, ExternalLink, ArrowLeft } from "lucide-react";

const EventDetail = () => {
  const { id } = useParams<{ id: string }>();
  const navigate = useNavigate();
  const [event, setEvent] = useState<RaceEvent | null>(null);
  const [related, setRelated] = useState<RaceEvent[]>([]);
  const [loading, setLoading] = useState(true);
  const [error, setError] = useState<string | null>(null);

  const fetchEvent = async (eventId: number) => {
    try {
      setLoading(true);
      setError(null);
      const [detailResp, upcomingResp] = await Promise.all([
        raceEventsAPI.getEvent(eventId),
        raceEventsAPI.getUpcomingEvents(4),
      ]);

      if (detailResp.success) {
        setEvent(detailResp.data);
      } else {
        setError("Unable to load event details.");
      }

      if (upcomingResp.success) {
        setRelated(upcomingResp.data.filter((ev: RaceEvent) => ev.id !== eventId));
      }
    } catch (err) {
      setError(err instanceof Error ? err.message : "Failed to load event");
    } finally {
      setLoading(false);
    }
  };

  useEffect(() => {
    if (id) {
      const numericId = Number(id);
      if (!Number.isNaN(numericId)) {
        fetchEvent(numericId);
      } else {
        setError("Invalid event id.");
      }
    }
  }, [id]);

  const quickFacts = useMemo(() => {
    if (!event) return [];
    return [
      { icon: Calendar, label: "Date", value: event.event_date_formatted },
      { icon: Clock, label: "Start", value: event.start_time_formatted || event.start_time },
      { icon: MapPin, label: "Location", value: event.location },
      { icon: Users, label: "Expected Runners", value: event.expected_runners || "TBD" },
    ];
  }, [event]);

  if (loading) {
    return (
      <PageTemplate title="Loading event..." showHero>
        <div className="max-w-5xl mx-auto text-center py-16 text-muted-foreground">Fetching event details...</div>
      </PageTemplate>
    );
  }

  if (error || !event) {
    return (
      <PageTemplate title="Event not found" showHero>
        <div className="max-w-5xl mx-auto text-center py-16 space-y-4">
          <p className="text-muted-foreground">{error || "We couldn’t find that event."}</p>
          <Button onClick={() => navigate("/races/calendar")}>Back to race calendar</Button>
        </div>
      </PageTemplate>
    );
  }

  return (
    <PageTemplate title={event.title} description={event.description || "Event details"} showHero>
      <div className="max-w-6xl mx-auto space-y-10">
        <div className="flex flex-col gap-6 lg:flex-row">
          <Card className="flex-1 border-0 shadow-strong">
            {event.image && (
              <div className="h-72 w-full overflow-hidden rounded-t-2xl">
                <img src={event.image} alt={event.title} className="h-full w-full object-cover" />
              </div>
            )}
            <CardContent className="p-6 space-y-4">
              <div className="flex flex-wrap gap-2">
                <Badge className="bg-primary/10 text-primary font-semibold">Upcoming</Badge>
                <Badge variant="secondary" className="bg-accent text-foreground">
                  {event.distance_options}
                </Badge>
              </div>

              <p className="text-muted-foreground leading-relaxed">
                {event.description ||
                  "Full event information will be available soon. Register to secure your spot and get updates."}
              </p>

              <div className="grid gap-3 sm:grid-cols-2">
                {quickFacts.map((fact) => {
                  const Icon = fact.icon;
                  return (
                    <div key={fact.label} className="flex items-center gap-3 rounded-lg border border-border p-3">
                      <Icon className="h-5 w-5 text-primary" />
                      <div>
                        <p className="text-xs uppercase tracking-wide text-muted-foreground">{fact.label}</p>
                        <p className="font-semibold text-foreground">{fact.value}</p>
                      </div>
                    </div>
                  );
                })}
              </div>

              <div className="flex flex-wrap gap-3">
                <Button
                  className="bg-primary text-primary-foreground hover:bg-primary/90"
                  onClick={() => {
                    if (event.registration_link) {
                      window.open(event.registration_link, "_blank", "noopener,noreferrer");
                    } else {
                      alert("Registration link coming soon.");
                    }
                  }}
                >
                  Register Now
                </Button>
                <Button variant="outline" onClick={() => navigate(-1)}>
                  <ArrowLeft className="mr-2 h-4 w-4" />
                  Back
                </Button>
              </div>
            </CardContent>
          </Card>

          <Card className="w-full lg:w-80 border-border">
            <CardContent className="p-5 space-y-4">
              <h3 className="text-lg font-semibold text-foreground">Event Snapshot</h3>
              <div className="space-y-3 text-muted-foreground">
                <div className="flex items-center gap-3">
                  <Calendar className="h-4 w-4 text-primary" />
                  <span>{event.event_date_formatted}</span>
                </div>
                <div className="flex items-center gap-3">
                  <MapPin className="h-4 w-4 text-primary" />
                  <span>{event.location}</span>
                </div>
                <div className="flex items-center gap-3">
                  <Clock className="h-4 w-4 text-primary" />
                  <span>{event.start_time_formatted || event.start_time}</span>
                </div>
                <div className="flex items-center gap-3">
                  <Users className="h-4 w-4 text-primary" />
                  <span>{event.expected_runners || "Open field"}</span>
                </div>
              </div>
              {event.registration_link && (
                <Button
                  variant="secondary"
                  className="w-full"
                  onClick={() => window.open(event.registration_link || "#", "_blank", "noopener,noreferrer")}
                >
                  <ExternalLink className="mr-2 h-4 w-4" />
                  Visit Registration
                </Button>
              )}
            </CardContent>
          </Card>
        </div>

        <div className="space-y-4">
          <div className="flex items-center justify-between">
            <h3 className="text-xl font-bold text-foreground">Other Events</h3>
            <Button variant="ghost" onClick={() => navigate("/races/calendar")}>
              View full calendar
            </Button>
          </div>
          <div className="grid gap-4 sm:grid-cols-2 lg:grid-cols-3">
            {related.map((item) => (
              <Card
                key={item.id}
                className="hover:shadow-medium transition-all duration-200 border-border cursor-pointer"
                onClick={() => navigate(`/races/events/${item.id}`)}
              >
                <CardContent className="p-4 space-y-2">
                  <p className="text-sm text-muted-foreground">{item.event_date_formatted}</p>
                  <h4 className="text-lg font-semibold text-foreground line-clamp-2">{item.title}</h4>
                  <p className="text-sm text-muted-foreground">{item.location}</p>
                  <div className="flex flex-wrap gap-2">
                    <Badge variant="secondary" className="bg-accent text-foreground">
                      {item.distance_options}
                    </Badge>
                  </div>
                </CardContent>
              </Card>
            ))}
            {related.length === 0 && (
              <Card className="border-dashed border-2 border-muted text-center p-6">
                <CardContent>No other events available right now.</CardContent>
              </Card>
            )}
          </div>
        </div>
      </div>
    </PageTemplate>
  );
};

export default EventDetail;
