import Navigation from "@/components/Navigation";
import Footer from "@/components/Footer";

const NAVY = "#10284a";
const RED  = "#d62835";

interface PageTemplateProps {
  title?: string;
  description?: string;
  children?: React.ReactNode;
  showHero?: boolean;
  className?: string;
}

const PageTemplate = ({
  title,
  description,
  children,
  showHero = true,
  className = "",
}: PageTemplateProps) => {
  return (
    <div className="min-h-screen flex flex-col">
      <Navigation />

      {showHero && title && (
        <section style={{ background: NAVY, paddingTop: "72px", paddingBottom: "28px" }}>
          <div style={{ width: "min(1180px,92%)", margin: "0 auto" }}>
            <div style={{
              display: "inline-block", background: RED, color: "#fff",
              fontSize: "10px", fontWeight: 900, letterSpacing: "1.5px",
              textTransform: "uppercase", padding: "4px 10px", borderRadius: "3px",
              marginBottom: "12px",
            }}>
              Eagle Sports
            </div>
            <h1 style={{
              fontSize: "clamp(22px,4vw,40px)", fontWeight: 900,
              color: "#fff", lineHeight: 1.1, letterSpacing: "-0.5px",
              margin: "0 0 10px",
            }}>
              {title}
            </h1>
            {description && (
              <p style={{
                fontSize: "clamp(13px,1.8vw,16px)", color: "#a8b8cc",
                maxWidth: "600px", lineHeight: 1.6, margin: 0,
              }}>
                {description}
              </p>
            )}
          </div>
        </section>
      )}

      <main className={`flex-1 ${!showHero || !title ? "pt-24" : ""} ${className}`}>
        {children || (
          <div style={{ width: "min(1180px,92%)", margin: "0 auto", padding: "64px 0", textAlign: "center" }}>
            <p style={{ fontSize: "18px", color: "#8896a5" }}>Content coming soon…</p>
          </div>
        )}
      </main>

      <Footer />
    </div>
  );
};

export default PageTemplate;