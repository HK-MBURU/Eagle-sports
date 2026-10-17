import { useStories } from "@/contexts/StoriesContext";
import heroRunner from "@/assets/hero-runner.jpg";
import type { Story } from "@/types/story";

const RED   = "#d62835";
const NAVY  = "#10284a";

/* ─── Gradient overlay used on every card ─── */
const CARD_OVERLAY = "linear-gradient(0deg,#06162bee 0%,#06162b18 70%)";

/* ─── Fallback content when no API data yet ─── */
const FALLBACKS = [
  {
    title: "Every Medal Tells a Beginning",
    excerpt: "Celebrating Kenyan athletes' journeys beyond their competitive careers — the stories that happen after the finish line.",
    category: "Featured",
    image_url: heroRunner,
    slug: null,
  },
  {
    title: "Diamond League: The Season's Biggest Stages",
    excerpt: "Meetings, results and athletes across the global Diamond League circuit.",
    category: "Competition Watch",
    image_url: heroRunner,
    slug: null,
  },
  {
    title: "Women Breaking Barriers in Kenyan Athletics",
    excerpt: "Empowering the next generation of women athletes through mentorship, coaching and advocacy.",
    category: "Women in Athletics",
    image_url: heroRunner,
    slug: null,
  },
];

type HeroStory = Pick<Story, "title" | "excerpt" | "category" | "image_url" | "slug"> & { image_url: string };

const HeroSection = () => {
  const { heroStories, heroLoading } = useStories();

  const stories: HeroStory[] = heroStories.length >= 1
    ? heroStories.slice(0, 3).map(s => ({
        title:     s.title,
        excerpt:   s.excerpt,
        category:  s.category ?? "Feature",
        image_url: s.image_url || heroRunner,
        slug:      s.slug,
      }))
    : FALLBACKS as HeroStory[];

  const main   = stories[0];
  const side1  = stories[1] ?? FALLBACKS[1] as HeroStory;
  const side2  = stories[2] ?? FALLBACKS[2] as HeroStory;

  return (
    <section
      id="home"
      style={{
        padding: "26px 0 30px",
        background: "#fff",
      }}
    >
      <div style={{ width: "min(1180px,92%)", margin: "0 auto" }}>

        {/* ─── HERO GRID ─── */}
        <div
          className="hero-grid"
          style={{
            display: "grid",
            gridTemplateColumns: "1.55fr 0.85fr",
            gap: "18px",
          }}
        >
          {/* ══ MAIN CARD ══ */}
          <a
            href={main.slug ? `/stories/${main.slug}` : "#stories"}
            style={{ textDecoration: "none", display: "block" }}
          >
            <article
              className="hero-main group"
              style={{
                minHeight: "390px",
                borderRadius: "4px",
                overflow: "hidden",
                position: "relative",
                display: "flex",
                alignItems: "flex-end",
                color: "#fff",
                background: `${CARD_OVERLAY}, url('${main.image_url}') center/cover no-repeat`,
              }}
            >
              <div
                style={{
                  padding: "28px",
                  maxWidth: "650px",
                  position: "relative",
                  zIndex: 1,
                }}
              >
                <span
                  style={{
                    display: "inline-block",
                    background: RED,
                    color: "white",
                    fontSize: "10px",
                    letterSpacing: "1px",
                    textTransform: "uppercase",
                    fontWeight: 900,
                    padding: "5px 9px",
                    borderRadius: "3px",
                    marginBottom: "12px",
                  }}
                >
                  {main.category}
                </span>

                <h1
                  style={{
                    fontSize: "clamp(24px,3.5vw,40px)",
                    lineHeight: 1.08,
                    letterSpacing: "-1px",
                    margin: "0 0 12px",
                    fontWeight: 900,
                  }}
                >
                  {main.title}
                </h1>

                <span
                  style={{
                    display: "inline-block",
                    background: RED,
                    color: "#fff",
                    border: "none",
                    borderRadius: "4px",
                    padding: "10px 15px",
                    fontWeight: 800,
                    fontSize: "12px",
                    cursor: "pointer",
                    letterSpacing: "0.3px",
                  }}
                >
                  {heroLoading && heroStories.length === 0 ? "Loading…" : "READ STORY →"}
                </span>

                <div style={{ marginTop: "14px", fontSize: "11px", color: "#aab5c4" }}>
                  EAGLE SPORTS · FEATURED COVERAGE
                </div>
              </div>
            </article>
          </a>

          {/* ══ SIDE CARDS ══ */}
          <div
            className="hero-side"
            style={{
              display: "grid",
              gridTemplateRows: "1fr 1fr",
              gap: "14px",
            }}
          >
            <SideCard story={side1} />
            <SideCard story={side2} />
          </div>
        </div>
      </div>

      {/* responsive breakpoint styles injected inline */}
      <style>{`
        @media (max-width: 768px) {
          .hero-grid {
            grid-template-columns: 1fr !important;
          }
          .hero-main {
            min-height: 320px !important;
          }
          .hero-side {
            grid-template-columns: 1fr 1fr !important;
            grid-template-rows: auto !important;
          }
        }
        @media (max-width: 480px) {
          .hero-side {
            grid-template-columns: 1fr !important;
          }
        }
      `}</style>
    </section>
  );
};

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
        <span
          style={{
            display: "inline-block",
            background: RED,
            color: "white",
            fontSize: "9px",
            letterSpacing: "1px",
            textTransform: "uppercase",
            fontWeight: 900,
            padding: "4px 7px",
            borderRadius: "3px",
            marginBottom: "8px",
          }}
        >
          {story.category}
        </span>
        <h3
          style={{
            fontSize: "17px",
            lineHeight: 1.22,
            margin: "0 0 6px",
            fontWeight: 800,
            color: "#fff",
          }}
        >
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
