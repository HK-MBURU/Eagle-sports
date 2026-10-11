import PageTemplate from "@/components/PageTemplate";
import { Card, CardContent } from "@/components/ui/card";
import { Button } from "@/components/ui/button";
import { Link } from "react-router-dom";
import { Medal, Trophy, Flame } from "lucide-react";

const inductees = [
  {
    name: "Agnes Tirop",
    discipline: "10,000m / Cross Country",
    highlight: "World Championship bronze and fearless front-runner.",
    era: "2015 — 2021",
  },
  {
    name: "Paul Tergat",
    discipline: "10,000m / Marathon",
    highlight: "Five-time World Cross Country champion and marathon legend.",
    era: "1994 — 2004",
  },
  {
    name: "Janeth Jepkosgei",
    discipline: "800m",
    highlight: "World champion and pioneer for Kenyan middle-distance women.",
    era: "2004 — 2012",
  },
  {
    name: "Catherine Ndereba",
    discipline: "Marathon",
    highlight: "Two-time world champion and four-time Boston Marathon winner.",
    era: "1999 — 2008",
  },
  {
    name: "Asbel Kiprop",
    discipline: "1500m",
    highlight: "Olympic and world champion redefining tactical racing.",
    era: "2008 — 2015",
  },
  {
    name: "Faith Kipyegon",
    discipline: "1500m / Mile",
    highlight: "Record-breaking excellence and double Olympic gold.",
    era: "2016 — present",
  },
];

const HallOfFame = () => {
  return (
    <PageTemplate
      title="Hall of Fame"
      description="Celebrating Kenyan athletes whose performances and character inspire generations."
    >
      <div className="max-w-6xl mx-auto space-y-10">
        <section className="grid gap-6 md:grid-cols-3">
          {inductees.map((inductee) => (
            <Card key={inductee.name} className="border border-border/60 shadow-soft">
              <CardContent className="p-6 space-y-2">
                <div className="flex items-center justify-between">
                  <div>
                    <p className="text-sm text-muted-foreground uppercase tracking-wide">{inductee.discipline}</p>
                    <h3 className="text-xl font-semibold text-foreground">{inductee.name}</h3>
                  </div>
                  <div className="h-10 w-10 rounded-full bg-accent/70 flex items-center justify-center text-foreground">
                    <Medal className="h-5 w-5" />
                  </div>
                </div>
                <p className="text-muted-foreground">{inductee.highlight}</p>
                <p className="text-sm text-muted-foreground">Era: {inductee.era}</p>
              </CardContent>
            </Card>
          ))}
        </section>

        <section className="rounded-3xl bg-primary text-primary-foreground p-8 space-y-5">
          <div className="flex items-center gap-3">
            <Flame />
            <h3 className="text-2xl font-bold">Nominate a legend</h3>
          </div>
          <p className="text-primary-foreground/90 max-w-3xl">
            Have an athlete whose story should be here? Share their journey, results, and the impact they
            created on and off the track.
          </p>
          <div className="flex flex-wrap gap-3">
            <Button asChild className="bg-accent-gold text-accent-gold-foreground hover:bg-accent-orange px-6">
              <Link to="/submit-story">Submit a nomination</Link>
            </Button>
            <Button asChild variant="outline" className="border-2 border-primary-foreground text-primary-foreground px-6">
              <Link to="/contact">Talk to the editors</Link>
            </Button>
          </div>
        </section>

        <section className="border border-dashed border-border rounded-2xl p-8 flex flex-col md:flex-row items-center gap-6">
          <div className="h-12 w-12 rounded-full bg-accent/70 flex items-center justify-center text-foreground">
            <Trophy />
          </div>
          <div className="space-y-2">
            <h4 className="text-xl font-semibold text-foreground">Tracking legacy beyond medals</h4>
            <p className="text-muted-foreground">
              We highlight mentorship, community work, and resilience alongside podium finishes. Each inductee
              profile includes interviews, timelines, and archival links when available.
            </p>
          </div>
        </section>
      </div>
    </PageTemplate>
  );
};

export default HallOfFame;
