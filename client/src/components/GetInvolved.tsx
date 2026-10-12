import { Input } from "@/components/ui/input";
import { Textarea } from "@/components/ui/textarea";
import { Mail, Users, Heart, BookOpen } from "lucide-react";
import { Link } from "react-router-dom";
import communityCelebration from "@/assets/community-celebration.jpg";

const NAVY = "#10284a";
const RED  = "#d62835";
const LINE = "#e2e7ef";

const opportunities = [
  {
    Icon: Users,
    title: "Mentorship Program",
    description: "Connect with experienced athletes who can guide your journey",
    href: "/mentorship",
  },
  {
    Icon: BookOpen,
    title: "Share Your Story",
    description: "Inspire others by sharing your athletic journey and experiences",
    href: "/submit-story",
  },
  {
    Icon: Heart,
    title: "Volunteer",
    description: "Support community events and help organize local races",
    href: "/contact",
  },
];

const GetInvolved = () => (
  <section id="involved" style={{ background: "#f4f6f9", padding: "48px 0" }}>
    <div style={{ width: "min(1180px,92%)", margin: "0 auto" }}>

      {/* Section header */}
      <div style={{ display: "flex", justifyContent: "space-between", alignItems: "flex-end", gap: 14, marginBottom: 20 }}>
        <div>
          <h2 style={{ margin: 0, color: NAVY, fontSize: 22, fontWeight: 900, letterSpacing: "-.5px", lineHeight: 1.1 }}>
            Get Involved
          </h2>
          <p style={{ margin: "5px 0 0", fontSize: 12, color: "#667085" }}>
            Join our community and be part of something bigger than yourself.
          </p>
        </div>
        <a href="/contact" style={{ fontSize: 12, color: RED, fontWeight: 900, whiteSpace: "nowrap", textDecoration: "none" }}>
          CONTACT US →
        </a>
      </div>

      {/* Opportunity cards */}
      <div style={{ display: "grid", gridTemplateColumns: "repeat(3,1fr)", gap: 16, marginBottom: 18 }} className="involved-grid">
        {opportunities.map(({ Icon, title, description, href }) => (
          <div
            key={title}
            className="involved-card"
            style={{
              border: `1px solid ${LINE}`,
              borderRadius: 10,
              background: "#fff",
              padding: "24px 22px",
              textAlign: "center",
              transition: "border-color .15s",
            }}
          >
            <div style={{
              display: "inline-flex", alignItems: "center", justifyContent: "center",
              width: 44, height: 44, borderRadius: "50%",
              background: "#f4f6f9", color: NAVY, marginBottom: 14,
            }}>
              <Icon size={20} />
            </div>
            <h3 style={{ margin: "0 0 8px", fontSize: 15, fontWeight: 900, color: NAVY }}>{title}</h3>
            <p style={{ margin: "0 0 16px", fontSize: 12, color: "#667085", lineHeight: 1.6 }}>{description}</p>
            <Link
              to={href}
              style={{
                display: "inline-block",
                padding: "7px 18px",
                borderRadius: 5,
                border: `1px solid ${LINE}`,
                background: "#fff",
                color: NAVY,
                fontSize: 12,
                fontWeight: 800,
                textDecoration: "none",
                transition: "border-color .15s",
              }}
              className="involved-link"
            >
              Learn More
            </Link>
          </div>
        ))}
      </div>

      {/* Newsletter panel */}
      <div style={{
        border: `1px solid ${LINE}`,
        borderRadius: 10,
        overflow: "hidden",
        background: "#fff",
        display: "grid",
        gridTemplateColumns: "1fr 1fr",
      }} className="newsletter-panel">

        {/* Image */}
        <div style={{ position: "relative", minHeight: 260 }}>
          <img
            src={communityCelebration}
            alt="Community celebration"
            style={{ width: "100%", height: "100%", objectFit: "cover", display: "block" }}
          />
        </div>

        {/* Form */}
        <div style={{ padding: "32px 36px", display: "flex", flexDirection: "column", justifyContent: "center" }}>
          <div style={{
            display: "inline-flex", alignItems: "center", justifyContent: "center",
            width: 40, height: 40, borderRadius: "50%",
            background: "#f4f6f9", color: NAVY, marginBottom: 14,
          }}>
            <Mail size={18} />
          </div>

          <h3 style={{ margin: "0 0 8px", fontSize: 18, fontWeight: 900, color: NAVY }}>Stay Connected</h3>
          <p style={{ margin: "0 0 20px", fontSize: 12, color: "#667085", lineHeight: 1.6 }}>
            Subscribe for inspiring stories, race updates, and community news.
          </p>

          <form style={{ display: "flex", flexDirection: "column", gap: 10 }}
            onSubmit={(e) => e.preventDefault()}>
            <Input type="text"  placeholder="Your Name"  className="text-sm" />
            <Input type="email" placeholder="Your Email" className="text-sm" />
            <Textarea placeholder="Tell us about your journey (optional)" className="text-sm min-h-[80px]" />
            <button
              type="submit"
              style={{
                padding: "10px 0",
                borderRadius: 5,
                background: NAVY,
                color: "#fff",
                border: "none",
                fontWeight: 800,
                fontSize: 13,
                cursor: "pointer",
                transition: "background .15s",
              }}
              className="newsletter-btn"
            >
              Subscribe Now
            </button>
          </form>

          <p style={{ margin: "12px 0 0", fontSize: 11, color: "#9ca3af" }}>
            Join 50,000+ athletes and supporters in our community.
          </p>
        </div>
      </div>

    </div>

    <style>{`
      .involved-card:hover { border-color: ${RED} !important; }
      .involved-link:hover { border-color: ${NAVY} !important; }
      .newsletter-btn:hover { background: #0d1e3b !important; }
      @media (max-width: 760px) {
        .involved-grid { grid-template-columns: 1fr 1fr !important; }
        .newsletter-panel { grid-template-columns: 1fr !important; }
      }
      @media (max-width: 480px) {
        .involved-grid { grid-template-columns: 1fr !important; }
      }
    `}</style>
  </section>
);

export default GetInvolved;
