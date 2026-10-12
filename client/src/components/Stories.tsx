import { useState, useEffect } from "react";
import { storiesAPI } from "@/services/api";
import type { Story } from "@/types/story";
import { Link, useNavigate } from "react-router-dom";

const RED  = "#d62835";
const NAVY = "#10284a";
const LINE = "#e2e7ef";

interface HomepageStoriesResponse {
  success: boolean;
  data: {
    featured: Story | null;
    regular: Story[];
    total_stories: number;
  };
}

const Stories = () => {
  const navigate = useNavigate();
  const [homepageStories, setHomepageStories] = useState<{
    featured: Story | null;
    regular: Story[];
  } | null>(null);
  const [loading, setLoading] = useState(true);
  const [error, setError]     = useState<string | null>(null);

  useEffect(() => {
    const fetchHomepageStories = async () => {
      try {
        setLoading(true);
        setError(null);
        const response: HomepageStoriesResponse = await storiesAPI.getHomepageStories();
        if (response.success) setHomepageStories(response.data);
      } catch (err) {
        setError(err instanceof Error ? err.message : "Failed to load stories");
        console.error("Error fetching homepage stories:", err);
      } finally {
        setLoading(false);
      }
    };
    fetchHomepageStories();
  }, []);

  /* ── Skeleton ── */
  if (loading) {
    return (
      <section id="stories" style={{ background: "#f4f6f9", padding: "48px 0" }}>
        <div style={{ width: "min(1180px,92%)", margin: "0 auto" }}>
          <div style={{ display: "flex", justifyContent: "space-between", alignItems: "flex-end", marginBottom: 24 }}>
            <div style={{ height: 28, width: 200, background: "#dde3eb", borderRadius: 4 }} className="animate-pulse" />
            <div style={{ height: 14, width: 100, background: "#dde3eb", borderRadius: 4 }} className="animate-pulse" />
          </div>
          <div style={{ display: "grid", gridTemplateColumns: "repeat(3,1fr)", gap: 18 }}>
            {[...Array(3)].map((_, i) => (
              <div key={i} style={{ border: `1px solid ${LINE}`, borderRadius: 10, overflow: "hidden", background: "#fff" }}>
                <div style={{ height: 180, background: "#dde3eb" }} className="animate-pulse" />
                <div style={{ padding: "16px 18px" }}>
                  <div style={{ height: 10, width: 60,  background: "#dde3eb", borderRadius: 3, marginBottom: 8 }} className="animate-pulse" />
                  <div style={{ height: 20, width: "90%", background: "#dde3eb", borderRadius: 3, marginBottom: 6 }} className="animate-pulse" />
                  <div style={{ height: 20, width: "65%", background: "#dde3eb", borderRadius: 3 }} className="animate-pulse" />
                </div>
              </div>
            ))}
          </div>
        </div>
      </section>
    );
  }

  /* ── Error ── */
  if (error) {
    return (
      <section id="stories" style={{ background: "#f4f6f9", padding: "48px 0" }}>
        <div style={{ width: "min(1180px,92%)", margin: "0 auto", textAlign: "center", padding: "48px 0" }}>
          <p style={{ color: RED, marginBottom: 16, fontSize: 14 }}>{error}</p>
          <button
            onClick={() => window.location.reload()}
            style={{ background: NAVY, color: "#fff", border: "none", padding: "10px 24px", borderRadius: 5, cursor: "pointer", fontWeight: 700 }}
          >
            Try Again
          </button>
        </div>
      </section>
    );
  }

  const { featured, regular } = homepageStories || {};

  return (
    <section id="stories" style={{ background: "#f4f6f9", padding: "48px 0" }}>
      <style>{`
        .es-card { transition: transform .15s, box-shadow .15s; }
        .es-card:hover { transform: translateY(-3px); box-shadow: 0 10px 25px rgba(20,39,71,0.08); }
        .es-featured { transition: box-shadow .15s; }
        .es-featured:hover { box-shadow: 0 12px 32px rgba(20,39,71,0.1); }
        @media (max-width: 900px) {
          .es-cards-grid { grid-template-columns: repeat(2,1fr) !important; }
        }
        @media (max-width: 600px) {
          .es-cards-grid { grid-template-columns: 1fr !important; }
          .es-featured { grid-template-columns: 1fr !important; }
        }
      `}</style>

      <div style={{ width: "min(1180px,92%)", margin: "0 auto" }}>

        {/* ── Section head ── */}
        <div style={{ display: "flex", justifyContent: "space-between", alignItems: "flex-end", marginBottom: 24 }}>
          <div>
            <div style={{ fontSize: 11, fontWeight: 900, color: RED, letterSpacing: "1.5px", textTransform: "uppercase", marginBottom: 5 }}>
              LATEST FROM EAGLE SPORTS
            </div>
            <h2 style={{ fontSize: "clamp(22px,3vw,28px)", fontWeight: 900, color: NAVY, margin: 0, letterSpacing: "-0.5px" }}>
              News &amp; Stories
            </h2>
          </div>
          <Link
            to="/stories/inspire"
            style={{ fontSize: 12, color: RED, fontWeight: 900, textDecoration: "none", letterSpacing: "0.5px", whiteSpace: "nowrap" }}
          >
            ALL STORIES →
          </Link>
        </div>

        {/* ── Featured story ── */}
        {featured && (
          <div style={{ marginBottom: 24 }}>
            <Link to={`/stories/${featured.slug}`} style={{ textDecoration: "none" }}>
              <article
                className="es-featured"
                style={{
                  display: "grid",
                  gridTemplateColumns: "1fr 1.5fr",
                  border: `1px solid ${LINE}`,
                  borderLeft: `5px solid ${RED}`,
                  borderRadius: 12,
                  overflow: "hidden",
                  background: "#fff",
                }}
              >
                <div style={{ height: 300, overflow: "hidden", background: "#dde3eb" }}>
                  <img
                    src={featured.image_url}
                    alt={featured.title}
                    style={{ width: "100%", height: "100%", objectFit: "cover", display: "block" }}
                    loading="lazy"
                  />
                </div>

                <div style={{ padding: "28px 32px", display: "flex", flexDirection: "column", justifyContent: "center" }}>
                  <div style={{ fontSize: 10, fontWeight: 900, color: RED, textTransform: "uppercase", letterSpacing: "1px", marginBottom: 10 }}>
                    FEATURED · {featured.category}
                  </div>
                  <h3 style={{ fontSize: "clamp(20px,2.4vw,28px)", fontWeight: 900, color: NAVY, margin: "0 0 12px", lineHeight: 1.2, letterSpacing: "-0.5px" }}>
                    {featured.title}
                  </h3>
                  <p style={{ color: "#4a5568", fontSize: 14, lineHeight: 1.65, margin: "0 0 18px" }}>
                    {featured.excerpt.length > 200 ? featured.excerpt.slice(0, 200) + "…" : featured.excerpt}
                  </p>
                  <div style={{ display: "flex", flexWrap: "wrap", gap: "6px 18px", fontSize: 11, color: "#8896a5", borderTop: `1px solid ${LINE}`, paddingTop: 14, marginBottom: 16 }}>
                    {featured.author_name  && <span>By {featured.author_name}</span>}
                    {featured.read_time    && <span>{featured.read_time} min read</span>}
                    {featured.formatted_date && <span>{featured.formatted_date}</span>}
                  </div>
                  <span style={{ fontSize: 13, color: RED, fontWeight: 800, letterSpacing: "0.3px" }}>
                    READ FULL STORY →
                  </span>
                </div>
              </article>
            </Link>
          </div>
        )}

        {/* ── Card grid ── */}
        {regular && regular.length > 0 ? (
          <>
            <div
              className="es-cards-grid"
              style={{ display: "grid", gridTemplateColumns: "repeat(3,1fr)", gap: 18, marginBottom: 32 }}
            >
              {regular.map(story => (
                <StoryCard key={story.id} story={story} />
              ))}
            </div>

            <div style={{ textAlign: "center" }}>
              <button
                onClick={() => navigate("/stories/inspire")}
                style={{
                  background: NAVY,
                  color: "#fff",
                  border: "none",
                  borderRadius: 5,
                  padding: "12px 32px",
                  fontWeight: 800,
                  fontSize: 13,
                  letterSpacing: "0.4px",
                  cursor: "pointer",
                  transition: "background 0.15s",
                }}
                onMouseEnter={e => { (e.currentTarget as HTMLButtonElement).style.background = RED; }}
                onMouseLeave={e => { (e.currentTarget as HTMLButtonElement).style.background = NAVY; }}
              >
                EXPLORE MORE STORIES →
              </button>
            </div>
          </>
        ) : (
          <div style={{ textAlign: "center", padding: "48px 0", color: "#8896a5", fontSize: 14 }}>
            No stories found.
          </div>
        )}

      </div>
    </section>
  );
};

/* ── Story card ── */
const StoryCard = ({ story }: { story: Story }) => (
  <Link to={`/stories/${story.slug}`} style={{ textDecoration: "none", display: "block", height: "100%" }}>
    <article
      className="es-card"
      style={{
        border: `1px solid ${LINE}`,
        borderRadius: 10,
        overflow: "hidden",
        background: "#fff",
        height: "100%",
        display: "flex",
        flexDirection: "column",
      }}
    >
      <div style={{ height: 180, overflow: "hidden", background: "#dde3eb", flexShrink: 0 }}>
        <img
          src={story.image_url}
          alt={story.title}
          style={{ width: "100%", height: "100%", objectFit: "cover", display: "block" }}
          loading="lazy"
        />
      </div>

      <div style={{ padding: "16px 18px", flex: 1, display: "flex", flexDirection: "column" }}>
        <div style={{ fontSize: 10, textTransform: "uppercase", letterSpacing: "1px", color: RED, fontWeight: 900, marginBottom: 6 }}>
          {story.category}
        </div>
        <h3 style={{ fontSize: 17, lineHeight: 1.25, color: NAVY, margin: "0 0 8px", fontWeight: 800 }}>
          {story.title.length > 80 ? story.title.slice(0, 80) + "…" : story.title}
        </h3>
        <p style={{ fontSize: 13, color: "#667085", lineHeight: 1.55, margin: "0 0 14px", flex: 1 }}>
          {story.excerpt.length > 110 ? story.excerpt.slice(0, 110) + "…" : story.excerpt}
        </p>
        <div style={{ display: "flex", alignItems: "center", justifyContent: "space-between", fontSize: 11, color: "#8896a5", borderTop: `1px solid ${LINE}`, paddingTop: 10 }}>
          {story.read_time ? <span>{story.read_time} min read</span> : <span />}
          <span style={{ color: RED, fontWeight: 800 }}>READ MORE →</span>
        </div>
      </div>
    </article>
  </Link>
);

export default Stories;
