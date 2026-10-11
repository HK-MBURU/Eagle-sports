import PageTemplate from "@/components/PageTemplate";
import { Card, CardContent } from "@/components/ui/card";
import { Badge } from "@/components/ui/badge";
import { Button } from "@/components/ui/button";
import { Tabs, TabsList, TabsTrigger } from "@/components/ui/tabs";
import { Input } from "@/components/ui/input";
import { Calendar, MapPin, Clock, Users, Filter, Search } from "lucide-react";
import { useMemo, useState } from "react";
import { useNavigate } from "react-router-dom";

type CalendarRace = {
  id: number;
  title: string;
  date: string;
  location: string;
  time: string;
  category: "Road" | "Trail" | "Track" | "Cross Country";
  distances: string[];
  spotsLeft?: number;
};

const MOCK_RACES: CalendarRace[] = [
  {
    id: 1,
    title: "Eldoret City Marathon",
    date: "2026-04-26",
    location: "Eldoret, Uasin Gishu",
    time: "06:30 AM EAT",
    category: "Road",
    distances: ["42km", "21km", "10km", "5km Fun Run"],
    spotsLeft: 200,
  },
  {
    id: 2,
    title: "Kenya Wildlife Marathon",
    date: "2026-05-24",
    location: "Lukenya Sanctuary, Athi River",
    time: "07:00 AM EAT",
    category: "Trail",
    distances: ["42km", "21km", "10km", "5km"],
    spotsLeft: 120,
  },
  {
    id: 3,
    title: "AK Betika National XC",
    date: "2026-02-14",
    location: "Nakuru, Kenya",
    time: "09:00 AM EAT",
    category: "Cross Country",
    distances: ["10km", "8km", "6km"],
  },
  {
    id: 4,
    title: "Nairobi Sunset Series",
    date: "2026-03-18",
    location: "Nairobi, Kenya",
    time: "05:30 PM EAT",
    category: "Road",
    distances: ["15km", "10km", "5km"],
    spotsLeft: 85,
  },
  {
    id: 5,
    title: "Kericho Trail Relay",
    date: "2026-06-08",
    location: "Kericho, Kenya",
    time: "08:00 AM EAT",
    category: "Trail",
    distances: ["21km Relay", "10km Relay"],
  },
];

const formatDate = (isoDate: string) =>
  new Date(isoDate).toLocaleDateString("en-KE", {
    month: "long",
    day: "numeric",
    year: "numeric",
  });

const RaceCalendar = () => {
  const [search, setSearch] = useState("");
  const [filter, setFilter] = useState<CalendarRace["category"] | "All">("All");
  const navigate = useNavigate();

  const filtered = useMemo(() => {
    return MOCK_RACES.filter((race) => {
      const matchesSearch =
        race.title.toLowerCase().includes(search.toLowerCase()) ||
        race.location.toLowerCase().includes(search.toLowerCase());
      const matchesFilter = filter === "All" ? true : race.category === filter;
      return matchesSearch && matchesFilter;
    });
  }, [search, filter]);

  return (
    <PageTemplate
      title="Race Calendar"
      description="Your modern, in-site calendar for road, trail, track, and cross country events."
      showHero
    >
      <div className="max-w-6xl mx-auto space-y-10">
        <Card className="border-0 shadow-strong">
          <CardContent className="p-6 md:p-8 space-y-4">
            <div className="flex flex-col md:flex-row md:items-center gap-4">
              <div className="relative flex-1">
                <Search className="absolute left-3 top-1/2 -translate-y-1/2 text-muted-foreground h-5 w-5" />
                <Input
                  placeholder="Search races by name or location"
                  className="pl-10 h-12"
                  value={search}
                  onChange={(e) => setSearch(e.target.value)}
                />
              </div>
              <Tabs value={filter} onValueChange={(v) => setFilter(v as any)} className="w-full md:w-auto">
                <TabsList className="grid grid-cols-2 md:grid-cols-5 w-full">
                  {["All", "Road", "Trail", "Track", "Cross Country"].map((cat) => (
                    <TabsTrigger key={cat} value={cat as any} className="text-sm">
                      {cat}
                    </TabsTrigger>
                  ))}
                </TabsList>
              </Tabs>
            </div>
          </CardContent>
        </Card>

        <div className="grid gap-6 md:grid-cols-2">
          {filtered.map((race) => (
            <Card key={race.id} className="hover:shadow-medium transition-all duration-200 border-border">
              <div className="h-2 bg-gradient-to-r from-accent-gold to-accent-orange" />
              <CardContent className="p-6 space-y-4">
                <div className="flex items-start justify-between gap-3">
                  <div>
                    <h3 className="text-xl font-bold text-foreground">{race.title}</h3>
                    <p className="text-sm text-muted-foreground">{race.category}</p>
                  </div>
                  <Badge className="bg-primary/10 text-primary font-semibold">Spots {race.spotsLeft ?? "Open"}</Badge>
                </div>

                <div className="grid grid-cols-1 gap-2 text-muted-foreground">
                  <div className="flex items-center gap-2">
                    <Calendar className="h-4 w-4 text-primary" />
                    <span>{formatDate(race.date)}</span>
                  </div>
                  <div className="flex items-center gap-2">
                    <MapPin className="h-4 w-4 text-primary" />
                    <span>{race.location}</span>
                  </div>
                  <div className="flex items-center gap-2">
                    <Clock className="h-4 w-4 text-primary" />
                    <span>{race.time}</span>
                  </div>
                  <div className="flex items-center gap-2">
                    <Users className="h-4 w-4 text-primary" />
                    <span>{race.spotsLeft ? `${race.spotsLeft} spots left` : "Open registration"}</span>
                  </div>
                </div>

                <div className="flex flex-wrap gap-2">
                  {race.distances.map((distance) => (
                    <Badge key={distance} variant="secondary" className="bg-accent text-foreground">
                      {distance}
                    </Badge>
                  ))}
                </div>

                <div className="flex flex-wrap gap-3">
                  <Button className="flex-1 bg-primary text-primary-foreground hover:bg-primary/90">
                    Register
                  </Button>
                  <Button
                    variant="outline"
                    className="flex-1 border-primary text-primary hover:bg-primary hover:text-primary-foreground"
                    onClick={() => navigate(`/races/events/${race.id}`)}
                  >
                    View details
                  </Button>
                </div>
              </CardContent>
            </Card>
          ))}

          {filtered.length === 0 && (
            <Card className="border-dashed border-2 border-muted text-center p-10">
              <CardContent className="space-y-3">
                <Filter className="mx-auto text-muted-foreground" />
                <p className="text-muted-foreground">No races match your filters. Try another category or search term.</p>
                <Button variant="outline" onClick={() => { setSearch(""); setFilter("All"); }}>
                  Clear filters
                </Button>
              </CardContent>
            </Card>
          )}
        </div>
      </div>
    </PageTemplate>
  );
};

export default RaceCalendar;
