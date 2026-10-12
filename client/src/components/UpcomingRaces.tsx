import { useState, useEffect } from "react";
import { Calendar, MapPin, Users, Clock } from "lucide-react";
import { raceEventsAPI } from "@/services/api";
import type { RaceEvent } from "@/types/race-event";
import { useNavigate } from "react-router-dom";

const NAVY = "#10284a";
const RED  = "#d62835";
const LINE = "#e2e7ef";

const UpcomingRaces = () => {
  const [races, setRaces]   = useState<RaceEvent[]>([]);
  const [loading, setLoading] = useState(true);
  const [error, setError]   = useState<string | null>(null);
  const navigate = useNavigate();

  const fetchUpcomingRaces = async () => {
    try {
      setLoading(true);
      setError(null);
      const res = await raceEventsAPI.getUpcomingEvents(3);
      if (res.success) setRaces(res.data);
      else setError("Failed to load upcoming races");
    } catch (err) {
      setError(err instanceof Error ? err.message : "Failed to load upcoming races");
    } finally {
      setLoading(false);
    }
  };

  useEffect(() => { fetchUpcomingRaces(); }, []);

  const formatTime = (t: string): string => {
    if (!t) return "TBD";
    try {
      const [h, m] = t.split(":");
      const hr = parseInt(h);
      return `${hr % 12 || 12}:${m} ${hr >= 12 ? "PM" : "AM"} EAT`;
    } catch { return t; }
  };

  const formatRunners = (v: string | null) =>
    !v ? "TBD" : /^\d+$/.test(v) ? `${v}+ runners` : v;

  /* ── Skeleton card ── */
  const SkeletonCard = () => (
    <div style={{ border: `1px solid ${LINE}`, borderRadius: 10, background: "#fff", overflow: "hidden" }}>
      <div style={{ height: 3, background: "#dde3eb" }} className="animate-pulse" />
      <div style={{ padding: "20px 22px" }}>
        <div style={{ height: 22, width: "70%", background: "#dde3eb", borderRadius: 3, marginBottom: 18 }} className="animate-pulse" />
        {[...Array(4)].map((_, i) => (
          <div key={i} style={{ height: 12, width: `${60 + i * 5}%`, background: "#dde3eb", borderRadius: 3, marginBottom: 10 }} className="animate-pulse" />
        ))}
        <div style={{ height: 52, background: "#f4f6f9", borderRadius: 6, margin: "16px 0" }} className="animate-pulse" />
        <div style={{ display: "flex", gap: 10 }}>
          <div style={{ flex: 1, height: 38, background: "#dde3eb", borderRadius: 5 }} className="animate-pulse" />
          <div style={{ flex: 1, height: 38, background: "#dde3eb", borderRadius: 5 }} className="animate-pulse" />
        </div>
      </div>
    </div>
  );

  return (
    <section id="races" style={{ background: "#fff", padding: "48px 0" }}>
      <div style={{ width: "min(1180px,92%)", margin: "0 auto" }}>

        {/* Section header */}
        <div style={{ display: "flex", justifyContent: "space-between", alignItems: "flex-end", gap: 14, marginBottom: 24 }}>
          <div>
            <h2 style={{ margin: 0, color: NAVY, fontSize: 22, fontWeight: 900, letterSpacing: "-.5px", lineHeight: 1.1 }}>
              Upcoming Races
            </h2>
            <p style={{ margin: "5px 0 0", fontSize: 12, color: "#667085" }}>
              Join the running community at these events across Kenya.
            </p>
          </div>
          <a
            href="/races/calendar"
            style={{ fontSize: 12, color: RED, fontWeight: 900, whiteSpace: "nowrap", textDecoration: "none" }}
          >
            VIEW CALENDAR →
          </a>
        </div>

        {/* Grid */}
        {error ? (
          <div style={{ padding: "32px 0", color: "#667085", fontSize: 13, textAlign: "center" }}>
            {error} —{" "}
            <button onClick={fetchUpcomingRaces} style={{ color: RED, fontWeight: 700, background: "none", border: "none", cursor: "pointer" }}>
              Try again
            </button>
          </div>
        ) : (
          <div style={{ display: "grid", gridTemplateColumns: "repeat(3,1fr)", gap: 18 }} className="races-grid">
            {loading ? (
              <><SkeletonCard /><SkeletonCard /><SkeletonCard /></>
            ) : races.length === 0 ? (
              <div style={{ gridColumn: "1/-1", padding: "32px 0", color: "#667085", fontSize: 13, textAlign: "center" }}>
                No upcoming races at the moment. Check back soon.
              </div>
            ) : (
              races.map((race) => (
                <div key={race.id} className="race-card" style={{
                  border: `1px solid ${LINE}`,
                  borderRadius: 10,
                  background: "#fff",
                  overflow: "hidden",
                  transition: "border-color .15s",
                }}>
                  {/* Red top stripe */}
                  <div style={{ height: 3, background: RED }} />

                  <div style={{ padding: "20px 22px" }}>
                    <h3 style={{ margin: "0 0 16px", fontSize: 16, fontWeight: 900, color: NAVY, lineHeight: 1.25 }}>
                      {race.title}
                    </h3>

                    {/* Meta rows */}
                    <div style={{ display: "flex", flexDirection: "column", gap: 9, marginBottom: 16 }}>
                      {[
                        { Icon: Calendar, text: race.event_date_formatted },
                        { Icon: MapPin,   text: race.location },
                        { Icon: Clock,    text: formatTime(race.start_time) },
                        { Icon: Users,    text: formatRunners(race.expected_runners) },
                      ].map(({ Icon, text }, i) => (
                        <div key={i} style={{ display: "flex", alignItems: "flex-start", gap: 10, color: "#667085", fontSize: 13 }}>
                          <Icon size={14} style={{ marginTop: 2, flexShrink: 0, color: NAVY }} />
                          <span>{text}</span>
                        </div>
                      ))}
                    </div>

                    {/* Distance options */}
                    <div style={{ background: "#f4f6f9", borderRadius: 6, padding: "10px 13px", marginBottom: 18 }}>
                      <div style={{ fontSize: 10, fontWeight: 800, color: NAVY, textTransform: "uppercase", letterSpacing: ".5px", marginBottom: 4 }}>
                        Distance Options
                      </div>
                      <div style={{ fontSize: 13, color: "#3d5275" }}>{race.distance_options}</div>
                    </div>

                    {/* Buttons */}
                    <div style={{ display: "flex", gap: 10 }}>
                      <button
                        onClick={() => race.registration_link
                          ? window.open(race.registration_link, "_blank", "noopener,noreferrer")
                          : alert("Registration details coming soon!")}
                        className="race-btn-primary"
                        style={{
                          flex: 1, padding: "9px 0", borderRadius: 5,
                          background: NAVY, color: "#fff",
                          border: "none", fontWeight: 800, fontSize: 12,
                          cursor: "pointer", transition: "background .15s",
                        }}
                      >
                        Register Now
                      </button>
                      <button
                        onClick={() => navigate(`/races/events/${race.id}`)}
                        className="race-btn-outline"
                        style={{
                          flex: 1, padding: "9px 0", borderRadius: 5,
                          background: "#fff", color: NAVY,
                          border: `1px solid ${LINE}`, fontWeight: 800, fontSize: 12,
                          cursor: "pointer", transition: "border-color .15s",
                        }}
                      >
                        Details
                      </button>
                    </div>
                  </div>
                </div>
              ))
            )}
          </div>
        )}

        {/* Footer CTA */}
        {!loading && !error && races.length > 0 && (
          <div style={{ textAlign: "center", marginTop: 28 }}>
            <p style={{ fontSize: 13, color: "#667085", margin: "0 0 12px" }}>Want to see all upcoming events?</p>
            <button
              onClick={() => navigate("/races/calendar")}
              style={{
                padding: "10px 28px", borderRadius: 5,
                background: "#fff", color: NAVY,
                border: `1px solid ${LINE}`, fontWeight: 800, fontSize: 12,
                cursor: "pointer", transition: "border-color .15s",
              }}
              className="race-btn-outline"
            >
              View Full Race Calendar
            </button>
          </div>
        )}

      </div>

      <style>{`
        .race-card:hover { border-color: ${RED} !important; }
        .race-btn-primary:hover { background: #0d1e3b !important; }
        .race-btn-outline:hover { border-color: ${NAVY} !important; }
        @media (max-width: 760px) {
          .races-grid { grid-template-columns: 1fr 1fr !important; }
        }
        @media (max-width: 480px) {
          .races-grid { grid-template-columns: 1fr !important; }
        }
      `}</style>
    </section>
  );
};

export default UpcomingRaces;
