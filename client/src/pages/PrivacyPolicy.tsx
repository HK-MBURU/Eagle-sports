import PageTemplate from "@/components/PageTemplate";

const PrivacyPolicy = () => {
  return (
    <PageTemplate
      title="Privacy Policy"
      description="How we collect, use, and protect your information."
    >
      <div className="max-w-4xl mx-auto space-y-8 text-muted-foreground leading-relaxed">
        <section className="space-y-3">
          <h2 className="text-xl font-semibold text-foreground">Information we collect</h2>
          <p>
            We collect contact details you share (like name and email), submitted stories, and analytics data
            to improve the site experience. We do not sell your personal data.
          </p>
        </section>

        <section className="space-y-3">
          <h2 className="text-xl font-semibold text-foreground">How we use it</h2>
          <ul className="list-disc list-inside space-y-2">
            <li>To respond to submissions, mentorship requests, and partnership inquiries.</li>
            <li>To fact-check and publish stories with your consent.</li>
            <li>To analyze site performance and improve accessibility and content.</li>
          </ul>
        </section>

        <section className="space-y-3">
          <h2 className="text-xl font-semibold text-foreground">Cookies and analytics</h2>
          <p>
            We use cookies and analytics tools to understand how visitors use the site. You can disable cookies
            in your browser; some features may be limited.
          </p>
        </section>

        <section className="space-y-3">
          <h2 className="text-xl font-semibold text-foreground">Your choices</h2>
          <p>
            You can request edits or removal of your story, and you can opt out of newsletters at any time.
            For data requests, email{" "}
            <a href="mailto:info@beyondfinishline.com" className="text-foreground underline">
              info@beyondfinishline.com
            </a>.
          </p>
        </section>

        <section className="space-y-3">
          <h2 className="text-xl font-semibold text-foreground">Security</h2>
          <p>
            We use reasonable safeguards to protect your data, but no system is perfectly secure. Please avoid
            sending sensitive personal information through public forms.
          </p>
        </section>
      </div>
    </PageTemplate>
  );
};

export default PrivacyPolicy;
