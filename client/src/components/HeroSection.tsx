import { useStories } from "@/contexts/StoriesContext";
import { useState, useEffect, useRef, useCallback } from "react";
import heroRunner from "@/assets/hero-runner.jpg";
import type { Story } from "@/types/story";

const RED          = "#d62835";
const CARD_OVERLAY = "linear-gradient(0deg,#06162bee 0%,#06162b18 70%)";
const SLIDE_HEIGHT = 390;
const AUTO_MS      = 5500;

const FALLBACKS = [
  { title: "Every Medal Tells a Beginning",              category: "Featured",           image_url: heroRunner, slug: null },
  { title: "Diamond League: The Season's Biggest Stages", category: "Competition Watch",  image_url: heroRunner, slug: null },
  { title: "Women Breaking Barriers in Kenyan Athletics", category: "Women in Athletics", image_url: heroRunner, slug: null },
  { title: "Road to the World Championships",            category: "Championships",       image_url: heroRunner, slug: null },
  { title: "Cross Country Season Preview",               category: "Cross Country",       image_url: heroRunner, slug: null },
  { title: "Training Secrets of Kenya's Elite",          category: "Training",            image_url: heroRunner, slug: null },
  { title: "Rising Stars of Kenyan Athletics",           category: "Athletes",            image_url: heroRunner, slug: null },
];

type HeroStory = { title: string; category: string; image_url: string; slug: string | null; excerpt?: string };

const toHero = (s: Story): HeroStory => ({
  title:     s.title,
  category:  s.category ?? "Feature",
  image_url: s.image_url || heroRunner,
  slug:      s.slug,
  excerpt:   s.excerpt,
});

/* ─── Main component ─── */
const HeroSection = () => {
  const { heroStories } = useStories();
  const [current, setCurrent] = useState(0);
  const timerRef = useRef<ReturnType<typeof setInterval> | null>(null);

  const all: HeroStory[] = heroStories.length >= 1
    ? heroStories.map(toHero)
    : FALLBACKS as HeroStory[];

  // Slider gets first 5, side cards get next 2 — zero overlap guaranteed
  const slides    = all.slice(0, Math.min(5, all.length));
  const sideAll   = all.slice(slides.length);
  const side1     = sideAll[0] ?? FALLBACKS[slides.length]     as HeroStory;
  const side2     = sideAll[1] ?? FALLBACKS[slides.length + 1] as HeroStory;

  const advance = useCallback(
    () => setCurrent(prev => (prev + 1) % slides.length),
    [slides.length],
  );

  const resetTimer = useCallback(() => {
    if (timerRef.current) clearInterval(timerRef.current);
    timerRef.current = setInterval(advance, AUTO_MS);
  }, [advance]);

  useEffect(() => {
    resetTimer();
    return () => { if (timerRef.current) clearInterval(timerRef.current); };
  }, [resetTimer]);

  const goTo = (idx: number) => { setCurrent(idx); resetTimer(); };
  const prev  = () => goTo((current - 1 + slides.length) % slides.length);
  const next  = () => goTo((current + 1) % slides.length);

  return (
    <section id="home" style={{ padding: "26px 0 30px", background: "#fff" }}>
      <div style={{ width: "min(1180px,92%)", margin: "0 auto" }}>

        <div className="hero-grid" style={{ display: "grid", gridTemplateColumns: "1.55fr 0.85fr", gap: "18px" }}>

          {/* ══ SLIDER ══ */}
          <div style={{ position: "relative", overflow: "hidden", borderRadius: "4px", height: `${SLIDE_HEIGHT}px` }}>

            {/* Sliding track */}
            <div
              style={{
                display: "flex",
                height: "100%",
                transform: `translateX(-${current * 100}%)`,
                transition: "transform 0.62s cubic-bezier(0.4,0,0.2,1)",
                willChange: "transform",
              }}
            >
              {slides.map((story, i) => (
                <a
                  key={i}
                  href={story.slug ? `/stories/${story.slug}` : "#stories"}
                  style={{ textDecoration: "none", minWidth: "100%", height: "100%", flexShrink: 0, display: "block" }}
                >
                  <article
                    style={{
                      width: "100%",
                      height: "100%",
                      position: "relative",
                      display: "flex",
                      alignItems: "flex-end",
                      color: "#fff",
                      background: `${CARD_OVERLAY}, url('${story.image_url}') center/cover no-repeat`,
                    }}
                  >
                    <div style={{ padding: "28px", maxWidth: "650px", position: "relative", zIndex: 1 }}>
                      <span style={{
                        display: "inline-block", background: RED, color: "white",
                        fontSize: "10px", letterSpacing: "1px", textTransform: "uppercase",
                        fontWeight: 900, padding: "5px 9px", borderRadius: "3px", marginBottom: "12px",
                      }}>
                        {story.category}
                      </span>

                      <h1 style={{
                        fontSize: "clamp(24px,3.5vw,40px)", lineHeight: 1.08,
                        letterSpacing: "-1px", margin: "0 0 12px", fontWeight: 900,
                      }}>
                        {story.title}
                      </h1>

                      <span style={{
                        display: "inline-block", background: RED, color: "#fff",
                        borderRadius: "4px", padding: "10px 15px",
                        fontWeight: 800, fontSize: "12px", letterSpacing: "0.3px",
                      }}>
                        READ STORY →
                      </span>

                      <div style={{ marginTop: "14px", fontSize: "11px", color: "#aab5c4" }}>
                        EAGLE SPORTS · FEATURED COVERAGE
                      </div>
                    </div>
                  </article>
                </a>
              ))}
            </div>

            {/* Prev / Next arrows */}
            {slides.length > 1 && (
              <>
                <button
                  onClick={prev}
                  aria-label="Previous story"
                  style={arrowStyle("left")}
                >
                  ‹
                </button>
                <button
                  onClick={next}
                  aria-label="Next story"
                  style={arrowStyle("right")}
                >
                  ›
                </button>
              </>
            )}

            {/* Dot indicators */}
            {slides.length > 1 && (
              <div style={{
                position: "absolute", bottom: "16px", right: "18px",
                display: "flex", gap: "6px", zIndex: 10,
              }}>
                {slides.map((_, i) => (
                  <button
                    key={i}
                    onClick={() => goTo(i)}
                    aria-label={`Go to slide ${i + 1}`}
                    style={{
                      width: i === current ? "24px" : "8px",
                      height: "8px",
                      borderRadius: "4px",
                      border: "none",
                      background: i === current ? RED : "rgba(255,255,255,0.45)",
                      cursor: "pointer",
                      padding: 0,
                      transition: "width 0.3s ease, background 0.3s ease",
                    }}
                  />
                ))}
              </div>
            )}
          </div>

          {/* ══ SIDE CARDS (never overlap the slider) ══ */}
          <div
            className="hero-side"
            style={{ display: "grid", gridTemplateRows: "1fr 1fr", gap: "14px" }}
          >
            <SideCard story={side1} />
            <SideCard story={side2} />
          </div>
        </div>
      </div>

      <style>{`
        @media (max-width: 768px) {
          .hero-grid { grid-template-columns: 1fr !important; }
          .hero-side { grid-template-columns: 1fr 1fr !important; grid-template-rows: auto !important; }
        }
        @media (max-width: 480px) {
          .hero-side { grid-template-columns: 1fr !important; }
        }
      `}</style>
    </section>
  );
};

/* ─── Arrow button style helper ─── */
const arrowStyle = (side: "left" | "right"): React.CSSProperties => ({
  position: "absolute",
  [side]: "12px",
  top: "50%",
  transform: "translateY(-50%)",
  zIndex: 10,
  background: "rgba(0,0,0,0.30)",
  border: "none",
  color: "#fff",
  width: "36px",
  height: "36px",
  borderRadius: "2px",
  cursor: "pointer",
  fontSize: "22px",
  lineHeight: 1,
  display: "flex",
  alignItems: "center",
  justifyContent: "center",
  padding: 0,
});

/* ─── Side story card ─── */
const SideCard = ({ story }: { story: HeroStory }) => (
  <a
    href={story.slug ? `/stories/${story.slug}` : "#stories"}
    style={{ textDecoration: "none", display: "block", height: "100%" }}
  >
    <article
      style={{
        minHeight: "183px",
        height: "100%",
        borderRadius: "4px",
        overflow: "hidden",
        position: "relative",
        color: "white",
        display: "flex",
        alignItems: "flex-end",
        background: `linear-gradient(0deg,#07172bec,#07172b10 85%), url('${story.image_url}') center/cover no-repeat`,
      }}
    >
      <div style={{ padding: "16px 18px", position: "relative", zIndex: 1 }}>
        <span style={{
          display: "inline-block", background: "#d62835", color: "white",
          fontSize: "9px", letterSpacing: "1px", textTransform: "uppercase",
          fontWeight: 900, padding: "4px 7px", borderRadius: "3px", marginBottom: "8px",
        }}>
          {story.category}
        </span>
        <h3 style={{ fontSize: "17px", lineHeight: 1.22, margin: "0 0 6px", fontWeight: 800, color: "#fff" }}>
          {story.title.length > 80 ? story.title.slice(0, 80) + "…" : story.title}
        </h3>
        <div style={{ fontSize: "10px", color: "#aab5c4", textTransform: "uppercase", letterSpacing: "0.5px" }}>
          EAGLE SPORTS · {story.category.toUpperCase()}
        </div>
      </div>
    </article>
  </a>
);

export default HeroSection;
