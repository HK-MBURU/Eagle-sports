import { useState, useEffect } from "react";
import { calendarAPI } from "@/services/api";

const NAVY = "#10284a";
const RED  = "#d62835";
const LINE = "#e2e7ef";

interface CalendarEvent {
  id: number;
  title: string;
  subtitle: string | null;
  location: string | null;
  description: string | null;
  cal_top: string | null;
  cal_bottom: string | null;
  cal_note: string | null;
  event_date: string;
  is_featured: boolean;
}

const DateBox = ({ top, bottom }: { top: string; bottom: string }) => (
  <div style={{
    width: 52, minWidth: 52, height: 56,
    background: "#fcecee",
    color: RED,
    borderRadius: 6,
    textAlign: "center",
    padding: "5px 4px",
    display: "flex", flexDirection: "column", alignItems: "center", justifyContent: "center",
  }}>
    <strong style={{ display: "block", fontSize: top.length > 3 ? 15 : 20, lineHeight: 1.1, fontWeight: 900 }}>
      {top}
    </strong>
    <span style={{ fontSize: 9, textTransform: "uppercase", fontWeight: 900, letterSpacing: ".3px" }}>
      {bottom}
    </span>
  </div>
);

const SkeletonRow = () => (
  <div style={{ display: "flex", gap: 13, padding: "14px 0", borderBottom: `1px solid ${LINE}`, alignItems: "center" }}>
    <div style={{ width: 52, height: 56, background: "#f0d8da", borderRadius: 6 }} className="animate-pulse" />
    <div style={{ flex: 1 }}>
      <div style={{ height: 13, width: "70%", background: "#dde3eb", borderRadius: 3, marginBottom: 6 }} className="animate-pulse" />
      <div style={{ height: 10, width: "50%", background: "#dde3eb", borderRadius: 3 }} className="animate-pulse" />
    </div>
  </div>
);

const CompetitionCalendar = () => {
  const [events, setEvents] = useState<CalendarEvent[]>([]);
  const [loading, setLoading] = useState(true);

  useEffect(() => {
    calendarAPI.getFeatured()
      .then((res: { success: boolean; data: CalendarEvent[] }) => {
        if (res.success) setEvents(res.data);
      })
      .catch(console.error)
      .finally(() => setLoading(false));
  }, []);

  return (
    <section id="calendar" style={{ background: "#f4f6f9", padding: "48px 0" }}>
      <div style={{ width: "min(1180px,92%)", margin: "0 auto" }}>

        {/* Section header */}
        <div style={{ display: "flex", justifyContent: "space-between", alignItems: "flex-end", marginBottom: 20 }}>
          <div>
            <h2 style={{ margin: 0, color: NAVY, fontSize: 22, fontWeight: 900, letterSpacing: "-.3px" }}>
              Competition Calendar
            </h2>
            <p style={{ margin: "4px 0 0", color: "#6b7e99", fontSize: 13 }}>
              Major upcoming athletics events and series.
            </p>
          </div>
          <a href="/calendar" style={{ color: RED, fontSize: 12, fontWeight: 700, textDecoration: "none", letterSpacing: ".5px" }}>
            FULL CALENDAR →
          </a>
        </div>

        {/* Panel */}
        <div style={{ border: `1px solid ${LINE}`, borderRadius: 10, overflow: "hidden", background: "#fff" }}>
          <div style={{
            padding: "14px 18px",
            background: "#f7f8fa",
            borderBottom: `1px solid ${LINE}`,
            display: "flex", justifyContent: "space-between", alignItems: "center",
          }}>
            <h3 style={{ margin: 0, color: NAVY, fontSize: 15, fontWeight: 800 }}>Upcoming & Featured Events</h3>
            <span style={{ fontSize: 10, color: "#6b7e99", fontWeight: 700, letterSpacing: ".5px" }}>COMPETITION CALENDAR</span>
          </div>

          <div style={{ padding: "0 16px" }}>
            {loading ? (
              <>
                <SkeletonRow />
                <SkeletonRow />
                <SkeletonRow />
                <SkeletonRow />
              </>
            ) : events.length === 0 ? (
              <div style={{ padding: "24px 0", color: "#6b7e99", fontSize: 13, textAlign: "center" }}>
                No calendar events available.
              </div>
            ) : (
              events.map((ev, i) => (
                <div
                  key={ev.id}
                  style={{
                    display: "flex",
                    gap: 13,
                    padding: "14px 0",
                    borderBottom: i < events.length - 1 ? `1px solid ${LINE}` : "none",
                    alignItems: "flex-start",
                  }}
                >
                  <DateBox top={ev.cal_top ?? "?"} bottom={ev.cal_bottom ?? ""} />
                  <div>
                    <h4 style={{ margin: "0 0 3px", fontSize: 13, color: NAVY, fontWeight: 800 }}>{ev.title}</h4>
                    <p style={{ margin: 0, fontSize: 11, color: "#6b7e99", lineHeight: 1.5 }}>
                      {[ev.location, ev.cal_note].filter(Boolean).join(" · ")}
                    </p>
                  </div>
                </div>
              ))
            )}
          </div>
        </div>

      </div>
    </section>
  );
};

export default CompetitionCalendar;
