import PageTemplate from "@/components/PageTemplate";
import { Card, CardContent } from "@/components/ui/card";
import { Badge } from "@/components/ui/badge";
import { Button } from "@/components/ui/button";
import { Link } from "react-router-dom";
import {
  Users,
  Target,
  Lightbulb,
  Sparkles,
  HeartHandshake,
  Megaphone,
} from "lucide-react";

const teamMembers = [
  {
    name: "Kiptoo Bett",
    role: "Founder & Lead Storyteller",
    focus: "Athlete advocacy, scholarships, and editorial direction.",
    strengths: ["Scholarship pathways", "Editorial leadership", "Community impact"],
  },
  {
    name: "Faith Cherop",
    role: "Partnerships & Community",
    focus: "Building bridges with federations, brands, and local clubs.",
    strengths: ["Partnership design", "Events", "Volunteer programs"],
  },
  {
    name: "Brian Otieno",
    role: "Digital & Product",
    focus: "Product experience, SEO, and ensuring the site stays fast and accessible.",
    strengths: ["Product strategy", "UX research", "Analytics"],
  },
  {
    name: "Linet Mwangi",
    role: "Content & Athlete Liaison",
    focus: "Coaching athletes through interviews and stories with dignity.",
    strengths: ["Interview prep", "Writing", "Safeguarding"],
  },
];

const teamValues = [
  {
    title: "Athlete-First",
    description: "Every story respects the person behind the performance.",
    icon: HeartHandshake,
  },
  {
    title: "Journalistic Integrity",
    description: "Fact-checked stories with transparent sources and approvals.",
    icon: Target,
  },
  {
    title: "Learning Mindset",
    description: "We test, iterate, and keep the platform welcoming for newcomers.",
    icon: Lightbulb,
  },
  {
    title: "Bold Storytelling",
    description: "We celebrate grit, resilience, and the journeys beyond medals.",
    icon: Megaphone,
  },
];

const Team = () => {
  return (
    <PageTemplate
      title="Meet the Team"
      description="Athletes, storytellers, and builders working together to spotlight Kenyan athletics."
    >
      <div className="max-w-6xl mx-auto space-y-14">
        <section className="grid gap-6 md:grid-cols-2">
          {teamMembers.map((member) => (
            <Card key={member.name} className="border border-border/60 shadow-soft">
              <CardContent className="p-6 space-y-4">
                <div className="flex items-start justify-between gap-4">
                  <div>
                    <p className="text-sm text-muted-foreground uppercase tracking-wide">
                      {member.role}
                    </p>
                    <h3 className="text-2xl font-bold text-foreground">{member.name}</h3>
                  </div>
                  <div className="inline-flex h-12 w-12 items-center justify-center rounded-full bg-primary text-primary-foreground">
                    <Users />
                  </div>
                </div>
                <p className="text-muted-foreground">{member.focus}</p>
                <div className="flex flex-wrap gap-2">
                  {member.strengths.map((item) => (
                    <Badge key={item} variant="secondary" className="bg-accent/80 text-foreground">
                      {item}
                    </Badge>
                  ))}
                </div>
              </CardContent>
            </Card>
          ))}
        </section>

        <section className="grid gap-6 md:grid-cols-2">
          <div className="rounded-3xl bg-primary text-primary-foreground p-8 space-y-4">
            <div className="inline-flex items-center justify-center h-12 w-12 rounded-full bg-accent-gold text-accent-gold-foreground">
              <Sparkles />
            </div>
            <h3 className="text-3xl font-bold">What Guides Us</h3>
            <p className="text-primary-foreground/90">
              From grassroots meets to global stages, we design stories and programs that keep athletes safe,
              seen, and supported. Partnerships, editorial rigor, and inclusive design shape every release.
            </p>
            <div className="flex flex-wrap gap-3">
              <Badge className="bg-accent-gold text-accent-gold-foreground">Inclusive</Badge>
              <Badge className="bg-accent-orange text-accent-orange-foreground">Impact-driven</Badge>
              <Badge className="bg-primary-light text-primary-foreground">Data-informed</Badge>
            </div>
          </div>

          <Card className="border border-border/60 shadow-soft">
            <CardContent className="p-8 space-y-5">
              <div className="flex items-center gap-3">
                <HeartHandshake className="text-primary" />
                <h3 className="text-2xl font-bold text-foreground">How We Work With You</h3>
              </div>
              <ul className="space-y-3 text-muted-foreground">
                <li>Editorial reviews to keep athlete voices authentic.</li>
                <li>Partnership playbooks for brands, federations, and universities.</li>
                <li>Onboarding guides for contributors, mentors, and volunteers.</li>
                <li>Clear response times and a single point of contact for collaborations.</li>
              </ul>
              <div className="flex flex-wrap gap-3">
                <Button asChild className="bg-accent-gold text-accent-gold-foreground hover:bg-accent-orange">
                  <Link to="/contact">Talk to the team</Link>
                </Button>
                <Button asChild variant="outline" className="border-2 border-primary text-primary">
                  <Link to="/partners">Partnerships</Link>
                </Button>
              </div>
            </CardContent>
          </Card>
        </section>

        <section className="bg-gray-50 rounded-3xl p-8 md:p-10">
          <div className="flex items-center gap-3 mb-6">
            <Target className="text-accent-orange" />
            <h3 className="text-2xl font-bold text-foreground">Team Values</h3>
          </div>
          <div className="grid gap-6 md:grid-cols-2">
            {teamValues.map((value) => {
              const Icon = value.icon;
              return (
                <Card key={value.title} className="border border-border/60 shadow-soft">
                  <CardContent className="p-6 flex gap-4">
                    <div className="h-12 w-12 rounded-full bg-accent/60 flex items-center justify-center text-foreground">
                      <Icon />
                    </div>
                    <div>
                      <h4 className="text-lg font-semibold text-foreground">{value.title}</h4>
                      <p className="text-muted-foreground">{value.description}</p>
                    </div>
                  </CardContent>
                </Card>
              );
            })}
          </div>
        </section>

        <section className="text-center space-y-4 border-t border-border pt-10">
          <p className="text-lg text-muted-foreground">
            Building something meaningful for athletes takes a team. Let&apos;s collaborate.
          </p>
          <div className="flex flex-wrap justify-center gap-4">
            <Button asChild className="bg-primary text-primary-foreground hover:bg-primary/90 px-8">
              <Link to="/submit-story">Share an athlete story</Link>
            </Button>
            <Button asChild variant="outline" className="border-2 border-accent-orange text-accent-orange px-8">
              <Link to="/mentorship">Join mentorship</Link>
            </Button>
          </div>
        </section>
      </div>
    </PageTemplate>
  );
};

export default Team;
