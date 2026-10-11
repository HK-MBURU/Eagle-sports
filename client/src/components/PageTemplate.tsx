import Navigation from "@/components/Navigation";
import Footer from "@/components/Footer";

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
  className = ""
}: PageTemplateProps) => {
  return (
    <div className="min-h-screen flex flex-col">
      <Navigation />
      
      {/* Hero Section - Only shows if showHero is true AND title exists */}
      {showHero && title && (
        <section className="pt-24 pb-8 bg-accent-orange/10">
          <div className="container mx-auto px-4">
            <h1 className="text-3xl md:text-4xl lg:text-5xl font-bold text-gray-900 mb-3">
              {title}
            </h1>
            {description && (
              <p className="text-base md:text-lg text-gray-600 max-w-3xl">
                {description}
              </p>
            )}
          </div>
        </section>
      )}

      {/* Main Content - Reduced padding */}
      <main className={`flex-1 ${showHero && title ? 'py-8' : 'pt-24 pb-8'} ${className}`}>
        {children || (
          <div className="container mx-auto px-4">
            <div className="text-center py-20">
              <p className="text-xl text-gray-600">
                Content coming soon...
              </p>
            </div>
          </div>
        )}
      </main>

      <Footer />
    </div>
  );
};

export default PageTemplate;