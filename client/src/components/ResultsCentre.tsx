import { useState, useEffect } from "react";
import { resultEntriesAPI } from "@/services/api";

const NAVY  = "#10284a";
const RED   = "#d62835";
const LINE  = "#e2e7ef";

interface ResultEntry {
  id: number;
  competition: string;
  event_discipline: string;
  performance: string;
  athlete_name: string | null;
  athlete_country: string | null;
  series: string | null;
  result_status: string;
  race_date: string | null;
  race_date_fmt: string | null;
  location: string | null;
  is_featured: boolean;
}

const BROWSE_ITEMS = [
  { code: "DL",  label: "Diamond League",       sub: "Meeting results · season standings · archives", tag: "Series" },
  { code: "CT",  label: "Continental Tour",      sub: "Gold, Silver, Bronze and Challenger meetings", tag: "Series" },
  { code: "RR",  label: "Marathons & Road Races",sub: "Finish times · categories · event reports",   tag: "Road"   },
];

const StatusPill = ({ status }: { status: string }) => {
  const isOfficial = status === "official";
  return (
    <span style={{
      display: "inline-block",
      background: isOfficial ? "#e8f5ed" : "#fff3e0",
      color:      isOfficial ? "#1c7847" : "#e65100",
      borderRadius: 20,
      padding: "3px 7px",
      fontSize: 9,
      fontWeight: 900,
      textTransform: "uppercase",
      letterSpacing: ".3px",
    }}>
      {isOfficial ? "Official" : status}
    </span>
  );
};

const SkeletonRow = () => (
  <tr>
    {[...Array(4)].map((_, i) => (
      <td key={i} style={{ padding: "12px 13px", borderBottom: `1px solid ${LINE}` }}>
        <div style={{ height: 11, background: "#dde3eb", borderRadius: 3 }} className="animate-pulse" />
      </td>
    ))}
  </tr>
);

const ResultsCentre = () => {
  const [entries, setEntries] = useState<ResultEntry[]>([]);
  const [loading, setLoading] = useState(true);

  useEffect(() => {
    resultEntriesAPI.getLatest(8)
      .then((res: { success: boolean; data: ResultEntry[] }) => {
        if (res.success) setEntries(res.data);
      })
      .catch(console.error)
      .finally(() => setLoading(false));
  }, []);

  return (
    <section id="results" style={{ background: "#fff", padding: "48px 0" }}>
      <div style={{ width: "min(1180px,92%)", margin: "0 auto" }}>

        {/* Section header */}
        <div style={{ display: "flex", justifyContent: "space-between", alignItems: "flex-end", marginBottom: 20 }}>
          <div>
            <h2 style={{ margin: 0, color: NAVY, fontSize: 22, fontWeight: 900, letterSpacing: "-.3px" }}>
              Results Centre
            </h2>
            <p style={{ margin: "4px 0 0", color: "#6b7e99", fontSize: 13 }}>
              Latest official performances from Kenyan and global athletics.
            </p>
          </div>
          <a href="/results" style={{ color: RED, fontSize: 12, fontWeight: 700, textDecoration: "none", letterSpacing: ".5px" }}>
            ALL RESULTS →
          </a>
        </div>

        {/* Two-column layout */}
        <div style={{
          display: "grid",
          gridTemplateColumns: "1.3fr 0.7fr",
          gap: 20,
        }}
          className="results-layout-grid"
        >
          {/* Left: Latest Results table */}
          <div style={{ border: `1px solid ${LINE}`, borderRadius: 10, overflow: "hidden", background: "#fff" }}>
            <div style={{
              padding: "14px 18px",
              background: "#f7f8fa",
              borderBottom: `1px solid ${LINE}`,
              display: "flex", justifyContent: "space-between", alignItems: "center",
            }}>
              <h3 style={{ margin: 0, color: NAVY, fontSize: 15, fontWeight: 800 }}>Latest Results</h3>
              <span style={{ fontSize: 10, color: "#6b7e99", fontWeight: 700, letterSpacing: ".5px" }}>VERIFIED DATA</span>
            </div>

            <div style={{ overflowX: "auto" }}>
              <table style={{ width: "100%", borderCollapse: "collapse", fontSize: 12 }}>
                <thead>
                  <tr>
                    {["Competition", "Event", "Performance", "Status"].map(h => (
                      <th key={h} style={{
                        textAlign: "left",
                        background: "#fff",
                        color: "#6b7e99",
                        fontSize: 10,
                        textTransform: "uppercase",
                        letterSpacing: ".5px",
                        padding: "12px 13px",
                        borderBottom: `1px solid ${LINE}`,
                        fontWeight: 700,
                      }}>{h}</th>
                    ))}
                  </tr>
                </thead>
                <tbody>
                  {loading ? (
                    <>
                      <SkeletonRow />
                      <SkeletonRow />
                      <SkeletonRow />
                      <SkeletonRow />
                    </>
                  ) : entries.length === 0 ? (
                    <tr>
                      <td colSpan={4} style={{ padding: "20px 13px", color: "#6b7e99", fontSize: 13 }}>
                        No results available.
                      </td>
                    </tr>
                  ) : (
                    entries.map((entry, i) => (
                      <tr key={entry.id}>
                        <td style={{
                          padding: "12px 13px",
                          borderBottom: i < entries.length - 1 ? `1px solid ${LINE}` : "none",
                          fontWeight: 800, color: NAVY, fontSize: 12,
                        }}>
                          {entry.competition}
                        </td>
                        <td style={{
                          padding: "12px 13px",
                          borderBottom: i < entries.length - 1 ? `1px solid ${LINE}` : "none",
                          color: "#3d5275", fontSize: 12,
                        }}>
                          {entry.event_discipline}
                        </td>
                        <td style={{
                          padding: "12px 13px",
                          borderBottom: i < entries.length - 1 ? `1px solid ${LINE}` : "none",
                          color: NAVY, fontWeight: 700, fontSize: 12,
                        }}>
                          {entry.performance}
                        </td>
                        <td style={{
                          padding: "12px 13px",
                          borderBottom: i < entries.length - 1 ? `1px solid ${LINE}` : "none",
                        }}>
                          <StatusPill status={entry.result_status} />
                        </td>
                      </tr>
                    ))
                  )}
                </tbody>
              </table>
            </div>
          </div>

          {/* Right: Browse Results */}
          <div style={{ border: `1px solid ${LINE}`, borderRadius: 10, overflow: "hidden", background: "#fff" }}>
            <div style={{
              padding: "14px 18px",
              background: "#f7f8fa",
              borderBottom: `1px solid ${LINE}`,
            }}>
              <h3 style={{ margin: 0, color: NAVY, fontSize: 15, fontWeight: 800 }}>Browse Results</h3>
            </div>

            <div style={{ padding: "0 16px" }}>
              {BROWSE_ITEMS.map((item, i) => (
                <div
                  key={item.code}
                  style={{
                    display: "flex",
                    gap: 13,
                    padding: "14px 0",
                    borderBottom: i < BROWSE_ITEMS.length - 1 ? `1px solid ${LINE}` : "none",
                    alignItems: "flex-start",
                    cursor: "pointer",
                  }}
                >
                  <div style={{
                    width: 48, minWidth: 48, height: 52,
                    background: "#fcecee",
                    color: RED,
                    borderRadius: 6,
                    textAlign: "center",
                    padding: "5px 4px",
                    display: "flex", flexDirection: "column", alignItems: "center", justifyContent: "center",
                  }}>
                    <strong style={{ display: "block", fontSize: 15, lineHeight: 1.1, fontWeight: 900 }}>{item.code}</strong>
                    <span style={{ fontSize: 9, textTransform: "uppercase", fontWeight: 900, letterSpacing: ".3px" }}>{item.tag}</span>
                  </div>
                  <div>
                    <h4 style={{ margin: "0 0 3px", fontSize: 13, color: NAVY, fontWeight: 800 }}>{item.label}</h4>
                    <p style={{ margin: 0, fontSize: 11, color: "#6b7e99", lineHeight: 1.5 }}>{item.sub}</p>
                  </div>
                </div>
              ))}
            </div>
          </div>
        </div>

      </div>

      {/* Responsive: stack columns on mobile */}
      <style>{`
        @media (max-width: 760px) {
          .results-layout-grid { grid-template-columns: 1fr !important; }
        }
      `}</style>
    </section>
  );
};

export default ResultsCentre;
