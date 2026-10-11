import { useState, useEffect } from "react";
import { Card, CardContent } from "@/components/ui/card";
import { Button } from "@/components/ui/button";
import { ArrowRight, Award, Heart, Users, Loader2 } from "lucide-react";
import { athletesAPI } from "@/services/api";
import type { Athlete } from "@/types/athlete";
import { useNavigate } from "react-router-dom";

const FeaturedAthletes = () => {
  const [athletes, setAthletes] = useState<Athlete[]>([]);
  const [stats, setStats] = useState({
    total: 0,
    active: 0,
    featured: 0,
  });
  const [loading, setLoading] = useState(true);
  const [error, setError] = useState<string | null>(null);
  const navigate = useNavigate();

  useEffect(() => {
    fetchAthletes();
    fetchStats();
  }, []);

  const fetchAthletes = async () => {
    try {
      setLoading(true);
      setError(null);

      const response = await athletesAPI.getFeaturedAthletes();

      if (response.success) {
        setAthletes(response.data);
      }
    } catch (err) {
      setError(err instanceof Error ? err.message : "Failed to load athletes");
      console.error("Error fetching athletes:", err);
    } finally {
      setLoading(false);
    }
  };

  const fetchStats = async () => {
    try {
      const response = await athletesAPI.getAthleteStats();
      if (response.success) {
        setStats(response.data);
      }
    } catch (err) {
      console.error("Error fetching stats:", err);
    }
  };

  // Loading state
  if (loading) {
    return (
      <section id="athletes" className="py-16 bg-background">
        <div className="container mx-auto px-4">
          <div className="flex justify-center items-center min-h-[400px]">
            <Loader2 className="w-12 h-12 animate-spin text-primary" />
          </div>
        </div>
      </section>
    );
  }

  // Error state
  if (error) {
    return (
      <section id="athletes" className="py-16 bg-background">
        <div className="container mx-auto px-4">
          <div className="text-center py-12">
            <p className="text-red-500 mb-4">{error}</p>
            <Button onClick={fetchAthletes}>Try Again</Button>
          </div>
        </div>
      </section>
    );
  }

  return (
    <section id="athletes" className="py-16 bg-background">
      <div className="container mx-auto px-4">
        {/* Section Header */}
        <div className="text-center mb-12">
          <h2 className="text-4xl md:text-5xl font-bold text-foreground mb-3">
            Athletes <span className="text-gradient-gold">Hall of Excellence</span>
          </h2>
          <p className="text-lg text-muted-foreground max-w-2xl mx-auto">
            Celebrating champions who continue to inspire beyond the finish line
          </p>
        </div>

        {/* Athletes Grid */}
        {athletes.length > 0 ? (
          <>
            <div className="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-5 mb-8">
              {athletes.map((athlete) => (
                <Card
                  key={athlete.id}
                  className="overflow-hidden border border-border bg-card"
                >
                  <div className="relative h-72 overflow-hidden">
                    <img
                      src={athlete.image_url}
                      alt={athlete.name}
                      className="w-full h-full object-cover"
                    />
                    <div className="absolute inset-0 bg-gradient-to-t from-black/70 to-transparent" />

                    {/* Badge */}
                    {athlete.badge && (
                      <div
                        className="absolute top-3 right-3 px-3 py-1.5 rounded-md text-xs font-bold flex items-center gap-1.5"
                        style={{
                          background: athlete.badge_color,
                          color: '#000'
                        }}
                      >
                        <Award size={12} />
                        {athlete.badge}
                      </div>
                    )}
                  </div>

                  <CardContent className="p-4">
                    <h3 className="text-lg font-bold text-foreground mb-1.5">
                      {athlete.name}
                    </h3>
                    <p className="text-sm text-[#FF6600] font-semibold mb-2">
                      {athlete.title}
                    </p>
                    <p className="text-sm text-muted-foreground mb-3 line-clamp-2">
                      {athlete.description}
                    </p>
                    <Button
                      variant="ghost"
                      size="sm"
                      className="w-full text-primary hover:bg-primary hover:text-primary-foreground px-3 py-2 h-auto"
                      onClick={() => navigate(`/athletes/${athlete.id}`)}
                    >
                      Read Story
                      <ArrowRight className="ml-2 h-4 w-4" />
                    </Button>
                  </CardContent>
                </Card>
              ))}
            </div>

            {/* View All Athletes Button */}
            <div className="flex justify-center mb-12">
              <Button
                onClick={() => navigate('/stories/profiles')}
                className="bg-gradient-to-r from-[#FF6600] to-[#FFBF00] hover:from-[#E55A00] hover:to-[#E5AB00] text-white font-semibold px-8 py-6 text-base shadow-lg hover:shadow-xl transition-all duration-300 group"
              >
                View All Athletes
                <ArrowRight className="ml-2 h-5 w-5 group-hover:translate-x-1 transition-transform" />
              </Button>
            </div>

            {/* Stats Section */}
            <div className="grid grid-cols-1 md:grid-cols-3 gap-6 max-w-5xl mx-auto">
              <div className="text-center p-6 bg-card border border-border">
                <div className="inline-flex items-center justify-center w-14 h-14 rounded-full bg-[#FFBF00]/10 mb-3">
                  <Award size={28} className="text-[#FFBF00]" />
                </div>
                <div className="text-3xl font-bold mb-1" style={{ color: '#FFBF00' }}>
                  {stats.total}+
                </div>
                <p className="text-sm text-muted-foreground">Athletes Featured</p>
              </div>
              
              <div className="text-center p-6 bg-card border border-border">
                <div className="inline-flex items-center justify-center w-14 h-14 rounded-full bg-[#FF6600]/10 mb-3">
                  <Users size={28} className="text-[#FF6600]" />
                </div>
                <div className="text-3xl font-bold mb-1" style={{ color: '#FF6600' }}>
                  {stats.active}+
                </div>
                <p className="text-sm text-muted-foreground">Active Athletes</p>
              </div>
              
              <div className="text-center p-6 bg-card border border-border">
                <div className="inline-flex items-center justify-center w-14 h-14 rounded-full bg-[#0F3D22]/10 mb-3">
                  <Heart size={28} className="text-[#0F3D22]" />
                </div>
                <div className="text-3xl font-bold mb-1" style={{ color: '#0F3D22' }}>
                  {stats.featured}+
                </div>
                <p className="text-sm text-muted-foreground">Featured Champions</p>
              </div>
            </div>
          </>
        ) : (
          <div className="text-center py-12">
            <p className="text-muted-foreground mb-4">No featured athletes available at the moment.</p>
          </div>
        )}
      </div>
    </section>
  );
};

export default FeaturedAthletes;