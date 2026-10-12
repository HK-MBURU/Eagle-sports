import { useState, useEffect, useRef } from "react";
import { raceEventsAPI } from "@/services/api";

const NAVY = "#10284a";
const RED  = "#d62835";

interface QuickLink {
  label: string;
  href: string;
}

interface FlagshipEventData {
  id: number;
  title: string;
  subtitle: string | null;
  eyebrow_text: string | null;
  coverage_description: string | null;
  event_date: string;
  event_date_formatted: string;
  event_datetime_iso: string;
  location: string;
  art_image_url: string | null;
  art_caption: string | null;
  quick_links: QuickLink[];
}

interface Countdown {
  days: number;
  hours: number;
  minutes: number;
  seconds: number;
}

const pad = (n: number) => String(Math.max(0, n)).padStart(2, "0");

const computeCountdown = (isoTarget: string): Countdown => {
  const diff = Math.max(0, new Date(isoTarget).getTime() - Date.now());
  const s    = Math.floor(diff / 1000);
  return {
    days:    Math.floor(s / 86400),
    hours:   Math.floor((s % 86400) / 3600),
    minutes: Math.floor((s % 3600) / 60),
    seconds: s % 60,
  };
};

const FlagshipEvent = () => {
  const [event, setEvent]       = useState<FlagshipEventData | null>(null);
  const [loading, setLoading]   = useState(true);
  const [countdown, setCountdown] = useState<Countdown>({ days: 0, hours: 0, minutes: 0, seconds: 0 });
  const timerRef = useRef<ReturnType<typeof setInterval> | null>(null);

  useEffect(() => {
    raceEventsAPI.getFlagshipEvent()
      .then((res: { success: boolean; data: FlagshipEventData }) => {
        if (res.success && res.data) setEvent(res.data);
      })
      .catch(() => {})
      .finally(() => setLoading(false));
  }, []);

  useEffect(() => {
    if (!event?.event_datetime_iso) return;
    const iso = event.event_datetime_iso;
    setCountdown(computeCountdown(iso));
    timerRef.current = setInterval(() => setCountdown(computeCountdown(iso)), 1000);
    return () => { if (timerRef.current) clearInterval(timerRef.current); };
  }, [event]);

  if (loading || !event) return null;

  const links: QuickLink[] = Array.isArray(event.quick_links) ? event.quick_links : [];
  const artImage = event.art_image_url
    || "https://images.unsplash.com/photo-1526676037777-05a232554f77?auto=format&fit=crop&w=1200&q=85";

  return (
    <section
      id="flagship"
      style={{ background: NAVY, color: "#fff", padding: "56px 0", position: "relative", overflow: "hidden" }}
    >
      {/* decorative circle accent */}
      <div style={{
        position: "absolute", right: -80, top: -130,
        width: 450, height: 450,
        border: "70px solid rgba(255,255,255,0.03)",
        borderRadius: "50%", pointerEvents: "none",
      }} />

      <style>{`
        .flagship-grid { display:grid; grid-template-columns:1fr 1fr; gap:28px; align-items:center; }
        .flagship-art-card { min-height:300px; }
        @media (max-width:760px) {
          .flagship-grid { grid-template-columns:1fr !important; }
          .flagship-art-card { min-height:230px; order:-1; }
        }
        @media (max-width:430px) {
          .flagship-countdown { gap:5px !important; }
          .flagship-timebox { padding:11px 4px !important; }
          .flagship-timebox strong { font-size:22px !important; }
          .flagship-links { grid-template-columns:1fr 1fr !important; }
        }
        .flagship-link { transition:background 0.13s,border-color 0.13s; }
        .flagship-link:hover { background:${RED} !important; border-color:${RED} !important; }
      `}</style>

      <div style={{ width: "min(1180px,92%)", margin: "0 auto", position: "relative" }}>
        <div className="flagship-grid">

          {/* ── Left column ── */}
          <div>
            {/* Eyebrow badge */}
            <span style={{
              display: "inline-block", background: RED, color: "#fff",
              fontSize: 10, fontWeight: 900, letterSpacing: "1.5px",
              textTransform: "uppercase", padding: "5px 10px",
              borderRadius: 3, marginBottom: 14,
            }}>
              {event.eyebrow_text || "FLAGSHIP COVERAGE · 2026"}
            </span>

            {/* Title */}
            <h2 style={{
              fontSize: "clamp(28px,3.5vw,38px)", fontWeight: 900,
              lineHeight: 1.1, margin: "0 0 14px", letterSpacing: "-0.5px",
            }}>
              {event.title}
              {event.subtitle && (
                <><br /><span style={{ fontWeight: 700, fontSize: "0.82em", opacity: 0.9 }}>{event.subtitle}</span></>
              )}
            </h2>

            {/* Description */}
            {event.coverage_description && (
              <p style={{ color: "#d2dced", maxWidth: 520, lineHeight: 1.65, marginBottom: 22, fontSize: 15 }}>
                {event.coverage_description}
              </p>
            )}

            {/* Countdown */}
            <div
              className="flagship-countdown"
              style={{ display: "grid", gridTemplateColumns: "repeat(4,1fr)", gap: 8, marginBottom: 14 }}
            >
              {([
                ["DAYS",    pad(countdown.days)],
                ["HOURS",   pad(countdown.hours)],
                ["MINUTES", pad(countdown.minutes)],
                ["SECONDS", pad(countdown.seconds)],
              ] as [string, string][]).map(([label, value]) => (
                <div
                  key={label}
                  className="flagship-timebox"
                  style={{
                    border: "1px solid rgba(255,255,255,0.18)",
                    background: "rgba(255,255,255,0.05)",
                    borderRadius: 8, padding: "13px 8px", textAlign: "center",
                  }}
                >
                  <strong style={{
                    display: "block",
                    fontSize: "clamp(22px,2.8vw,28px)",
                    lineHeight: 1.1,
                    fontVariantNumeric: "tabular-nums",
                  }}>
                    {value}
                  </strong>
                  <span style={{ fontSize: 9, textTransform: "uppercase", letterSpacing: "1px", color: "#c5d1e1" }}>
                    {label}
                  </span>
                </div>
              ))}
            </div>

            {/* Meta note */}
            <p style={{ fontSize: 11, color: "#7e99b8", lineHeight: 1.5 }}>
              Countdown to {event.event_date_formatted} — {event.location}.
            </p>
          </div>

          {/* ── Right column ── */}
          <div>
            {/* Art card */}
            <div
              className="flagship-art-card"
              style={{
                borderRadius: 12, marginBottom: 12,
                background: `linear-gradient(0deg,rgba(7,22,42,0.62) 0%,rgba(7,22,42,0) 70%), url('${artImage}') center/cover no-repeat`,
                display: "flex", alignItems: "flex-end", padding: 20,
              }}
            >
              <span style={{ fontWeight: 900, fontSize: 22, textShadow: "0 2px 10px #000" }}>
                {event.art_caption || "ROAD TO DAKAR · TEAM KENYA"}
              </span>
            </div>

            {/* Quick links grid */}
            {links.length > 0 && (
              <div
                className="flagship-links"
                style={{ display: "grid", gridTemplateColumns: "repeat(2,1fr)", gap: 9 }}
              >
                {links.map((link, i) => (
                  <a
                    key={i}
                    href={link.href}
                    className="flagship-link"
                    style={{
                      border: "1px solid rgba(255,255,255,0.18)",
                      padding: "12px 14px", borderRadius: 6,
                      fontSize: 12, fontWeight: 800,
                      color: "#fff", textDecoration: "none", display: "block",
                    }}
                  >
                    → {link.label}
                  </a>
                ))}
              </div>
            )}
          </div>

        </div>
      </div>
    </section>
  );
};

export default FlagshipEvent;
