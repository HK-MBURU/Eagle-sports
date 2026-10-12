import { Droplets, Wind, TreePine } from "lucide-react";
import climateRunner from "@/assets/climate-runner.jpg";
import QuoteSection from "./QuoteSection";

const NAVY = "#10284a";
const RED  = "#d62835";
const LINE = "#e2e7ef";

const initiatives = [
  {
    title: "Carbon-Neutral Races",
    description: "Partnering with major marathons to implement sustainable practices and offset carbon emissions",
    Icon: Wind,
    impact: "50K tons CO₂ offset",
  },
  {
    title: "Forest Running Trails",
    description: "Protecting and maintaining natural running routes while planting 100,000 trees annually",
    Icon: TreePine,
    impact: "100K trees planted",
  },
  {
    title: "Clean Water Access",
    description: "Installing water stations in training areas to reduce plastic waste and ensure safe hydration",
    Icon: Droplets,
    impact: "200+ stations built",
  },
];

const ClimateInitiative = () => (
  <section id="climate" style={{ background: "#f4f6f9", padding: "48px 0" }}>
    <div style={{ width: "min(1180px,92%)", margin: "0 auto" }}>

      {/* Section header */}
      <div style={{ display: "flex", justifyContent: "space-between", alignItems: "flex-end", gap: 14, marginBottom: 20 }}>
        <div>
          <h2 style={{ margin: 0, color: NAVY, fontSize: 22, fontWeight: 900, letterSpacing: "-.5px", lineHeight: 1.1 }}>
            Running for Our Planet
          </h2>
          <p style={{ margin: "5px 0 0", fontSize: 12, color: "#667085" }}>
            Kenyan athletes leading the charge in environmental conservation and climate action.
          </p>
        </div>
        <a
          href="/impact/climate"
          style={{ fontSize: 12, color: RED, fontWeight: 900, whiteSpace: "nowrap", textDecoration: "none" }}
        >
          LEARN MORE →
        </a>
      </div>

      {/* Hero panel */}
      <div style={{
        border: `1px solid ${LINE}`,
        borderRadius: 10,
        overflow: "hidden",
        background: "#fff",
        display: "grid",
        gridTemplateColumns: "2fr 3fr",
        marginBottom: 18,
      }} className="climate-hero">
        {/* Image */}
        <div style={{ position: "relative", minHeight: 280 }}>
          <img
            src={climateRunner}
            alt="Runner in forest"
            style={{ width: "100%", height: "100%", objectFit: "cover", display: "block" }}
          />
        </div>

        {/* Content */}
        <div style={{ padding: "32px 36px", display: "flex", flexDirection: "column", justifyContent: "center" }}>
          <h3 style={{ margin: "0 0 12px", fontSize: 20, fontWeight: 900, color: NAVY, lineHeight: 1.25 }}>
            Climate Change Impacts Training Conditions
          </h3>
          <p style={{ margin: "0 0 20px", fontSize: 13, color: "#667085", lineHeight: 1.7 }}>
            Rising temperatures and unpredictable weather patterns are affecting training schedules and race conditions across Kenya. Athletes are witnessing firsthand the environmental changes and are taking action to protect the landscapes that shaped their careers.
          </p>

          {/* Stats */}
          <div style={{ display: "grid", gridTemplateColumns: "1fr 1fr", gap: 12, marginBottom: 22 }}>
            {[
              { value: "+2.5°C", label: "Temp increase in Rift Valley" },
              { value: "-30%",   label: "Rainfall variability" },
            ].map(({ value, label }) => (
              <div key={label} style={{ background: "#f4f6f9", borderRadius: 6, padding: "12px 14px" }}>
                <div style={{ fontSize: 20, fontWeight: 900, color: NAVY, marginBottom: 3 }}>{value}</div>
                <div style={{ fontSize: 11, color: "#667085" }}>{label}</div>
              </div>
            ))}
          </div>

          <a
            href="/impact/climate"
            style={{
              display: "inline-block",
              padding: "10px 18px",
              background: NAVY,
              color: "#fff",
              borderRadius: 5,
              fontWeight: 800,
              fontSize: 12,
              textDecoration: "none",
              alignSelf: "flex-start",
              transition: "background .15s",
            }}
            className="climate-cta-btn"
          >
            Learn More About Our Climate Work
          </a>
        </div>
      </div>

      {/* Initiatives grid */}
      <div style={{ display: "grid", gridTemplateColumns: "repeat(3,1fr)", gap: 16 }} className="initiatives-grid">
        {initiatives.map(({ title, description, Icon, impact }) => (
          <div
            key={title}
            className="initiative-card"
            style={{
              border: `1px solid ${LINE}`,
              borderRadius: 10,
              background: "#fff",
              padding: "22px 22px 20px",
              transition: "border-color .15s",
            }}
          >
            {/* Icon */}
            <div style={{
              display: "inline-flex",
              alignItems: "center",
              justifyContent: "center",
              width: 44,
              height: 44,
              borderRadius: "50%",
              background: "#f4f6f9",
              color: NAVY,
              marginBottom: 14,
            }}>
              <Icon size={20} />
            </div>

            <h3 style={{ margin: "0 0 8px", fontSize: 15, fontWeight: 900, color: NAVY }}>{title}</h3>
            <p style={{ margin: "0 0 14px", fontSize: 12, color: "#667085", lineHeight: 1.6 }}>{description}</p>

            <span style={{
              display: "inline-block",
              background: "#eaf0f7",
              color: NAVY,
              borderRadius: 20,
              padding: "3px 10px",
              fontSize: 10,
              fontWeight: 900,
              letterSpacing: ".3px",
            }}>
              {impact}
            </span>
          </div>
        ))}
      </div>

      <div style={{ marginTop: 32 }}>
        <QuoteSection />
      </div>

    </div>

    <style>{`
      .initiative-card:hover { border-color: ${RED} !important; }
      .climate-cta-btn:hover { background: #0d1e3b !important; }
      @media (max-width: 760px) {
        .climate-hero { grid-template-columns: 1fr !important; }
        .initiatives-grid { grid-template-columns: 1fr 1fr !important; }
      }
      @media (max-width: 480px) {
        .initiatives-grid { grid-template-columns: 1fr !important; }
      }
    `}</style>
  </section>
);

export default ClimateInitiative;
