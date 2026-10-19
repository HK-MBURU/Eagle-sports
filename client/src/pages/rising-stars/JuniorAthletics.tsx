import { useState, useEffect } from "react";
import { Link } from "react-router-dom";
import PageTemplate from "@/components/PageTemplate";
import { storiesAPI } from "@/services/api";
import type { Story } from "@/types/story";

const RED  = "#d62835";
const NAVY = "#10284a";
const LINE = "#e2e7ef";

/* ── stat cards ── */
const STATS = [
  { value: "500+", label: "Active Juniors",     accent: RED   },
  { value: "25",   label: "National Events",    accent: NAVY  },
  { value: "15",   label: "Training Centres",   accent: "#059669" },
  { value: "50+",  label: "Qualified Coaches",  accent: "#d97706" },
];

/* ── pathway steps ── */
const PATHWAY = [
  {
    step: "01",
    title: "Skill Development",
    body:  "Technical training, biomechanics coaching and structured competition exposure for athletes aged 13–17.",
  },
  {
    step: "02",
    title: "National Competition",
    body:  "Regional qualifiers and national championship events that give juniors measurable performance benchmarks.",
  },
  {
    step: "03",
    title: "Elite Progression",
    body:  "Defined pathways from junior ranks to senior and international-level competition with AK accreditation.",
  },
];

const JuniorAthletics = () => {
  const [stories, setStories]   = useState<Story[]>([]);
  const [loading, setLoading]   = useState(true);
  const [error,   setError]     = useState<string | null>(null);

  useEffect(() => {
    let cancelled = false;

    const fetchStories = async () => {
      try {
        setLoading(true);
        setError(null);

        // Fetch in parallel with different search terms, then merge + deduplicate
        const [r1, r2, r3] = await Promise.all([
          storiesAPI.getStories({ search: "junior",  per_page: 12 }),
          storiesAPI.getStories({ search: "youth",   per_page: 12 }),
          storiesAPI.getStories({ search: "teen",    per_page: 8  }),
        ]);

        if (cancelled) return;

        const seen  = new Set<number>();
        const merged: Story[] = [];

        for (const resp of [r1, r2, r3]) {
          if (resp.success && Array.isArray(resp.data)) {
            for (const s of resp.data) {
              if (!seen.has(s.id)) {
                seen.add(s.id);
                merged.push(s);
              }
            }
          }
        }

        setStories(merged);
      } catch (err) {
        if (!cancelled) setError("Failed to load stories. Please try again.");
      } finally {
        if (!cancelled) setLoading(false);
      }
    };

    fetchStories();
    return () => { cancelled = true; };
  }, []);

  return (
    <PageTemplate showHero={false}>
      {/* ── Hero banner ── */}
      <div style={{ background: NAVY, color: "#fff", padding: "64px 0 48px" }}>
        <div style={{ width: "min(1180px,92%)", margin: "0 auto" }}>
          <span style={{
            display: "inline-block", background: RED, color: "#fff",
            fontSize: "10px", fontWeight: 900, letterSpacing: "1.5px",
            textTransform: "uppercase", padding: "5px 10px", borderRadius: "3px",
            marginBottom: "16px",
          }}>
            Rising Stars
          </span>
          <h1 style={{
            fontSize: "clamp(28px,5vw,52px)", fontWeight: 900,
            lineHeight: 1.08, letterSpacing: "-1px", margin: "0 0 16px",
          }}>
            Junior Athletics
          </h1>
          <p style={{
            fontSize: "clamp(14px,2vw,18px)", color: "#a8b8cc",
            maxWidth: "600px", lineHeight: 1.6, margin: 0,
          }}>
            Developing competitive young athletes through structured training,
            mentorship and national competitions — the next generation of Kenyan champions.
          </p>
        </div>
      </div>

      {/* ── Stats bar ── */}
      <div style={{ background: RED }}>
        <div style={{
          width: "min(1180px,92%)", margin: "0 auto",
          display: "grid", gridTemplateColumns: "repeat(4,1fr)",
        }} className="stats-grid">
          {STATS.map((s, i) => (
            <div
              key={i}
              style={{
                padding: "20px 16px", textAlign: "center", color: "#fff",
                borderRight: i < STATS.length - 1 ? "1px solid rgba(255,255,255,0.2)" : "none",
              }}
            >
              <div style={{ fontSize: "clamp(22px,3vw,32px)", fontWeight: 900, lineHeight: 1 }}>
                {s.value}
              </div>
              <div style={{ fontSize: "11px", opacity: 0.85, marginTop: "4px", fontWeight: 600, letterSpacing: "0.5px" }}>
                {s.label}
              </div>
            </div>
          ))}
        </div>
      </div>

      {/* ── Stories section ── */}
      <div style={{ background: "#f8f9fb", padding: "56px 0" }}>
        <div style={{ width: "min(1180px,92%)", margin: "0 auto" }}>

          <div style={{ marginBottom: "32px" }}>
            <div style={{ fontSize: "11px", fontWeight: 900, color: RED, letterSpacing: "1.5px", textTransform: "uppercase", marginBottom: "6px" }}>
              LATEST FROM THE TRACK
            </div>
            <h2 style={{ fontSize: "clamp(22px,3vw,30px)", fontWeight: 900, color: NAVY, margin: 0, letterSpacing: "-0.5px" }}>
              Junior Athlete Spotlights
            </h2>
          </div>

          {/* Loading skeleton */}
          {loading && (
            <div style={{ display: "grid", gridTemplateColumns: "repeat(3,1fr)", gap: "20px" }} className="stories-grid">
              {[...Array(6)].map((_, i) => (
                <div key={i} style={{ border: `1px solid ${LINE}`, borderRadius: "6px", overflow: "hidden", background: "#fff" }}>
                  <div style={{ height: "186px", background: "#e5eaf1" }} className="animate-pulse" />
                  <div style={{ padding: "16px" }}>
                    <div style={{ height: "10px", width: "60px",  background: "#e5eaf1", borderRadius: "3px", marginBottom: "10px" }} className="animate-pulse" />
                    <div style={{ height: "18px", width: "90%",   background: "#e5eaf1", borderRadius: "3px", marginBottom: "8px"  }} className="animate-pulse" />
                    <div style={{ height: "18px", width: "65%",   background: "#e5eaf1", borderRadius: "3px"                       }} className="animate-pulse" />
                  </div>
                </div>
              ))}
            </div>
          )}

          {/* Error state */}
          {!loading && error && (
            <div style={{ textAlign: "center", padding: "60px 0" }}>
              <p style={{ color: RED, marginBottom: "16px", fontSize: "14px" }}>{error}</p>
              <button
                onClick={() => window.location.reload()}
                style={{ background: NAVY, color: "#fff", border: "none", padding: "10px 24px", borderRadius: "5px", cursor: "pointer", fontWeight: 700, fontSize: "13px" }}
              >
                Try Again
              </button>
            </div>
          )}

          {/* Stories grid */}
          {!loading && !error && stories.length > 0 && (
            <div style={{ display: "grid", gridTemplateColumns: "repeat(3,1fr)", gap: "20px" }} className="stories-grid">
              {stories.map(story => (
                <StoryCard key={story.id} story={story} />
              ))}
            </div>
          )}

          {/* Empty state */}
          {!loading && !error && stories.length === 0 && (
            <div style={{
              textAlign: "center", padding: "64px 24px",
              border: `1px solid ${LINE}`, borderRadius: "8px", background: "#fff",
            }}>
              <div style={{ fontSize: "40px", marginBottom: "12px" }}>🏃</div>
              <h3 style={{ color: NAVY, fontWeight: 800, marginBottom: "8px" }}>No stories yet</h3>
              <p style={{ color: "#8896a5", fontSize: "14px", marginBottom: "24px" }}>
                Junior athletics stories are being added soon.
              </p>
              <Link
                to="/stories/inspire"
                style={{
                  display: "inline-block", background: RED, color: "#fff",
                  padding: "10px 24px", borderRadius: "4px", fontWeight: 800,
                  fontSize: "13px", textDecoration: "none", letterSpacing: "0.3px",
                }}
              >
                BROWSE ALL STORIES →
              </Link>
            </div>
          )}
        </div>
      </div>

      {/* ── Development Pathway ── */}
      <div style={{ background: "#fff", padding: "56px 0" }}>
        <div style={{ width: "min(1180px,92%)", margin: "0 auto" }}>

          <div style={{ marginBottom: "36px" }}>
            <div style={{ fontSize: "11px", fontWeight: 900, color: RED, letterSpacing: "1.5px", textTransform: "uppercase", marginBottom: "6px" }}>
              STRUCTURED GROWTH
            </div>
            <h2 style={{ fontSize: "clamp(22px,3vw,30px)", fontWeight: 900, color: NAVY, margin: 0, letterSpacing: "-0.5px" }}>
              Junior Development Pathway
            </h2>
          </div>

          <div style={{ display: "grid", gridTemplateColumns: "repeat(3,1fr)", gap: "24px" }} className="pathway-grid">
            {PATHWAY.map((step, i) => (
              <div
                key={i}
                style={{
                  padding: "28px 24px",
                  border: `1px solid ${LINE}`,
                  borderRadius: "6px",
                  position: "relative",
                  overflow: "hidden",
                }}
              >
                {/* Big step number watermark */}
                <div style={{
                  position: "absolute", top: "-10px", right: "16px",
                  fontSize: "72px", fontWeight: 900, color: "#f0f3f8",
                  lineHeight: 1, userSelect: "none", pointerEvents: "none",
                }}>
                  {step.step}
                </div>
                <div style={{
                  display: "inline-block", background: RED, color: "#fff",
                  fontSize: "10px", fontWeight: 900, letterSpacing: "1px",
                  padding: "4px 8px", borderRadius: "3px", marginBottom: "14px",
                }}>
                  STEP {step.step}
                </div>
                <h3 style={{ fontSize: "18px", fontWeight: 900, color: NAVY, margin: "0 0 10px" }}>
                  {step.title}
                </h3>
                <p style={{ fontSize: "14px", color: "#5a6a7e", lineHeight: 1.65, margin: 0 }}>
                  {step.body}
                </p>
              </div>
            ))}
          </div>
        </div>
      </div>

      {/* ── CTA ── */}
      <div style={{ background: NAVY, padding: "48px 0" }}>
        <div style={{ width: "min(1180px,92%)", margin: "0 auto", textAlign: "center" }}>
          <h2 style={{ color: "#fff", fontSize: "clamp(20px,3vw,28px)", fontWeight: 900, margin: "0 0 12px" }}>
            Know a Rising Junior Star?
          </h2>
          <p style={{ color: "#a8b8cc", fontSize: "14px", marginBottom: "28px", lineHeight: 1.6 }}>
            Share their story with the Eagle Sports community and inspire the next generation.
          </p>
          <Link
            to="/submit-story"
            style={{
              display: "inline-block", background: RED, color: "#fff",
              padding: "13px 32px", borderRadius: "4px",
              fontWeight: 800, fontSize: "13px",
              textDecoration: "none", letterSpacing: "0.5px",
            }}
          >
            SUBMIT A STORY →
          </Link>
        </div>
      </div>

      {/* responsive styles */}
      <style>{`
        .stats-grid  { grid-template-columns: repeat(4,1fr) !important; }
        .stories-grid { grid-template-columns: repeat(3,1fr) !important; }
        .pathway-grid { grid-template-columns: repeat(3,1fr) !important; }

        @media (max-width: 900px) {
          .stories-grid { grid-template-columns: repeat(2,1fr) !important; }
          .pathway-grid { grid-template-columns: repeat(1,1fr) !important; }
        }
        @media (max-width: 640px) {
          .stats-grid   { grid-template-columns: repeat(2,1fr) !important; }
          .stories-grid { grid-template-columns: repeat(1,1fr) !important; }
        }
      `}</style>
    </PageTemplate>
  );
};

/* ── Story card ── */
const StoryCard = ({ story }: { story: Story }) => (
  <Link to={`/stories/${story.slug}`} style={{ textDecoration: "none", display: "block", height: "100%" }}>
    <article
      style={{
        border: `1px solid ${LINE}`, borderRadius: "6px",
        overflow: "hidden", background: "#fff",
        height: "100%", display: "flex", flexDirection: "column",
        transition: "border-color .15s, box-shadow .15s",
      }}
      onMouseEnter={e => {
        (e.currentTarget as HTMLElement).style.borderColor = RED;
        (e.currentTarget as HTMLElement).style.boxShadow  = "0 4px 16px rgba(214,40,53,.08)";
      }}
      onMouseLeave={e => {
        (e.currentTarget as HTMLElement).style.borderColor = LINE;
        (e.currentTarget as HTMLElement).style.boxShadow  = "none";
      }}
    >
      {/* Image */}
      <div style={{ height: "186px", overflow: "hidden", background: "#e5eaf1", flexShrink: 0, position: "relative" }}>
        <img
          src={story.image_url}
          alt={story.title}
          style={{ width: "100%", height: "100%", objectFit: "cover", display: "block" }}
          loading="lazy"
        />
        <span style={{
          position: "absolute", top: "10px", left: "10px",
          background: RED, color: "#fff",
          fontSize: "9px", fontWeight: 900, letterSpacing: "1px",
          textTransform: "uppercase", padding: "4px 8px", borderRadius: "3px",
        }}>
          {story.category || "Junior Star"}
        </span>
      </div>

      {story.image_attribution && (
        <p style={{ margin: "6px 16px 0", fontSize: "11px", color: "#8896a5", fontStyle: "italic" }}>
          {story.image_attribution}
        </p>
      )}

      {/* Body */}
      <div style={{ padding: "16px", flex: 1, display: "flex", flexDirection: "column" }}>
        <h3 style={{
          fontSize: "16px", fontWeight: 800, color: NAVY,
          margin: "0 0 auto", lineHeight: 1.3,
          display: "-webkit-box", WebkitLineClamp: 2,
          WebkitBoxOrient: "vertical", overflow: "hidden",
        }}>
          {story.title}
        </h3>

        <div style={{
          display: "flex", alignItems: "center", justifyContent: "space-between",
          fontSize: "11px", color: "#8896a5",
          borderTop: `1px solid ${LINE}`, paddingTop: "10px", marginTop: "14px",
        }}>
          <span>{story.read_time ? `${story.read_time} min read` : ""}</span>
          <span style={{ color: RED, fontWeight: 800 }}>READ MORE →</span>
        </div>
      </div>
    </article>
  </Link>
);

export default JuniorAthletics;
