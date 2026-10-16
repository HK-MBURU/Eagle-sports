import { useState, useEffect } from "react";
import { videosAPI } from "@/services/api";
import type { Video } from "@/types/video";
import { VideoModal } from "./VideoModal";

const NAVY = "#10284a";
const RED  = "#d62835";
const LINE = "#e2e7ef";

const VideoGallery = () => {
  const [videos, setVideos]           = useState<Video[]>([]);
  const [loading, setLoading]         = useState(true);
  const [isModalOpen, setIsModalOpen] = useState(false);
  const [selectedVideo, setSelectedVideo] = useState<Video | null>(null);

  const extractYouTubeId = (url: string): string | null => {
    const m = url.match(/(?:youtube\.com\/(?:[^/]+\/.+\/|(?:v|e(?:mbed)?)\/|.*[?&]v=)|youtu\.be\/)([^"&?/\s]{11})/);
    return m ? m[1] : null;
  };

  const getThumbnail = (v: Video): string => {
    if (v.youtube_thumbnail) return v.youtube_thumbnail;
    const id = extractYouTubeId(v.youtube_url);
    return id ? `https://img.youtube.com/vi/${id}/maxresdefault.jpg` : "";
  };

  const getFallbackThumbnail = (v: Video): string => {
    const id = extractYouTubeId(v.youtube_url);
    return id ? `https://img.youtube.com/vi/${id}/hqdefault.jpg` : "";
  };

  const handlePlay = (v: Video) => {
    setSelectedVideo(v);
    setIsModalOpen(true);
  };

  const closeModal = () => {
    setIsModalOpen(false);
    setTimeout(() => setSelectedVideo(null), 250);
  };

  useEffect(() => {
    videosAPI.getFeaturedVideos(4)
      .then((res: { success: boolean; data: Video[] }) => {
        if (res.success) setVideos(res.data.slice(0, 4));
      })
      .catch(console.error)
      .finally(() => setLoading(false));
  }, []);

  /* ── Skeleton ── */
  const skeletonCard = (wide = false) => (
    <div style={{
      borderRadius: 10, overflow: "hidden",
      background: "#dde3eb",
      minHeight: wide ? 240 : 205,
      gridColumn: wide ? "1 / -1" : undefined,
    }} className="animate-pulse" />
  );

  return (
    <section id="videos" style={{ background: "#f4f6f9", padding: "32px 0" }}>
      <div style={{ width: "min(1180px,92%)", margin: "0 auto" }}>

        {selectedVideo && (
          <VideoModal
            isOpen={isModalOpen}
            onClose={closeModal}
            videoUrl={selectedVideo.youtube_embed_url || selectedVideo.youtube_url}
            title={selectedVideo.title}
            athleteName={selectedVideo.creator?.name || selectedVideo.category_name}
          />
        )}

        {/* Section header */}
        <div style={{ display: "flex", justifyContent: "space-between", alignItems: "flex-end", gap: 14, marginBottom: 18 }}>
          <div>
            <h2 style={{ margin: 0, color: NAVY, fontSize: 22, fontWeight: 900, letterSpacing: "-.5px", lineHeight: 1.1 }}>
              Watch &amp; Explore
            </h2>
            <p style={{ margin: "5px 0 0", fontSize: 12, color: "#667085" }}>
              Video interviews, explainers, race highlights and event photography.
            </p>
          </div>
          <a
            href="https://www.youtube.com/@BeyondthefinishlineTV"
            target="_blank" rel="noopener noreferrer"
            style={{ fontSize: 12, color: RED, fontWeight: 900, whiteSpace: "nowrap", textDecoration: "none" }}
          >
            ALL MULTIMEDIA →
          </a>
        </div>

        {/* Video grid */}
        <div style={{ display: "grid", gridTemplateColumns: "1.2fr 1fr 1fr", gap: 16 }} className="es-video-grid">
          {loading ? (
            <>
              {skeletonCard(false)}
              {skeletonCard(false)}
              {skeletonCard(false)}
            </>
          ) : videos.length === 0 ? (
            <div style={{ gridColumn: "1/-1", padding: "32px 0", color: "#667085", fontSize: 13, textAlign: "center" }}>
              No videos available.
            </div>
          ) : (
            videos.map((v) => (
              <div
                key={v.id}
                onClick={() => handlePlay(v)}
                className="es-video-card"
                style={{
                  borderRadius: 10,
                  overflow: "hidden",
                  background: NAVY,
                  color: "#fff",
                  position: "relative",
                  minHeight: 205,
                  display: "flex",
                  alignItems: "flex-end",
                  cursor: "pointer",
                }}
              >
                {/* Thumbnail image */}
                <img
                  src={getThumbnail(v)}
                  alt={v.title}
                  onError={(e) => {
                    const img = e.currentTarget;
                    const fallback = getFallbackThumbnail(v);
                    if (img.src !== fallback && fallback) {
                      img.src = fallback;
                    } else {
                      img.style.display = "none";
                    }
                  }}
                  style={{
                    position: "absolute",
                    inset: 0,
                    width: "100%",
                    height: "100%",
                    objectFit: "cover",
                    zIndex: 0,
                  }}
                />

                {/* Dark overlay */}
                <div style={{
                  position: "absolute",
                  inset: 0,
                  background: "linear-gradient(0deg,#07172bf0 0%,#07172b30 60%,transparent 100%)",
                  zIndex: 1,
                }} />

                {/* Play button */}
                <div style={{
                  position: "absolute",
                  top: 15,
                  left: 15,
                  width: 36,
                  height: 36,
                  borderRadius: "50%",
                  background: RED,
                  display: "grid",
                  placeItems: "center",
                  fontSize: 12,
                  zIndex: 2,
                }}>
                  ▶
                </div>

                {/* Bottom text */}
                <div style={{
                  position: "relative",
                  width: "100%",
                  padding: 18,
                  zIndex: 2,
                }}>
                  <h3 style={{ fontSize: 17, lineHeight: 1.2, margin: 0, fontWeight: 800 }}>
                    {v.title}
                  </h3>
                  <p style={{ fontSize: 10, color: "#d3dceb", margin: "6px 0 0", textTransform: "uppercase", letterSpacing: ".5px", fontWeight: 700 }}>
                    {v.category_name}
                  </p>
                </div>
              </div>
            ))
          )}
        </div>

      </div>

      <style>{`
        .es-video-grid { grid-template-columns: 1.2fr 1fr 1fr; }
        .es-video-card { transition: filter .15s; }
        .es-video-card:hover { filter: brightness(1.08); }
        @media (max-width: 760px) {
          .es-video-grid { grid-template-columns: 1fr 1fr !important; }
          .es-video-grid .es-video-card:first-child { grid-column: 1 / -1; }
        }
        @media (max-width: 430px) {
          .es-video-grid { grid-template-columns: 1fr !important; }
          .es-video-grid .es-video-card:first-child { grid-column: auto; }
        }
      `}</style>
    </section>
  );
};

export default VideoGallery;
