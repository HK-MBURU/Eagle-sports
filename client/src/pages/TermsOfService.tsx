import PageTemplate from "@/components/PageTemplate";

const TermsOfService = () => {
  return (
    <PageTemplate
      title="Terms of Service"
      description="The ground rules for using Beyond the Finish Line."
    >
      <div className="max-w-4xl mx-auto space-y-8 text-muted-foreground leading-relaxed">
        <section className="space-y-3">
          <h2 className="text-xl font-semibold text-foreground">1. Who we are</h2>
          <p>
            Beyond the Finish Line is a storytelling and community platform for athletes and partners.
            By accessing our site, you agree to these terms and to any policies referenced here.
          </p>
        </section>

        <section className="space-y-3">
          <h2 className="text-xl font-semibold text-foreground">2. Content and attribution</h2>
          <p>
            We credit sources and respect athlete approvals. You may share our articles with proper attribution,
            but commercial use requires written permission.
          </p>
          <p>
            If you submit content, you confirm you have the rights to share it and you grant us permission to edit
            for clarity while keeping your story authentic.
          </p>
        </section>

        <section className="space-y-3">
          <h2 className="text-xl font-semibold text-foreground">3. Acceptable use</h2>
          <ul className="list-disc list-inside space-y-2">
            <li>No harassment, discrimination, or harmful content.</li>
            <li>No posting of content that violates privacy or intellectual property.</li>
            <li>No misuse of our platform for spam, scraping, or malicious activity.</li>
          </ul>
        </section>

        <section className="space-y-3">
          <h2 className="text-xl font-semibold text-foreground">4. Disclaimers</h2>
          <p>
            We aim for accuracy, but athletics data changes. Use information at your own risk and confirm
            critical details before making decisions.
          </p>
        </section>

        <section className="space-y-3">
          <h2 className="text-xl font-semibold text-foreground">5. Updates</h2>
          <p>
            We may update these terms to reflect new features or legal requirements. Continued use of the site
            means you accept the latest version.
          </p>
        </section>

        <section className="space-y-3">
          <h2 className="text-xl font-semibold text-foreground">6. Contact</h2>
          <p>
            Questions about these terms? Email us at{" "}
            <a href="mailto:info@eaglesports.co.ke" className="text-foreground underline">
              info@eaglesports.co.ke
            </a>.
          </p>
        </section>
      </div>
    </PageTemplate>
  );
};

export default TermsOfService;
