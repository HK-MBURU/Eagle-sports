import { Facebook, Twitter, Instagram, Youtube, Mail, MapPin } from "lucide-react";

const Footer = () => {
  const currentYear = new Date().getFullYear();
  
  // Structured data for LocalBusiness schema
  const localBusinessSchema = {
    "@context": "https://schema.org",
    "@type": "SportsOrganization",
    "name": "Beyond the Finish Line",
    "description": "Celebrating Kenyan athletes' journeys beyond competition, inspiring the next generation through stories of resilience, leadership, and community impact.",
    "url": "https://beyondfinishline.com",
    "logo": "https://beyondfinishline.com/logo.png",
    "email": "info@beyondfinishline.com",
    "address": {
      "@type": "PostalAddress",
      "addressLocality": "Nairobi",
      "addressCountry": "Kenya"
    },
    "sameAs": [
      "https://www.facebook.com/Beyondthefinishline25",
      "https://x.com/bfline001",
      "https://www.youtube.com/@BeyondthefinishlineTV"
    ]
  };

  const footerLinks = {
    about: [
      { name: "Our Mission", href: "/about" },
      { name: "Team", href: "/team" },
      { name: "Partners", href: "/partners" },
      { name: "Contact Us", href: "/contact" },
    ],
    athletes: [
      { name: "Featured Athletes", href: "/stories/profiles" },
      { name: "Submit Your Story", href: "/submit-story" },
      { name: "Mentorship", href: "/mentorship" },
      { name: "Hall of Fame", href: "/hall-of-fame" },
    ],
    initiatives: [
      { name: "Climate Action", href: "/impact/climate" },
      { name: "Women in Athletics", href: "/impact/women" },
      { name: "Community Programs", href: "/impact/community" },
      { name: "Youth Development", href: "/impact/youth" },
    ],
    resources: [
      { name: "Race Calendar", href: "/races/calendar" },
      { name: "Video Gallery", href: "/media/videos" },
      { name: "Training Resources", href: "/training/coaching" },
      { name: "Newsroom", href: "/news/athletics" },
    ],
  };

  return (
    <footer className="bg-gradient-to-b from-primary to-primary-dark text-primary-foreground" role="contentinfo">
      {/* JSON-LD Structured Data */}
      <script
        type="application/ld+json"
        dangerouslySetInnerHTML={{ __html: JSON.stringify(localBusinessSchema) }}
      />
      
      <div className="container mx-auto px-4 py-16">
        <div className="text-center mb-12 space-y-4">
          <h2 className="text-3xl font-bold">Join Our Mission</h2>
          <p className="text-primary-foreground/80 max-w-2xl mx-auto">
            Whether you&apos;re an athlete with a story to share or a partner looking to support sports development,
            we&apos;d love to hear from you.
          </p>
          <div className="flex flex-wrap justify-center gap-4">
            <a
              href="/submit-story"
              className="inline-flex items-center justify-center px-6 py-3 rounded-lg bg-accent-orange text-accent-orange-foreground font-semibold shadow-soft hover:bg-accent-orange/90 transition-colors"
            >
              Share Your Story
            </a>
            <a
              href="/partners"
              className="inline-flex items-center justify-center px-6 py-3 rounded-lg border-2 border-accent-orange text-accent-orange font-semibold bg-transparent hover:bg-accent-orange/10 transition-colors"
            >
              Partner With Us
            </a>
          </div>
        </div>

        {/* Top Section */}
        <div className="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-6 gap-12 mb-12">
          {/* Brand Section */}
          <div className="lg:col-span-2">
            <div className="flex items-center space-x-2 mb-4">
              <div className="w-12 h-12 rounded-full bg-accent-gold flex items-center justify-center" aria-hidden="true">
                <span className="text-2xl font-bold text-accent-gold-foreground">B</span>
              </div>
              <div>
                <h2 className="text-lg font-bold">Beyond the Finish Line</h2>
                <p className="text-xs text-primary-foreground/80">Every Medal Tells a Beginning</p>
              </div>
            </div>
            <p className="text-primary-foreground/80 mb-6 text-sm leading-relaxed">
              Celebrating Kenyan athletes' journeys beyond competition, inspiring the next generation through stories of resilience, leadership, and community impact.
            </p>

            {/* Social Media with improved accessibility */}
            <div className="flex gap-4" aria-label="Social media links">
              <a
                href="https://www.facebook.com/Beyondthefinishline25"
                className="w-10 h-10 rounded-full bg-primary-foreground/10 hover:bg-accent-gold flex items-center justify-center transition-colors group"
                aria-label="Follow us on Facebook"
                rel="noopener noreferrer"
                target="_blank"
              >
                <Facebook size={20} className="group-hover:text-accent-gold-foreground" />
              </a>
              <a
                href="https://x.com/bfline001"
                className="w-10 h-10 rounded-full bg-primary-foreground/10 hover:bg-accent-gold flex items-center justify-center transition-colors group"
                aria-label="Follow us on X (formerly Twitter)"
                rel="noopener noreferrer"
                target="_blank"
              >
                <Twitter size={20} className="group-hover:text-accent-gold-foreground" />
              </a>
              <a
                href="https://instagram.com/beyondfinishline"
                className="w-10 h-10 rounded-full bg-primary-foreground/10 hover:bg-accent-gold flex items-center justify-center transition-colors group"
                aria-label="Follow us on Instagram"
                rel="noopener noreferrer"
                target="_blank"
              >
                <Instagram size={20} className="group-hover:text-accent-gold-foreground" />
              </a>
              <a
                href="https://www.youtube.com/@BeyondthefinishlineTV"
                className="w-10 h-10 rounded-full bg-primary-foreground/10 hover:bg-accent-gold flex items-center justify-center transition-colors group"
                aria-label="Subscribe to our YouTube channel"
                rel="noopener noreferrer"
                target="_blank"
              >
                <Youtube size={20} className="group-hover:text-accent-gold-foreground" />
              </a>
            </div>
          </div>

          {/* Links Columns with semantic headings */}
          <div>
            <h3 className="font-bold mb-4 text-accent-gold">About</h3>
            <ul className="space-y-2">
              {footerLinks.about.map((link) => (
                <li key={link.name}>
                  <a
                    href={link.href}
                    className="text-sm text-primary-foreground/80 hover:text-accent-gold transition-colors"
                    aria-label={`Learn more about ${link.name}`}
                  >
                    {link.name}
                  </a>
                </li>
              ))}
            </ul>
          </div>

          <div>
            <h3 className="font-bold mb-4 text-accent-gold">Athletes</h3>
            <ul className="space-y-2">
              {footerLinks.athletes.map((link) => (
                <li key={link.name}>
                  <a
                    href={link.href}
                    className="text-sm text-primary-foreground/80 hover:text-accent-gold transition-colors"
                    aria-label={`Explore ${link.name}`}
                  >
                    {link.name}
                  </a>
                </li>
              ))}
            </ul>
          </div>

          <div>
            <h3 className="font-bold mb-4 text-accent-gold">Initiatives</h3>
            <ul className="space-y-2">
              {footerLinks.initiatives.map((link) => (
                <li key={link.name}>
                  <a
                    href={link.href}
                    className="text-sm text-primary-foreground/80 hover:text-accent-gold transition-colors"
                    aria-label={`Discover our ${link.name} initiatives`}
                  >
                    {link.name}
                  </a>
                </li>
              ))}
            </ul>
          </div>

          <div>
            <h3 className="font-bold mb-4 text-accent-gold">Resources</h3>
            <ul className="space-y-2">
              {footerLinks.resources.map((link) => (
                <li key={link.name}>
                  <a
                    href={link.href}
                    className="text-sm text-primary-foreground/80 hover:text-accent-gold transition-colors"
                    aria-label={`Access ${link.name}`}
                  >
                    {link.name}
                  </a>
                </li>
              ))}
            </ul>
          </div>
        </div>

        {/* Contact Info with semantic markup */}
        <div className="border-t border-primary-foreground/20 pt-8 mb-8">
          <div className="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div className="flex items-start gap-3">
              <Mail className="h-5 w-5 text-accent-gold flex-shrink-0 mt-1" aria-hidden="true" />
              <div>
                <div className="font-semibold mb-1">Email Us</div>
                <a 
                  href="mailto:info@beyondfinishline.com" 
                  className="text-sm text-primary-foreground/80 hover:text-accent-gold transition-colors"
                  aria-label="Send email to info@beyondfinishline.com"
                >
                  info@beyondfinishline.com
                </a>
              </div>
            </div>
            <div className="flex items-start gap-3">
              <MapPin className="h-5 w-5 text-accent-gold flex-shrink-0 mt-1" aria-hidden="true" />
              <div>
                <div className="font-semibold mb-1">Location</div>
                <address className="text-sm text-primary-foreground/80 not-italic">
                  Nairobi, Kenya
                </address>
              </div>
            </div>
          </div>
        </div>

        {/* Bottom Section */}
        <div className="border-t border-primary-foreground/20 pt-8">
          <div className="flex flex-col md:flex-row justify-between items-center gap-4">
            <p className="text-sm text-primary-foreground/80">
              © {currentYear} Beyond the Finish Line. All rights reserved.
            </p>
            <nav aria-label="Legal links">
              <div className="flex gap-6">
                <a 
                  href="/privacy-policy" 
                  className="text-sm text-primary-foreground/80 hover:text-accent-gold transition-colors"
                  aria-label="Read our Privacy Policy"
                >
                  Privacy Policy
                </a>
                <a 
                  href="/terms-of-service" 
                  className="text-sm text-primary-foreground/80 hover:text-accent-gold transition-colors"
                  aria-label="Read our Terms of Service"
                >
                  Terms of Service
                </a>
              
              </div>
            </nav>
          </div>
        </div>
      </div>
    </footer>
  );
};

export default Footer;
