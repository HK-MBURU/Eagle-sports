import PageTemplate from "@/components/PageTemplate";
import { Card, CardContent } from "@/components/ui/card";
import { Button } from "@/components/ui/button";
import { Link } from "react-router-dom";
import { Compass, GraduationCap, Heart, ArrowRight } from "lucide-react";

const mentorshipTracks = [
  {
    title: "Peer Mentors",
    description: "Athletes guiding athletes on training blocks, race prep, and balancing school.",
    icon: Compass,
  },
  {
    title: "Scholarship Guides",
    description: "Support with essays, outreach emails, and finding the right program fit.",
    icon: GraduationCap,
  },
  {
    title: "Life After Track",
    description: "Career pivots, wellness, and storytelling for alumni and transitioning pros.",
    icon: Heart,
  },
];

const steps = [
  "Pick the track that matches your need.",
  "Share a short profile so we can pair you with the right mentor.",
  "Join a monthly small-group clinic and one-on-one check-ins.",
];

const Mentorship = () => {
  return (
    <PageTemplate
      title="Mentorship Program"
      description="Guidance for athletes at every stage — from juniors to alumni."
    >
      <div className="max-w-5xl mx-auto space-y-12">
        <section className="grid gap-6 md:grid-cols-3">
          {mentorshipTracks.map((track) => {
            const Icon = track.icon;
            return (
              <Card key={track.title} className="border border-border/60 shadow-soft">
                <CardContent className="p-6 space-y-3">
                  <div className="h-11 w-11 rounded-full bg-accent/70 flex items-center justify-center text-foreground">
                    <Icon className="h-5 w-5" />
                  </div>
                  <h3 className="text-xl font-semibold text-foreground">{track.title}</h3>
                  <p className="text-muted-foreground">{track.description}</p>
                </CardContent>
              </Card>
            );
          })}
        </section>

        <section className="rounded-3xl bg-primary text-primary-foreground p-8 space-y-5">
          <div className="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
            <div>
              <p className="text-sm uppercase tracking-wide text-primary-foreground/80">How it works</p>
              <h3 className="text-3xl font-bold">Simple steps to join</h3>
            </div>
            <div className="flex flex-wrap gap-3">
              <Button asChild className="bg-accent-gold text-accent-gold-foreground hover:bg-accent-orange px-6">
                <Link to="/submit-story">Share your profile</Link>
              </Button>
              <Button asChild variant="outline" className="border-2 border-primary-foreground text-primary-foreground px-6">
                <Link to="/contact">Schedule a call</Link>
              </Button>
            </div>
          </div>
          <div className="grid gap-3 md:grid-cols-3">
            {steps.map((step, index) => (
              <div key={step} className="rounded-2xl bg-primary-light/40 p-5">
                <p className="text-sm text-primary-foreground/80 mb-1">Step {index + 1}</p>
                <p className="text-primary-foreground">{step}</p>
              </div>
            ))}
          </div>
        </section>

        <section className="border border-dashed border-border rounded-2xl p-8 text-center space-y-4">
          <div className="inline-flex items-center justify-center h-12 w-12 rounded-full bg-accent/70 text-foreground">
            <ArrowRight />
          </div>
          <h3 className="text-2xl font-bold text-foreground">Looking to mentor?</h3>
          <p className="text-muted-foreground max-w-3xl mx-auto">
            Coaches, alumni, sports med professionals, and storytellers are welcome.
            Let us know where you can add value and we&apos;ll pair you with the right group.
          </p>
          <Button asChild variant="outline" className="border-2 border-border text-foreground px-6">
            <Link to="/partners">Partner with us</Link>
          </Button>
        </section>
      </div>
    </PageTemplate>
  );
};

export default Mentorship;
