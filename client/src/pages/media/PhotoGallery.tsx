import { useState, useEffect, useCallback, useRef } from "react";
import PageTemplate from "@/components/PageTemplate";

const NAVY = "#10284a";
const RED  = "#d62835";

interface Photo {
  id: number;
  title: string;
  caption: string | null;
  alt_text: string;
  image_url: string;
  category: string;
  category_name: string;
  is_featured: boolean;
}

// ── Lightbox ────────────────────────────────────────────────────
const Lightbox = ({
  photos, index, onClose, onPrev, onNext,
}: {
  photos: Photo[];
  index: number;
  onClose: () => void;
  onPrev: () => void;
  onNext: () => void;
}) => {
  const photo = photos[index];

  useEffect(() => {
    const handler = (e: KeyboardEvent) => {
      if (e.key === "Escape")      onClose();
      if (e.key === "ArrowLeft")   onPrev();
      if (e.key === "ArrowRight")  onNext();
    };
    document.addEventListener("keydown", handler);
    document.body.style.overflow = "hidden";
    return () => {
      document.removeEventListener("keydown", handler);
      document.body.style.overflow = "";
    };
  }, [onClose, onPrev, onNext]);

  if (!photo) return null;

  return (
    <div
      onClick={onClose}
      style={{
        position: "fixed", inset: 0, zIndex: 9999,
        background: "rgba(5,13,24,.93)",
        display: "flex", alignItems: "center", justifyContent: "center",
      }}
    >
      {/* Close */}
      <button
        onClick={onClose}
        style={{
          position: "absolute", top: 16, right: 16,
          background: "rgba(255,255,255,.12)", border: "1.5px solid rgba(255,255,255,.25)",
          color: "#fff", width: 40, height: 40, borderRadius: "50%",
          fontSize: 18, cursor: "pointer", display: "grid", placeItems: "center",
        }}
      >✕</button>

      {/* Prev */}
      {index > 0 && (
        <button
          onClick={e => { e.stopPropagation(); onPrev(); }}
          style={{
            position: "absolute", left: 12, top: "50%", transform: "translateY(-50%)",
            background: "rgba(255,255,255,.12)", border: "1.5px solid rgba(255,255,255,.25)",
            color: "#fff", width: 44, height: 44, borderRadius: "50%",
            fontSize: 20, cursor: "pointer", display: "grid", placeItems: "center",
          }}
        >‹</button>
      )}

      {/* Next */}
      {index < photos.length - 1 && (
        <button
          onClick={e => { e.stopPropagation(); onNext(); }}
          style={{
            position: "absolute", right: 12, top: "50%", transform: "translateY(-50%)",
            background: "rgba(255,255,255,.12)", border: "1.5px solid rgba(255,255,255,.25)",
            color: "#fff", width: 44, height: 44, borderRadius: "50%",
            fontSize: 20, cursor: "pointer", display: "grid", placeItems: "center",
          }}
        >›</button>
      )}

      {/* Image + caption */}
      <div
        onClick={e => e.stopPropagation()}
        style={{
          maxWidth: "min(90vw, 1000px)", maxHeight: "90vh",
          display: "flex", flexDirection: "column", alignItems: "center", gap: 14,
        }}
      >
        <img
          src={photo.image_url}
          alt={photo.alt_text}
          style={{ maxWidth: "100%", maxHeight: "75vh", objectFit: "contain", borderRadius: 8 }}
        />
        <div style={{ textAlign: "center" }}>
          <p style={{ margin: 0, color: "#fff", fontSize: 15, fontWeight: 700 }}>{photo.title}</p>
          {photo.caption && (
            <p style={{ margin: "5px 0 0", color: "#9aa5b4", fontSize: 13 }}>{photo.caption}</p>
          )}
          <span style={{
            display: "inline-block", marginTop: 8,
            background: RED, color: "#fff", borderRadius: 4,
            fontSize: 10, fontWeight: 700, padding: "2px 8px", textTransform: "uppercase" as const,
          }}>
            {photo.category_name}
          </span>
        </div>
        <p style={{ color: "#5a6a7e", fontSize: 11, margin: 0 }}>
          {index + 1} / {photos.length}
        </p>
      </div>
    </div>
  );
};

// ── Skeleton ────────────────────────────────────────────────────
const SkeletonCard = () => (
  <div className="animate-pulse" style={{ borderRadius: 10, background: "#dde3eb", aspectRatio: "4/3" as const }} />
);

// ── Photo card ──────────────────────────────────────────────────
const PhotoCard = ({ photo, onClick }: { photo: Photo; onClick: () => void }) => {
  const [loaded, setLoaded] = useState(false);
  const [error,  setError]  = useState(false);

  return (
    <div
      onClick={onClick}
      className="es-pg-card"
      style={{ borderRadius: 10, overflow: "hidden", cursor: "pointer", position: "relative", background: "#dde3eb", aspectRatio: "4/3" as const }}
    >
      {!error && (
        <img
          src={photo.image_url}
          alt={photo.alt_text}
          loading="lazy"
          onLoad={() => setLoaded(true)}
          onError={() => setError(true)}
          style={{ width: "100%", height: "100%", objectFit: "cover", display: "block", transition: "opacity .3s", opacity: loaded ? 1 : 0 }}
        />
      )}
      {error && (
        <div style={{ width: "100%", height: "100%", display: "grid", placeItems: "center", color: "#9aa5b4", fontSize: 28 }}>🖼</div>
      )}
      <div className="es-pg-overlay" style={{
        position: "absolute", inset: 0,
        background: "linear-gradient(0deg,#07172be0 0%,transparent 55%)",
        opacity: 0, transition: "opacity .18s",
        display: "flex", alignItems: "flex-end", padding: "12px 14px",
      }}>
        <div>
          <p style={{ margin: 0, color: "#fff", fontSize: 12, fontWeight: 700, lineHeight: 1.3 }}>{photo.title}</p>
          <span style={{ fontSize: 10, color: "#d3dceb", textTransform: "uppercase" as const, letterSpacing: ".5px", fontWeight: 700 }}>
            {photo.category_name}
          </span>
        </div>
      </div>
      {photo.is_featured && (
        <div style={{
          position: "absolute", top: 8, right: 8,
          background: "#f59e0b", borderRadius: "50%",
          width: 22, height: 22, display: "grid", placeItems: "center",
          fontSize: 10, color: "#fff",
        }}>★</div>
      )}
    </div>
  );
};

// ── Main ─────────────────────────────────────────────────────────
const PhotoGallery = () => {
  const [photos,   setPhotos]   = useState<Photo[]>([]);
  const [loading,  setLoading]  = useState(true);
  const [loadingMore, setLoadingMore] = useState(false);
  const [search,   setSearch]   = useState("");
  const [activeCategory, setActiveCategory] = useState("All");
  const [categories, setCategories] = useState<{ key: string; label: string }[]>([]);
  const [currentPage, setCurrentPage] = useState(1);
  const [lastPage,    setLastPage]    = useState(1);
  const [total,       setTotal]       = useState(0);
  const [lightboxIdx, setLightboxIdx] = useState<number | null>(null);
  const sentinelRef = useRef<HTMLDivElement>(null);
  const searchTimer = useRef<ReturnType<typeof setTimeout> | null>(null);
  const isFetching  = useRef(false);

  const PER_PAGE = 24;

  const fetchPhotos = useCallback(async (page = 1, append = false) => {
    if (isFetching.current) return;
    isFetching.current = true;
    if (page === 1) setLoading(true); else setLoadingMore(true);

    try {
      const params = new URLSearchParams({ page: String(page), per_page: String(PER_PAGE) });
      if (search.trim())            params.set("search", search.trim());
      if (activeCategory !== "All") params.set("category", activeCategory);

      const res  = await fetch(`/api/photos?${params}`);
      if (!res.ok) throw new Error("fetch failed");
      const json = await res.json();

      if (json.success) {
        const data: Photo[] = json.data?.photos ?? [];
        const pag = json.data?.pagination;
        setPhotos(prev => append ? [...prev, ...data] : data);
        setCurrentPage(pag?.current_page ?? page);
        setLastPage(pag?.last_page ?? 1);
        setTotal(pag?.total ?? data.length);

        if (page === 1 && !append) {
          const cats = json.data?.categories;
          if (cats && typeof cats === "object") {
            setCategories(Object.entries(cats as Record<string, string>).map(([key, label]) => ({ key, label })));
          }
        }
      }
    } catch {
      // silent
    } finally {
      setLoading(false);
      setLoadingMore(false);
      isFetching.current = false;
    }
  }, [search, activeCategory]);

  useEffect(() => { fetchPhotos(1, false); }, [activeCategory, fetchPhotos]);

  useEffect(() => {
    if (searchTimer.current) clearTimeout(searchTimer.current);
    searchTimer.current = setTimeout(() => fetchPhotos(1, false), 380);
    return () => { if (searchTimer.current) clearTimeout(searchTimer.current); };
  }, [search]);

  // Infinity scroll
  useEffect(() => {
    const sentinel = sentinelRef.current;
    if (!sentinel) return;
    const obs = new IntersectionObserver(entries => {
      if (entries[0].isIntersecting && currentPage < lastPage && !loadingMore && !loading) {
        fetchPhotos(currentPage + 1, true);
      }
    }, { rootMargin: "300px" });
    obs.observe(sentinel);
    return () => obs.disconnect();
  }, [currentPage, lastPage, loadingMore, loading, fetchPhotos]);

  return (
    <PageTemplate
      title="Photo Gallery"
      description="Browse stunning photography capturing the beauty and intensity of athletic competition."
    >
      {lightboxIdx !== null && (
        <Lightbox
          photos={photos}
          index={lightboxIdx}
          onClose={() => setLightboxIdx(null)}
          onPrev={() => setLightboxIdx(i => (i !== null && i > 0 ? i - 1 : i))}
          onNext={() => setLightboxIdx(i => (i !== null && i < photos.length - 1 ? i + 1 : i))}
        />
      )}

      <div style={{ width: "min(1180px,92%)", margin: "0 auto", padding: "32px 0 56px" }}>

        {/* Search + filter bar */}
        <div style={{ display: "flex", flexWrap: "wrap", gap: 12, alignItems: "center", marginBottom: 24 }}>
          <div style={{ position: "relative", flex: "1 1 220px", minWidth: 200 }}>
            <span style={{ position: "absolute", left: 12, top: "50%", transform: "translateY(-50%)", color: "#9aa5b4", fontSize: 14, pointerEvents: "none" }}>🔍</span>
            <input
              type="text" placeholder="Search photos…" value={search}
              onChange={e => setSearch(e.target.value)}
              style={{ width: "100%", padding: "10px 14px 10px 36px", border: "1.5px solid #e2e7ef", borderRadius: 8, fontSize: 14, outline: "none", background: "#fff", color: NAVY, boxSizing: "border-box" as const }}
            />
          </div>

          {categories.length > 0 && (
            <div style={{ display: "flex", flexWrap: "wrap", gap: 8 }}>
              {[{ key: "All", label: "All" }, ...categories].map(cat => (
                <button
                  key={cat.key}
                  onClick={() => setActiveCategory(cat.key)}
                  style={{
                    padding: "7px 16px", borderRadius: 20, border: "1.5px solid",
                    borderColor: activeCategory === cat.key ? RED : "#d1d9e0",
                    background: activeCategory === cat.key ? RED : "#fff",
                    color: activeCategory === cat.key ? "#fff" : "#4a5568",
                    fontSize: 13, fontWeight: 600, cursor: "pointer", transition: "all .15s",
                  }}
                >{cat.label}</button>
              ))}
            </div>
          )}

          {!loading && total > 0 && (
            <span style={{ fontSize: 12, color: "#667085", marginLeft: "auto", whiteSpace: "nowrap" }}>
              {total} photo{total !== 1 ? "s" : ""}
            </span>
          )}
        </div>

        {/* Grid */}
        {loading ? (
          <div className="es-pg-grid">
            {Array.from({ length: 12 }).map((_, i) => <SkeletonCard key={i} />)}
          </div>
        ) : photos.length === 0 ? (
          <div style={{ textAlign: "center", padding: "64px 0", color: "#667085", fontSize: 15 }}>
            <div style={{ fontSize: 40, marginBottom: 12 }}>📷</div>
            <p style={{ margin: 0, fontWeight: 600 }}>No photos yet</p>
            <p style={{ margin: "6px 0 0", fontSize: 13 }}>
              {search ? `No results for "${search}"` : "Check back soon for new content."}
            </p>
            {(search || activeCategory !== "All") && (
              <button
                onClick={() => { setSearch(""); setActiveCategory("All"); }}
                style={{ marginTop: 16, padding: "8px 20px", background: RED, color: "#fff", border: "none", borderRadius: 6, fontSize: 13, fontWeight: 700, cursor: "pointer" }}
              >Clear filters</button>
            )}
          </div>
        ) : (
          <>
            <div className="es-pg-grid">
              {photos.map((photo, idx) => (
                <PhotoCard key={photo.id} photo={photo} onClick={() => setLightboxIdx(idx)} />
              ))}
            </div>

            {/* Infinity scroll sentinel */}
            <div ref={sentinelRef} style={{ height: 1 }} />

            {loadingMore && (
              <div style={{ textAlign: "center", padding: "28px 0", color: "#9aa5b4", fontSize: 14 }}>
                <span className="es-pg-spinner" /> Loading more…
              </div>
            )}

            {!loadingMore && currentPage >= lastPage && photos.length > 0 && (
              <p style={{ textAlign: "center", padding: "28px 0", color: "#9aa5b4", fontSize: 13, margin: 0 }}>
                — {photos.length} of {total} photos —
              </p>
            )}
          </>
        )}
      </div>

      <style>{`
        .es-pg-grid {
          display: grid;
          grid-template-columns: repeat(4, 1fr);
          gap: 14px;
        }
        .es-pg-card { transition: transform .15s; }
        .es-pg-card:hover { transform: translateY(-2px); }
        .es-pg-card:hover .es-pg-overlay { opacity: 1 !important; }
        @media (max-width: 1024px) { .es-pg-grid { grid-template-columns: repeat(3, 1fr); } }
        @media (max-width: 700px)  { .es-pg-grid { grid-template-columns: repeat(2, 1fr) !important; } }
        @media (max-width: 420px)  { .es-pg-grid { grid-template-columns: 1fr !important; } }
        @keyframes es-spin { to { transform: rotate(360deg); } }
        .es-pg-spinner {
          display: inline-block; width: 18px; height: 18px;
          border: 2px solid #dde3eb; border-top-color: #10284a;
          border-radius: 50%; animation: es-spin .7s linear infinite;
          vertical-align: middle; margin-right: 8px;
        }
      `}</style>
    </PageTemplate>
  );
};

export default PhotoGallery;
