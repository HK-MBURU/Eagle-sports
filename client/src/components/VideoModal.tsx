import { useEffect, useRef } from "react";
import { X } from "lucide-react";

interface VideoModalProps {
  isOpen: boolean;
  onClose: () => void;
  videoUrl: string;
  title: string;
  athleteName?: string;
}

const getEmbedUrl = (url: string): string | null => {
  if (!url) return null;
  // Already an embed URL
  if (url.includes("youtube.com/embed/")) {
    const base = url.split("?")[0];
    return `${base}?autoplay=1&rel=0&modestbranding=1`;
  }
  // youtu.be short link
  const short = url.match(/youtu\.be\/([^?&]+)/);
  if (short) return `https://www.youtube.com/embed/${short[1]}?autoplay=1&rel=0&modestbranding=1`;
  // Standard watch URL
  const watch = url.match(/[?&]v=([^?&#]+)/);
  if (watch) return `https://www.youtube.com/embed/${watch[1]}?autoplay=1&rel=0&modestbranding=1`;
  return null;
};

export const VideoModal = ({ isOpen, onClose, videoUrl, title, athleteName }: VideoModalProps) => {
  const overlayRef = useRef<HTMLDivElement>(null);
  const embedUrl = getEmbedUrl(videoUrl);

  // Close on Escape key
  useEffect(() => {
    if (!isOpen) return;
    const handler = (e: KeyboardEvent) => { if (e.key === "Escape") onClose(); };
    document.addEventListener("keydown", handler);
    document.body.style.overflow = "hidden";
    return () => {
      document.removeEventListener("keydown", handler);
      document.body.style.overflow = "";
    };
  }, [isOpen, onClose]);

  if (!isOpen) return null;

  return (
    <div
      ref={overlayRef}
      onClick={(e) => { if (e.target === overlayRef.current) onClose(); }}
      style={{
        position: "fixed",
        inset: 0,
        background: "rgba(0,0,0,0.85)",
        zIndex: 9999,
        display: "flex",
        alignItems: "center",
        justifyContent: "center",
        padding: "16px",
      }}
    >
      <div style={{
        width: "100%",
        maxWidth: 900,
        background: "#000",
        borderRadius: 12,
        overflow: "hidden",
        boxShadow: "0 24px 64px rgba(0,0,0,0.7)",
      }}>
        {/* Header */}
        <div style={{ background: "#d62835", padding: "16px 20px", display: "flex", justifyContent: "space-between", alignItems: "flex-start", gap: 12 }}>
          <div style={{ flex: 1 }}>
            <h2 style={{ margin: 0, fontSize: 18, fontWeight: 800, color: "#fff", lineHeight: 1.3 }}>{title}</h2>
            {athleteName && (
              <p style={{ margin: "4px 0 0", fontSize: 13, color: "rgba(255,255,255,0.85)" }}>{athleteName}</p>
            )}
          </div>
          <button
            onClick={onClose}
            style={{
              flexShrink: 0,
              background: "rgba(255,255,255,0.2)",
              border: "none",
              borderRadius: "50%",
              width: 36,
              height: 36,
              cursor: "pointer",
              display: "grid",
              placeItems: "center",
              color: "#fff",
            }}
            aria-label="Close"
          >
            <X size={20} />
          </button>
        </div>

        {/* Video */}
        <div style={{ position: "relative", paddingBottom: "56.25%", background: "#000" }}>
          {embedUrl ? (
            <iframe
              style={{ position: "absolute", inset: 0, width: "100%", height: "100%", border: "none" }}
              src={embedUrl}
              title={title}
              allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share"
              allowFullScreen
            />
          ) : (
            <div style={{ position: "absolute", inset: 0, display: "flex", alignItems: "center", justifyContent: "center" }}>
              <p style={{ color: "#fff", fontSize: 14 }}>Unable to load video</p>
            </div>
          )}
        </div>

        {/* Footer */}
        <div style={{ background: "#111", padding: "12px 20px", textAlign: "center" }}>
          <p style={{ margin: 0, fontSize: 12, color: "#888" }}>
            {athleteName ? <>Watching with <strong style={{ color: "#d62835" }}>{athleteName}</strong></> : "Eagle Sports — Beyond the Finish Line"}
          </p>
        </div>
      </div>
    </div>
  );
};
