import { Card, CardContent } from "@/components/ui/card";
import { Button } from "@/components/ui/button";
import { Leaf, Droplets, Wind, TreePine } from "lucide-react";
import climateRunner from "@/assets/climate-runner.jpg";
import QuoteSection from "./QuoteSection";

const ClimateInitiative = () => {
  const initiatives = [
    {
      title: "Carbon-Neutral Races",
      description: "Partnering with major marathons to implement sustainable practices and offset carbon emissions",
      icon: Wind,
      impact: "50K tons CO₂ offset",
    },
    {
      title: "Forest Running Trails",
      description: "Protecting and maintaining natural running routes while planting 100,000 trees annually",
      icon: TreePine,
      impact: "100K trees planted",
    },
    {
      title: "Clean Water Access",
      description: "Installing water stations in training areas to reduce plastic waste and ensure safe hydration",
      icon: Droplets,
      impact: "200+ stations built",
    },
  ];

  return (
    <section id="climate" className="pt-12 pb-24 bg-background relative overflow-hidden">
      {/* Decorative Elements */}
      <div className="absolute top-0 right-0 w-96 h-96 bg-accent-blue/5 rounded-full blur-3xl" />
      <div className="absolute bottom-0 left-0 w-96 h-96 bg-primary/5 rounded-full blur-3xl" />

      <div className="container mx-auto px-4 relative z-10">
        {/* Section Header */}
        <div className="text-center mb-16 animate-fade-up">
          <div className="inline-flex items-center justify-center w-16 h-16 rounded-full bg-primary/10 text-primary mb-6">
            <Leaf size={32} />
          </div>
          <h2 className="text-4xl md:text-5xl font-bold text-foreground mb-4">
            Running for <span className="text-primary">Our Planet</span>
          </h2>
          <p className="text-lg text-muted-foreground max-w-2xl mx-auto">
            Kenyan athletes leading the charge in environmental conservation and climate action
          </p>
        </div>

        {/* Hero Content */}
        <div className="max-w-6xl mx-auto mb-16">
          <Card className="overflow-hidden border-0 shadow-strong">
            <div className="grid md:grid-cols-5 gap-0">
              <div className="md:col-span-2 relative h-80 md:h-auto">
                <img
                  src={climateRunner}
                  alt="Runner in forest"
                  className="w-full h-full object-cover"
                />
                <div className="absolute inset-0 bg-gradient-to-r from-primary/30 to-transparent" />
              </div>
              <CardContent className="md:col-span-3 p-8 md:p-12 flex flex-col justify-center bg-card">
                <h3 className="text-3xl font-bold text-foreground mb-4">
                  Climate Change Impacts Training Conditions
                </h3>
                <p className="text-muted-foreground mb-6 text-lg leading-relaxed">
                  Rising temperatures and unpredictable weather patterns are affecting training schedules and race conditions across Kenya. Athletes are witnessing firsthand the environmental changes and are taking action to protect the landscapes that shaped their careers.
                </p>
                <div className="grid grid-cols-2 gap-4 mb-6">
                  <div className="bg-accent p-4 rounded-lg">
                    <div className="text-2xl font-bold text-primary mb-1">+2.5°C</div>
                    <div className="text-sm text-muted-foreground">Temp increase in Rift Valley</div>
                  </div>
                  <div className="bg-accent p-4 rounded-lg">
                    <div className="text-2xl font-bold text-primary mb-1">-30%</div>
                    <div className="text-sm text-muted-foreground">Rainfall variability</div>
                  </div>
                </div>
                <Button
                  className="bg-primary text-primary-foreground hover:bg-primary-dark w-fit"
                  onClick={() => window.location.href = '/impact/climate'}
                >
                  Learn More About Our Climate Work
                </Button>
              </CardContent>
            </div>
          </Card>
        </div>

        {/* Initiatives Grid */}
        <div className="grid grid-cols-1 md:grid-cols-3 gap-8 max-w-6xl mx-auto mb-12">
          {initiatives.map((initiative, index) => {
            const Icon = initiative.icon;
            return (
              <Card
                key={initiative.title}
                className="group hover:shadow-medium transition-all duration-300 hover:-translate-y-1 border-border animate-fade-up"
                style={{ animationDelay: `${index * 100}ms` }}
              >
                <CardContent className="p-6">
                  <div className="inline-flex items-center justify-center w-14 h-14 rounded-full bg-primary/10 text-primary mb-4 group-hover:bg-primary group-hover:text-primary-foreground transition-colors">
                    <Icon size={28} />
                  </div>
                  <h3 className="text-xl font-bold text-foreground mb-2">{initiative.title}</h3>
                  <p className="text-muted-foreground mb-4">{initiative.description}</p>
                  <div className="inline-block bg-accent-blue/10 text-accent-blue px-3 py-1 rounded-full text-sm font-semibold">
                    {initiative.impact}
                  </div>
                </CardContent>
              </Card>
            );
          })}
        </div>

       <QuoteSection />
      </div>
    </section>
  );
};

export default ClimateInitiative;
