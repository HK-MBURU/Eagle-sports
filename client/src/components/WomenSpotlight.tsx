import { useState } from "react";
import { Play, ArrowRight, Loader2 } from "lucide-react";
import femaleAthlete from "@/assets/female-athlete.jpg";
import { useWomenRunning } from "@/hooks/useWomenRunning";
import { getIconComponent } from "@/utils/iconMapper";
import { useNavigate } from "react-router-dom";
import { VideoModal } from "./VideoModal";

const NAVY = "#10284a";
const RED  = "#d62835";
const LINE = "#e2e7ef";

const WomenSpotlight = () => {
  const { entries, featuredEntry, loading, error } = useWomenRunning();
  const navigate = useNavigate();

  const [isVideoModalOpen, setIsVideoModalOpen] = useState(false);
  const [selectedVideo, setSelectedVideo] = useState<{ url: string; title: string; name: string } | null>(null);

  const handleWatchVideo = (url: string, title: string, name: string) => {
    setSelectedVideo({ url, title, name });
    setIsVideoModalOpen(true);
  };

  const closeVideoModal = () => {
    setIsVideoModalOpen(false);
    setTimeout(() => setSelectedVideo(null), 300);
  };

  /* ── Loading ── */
  if (loading) {
    return (
      <section id="women" style={{ background: "#fff", padding: "48px 0" }}>
        <div style={{ width: "min(1180px,92%)", margin: "0 auto", display: "flex", justifyContent: "center", padding: "48px 0" }}>
          <Loader2 size={28} style={{ color: RED }} className="animate-spin" />
        </div>
      </section>
    );
  }

  /* ── Error ── */
  if (error) {
    return (
      <section id="women" style={{ background: "#fff", padding: "48px 0" }}>
        <div style={{ width: "min(1180px,92%)", margin: "0 auto", textAlign: "center", color: "#667085", fontSize: 13 }}>
          Failed to load content —{" "}
          <button onClick={() => window.location.reload()} style={{ color: RED, fontWeight: 700, background: "none", border: "none", cursor: "pointer" }}>
            Retry
          </button>
        </div>
      </section>
    );
  }

  return (
    <section id="women" style={{ background: "#fff", padding: "48px 0" }}>
      <div style={{ width: "min(1180px,92%)", margin: "0 auto" }}>

        {/* Section header */}
        <div style={{ display: "flex", justifyContent: "space-between", alignItems: "flex-end", gap: 14, marginBottom: 20 }}>
          <div>
            <h2 style={{ margin: 0, color: NAVY, fontSize: 22, fontWeight: 900, letterSpacing: "-.5px", lineHeight: 1.1 }}>
              Women Breaking Barriers
            </h2>
            <p style={{ margin: "5px 0 0", fontSize: 12, color: "#667085" }}>
              Celebrating female athletes reshaping the landscape of Kenyan athletics.
            </p>
          </div>
          <a
            href="/impact/women"
            style={{ fontSize: 12, color: RED, fontWeight: 900, whiteSpace: "nowrap", textDecoration: "none" }}
          >
            EXPLORE →
          </a>
        </div>

        {/* Featured story panel */}
        {featuredEntry && (
          <div style={{
            border: `1px solid ${LINE}`,
            borderRadius: 10,
            overflow: "hidden",
            background: "#fff",
            display: "grid",
            gridTemplateColumns: "1fr 1fr",
            marginBottom: 18,
          }} className="women-hero">

            {/* Image */}
            <div style={{ position: "relative", minHeight: 300 }}>
              <img
                src={featuredEntry.image || femaleAthlete}
                alt={featuredEntry.name}
                style={{ width: "100%", height: "100%", objectFit: "cover", display: "block" }}
                onError={(e) => { (e.target as HTMLImageElement).src = femaleAthlete; }}
              />
              {/* Play overlay */}
              {featuredEntry.video_url && (
                <div
                  style={{ position: "absolute", inset: 0, display: "flex", alignItems: "center", justifyContent: "center",
                    background: "rgba(7,23,43,.2)", cursor: "pointer" }}
                  onClick={() => handleWatchVideo(featuredEntry.video_url!, featuredEntry.title, featuredEntry.name)}
                >
                  <div style={{
                    width: 52, height: 52, borderRadius: "50%", background: RED,
                    display: "flex", alignItems: "center", justifyContent: "center",
                  }}>
                    <Play size={22} fill="#fff" color="#fff" style={{ marginLeft: 3 }} />
                  </div>
                </div>
              )}
            </div>

            {/* Content */}
            <div style={{ padding: "32px 36px", display: "flex", flexDirection: "column", justifyContent: "center" }}>
              <span style={{
                display: "inline-block",
                background: "#fcecee", color: RED,
                padding: "4px 12px", borderRadius: 20,
                fontSize: 10, fontWeight: 900, letterSpacing: ".5px",
                textTransform: "uppercase", marginBottom: 14, alignSelf: "flex-start",
              }}>
                Featured Story
              </span>

              <h3 style={{ margin: "0 0 12px", fontSize: 20, fontWeight: 900, color: NAVY, lineHeight: 1.25 }}>
                {featuredEntry.title}
              </h3>
              <p style={{ margin: "0 0 20px", fontSize: 13, color: "#667085", lineHeight: 1.7,
                overflow: "hidden", display: "-webkit-box", WebkitLineClamp: 4, WebkitBoxOrient: "vertical" }}>
                {featuredEntry.description}
              </p>

              <div style={{ display: "flex", gap: 10, flexWrap: "wrap" }}>
                <button
                  onClick={() => navigate(`/women-stories/${featuredEntry.id}`)}
                  style={{
                    padding: "9px 18px", borderRadius: 5,
                    background: RED, color: "#fff",
                    border: "none", fontWeight: 800, fontSize: 12, cursor: "pointer",
                    transition: "background .15s",
                  }}
                  className="women-btn-primary"
                >
                  Read Full Story
                </button>
                {featuredEntry.video_url && (
                  <button
                    onClick={() => handleWatchVideo(featuredEntry.video_url!, featuredEntry.title, featuredEntry.name)}
                    style={{
                      padding: "9px 16px", borderRadius: 5,
                      background: "#fff", color: NAVY,
                      border: `1px solid ${LINE}`, fontWeight: 800, fontSize: 12, cursor: "pointer",
                      display: "flex", alignItems: "center", gap: 7,
                      transition: "border-color .15s",
                    }}
                    className="women-btn-outline"
                  >
                    <Play size={13} /> Watch Interview
                  </button>
                )}
              </div>
            </div>
          </div>
        )}

        {/* Stories grid */}
        {entries.length > 0 && (
          <div style={{ display: "grid", gridTemplateColumns: "repeat(3,1fr)", gap: 16 }} className="women-grid">
            {entries.map((entry) => {
              const Icon = getIconComponent(entry.icon);
              return (
                <div
                  key={entry.id}
                  onClick={() => navigate(`/women-stories/${entry.id}`)}
                  className="women-card"
                  style={{
                    border: `1px solid ${LINE}`,
                    borderRadius: 10,
                    background: "#fff",
                    padding: "20px 22px",
                    cursor: "pointer",
                    transition: "border-color .15s",
                  }}
                >
                  <div style={{
                    display: "inline-flex", alignItems: "center", justifyContent: "center",
                    width: 42, height: 42, borderRadius: "50%",
                    background: "#f4f6f9", color: NAVY, marginBottom: 14,
                  }}>
                    <Icon size={20} />
                  </div>
                  <h3 style={{ margin: "0 0 4px", fontSize: 15, fontWeight: 900, color: NAVY, lineHeight: 1.3 }}>
                    {entry.title}
                  </h3>
                  <p style={{ margin: "0 0 8px", fontSize: 11, fontWeight: 800, color: RED }}>
                    {entry.name}
                  </p>
                  <p style={{ margin: 0, fontSize: 12, color: "#667085", lineHeight: 1.6,
                    overflow: "hidden", display: "-webkit-box", WebkitLineClamp: 3, WebkitBoxOrient: "vertical" }}>
                    {entry.description}
                  </p>
                </div>
              );
            })}
          </div>
        )}

        {/* Empty state */}
        {!loading && entries.length === 0 && !featuredEntry && (
          <div style={{ padding: "32px 0", color: "#667085", fontSize: 13, textAlign: "center" }}>
            No stories available at the moment. Check back soon.
          </div>
        )}

        {/* CTA */}
        <div style={{ textAlign: "center", marginTop: 24 }}>
          <p style={{ fontSize: 13, color: "#667085", margin: "0 0 12px" }}>More stories about women in athletics</p>
          <button
            onClick={() => window.location.href = "/impact/women"}
            style={{
              padding: "10px 24px", borderRadius: 5,
              background: "#fff", color: NAVY,
              border: `1px solid ${LINE}`, fontWeight: 800, fontSize: 12,
              cursor: "pointer", display: "inline-flex", alignItems: "center", gap: 7,
              transition: "border-color .15s",
            }}
            className="women-btn-outline"
          >
            Explore <ArrowRight size={14} />
          </button>
        </div>

      </div>

      {selectedVideo && (
        <VideoModal
          isOpen={isVideoModalOpen}
          onClose={closeVideoModal}
          videoUrl={selectedVideo.url}
          title={selectedVideo.title}
          athleteName={selectedVideo.name}
        />
      )}

      <style>{`
        .women-card:hover { border-color: ${RED} !important; }
        .women-btn-primary:hover { background: #b51e2a !important; }
        .women-btn-outline:hover { border-color: ${NAVY} !important; }
        @media (max-width: 760px) {
          .women-hero { grid-template-columns: 1fr !important; }
          .women-grid { grid-template-columns: 1fr 1fr !important; }
        }
        @media (max-width: 480px) {
          .women-grid { grid-template-columns: 1fr !important; }
        }
      `}</style>
    </section>
  );
};

export default WomenSpotlight;
