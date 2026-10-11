import { useState, useEffect } from "react";
import { useParams, useNavigate } from "react-router-dom";
import { Button } from "@/components/ui/button";
import { Badge } from "@/components/ui/badge";
import { ArrowLeft, Award, Loader2 } from "lucide-react";
import { athletesAPI } from "@/services/api";
import type { Athlete } from "@/types/athlete";

const AthleteDetail = () => {
  const { id } = useParams<{ id: string }>();
  const navigate = useNavigate();
  const [athlete, setAthlete] = useState<Athlete | null>(null);
  const [loading, setLoading] = useState(true);
  const [error, setError] = useState<string | null>(null);

  useEffect(() => {
    if (id) {
      fetchAthlete();
    }
  }, [id]);

  const fetchAthlete = async () => {
    try {
      setLoading(true);
      const response = await athletesAPI.getAthlete(Number(id));
      
      if (response.success) {
        setAthlete(response.data);
      }
    } catch (err) {
      setError(err instanceof Error ? err.message : "Failed to load athlete");
    } finally {
      setLoading(false);
    }
  };

  if (loading) {
    return (
      <div className="min-h-screen flex items-center justify-center">
        <Loader2 className="w-12 h-12 animate-spin text-primary" />
      </div>
    );
  }

  if (error || !athlete) {
    return (
      <div className="min-h-screen flex items-center justify-center px-4">
        <div className="text-center">
          <h2 className="text-2xl font-bold mb-4">Athlete Not Found</h2>
          <Button onClick={() => navigate("/")}>
            <ArrowLeft className="mr-2 w-4 h-4" />
            Back to Home
          </Button>
        </div>
      </div>
    );
  }

  return (
    <div className="min-h-screen bg-background">
      {/* Hero Section */}
      <div className="relative h-[500px]">
        <img
          src={athlete.image_url}
          alt={athlete.name}
          className="w-full h-full object-cover"
        />
        <div className="absolute inset-0 bg-gradient-to-t from-black/80 to-transparent" />
        
        <div className="absolute bottom-0 left-0 right-0 p-12">
          <div className="container mx-auto max-w-6xl">
            {athlete.badge && (
              <Badge
                className="mb-4"
                style={{ background: athlete.badge_color, color: "#000" }}
              >
                <Award className="w-4 h-4 mr-1" />
                {athlete.badge}
              </Badge>
            )}
            <h1 className="text-4xl md:text-6xl font-bold text-white mb-4">
              {athlete.name}
            </h1>
            <p className="text-2xl text-[#D43028] font-semibold mb-2">
              {athlete.title}
            </p>
            <p className="text-xl text-white/90">{athlete.description}</p>
          </div>
        </div>
      </div>

      {/* Content */}
      <div className="container mx-auto max-w-4xl px-4 py-12">
        <Button
          variant="ghost"
          onClick={() => navigate("/")}
          className="mb-8"
        >
          <ArrowLeft className="mr-2 w-4 h-4" />
          Back to Athletes
        </Button>

        <div className="prose prose-lg max-w-none">
          <h2 className="text-3xl font-bold mb-6">Biography</h2>
          <div className="text-muted-foreground leading-relaxed whitespace-pre-line">
            {athlete.bio}
          </div>
        </div>
      </div>
    </div>
  );
};

export default AthleteDetail;