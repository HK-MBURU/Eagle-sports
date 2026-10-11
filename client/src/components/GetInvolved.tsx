import { Card, CardContent } from "@/components/ui/card";
import { Button } from "@/components/ui/button";
import { Input } from "@/components/ui/input";
import { Textarea } from "@/components/ui/textarea";
import { Mail, Users, Heart, BookOpen } from "lucide-react";
import { Link } from "react-router-dom";
import communityCelebration from "@/assets/community-celebration.jpg";

const GetInvolved = () => {
  const opportunities = [
    {
      icon: Users,
      title: "Mentorship Program",
      description: "Connect with experienced athletes who can guide your journey",
      href: "/mentorship",
    },
    {
      icon: BookOpen,
      title: "Share Your Story",
      description: "Inspire others by sharing your athletic journey and experiences",
      href: "/submit-story",
    },
    {
      icon: Heart,
      title: "Volunteer",
      description: "Support community events and help organize local races",
      href: "/contact",
    },
  ];

  return (
    <section id="involved" className="pt-12 pb-24 bg-background">
      <div className="container mx-auto px-4">
        {/* Section Header */}
        <div className="text-center mb-16 animate-fade-up">
          <div className="inline-flex items-center justify-center w-16 h-16 rounded-full bg-accent-blue/10 text-accent-blue mb-6">
            <Heart size={32} />
          </div>
          <h2 className="text-4xl md:text-5xl font-bold text-foreground mb-4">
            Get <span className="text-accent-orange">Involved</span>
          </h2>
          <p className="text-lg text-muted-foreground max-w-2xl mx-auto">
            Join our community and be part of something bigger than yourself
          </p>
        </div>

        {/* Opportunities Grid */}
        <div className="grid grid-cols-1 md:grid-cols-3 gap-8 max-w-5xl mx-auto mb-16">
          {opportunities.map((item, index) => {
            const Icon = item.icon;
            return (
              <Card 
                key={item.title}
                className="group hover:shadow-strong transition-all duration-300 hover:-translate-y-1 border-border text-center animate-fade-up bg-white hover:border-accent-orange/40"
                style={{ animationDelay: `${index * 100}ms` }}
              >
                <CardContent className="p-6">
                  <div className="inline-flex items-center justify-center w-16 h-16 rounded-full bg-accent-blue/10 text-accent-blue mb-4 group-hover:bg-primary group-hover:text-primary-foreground transition-colors shadow-soft group-hover:shadow-medium">
                    <Icon size={28} />
                  </div>
                  <h3 className="text-xl font-bold text-foreground mb-2 group-hover:text-primary transition-colors">
                    {item.title}
                  </h3>
                  <p className="text-muted-foreground mb-4 group-hover:text-foreground transition-colors">
                    {item.description}
                  </p>
                  <Button
                    asChild
                    variant="outline"
                    className="text-accent-blue border-accent-blue/30 hover:bg-accent-blue hover:text-white hover:border-accent-blue"
                  >
                    <Link to={item.href}>Learn More</Link>
                  </Button>
                </CardContent>
              </Card>
            );
          })}
        </div>

        {/* Newsletter Section */}
        <div className="max-w-6xl mx-auto">
          <Card className="overflow-hidden border-0 shadow-strong">
            <div className="grid md:grid-cols-2 gap-0">
              <div className="relative h-80 md:h-auto">
                <img 
                  src={communityCelebration} 
                  alt="Community celebration"
                  className="w-full h-full object-cover"
                />
                <div className="absolute inset-0 bg-gradient-to-r from-primary/40 to-transparent" />
              </div>
              <CardContent className="p-8 md:p-12 flex flex-col justify-center bg-card">
                <div className="inline-flex items-center justify-center w-14 h-14 rounded-full bg-accent-gold/10 text-accent-gold mb-4 w-fit">
                  <Mail size={28} />
                </div>
                <h3 className="text-3xl font-bold text-foreground mb-4">
                  Stay Connected
                </h3>
                <p className="text-muted-foreground mb-6">
                  Subscribe to our newsletter for inspiring stories, race updates, and community news delivered to your inbox.
                </p>
                
                <form className="space-y-4">
                  <Input 
                    type="text" 
                    placeholder="Your Name" 
                    className="w-full"
                  />
                  <Input 
                    type="email" 
                    placeholder="Your Email" 
                    className="w-full"
                  />
                  <Textarea 
                    placeholder="Tell us about your journey (optional)" 
                    className="w-full min-h-[100px]"
                  />
                  <Button className="w-full bg-accent-gold text-accent-gold-foreground hover:bg-accent-orange text-lg py-6">
                    Subscribe Now
                  </Button>
                </form>

                <p className="text-sm text-muted-foreground mt-4">
                  Join 50,000+ athletes and supporters in our community
                </p>
              </CardContent>
            </div>
          </Card>
        </div>
      </div>
    </section>
  );
};

export default GetInvolved;
