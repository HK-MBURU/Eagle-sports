import { useState, useEffect } from "react";
import { quotesAPI } from "../services/api";
import type { Quote } from "../types/quote";

const NAVY = "#10284a";
const RED  = "#d62835";
const LINE = "#e2e7ef";

const CARD_W  = 320; // px
const CARD_GAP = 20; // px
const STEP    = CARD_W + CARD_GAP; // 340 px per slot
const PX_PER_S = 55; // scroll speed

// Minimum number of items in the visible (un-duplicated) half so ticker always fills viewport
const MIN_ITEMS = 6;

const FALLBACK: Quote = {
  id: 0,
  content:
    "The same determination that drives us to win races must now drive us to protect our environment. Our training grounds are disappearing, and we have a responsibility to preserve them for future generations.",
  owner: "Eliud Kipchoge",
  owner_title: "Olympic Marathon Champion",
  owner_image: null,
  owner_image_url: null,
  added_by: 0,
  created_at: "",
  updated_at: "",
};

/* ─── helpers ───────────────────────────────────────────────── */

function tileToMin(arr: Quote[]): Quote[] {
  if (arr.length >= MIN_ITEMS) return arr;
  const times = Math.ceil(MIN_ITEMS / arr.length);
  return Array.from({ length: times }, () => arr).flat();
}

function initials(name: string): string {
  return name
    .split(" ")
    .map((w) => w[0])
    .slice(0, 2)
    .join("")
    .toUpperCase();
}

/* ─── QuoteSection ───────────────────────────────────────────── */

const QuoteSection = () => {
  const [quotes, setQuotes]   = useState<Quote[]>([]);
  const [loading, setLoading] = useState(true);
  const [paused, setPaused]   = useState(false);

  useEffect(() => {
    quotesAPI
      .getQuotes({ per_page: 100 })
      .then((res: { success: boolean; data: { quotes: Quote[] } }) => {
        const list = res?.success && res.data?.quotes?.length
          ? res.data.quotes
          : [FALLBACK];
        setQuotes(list);
      })
      .catch(() => setQuotes([FALLBACK]))
      .finally(() => setLoading(false));
  }, []);

  if (loading) {
    return (
      <div style={{ padding: "24px 0", display: "flex", gap: CARD_GAP, overflow: "hidden" }}>
        {[...Array(4)].map((_, i) => (
          <div key={i} style={{ flexShrink: 0, width: CARD_W, height: 190, borderRadius: 12,
            background: "#dde3eb", opacity: 0.5 }} className="animate-pulse" />
        ))}
      </div>
    );
  }

  const tiled    = tileToMin(quotes);
  const display  = [...tiled, ...tiled]; // double for seamless loop
  const duration = (tiled.length * STEP) / PX_PER_S;

  return (
    <div style={{ position: "relative", overflow: "hidden", padding: "4px 0" }}>
      {/* Fade-out edges */}
      <div style={{ position: "absolute", left: 0, top: 0, bottom: 0, width: 72,
        background: "linear-gradient(to right, #f4f6f9 20%, transparent)",
        zIndex: 2, pointerEvents: "none" }} />
      <div style={{ position: "absolute", right: 0, top: 0, bottom: 0, width: 72,
        background: "linear-gradient(to left, #f4f6f9 20%, transparent)",
        zIndex: 2, pointerEvents: "none" }} />

      <style>{`
        @keyframes qs-scroll {
          from { transform: translateX(0); }
          to   { transform: translateX(-50%); }
        }
      `}</style>

      <div
        style={{
          display: "flex",
          gap: CARD_GAP,
          /* width must be max-content so -50% = exactly one set */
          width: "max-content",
          animation: `qs-scroll ${duration}s linear infinite`,
          animationPlayState: paused ? "paused" : "running",
          willChange: "transform",
        }}
        onMouseEnter={() => setPaused(true)}
        onMouseLeave={() => setPaused(false)}
      >
        {display.map((q, i) => (
          <QuoteCard key={`${q.id}-${i}`} quote={q} />
        ))}
      </div>
    </div>
  );
};

/* ─── QuoteCard ──────────────────────────────────────────────── */

const QuoteCard = ({ quote }: { quote: Quote }) => {
  const [imgFailed, setImgFailed] = useState(false);
  const hasImage = !!quote.owner_image_url && !imgFailed;

  return (
    <div
      style={{
        flexShrink: 0,
        width: CARD_W,
        background: "#fff",
        borderRadius: 12,
        padding: "22px 22px 18px",
        border: `1px solid ${LINE}`,
        display: "flex",
        flexDirection: "column",
        gap: 12,
      }}
    >
      {/* Opening quote mark */}
      <div style={{ fontSize: 38, lineHeight: 1, color: RED,
        fontFamily: "Georgia, serif", userSelect: "none", marginBottom: -6 }}>
        "
      </div>

      {/* Quote text — max 4 lines */}
      <p style={{
        fontSize: 13.5,
        lineHeight: 1.65,
        color: "#374151",
        margin: 0,
        flex: 1,
        display: "-webkit-box",
        WebkitLineClamp: 4,
        WebkitBoxOrient: "vertical",
        overflow: "hidden",
      } as React.CSSProperties}>
        {quote.content}
      </p>

      {/* Attribution row */}
      <div style={{ display: "flex", alignItems: "center", gap: 11,
        borderTop: `1px solid ${LINE}`, paddingTop: 13 }}>

        {/* Avatar — image or initials fallback */}
        {hasImage ? (
          <img
            src={quote.owner_image_url!}
            alt={quote.owner}
            onError={() => setImgFailed(true)}
            style={{ width: 38, height: 38, borderRadius: "50%",
              objectFit: "cover", flexShrink: 0,
              border: `2px solid ${LINE}` }}
          />
        ) : (
          <div style={{
            width: 38, height: 38, borderRadius: "50%",
            background: NAVY, color: "#fff", flexShrink: 0,
            display: "flex", alignItems: "center", justifyContent: "center",
            fontSize: 12, fontWeight: 800, letterSpacing: "0.5px",
          }}>
            {initials(quote.owner)}
          </div>
        )}

        <div style={{ minWidth: 0 }}>
          <div style={{ fontSize: 13, fontWeight: 800, color: NAVY,
            whiteSpace: "nowrap", overflow: "hidden", textOverflow: "ellipsis" }}>
            {quote.owner}
          </div>
          {quote.owner_title && (
            <div style={{ fontSize: 11, color: "#8896a5",
              whiteSpace: "nowrap", overflow: "hidden", textOverflow: "ellipsis" }}>
              {quote.owner_title}
            </div>
          )}
        </div>
      </div>
    </div>
  );
};

export default QuoteSection;
