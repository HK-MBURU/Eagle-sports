import PageTemplate from "@/components/PageTemplate";
import PartnersSection from "@/components/Partners";
import { Card, CardContent } from "@/components/ui/card";
import { Button } from "@/components/ui/button";
import { Link } from "react-router-dom";
import { Handshake, Globe2, Megaphone, Users, Heart, BookOpen } from "lucide-react";

const partnershipBenefits = [
  {
    title: "Community Impact",
    description: "Support youth programs, women in athletics, and local track clubs.",
    icon: Heart,
  },
  {
    title: "Meaningful Visibility",
    description: "Brand placement across features, race coverage, and athlete spotlights.",
    icon: Megaphone,
  },
  {
    title: "Global Reach",
    description: "Tap into Kenyan athletics stories followed in NCAA and pro circuits.",
    icon: Globe2,
  },
  {
    title: "Knowledge Sharing",
    description: "Workshops, mentorship, and storytelling that transfer real skills.",
    icon: BookOpen,
  },
];

const partnershipTracks = [
  {
    name: "Impact Partners",
    details: "Co-create programs for youth development, women in athletics, and climate action.",
  },
  {
    name: "Media Partners",
    details: "Sponsor series, editorial segments, or athlete profile collections.",
  },
  {
    name: "Scholarship Partners",
    details: "Connect athletes to training camps, universities, and international opportunities.",
  },
];

const PartnersPage = () => {
  return (
    <PageTemplate
      title="Partners"
      description="Brands, federations, and communities collaborating to elevate athletes."
    >
      <div className="max-w-6xl mx-auto space-y-14">
        <section className="grid gap-6 md:grid-cols-2">
          {partnershipBenefits.map((item) => {
            const Icon = item.icon;
            return (
              <Card key={item.title} className="border border-border/60 shadow-soft">
                <CardContent className="p-6 space-y-3">
                  <div className="inline-flex h-12 w-12 items-center justify-center rounded-full bg-accent/70 text-foreground">
                    <Icon />
                  </div>
                  <h3 className="text-xl font-semibold text-foreground">{item.title}</h3>
                  <p className="text-muted-foreground">{item.description}</p>
                </CardContent>
              </Card>
            );
          })}
        </section>

        <section className="rounded-3xl bg-primary text-primary-foreground p-8 space-y-6">
          <div className="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
            <div className="space-y-2">
              <div className="inline-flex h-12 w-12 items-center justify-center rounded-full bg-accent-gold text-accent-gold-foreground">
                <Handshake />
              </div>
              <h3 className="text-3xl font-bold">Partnership Tracks</h3>
              <p className="text-primary-foreground/90 max-w-2xl">
                Choose the path that fits your goals or let&apos;s design something bespoke.
              </p>
            </div>
            <div className="flex gap-3">
              <Button asChild className="bg-accent-gold text-accent-gold-foreground hover:bg-accent-orange px-6">
                <Link to="/contact">Start a conversation</Link>
              </Button>
              <Button asChild variant="outline" className="border-2 border-primary-foreground text-primary-foreground px-6">
                <Link to="/team">Meet the team</Link>
              </Button>
            </div>
          </div>
          <div className="grid gap-4 md:grid-cols-3">
            {partnershipTracks.map((track) => (
              <div key={track.name} className="rounded-2xl bg-primary-light/40 p-5">
                <div className="flex items-center gap-2 text-lg font-semibold">
                  <Users className="h-5 w-5" />
                  {track.name}
                </div>
                <p className="text-primary-foreground/90 mt-2">{track.details}</p>
              </div>
            ))}
          </div>
        </section>

        <section className="space-y-8">
          <div className="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
            <div>
              <h3 className="text-2xl font-bold text-foreground">Featured Partners</h3>
              <p className="text-muted-foreground">
                A snapshot of the organizations walking this journey with us.
              </p>
            </div>
            <Button asChild variant="outline" className="border-2 border-border text-foreground px-6">
              <Link to="/mentorship">View mentorship network</Link>
            </Button>
          </div>
          <PartnersSection />
        </section>
      </div>
    </PageTemplate>
  );
};

export default PartnersPage;
