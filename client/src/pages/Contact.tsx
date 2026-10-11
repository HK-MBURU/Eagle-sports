import PageTemplate from "@/components/PageTemplate";
import { Card, CardContent } from "@/components/ui/card";
import { Input } from "@/components/ui/input";
import { Textarea } from "@/components/ui/textarea";
import { Button } from "@/components/ui/button";
import { useToast } from "@/hooks/use-toast";
import { Mail, Phone, MapPin, Clock, Send } from "lucide-react";

const contactOptions = [
  {
    title: "Email",
    detail: "info@beyondfinishline.com",
    href: "mailto:info@beyondfinishline.com",
    icon: Mail,
  },
  {
    title: "WhatsApp",
    detail: "+254 722 851458",
    href: "https://wa.me/254722851458",
    icon: Phone,
  },
  {
    title: "Location",
    detail: "Nairobi, Kenya",
    href: "https://maps.google.com/?q=Nairobi,Kenya",
    icon: MapPin,
  },
];

const Contact = () => {
  const { toast } = useToast();

  const handleSubmit = (event: React.FormEvent<HTMLFormElement>) => {
    event.preventDefault();
    toast({
      title: "Message sent",
      description: "Thanks for reaching out. We typically reply within one business day.",
    });
    event.currentTarget.reset();
  };

  return (
    <PageTemplate
      title="Contact Us"
      description="We'd love to hear from you — whether it's a story lead, partnership idea, or a quick hello."
    >
      <div className="max-w-5xl mx-auto space-y-10">
        <section className="grid gap-4 md:grid-cols-3">
          {contactOptions.map((option) => {
            const Icon = option.icon;
            return (
              <Card key={option.title} className="border border-border/60 shadow-soft">
                <CardContent className="p-5 flex items-start gap-3">
                  <div className="h-10 w-10 rounded-full bg-accent/70 flex items-center justify-center text-foreground">
                    <Icon className="h-5 w-5" />
                  </div>
                  <div>
                    <p className="text-sm text-muted-foreground">{option.title}</p>
                    <a
                      href={option.href}
                      target="_blank"
                      rel="noreferrer"
                      className="text-lg font-semibold text-foreground hover:text-accent-orange"
                    >
                      {option.detail}
                    </a>
                  </div>
                </CardContent>
              </Card>
            );
          })}
        </section>

        <section className="grid gap-8 md:grid-cols-[1.1fr_0.9fr]">
          <Card className="border border-border/60 shadow-soft">
            <CardContent className="p-6 md:p-8">
              <div className="flex items-center gap-3 mb-6">
                <div className="inline-flex h-10 w-10 items-center justify-center rounded-full bg-accent-orange text-accent-orange-foreground">
                  <Send className="h-5 w-5" />
                </div>
                <div>
                  <h3 className="text-2xl font-bold text-foreground">Send a Message</h3>
                  <p className="text-sm text-muted-foreground">Tell us what you need and we&apos;ll follow up.</p>
                </div>
              </div>
              <form className="space-y-4" onSubmit={handleSubmit}>
                <div className="grid gap-4 md:grid-cols-2">
                  <Input name="name" placeholder="Your name" required />
                  <Input name="email" type="email" placeholder="Email" required />
                </div>
                <Input name="subject" placeholder="Subject" />
                <Textarea name="message" placeholder="How can we help?" className="min-h-[140px]" required />
                <Button type="submit" className="w-full bg-primary text-primary-foreground hover:bg-primary/90">
                  Send Message
                </Button>
              </form>
            </CardContent>
          </Card>

          <Card className="border border-border/60 shadow-soft bg-gray-50">
            <CardContent className="p-6 md:p-8 space-y-4">
              <div className="flex items-center gap-3">
                <Clock className="text-primary" />
                <h3 className="text-xl font-semibold text-foreground">Response Times</h3>
              </div>
              <ul className="space-y-2 text-muted-foreground">
                <li>Editorial & stories: within 24 hours.</li>
                <li>Partnerships & media: within 1-2 business days.</li>
                <li>Urgent athlete support: we prioritize same-day responses.</li>
              </ul>
              <p className="text-muted-foreground">
                Need to jump on a call? Share your availability and we&apos;ll schedule a quick huddle.
              </p>
            </CardContent>
          </Card>
        </section>
      </div>
    </PageTemplate>
  );
};

export default Contact;
