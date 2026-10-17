import { useState, useRef, useEffect } from "react";
import { ChevronDown, Search, X } from "lucide-react";
import { navigationData, NavItem } from "@/data/navigationData";
import SearchBar from "@/components/SearchBar";
import { useSearchNavigation } from "@/hooks/useSearchNavigation";
import { SearchResult } from "@/types/search";

/* ─── Brand colours (matching mockup exactly) ─── */
const NAVY   = "#10284a";
const NAVY_DARK = "#091a31";
const NAVY2  = "#173a67";
const RED    = "#d62835";
const INK    = "#172235";
const MUTED  = "#667085";

/* ─────────────────────────────────────────────── */

const Navigation = () => {
  const [mobileOpen, setMobileOpen]           = useState(false);
  const [openDesktopDD, setOpenDesktopDD]     = useState<string | null>(null);
  const [openMobileItems, setOpenMobileItems] = useState<string[]>([]);
  const ddTimer = useRef<NodeJS.Timeout>();
  const eagleLogo = "/images/es-logo.png";

  const { handleSearchResultClick } = useSearchNavigation();
  const handleSearchResult = (result: SearchResult) => {
    handleSearchResultClick(result);
    setMobileOpen(false);
  };

  /* lock body scroll when mobile menu open */
  useEffect(() => {
    if (mobileOpen) {
      document.body.style.overflow = "hidden";
    } else {
      document.body.style.overflow = "";
    }
    return () => { document.body.style.overflow = ""; };
  }, [mobileOpen]);

  /* desktop dropdown open / close with small hover delay */
  const ddEnter = (name: string) => {
    clearTimeout(ddTimer.current);
    setOpenDesktopDD(name);
  };
  const ddLeave = () => {
    ddTimer.current = setTimeout(() => setOpenDesktopDD(null), 140);
  };

  const toggleMobileItem = (name: string) =>
    setOpenMobileItems(prev =>
      prev.includes(name) ? prev.filter(n => n !== name) : [...prev, name]
    );

  return (
    <>
      {/* ═══════════════ TOP LINE ═══════════════ */}
      <div
        className="hidden sm:flex justify-between items-center flex-wrap gap-3 text-[11px] font-semibold tracking-wide"
        style={{ background: NAVY_DARK, color: "#dbe5f2", padding: "7px 5%" }}
      >
        <span>INDEPENDENT ATHLETICS JOURNALISM</span>
        <span>KENYA · AFRICA · THE WORLD</span>
        <span className="opacity-80">eaglesports.co.ke</span>
      </div>

      {/* ═══════════════ MASTHEAD ═══════════════ */}
      <div className="hidden md:block bg-white border-b border-gray-100">
        <div
          className="flex items-center justify-between gap-5"
          style={{ width: "min(1180px, 92%)", margin: "0 auto", padding: "14px 0" }}
        >
          {/* Brand */}
          <a href="/" className="flex items-center gap-3" style={{ textDecoration: "none" }} aria-label="Eagle Sports home">
            <img src={eagleLogo} alt="Eagle Sports" style={{ height: "52px", width: "auto" }} />
            <div className="hidden sm:block">
              <div
                className="font-black leading-none"
                style={{ fontSize: "27px", letterSpacing: "-1.2px", color: NAVY }}
              >
                EAGLE <span style={{ color: RED }}>SPORTS</span>
              </div>
              <div
                className="font-extrabold uppercase"
                style={{ fontSize: "10px", letterSpacing: "2px", color: NAVY2, marginTop: "6px" }}
              >
                Beyond the Finish Line
              </div>
            </div>
          </a>

          {/* Brand note — desktop only */}
          <div className="hidden lg:block text-right" style={{ fontSize: "12px", color: MUTED }}>
            <strong className="block" style={{ color: RED, fontSize: "13px" }}>THE JOURNALISM THAT MATTERS</strong>
            Stories. Results. People.
          </div>
        </div>
      </div>

      {/* ═══════════════ STICKY NAV SHELL ═══════════════ */}
      <div
        className="sticky top-0 z-50"
        style={{ background: NAVY, boxShadow: "0 4px 16px rgba(16,40,74,0.22)" }}
      >
        <div style={{ width: "min(1180px, 92%)", margin: "0 auto" }}>

          {/* ── Desktop nav ── */}
          <nav
            className="hidden md:flex items-center"
            style={{ minHeight: "51px" }}
            aria-label="Main navigation"
          >
            {navigationData.map(item => (
              <DesktopItem
                key={item.name}
                item={item}
                open={openDesktopDD === item.name}
                onEnter={() => ddEnter(item.name)}
                onLeave={ddLeave}
              />
            ))}

            {/* Search — right side */}
            <div className="ml-auto pl-4 flex items-center">
              <SearchBar
                onResultClick={handleSearchResult}
                placeholder="Search…"
                variant="desktop"
              />
            </div>
          </nav>

          {/* ── Mobile bar (logo + hamburger) ── */}
          <div className="flex md:hidden items-center justify-between" style={{ minHeight: "51px" }}>
            <a href="/" className="flex items-center gap-2" style={{ textDecoration: "none" }}>
              <img src={eagleLogo} alt="Eagle Sports" style={{ height: "34px", width: "auto" }} />
              <span className="font-black" style={{ fontSize: "17px", color: "#fff", letterSpacing: "-0.5px" }}>
                EAGLE <span style={{ color: RED }}>SPORTS</span>
              </span>
            </a>
            <button
              onClick={() => setMobileOpen(v => !v)}
              className="flex items-center gap-2 font-bold text-white text-sm px-3 py-2"
              style={{ background: RED, border: "none", cursor: "pointer" }}
              aria-expanded={mobileOpen}
              aria-label="Toggle navigation"
            >
              {mobileOpen ? <X size={16} /> : "☰"} MENU
            </button>
          </div>
        </div>
      </div>

      {/* ═══════════════ MOBILE FULL-SCREEN MENU ═══════════════ */}
      {mobileOpen && (
        <div
          className="fixed inset-0 z-40 overflow-y-auto"
          style={{ background: "#fff", top: 0, paddingTop: "51px" }}
        >
          {/* Sticky close bar */}
          <div
            className="sticky top-0 flex items-center justify-between px-4 py-3"
            style={{ background: NAVY, zIndex: 10 }}
          >
            <a href="/" className="flex items-center gap-2" onClick={() => setMobileOpen(false)}>
              <img src={eagleLogo} alt="" style={{ height: "30px" }} />
              <span className="font-black text-white text-base" style={{ letterSpacing: "-0.5px" }}>
                EAGLE <span style={{ color: RED }}>SPORTS</span>
              </span>
            </a>
            <button
              onClick={() => setMobileOpen(false)}
              className="text-white p-2"
              style={{ background: "none", border: "none", cursor: "pointer" }}
              aria-label="Close menu"
            >
              <X size={22} />
            </button>
          </div>

          {/* Search in mobile menu */}
          <div className="px-4 py-3 border-b border-gray-100">
            <SearchBar
              onResultClick={result => { handleSearchResult(result); setMobileOpen(false); }}
              placeholder="Search stories, athletes, events…"
              variant="mobile-menu"
            />
          </div>

          {/* Nav items */}
          <div className="px-2 py-3">
            {navigationData.map(item => (
              <MobileItem
                key={item.name}
                item={item}
                openItems={openMobileItems}
                toggle={toggleMobileItem}
                close={() => setMobileOpen(false)}
              />
            ))}
          </div>
        </div>
      )}
    </>
  );
};

/* ═══════════════════════════════════════════════
   DESKTOP NAV ITEM
════════════════════════════════════════════════ */
const DesktopItem = ({
  item,
  open,
  onEnter,
  onLeave,
}: {
  item: NavItem;
  open: boolean;
  onEnter: () => void;
  onLeave: () => void;
}) => {
  const hasChildren = item.children && item.children.length > 0;

  const linkStyle: React.CSSProperties = {
    display: "block",
    padding: "15px 11px",
    fontSize: "13px",
    fontWeight: 700,
    whiteSpace: "nowrap",
    color: "white",
    background: open ? RED : "none",
    border: "none",
    cursor: "pointer",
    textDecoration: "none",
    fontFamily: "inherit",
    transition: "background 0.12s",
  };

  const hoverStyle: React.CSSProperties = { background: RED };

  if (!hasChildren) {
    return (
      <a
        href={item.href || "/"}
        style={linkStyle}
        onMouseEnter={e => Object.assign((e.currentTarget as HTMLElement).style, hoverStyle)}
        onMouseLeave={e => Object.assign((e.currentTarget as HTMLElement).style, { background: "none" })}
      >
        {item.name.toUpperCase()}
      </a>
    );
  }

  return (
    <div
      className="relative"
      onMouseEnter={onEnter}
      onMouseLeave={onLeave}
    >
      <button
        style={{ ...linkStyle, display: "flex", alignItems: "center", gap: "5px" }}
        onMouseEnter={e => Object.assign((e.currentTarget as HTMLElement).style, { background: RED })}
        onMouseLeave={e => !open && Object.assign((e.currentTarget as HTMLElement).style, { background: "none" })}
      >
        {item.name.toUpperCase()}
        <ChevronDown size={13} style={{ transition: "transform 0.2s", transform: open ? "rotate(180deg)" : "none" }} />
      </button>

      {open && (
        <div
          className="absolute top-full left-0 z-50 overflow-y-auto"
          style={{
            minWidth: "230px",
            maxWidth: "280px",
            maxHeight: "75vh",
            background: "white",
            color: INK,
            boxShadow: "0 12px 28px rgba(7,23,43,0.18)",
            borderTop: `3px solid ${RED}`,
            padding: "8px 0",
          }}
        >
          {item.children!.map(child => (
            <DropdownItem key={child.name} item={child} />
          ))}
        </div>
      )}
    </div>
  );
};

/* ═══════════════════════════════════════════════
   DROPDOWN ITEM
════════════════════════════════════════════════ */
const DropdownItem = ({ item }: { item: NavItem }) => {
  if (item.isGroupLabel) {
    return (
      <div
        style={{
          padding: "9px 16px 5px",
          fontSize: "10px",
          letterSpacing: "1.1px",
          textTransform: "uppercase",
          color: RED,
          fontWeight: 900,
        }}
      >
        {item.name}
      </div>
    );
  }

  const [hovered, setHovered] = useState(false);
  return (
    <a
      href={item.href || "#"}
      style={{
        display: "block",
        padding: "9px 16px",
        fontSize: "13px",
        fontWeight: 600,
        color: hovered ? "white" : INK,
        background: hovered ? RED : "transparent",
        textDecoration: "none",
        transition: "background 0.1s, color 0.1s",
        whiteSpace: "normal",
      }}
      onMouseEnter={() => setHovered(true)}
      onMouseLeave={() => setHovered(false)}
    >
      {item.name}
    </a>
  );
};

/* ═══════════════════════════════════════════════
   MOBILE NAV ITEM
════════════════════════════════════════════════ */
const MobileItem = ({
  item,
  openItems,
  toggle,
  close,
  level = 0,
}: {
  item: NavItem;
  openItems: string[];
  toggle: (name: string) => void;
  close: () => void;
  level?: number;
}) => {
  if (item.isGroupLabel) return null;

  const hasChildren = item.children && item.children.length > 0;
  const isOpen = openItems.includes(item.name);
  const pl = 16 + level * 16;

  if (!hasChildren) {
    return (
      <a
        href={item.href || "#"}
        onClick={close}
        className="block py-3 font-semibold border-b border-gray-50"
        style={{ paddingLeft: pl, paddingRight: 16, fontSize: "15px", color: INK, textDecoration: "none" }}
      >
        {item.name}
      </a>
    );
  }

  return (
    <div>
      <button
        onClick={() => toggle(item.name)}
        className="w-full flex items-center justify-between py-3 font-semibold border-b border-gray-100"
        style={{ paddingLeft: pl, paddingRight: 16, fontSize: "15px", color: INK, background: "none", border: "none", borderBottom: "1px solid #f3f4f6", cursor: "pointer", width: "100%", textAlign: "left" }}
      >
        {item.name}
        <ChevronDown
          size={18}
          style={{
            transition: "transform 0.25s",
            transform: isOpen ? "rotate(180deg)" : "none",
            color: isOpen ? RED : "#9ca3af",
            flexShrink: 0,
          }}
        />
      </button>

      {isOpen && (
        <div style={{ background: "#f9fafb", borderBottom: "1px solid #e5e7eb" }}>
          {item.children!.map(child => (
            <MobileItem
              key={child.name}
              item={child}
              openItems={openItems}
              toggle={toggle}
              close={close}
              level={level + 1}
            />
          ))}
        </div>
      )}
    </div>
  );
};

export default Navigation;
