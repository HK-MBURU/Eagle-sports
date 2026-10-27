import { useState, useEffect, useCallback, useRef } from "react";
import PageTemplate from "@/components/PageTemplate";
import { VideoModal } from "@/components/VideoModal";
import { videosAPI } from "@/services/api";
import type { Video } from "@/types/video";

const NAVY = "#10284a";
const RED  = "#d62835";

const extractYouTubeId = (url: string): string | null => {
  const m = url.match(/(?:youtube\.com\/(?:[^/]+\/.+\/|(?:v|e(?:mbed)?)\/|.*[?&]v=)|youtu\.be\/)([^"&?/\s]{11})/);
  return m ? m[1] : null;
};
const getThumbnail = (v: Video) =>
  v.youtube_thumbnail ||
  (extractYouTubeId(v.youtube_url)
    ? `https://img.youtube.com/vi/${extractYouTubeId(v.youtube_url)}/maxresdefault.jpg`
    : "");
const getFallback = (v: Video) => {
  const id = extractYouTubeId(v.youtube_url);
  return id ? `https://img.youtube.com/vi/${id}/hqdefault.jpg` : "";
};

const SkeletonCard = () => (
  <div className="animate-pulse" style={{
    borderRadius: 10, overflow: "hidden",
    background: "#dde3eb", minHeight: 220,
  }} />
);

const VideoCard = ({ v, onPlay }: { v: Video; onPlay: (v: Video) => void }) => (
  <div
    onClick={() => onPlay(v)}
    className="es-vi-card"
    style={{
      borderRadius: 10, overflow: "hidden",
      background: NAVY, color: "#fff",
      position: "relative", minHeight: 220,
      display: "flex", alignItems: "flex-end",
      cursor: "pointer",
    }}
  >
    <img
      src={getThumbnail(v)}
      alt={v.title}
      onError={(e) => {
        const img = e.currentTarget;
        const fb = getFallback(v);
        if (img.src !== fb && fb) img.src = fb;
        else img.style.display = "none";
      }}
      style={{ position: "absolute", inset: 0, width: "100%", height: "100%", objectFit: "cover", zIndex: 0 }}
    />
    <div style={{
      position: "absolute", inset: 0,
      background: "linear-gradient(0deg,#07172bf0 0%,#07172b30 60%,transparent 100%)",
      zIndex: 1,
    }} />
    <div style={{
      position: "absolute", top: 12, left: 12,
      width: 34, height: 34, borderRadius: "50%",
      background: RED, display: "grid", placeItems: "center",
      fontSize: 11, zIndex: 2,
    }}>▶</div>
    <div style={{ position: "relative", width: "100%", padding: "14px 16px", zIndex: 2 }}>
      <h3 style={{ fontSize: 15, lineHeight: 1.25, margin: 0, fontWeight: 800 }}>{v.title}</h3>
      <p style={{ fontSize: 10, color: "#d3dceb", margin: "5px 0 0", textTransform: "uppercase", letterSpacing: ".5px", fontWeight: 700 }}>
        {v.category_name}
      </p>
    </div>
  </div>
);

const Interviews = () => {
  const [videos, setVideos]       = useState<Video[]>([]);
  const [loading, setLoading]     = useState(true);
  const [loadingMore, setLoadingMore] = useState(false);
  const [search, setSearch]       = useState("");
  const [activeCategory, setActiveCategory] = useState("All");
  const [categories, setCategories] = useState<string[]>([]);
  const [currentPage, setCurrentPage]   = useState(1);
  const [lastPage, setLastPage]         = useState(1);
  const [total, setTotal]               = useState(0);
  const [selectedVideo, setSelectedVideo] = useState<Video | null>(null);
  const [isModalOpen, setIsModalOpen]   = useState(false);
  const searchTimer = useRef<ReturnType<typeof setTimeout> | null>(null);

  const PER_PAGE = 12;

  const fetchVideos = useCallback(async (page = 1, append = false) => {
    if (page === 1) setLoading(true); else setLoadingMore(true);

    try {
      const params: Parameters<typeof videosAPI.getVideos>[0] = {
        page,
        per_page: PER_PAGE,
        ...(search.trim()        ? { search: search.trim() }                  : {}),
        ...(activeCategory !== "All" ? { category: activeCategory }           : {}),
      };

      const res = await videosAPI.getVideos(params);

      if (res.success) {
        const data: Video[] = res.data?.videos ?? [];
        const pag = res.data?.pagination;
        setVideos(prev => append ? [...prev, ...data] : data);
        setCurrentPage(pag?.current_page ?? page);
        setLastPage(pag?.last_page ?? 1);
        setTotal(pag?.total ?? data.length);

        // Build category list from first load using res.data.categories
        if (page === 1 && !append && activeCategory === "All" && !search) {
          const cats = res.data?.categories;
          if (cats && typeof cats === "object") {
            setCategories(Object.values(cats) as string[]);
          } else {
            const seen = new Set<string>();
            data.forEach((v: Video) => { if (v.category_name) seen.add(v.category_name); });
            if (seen.size > 0) setCategories(Array.from(seen));
          }
        }
      }
    } catch {
      // keep showing whatever was already there
    } finally {
      setLoading(false);
      setLoadingMore(false);
    }
  }, [search, activeCategory]);

  // Initial load + category change
  useEffect(() => {
    fetchVideos(1, false);
  }, [activeCategory, fetchVideos]);

  // Debounced search
  useEffect(() => {
    if (searchTimer.current) clearTimeout(searchTimer.current);
    searchTimer.current = setTimeout(() => {
      fetchVideos(1, false);
    }, 380);
    return () => { if (searchTimer.current) clearTimeout(searchTimer.current); };
  }, [search]);

  const handleLoadMore = () => fetchVideos(currentPage + 1, true);

  const handlePlay = (v: Video) => { setSelectedVideo(v); setIsModalOpen(true); };
  const closeModal = () => { setIsModalOpen(false); setTimeout(() => setSelectedVideo(null), 250); };

  const allCategories = ["All", ...categories];

  return (
    <PageTemplate
      title="Interviews & Videos"
      description="In-depth conversations with athletes, coaches, and sports personalities — plus race highlights and event coverage."
    >
      {selectedVideo && (
        <VideoModal
          isOpen={isModalOpen}
          onClose={closeModal}
          videoUrl={selectedVideo.youtube_embed_url || selectedVideo.youtube_url}
          title={selectedVideo.title}
          athleteName={selectedVideo.creator?.name || selectedVideo.category_name}
        />
      )}

      <div style={{ width: "min(1180px,92%)", margin: "0 auto", padding: "32px 0 56px" }}>

        {/* Search + filter bar */}
        <div style={{ display: "flex", flexWrap: "wrap", gap: 12, alignItems: "center", marginBottom: 24 }}>
          {/* Search */}
          <div style={{ position: "relative", flex: "1 1 220px", minWidth: 200 }}>
            <span style={{
              position: "absolute", left: 12, top: "50%", transform: "translateY(-50%)",
              color: "#9aa5b4", fontSize: 14, pointerEvents: "none",
            }}>🔍</span>
            <input
              type="text"
              placeholder="Search videos..."
              value={search}
              onChange={e => setSearch(e.target.value)}
              style={{
                width: "100%", padding: "10px 14px 10px 36px",
                border: "1.5px solid #e2e7ef", borderRadius: 8,
                fontSize: 14, outline: "none", background: "#fff",
                color: NAVY, boxSizing: "border-box",
              }}
            />
          </div>

          {/* Category pills */}
          {allCategories.length > 1 && (
            <div style={{ display: "flex", flexWrap: "wrap", gap: 8 }}>
              {allCategories.map(cat => (
                <button
                  key={cat}
                  onClick={() => setActiveCategory(cat)}
                  style={{
                    padding: "7px 16px",
                    borderRadius: 20,
                    border: "1.5px solid",
                    borderColor: activeCategory === cat ? RED : "#d1d9e0",
                    background: activeCategory === cat ? RED : "#fff",
                    color: activeCategory === cat ? "#fff" : "#4a5568",
                    fontSize: 13, fontWeight: 600,
                    cursor: "pointer",
                    transition: "all .15s",
                  }}
                >
                  {cat}
                </button>
              ))}
            </div>
          )}

          {/* Count */}
          {!loading && total > 0 && (
            <span style={{ fontSize: 12, color: "#667085", marginLeft: "auto", whiteSpace: "nowrap" }}>
              {total} video{total !== 1 ? "s" : ""}
            </span>
          )}
        </div>

        {/* Grid */}
        {loading ? (
          <div className="es-vi-grid">
            {Array.from({ length: 6 }).map((_, i) => <SkeletonCard key={i} />)}
          </div>
        ) : videos.length === 0 ? (
          <div style={{
            textAlign: "center", padding: "64px 0",
            color: "#667085", fontSize: 15,
          }}>
            <div style={{ fontSize: 40, marginBottom: 12 }}>🎥</div>
            <p style={{ margin: 0, fontWeight: 600 }}>No videos found</p>
            <p style={{ margin: "6px 0 0", fontSize: 13 }}>
              {search ? `No results for "${search}"` : "Check back soon for new content."}
            </p>
            {(search || activeCategory !== "All") && (
              <button
                onClick={() => { setSearch(""); setActiveCategory("All"); }}
                style={{
                  marginTop: 16, padding: "8px 20px",
                  background: RED, color: "#fff",
                  border: "none", borderRadius: 6,
                  fontSize: 13, fontWeight: 700, cursor: "pointer",
                }}
              >Clear filters</button>
            )}
          </div>
        ) : (
          <>
            <div className="es-vi-grid">
              {videos.map(v => <VideoCard key={v.id} v={v} onPlay={handlePlay} />)}
            </div>

            {/* Load more */}
            {currentPage < lastPage && (
              <div style={{ textAlign: "center", marginTop: 36 }}>
                <button
                  onClick={handleLoadMore}
                  disabled={loadingMore}
                  style={{
                    padding: "12px 36px",
                    background: loadingMore ? "#9aa5b4" : NAVY,
                    color: "#fff", border: "none", borderRadius: 8,
                    fontSize: 14, fontWeight: 700, cursor: loadingMore ? "default" : "pointer",
                    transition: "background .15s",
                  }}
                >
                  {loadingMore ? "Loading…" : `Load More (${total - videos.length} remaining)`}
                </button>
              </div>
            )}
          </>
        )}
      </div>

      <style>{`
        .es-vi-grid {
          display: grid;
          grid-template-columns: repeat(3, 1fr);
          gap: 18px;
        }
        .es-vi-card { transition: filter .15s, transform .15s; }
        .es-vi-card:hover { filter: brightness(1.08); transform: translateY(-2px); }
        @media (max-width: 900px) {
          .es-vi-grid { grid-template-columns: repeat(2, 1fr) !important; }
        }
        @media (max-width: 500px) {
          .es-vi-grid { grid-template-columns: 1fr !important; }
        }
      `}</style>
    </PageTemplate>
  );
};

export default Interviews;
