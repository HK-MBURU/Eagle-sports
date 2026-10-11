import PageTemplate from "@/components/PageTemplate";
import { Card, CardContent } from "@/components/ui/card";
import { Input } from "@/components/ui/input";
import { Textarea } from "@/components/ui/textarea";
import { Button } from "@/components/ui/button";
import { useToast } from "@/hooks/use-toast";
import { PenSquare, Camera, CheckCircle2, UploadCloud } from "lucide-react";

const storyTips = [
  "Focus on the journey — training, setbacks, and the people who lifted you up.",
  "Share 2-3 clear photos or short clips that you own or have permission to use.",
  "Add dates, events, and locations to help us fact-check quickly.",
];

const storyCategories = [
  "Athlete Profile",
  "Scholarship Pathway",
  "Community Impact",
  "Comeback Story",
  "Rising Star",
];

const SubmitStory = () => {
  const { toast } = useToast();

  const handleSubmit = (event: React.FormEvent<HTMLFormElement>) => {
    event.preventDefault();
    toast({
      title: "Story received",
      description: "Thanks for trusting us with your journey. Our editors will follow up shortly.",
    });
    event.currentTarget.reset();
  };

  return (
    <PageTemplate
      title="Submit Your Story"
      description="Inspire the next generation with your experience on and off the track."
    >
      <div className="max-w-5xl mx-auto space-y-12">
        <section className="grid gap-4 md:grid-cols-3">
          {[PenSquare, Camera, CheckCircle2].map((Icon, index) => (
            <Card key={index} className="border border-border/60 shadow-soft">
              <CardContent className="p-5 flex gap-3">
                <div className="h-10 w-10 rounded-full bg-accent/70 flex items-center justify-center text-foreground">
                  <Icon className="h-5 w-5" />
                </div>
                <div>
                  <h3 className="text-lg font-semibold text-foreground">
                    {["Write", "Add visuals", "We fact-check"][index]}
                  </h3>
                  <p className="text-muted-foreground text-sm">
                    {[
                      "Tell us what the results sheet does not capture.",
                      "Photos or short clips make your story come alive.",
                      "Our editors verify details before publishing.",
                    ][index]}
                  </p>
                </div>
              </CardContent>
            </Card>
          ))}
        </section>

        <Card className="border border-border/60 shadow-soft">
          <CardContent className="p-6 md:p-8">
            <div className="flex items-center gap-3 mb-6">
              <div className="inline-flex h-12 w-12 items-center justify-center rounded-full bg-accent-orange text-accent-orange-foreground">
                <UploadCloud className="h-5 w-5" />
              </div>
              <div>
                <h3 className="text-2xl font-bold text-foreground">Share your details</h3>
                <p className="text-muted-foreground">
                  We keep your contact private and only publish with your approval.
                </p>
              </div>
            </div>

            <form className="space-y-4" onSubmit={handleSubmit}>
              <div className="grid gap-4 md:grid-cols-2">
                <Input name="name" placeholder="Your name" required />
                <Input name="email" type="email" placeholder="Email" required />
              </div>
              <div className="grid gap-4 md:grid-cols-2">
                <Input name="location" placeholder="Location (City, Country)" />
                <select
                  name="category"
                  className="h-10 rounded-md border border-input bg-background px-3 text-sm text-foreground"
                  defaultValue=""
                  required
                >
                  <option value="" disabled>
                    Select a category
                  </option>
                  {storyCategories.map((category) => (
                    <option key={category} value={category}>
                      {category}
                    </option>
                  ))}
                </select>
              </div>
              <Textarea
                name="story"
                className="min-h-[200px]"
                placeholder="Tell us about your journey, key moments, and what you learned."
                required
              />
              <Input name="links" placeholder="Links to race results, social profiles, or videos (optional)" />
              <Button type="submit" className="w-full bg-primary text-primary-foreground hover:bg-primary/90">
                Submit Story
              </Button>
            </form>
          </CardContent>
        </Card>

        <section className="bg-gray-50 rounded-3xl p-8 space-y-4">
          <h3 className="text-xl font-semibold text-foreground">Editor tips for a stronger feature</h3>
          <ul className="list-disc list-inside space-y-2 text-muted-foreground">
            {storyTips.map((tip) => (
              <li key={tip}>{tip}</li>
            ))}
          </ul>
          <p className="text-muted-foreground">
            Once we receive your submission, we&apos;ll schedule a brief check-in to confirm quotes, names,
            and any sensitive details.
          </p>
        </section>
      </div>
    </PageTemplate>
  );
};

export default SubmitStory;
