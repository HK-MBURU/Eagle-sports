// src/components/HeroSection.tsx

import { useState, useEffect } from "react";
import { Button } from "@/components/ui/button";
import { ChevronLeft, ChevronRight, Play, Loader2 } from "lucide-react";
import { useStories } from "@/contexts/StoriesContext";
import heroRunner from "@/assets/hero-runner.jpg";

const HeroSection = () => {
  const { heroStories, heroLoading } = useStories();
  const [currentSlide, setCurrentSlide] = useState(0);

  // Fallback slides if no hero stories are loaded
  const fallbackSlides = [
    {
      title: "Every Medal Tells a Beginning",
      subtitle: "Celebrating Kenyan athletes' journeys beyond their competitive careers",
      image: heroRunner,
      slug: undefined,
      category: undefined,
      image_attribution: undefined,
    },
    {
      title: "Running for Our Planet",
      subtitle: "Athletes leading the charge in climate advocacy and environmental sustainability",
      image: heroRunner,
      slug: undefined,
      category: undefined,
      image_attribution: undefined,
    },
    {
      title: "Breaking Barriers",
      subtitle: "Empowering the next generation of women athletes in Kenya",
      image: heroRunner,
      slug: undefined,
      category: undefined,
      image_attribution: undefined,
    },
  ];

  // Use top stories if available, otherwise use fallback
  const slides = heroStories.length > 0
    ? heroStories.map(story => ({
        title: story.title,
        subtitle: story.excerpt,
        image: story.image_url || heroRunner,
        slug: story.slug,
        category: story.category,
        image_attribution: story.image_attribution,
      }))
    : fallbackSlides;

  const activeSlide = slides[currentSlide] ?? slides[0];

  useEffect(() => {
    const interval = setInterval(() => {
      setCurrentSlide((prev) => (prev + 1) % slides.length);
    }, 15000);
    return () => clearInterval(interval);
  }, [slides.length]);

  useEffect(() => {
    setCurrentSlide((prev) => (prev >= slides.length ? 0 : prev));
  }, [slides.length]);

  const nextSlide = () => setCurrentSlide((prev) => (prev + 1) % slides.length);
  const prevSlide = () => setCurrentSlide((prev) => (prev - 1 + slides.length) % slides.length);

  const handleReadStory = () => {
    if (heroStories.length > 0 && activeSlide.slug) {
      window.location.href = `/stories/${activeSlide.slug}`;
    } else {
      window.location.href = "#stories";
    }
  };

  return (
    <section id="home" className="relative h-screen flex items-center justify-center overflow-hidden">
      {/* Background Image with Dark Gradient Overlay */}
      <div 
        className="absolute inset-0 transition-opacity duration-1000 bg-cover bg-center"
        style={{
          backgroundImage: `url(${activeSlide.image})`,
        }}
      >
        {/* Dark gradient overlay for better text readability */}
        <div className="absolute inset-0 bg-gradient-to-b from-black/70 via-black/50 to-black/70" />
      </div>

      {/* Content Container */}
      <div className="relative z-10 container mx-auto px-4 sm:px-6 lg:px-8">
        <div className="max-w-4xl mx-auto text-center space-y-6 sm:space-y-8">
          {/* Category Badge */}
          {activeSlide.category && (
            <div className="animate-fade-in">
              <span className="inline-flex items-center bg-white/10 backdrop-blur-md text-white px-4 py-2 rounded-full text-xs sm:text-sm font-semibold border border-white/20 shadow-lg">
                {activeSlide.category}
              </span>
            </div>
          )}

          {/* Main Heading */}
          <h1 className="text-3xl sm:text-4xl md:text-5xl lg:text-6xl font-bold text-white leading-tight animate-fade-up px-4">
            {activeSlide.title}
          </h1>

          {/* Subtitle */}
          <p className="text-base sm:text-lg md:text-xl text-white/90 max-w-2xl mx-auto leading-relaxed animate-fade-up animation-delay-100 px-4">
            {activeSlide.subtitle.length > 150 
              ? activeSlide.subtitle.substring(0, 150) + '...' 
              : activeSlide.subtitle}
          </p>
          
          {/* CTA Buttons */}
          <div className="flex flex-col sm:flex-row gap-3 sm:gap-4 justify-center items-center animate-fade-up animation-delay-200 px-4">
            <Button 
              size="lg" 
              className="w-full sm:w-auto bg-gradient-to-r from-[#FF6600] to-[#FFBF00] hover:from-[#E55A00] hover:to-[#E6AB00] text-white font-semibold text-sm sm:text-base px-6 sm:px-8 py-5 sm:py-6 shadow-xl hover:shadow-2xl transition-all duration-300 rounded-lg"
              onClick={handleReadStory}
            >
              {heroStories.length > 0 ? 'Read This Story' : 'Share Your Story'}
            </Button>
            <Button 
              size="lg" 
              variant="outline" 
              className="w-full sm:w-auto bg-white/10 backdrop-blur-md border-2 border-white/30 text-white hover:bg-white hover:text-gray-900 font-semibold text-sm sm:text-base px-6 sm:px-8 py-5 sm:py-6 transition-all duration-300 rounded-lg"
              onClick={() => window.location.href = "#videos"}
            >
              <Play className="mr-2 h-4 w-4 sm:h-5 sm:w-5" />
              Watch Stories
            </Button>
          </div>

          {heroLoading && heroStories.length === 0 && (
            <div className="flex items-center justify-center gap-2 text-white/80 text-xs sm:text-sm animate-fade-in">
              <Loader2 className="h-4 w-4 animate-spin" />
              <span>Fetching the latest story...</span>
            </div>
          )}

          {/* Slide Indicators */}
          <div className="flex justify-center items-center gap-2 sm:gap-3 pt-4 sm:pt-6 animate-fade-in animation-delay-300">
            {slides.map((_, index) => (
              <button
                key={index}
                onClick={() => setCurrentSlide(index)}
                className={`h-1.5 sm:h-2 rounded-full transition-all duration-300 ${
                  index === currentSlide 
                    ? "w-8 sm:w-12 bg-white" 
                    : "w-1.5 sm:w-2 bg-white/40 hover:bg-white/60"
                }`}
                aria-label={`Go to slide ${index + 1}`}
              />
            ))}
          </div>

        
        </div>
      </div>

      {activeSlide.image_attribution && (
        <div className="absolute bottom-4 left-4 z-20 bg-black/55 text-white text-xs italic px-3 py-1.5 rounded backdrop-blur-sm">
          {activeSlide.image_attribution}
        </div>
      )}

      {/* Navigation Arrows - Hidden on mobile, visible on tablet+ */}
      <button
        onClick={prevSlide}
        className="hidden md:flex absolute left-4 lg:left-8 top-1/2 -translate-y-1/2 z-20 items-center justify-center bg-white/10 hover:bg-white/20 backdrop-blur-md text-white w-12 h-12 lg:w-14 lg:h-14 rounded-full transition-all duration-300 hover:scale-110 border border-white/20"
        aria-label="Previous slide"
      >
        <ChevronLeft className="w-6 h-6 lg:w-7 lg:h-7" />
      </button>
      <button
        onClick={nextSlide}
        className="hidden md:flex absolute right-4 lg:right-8 top-1/2 -translate-y-1/2 z-20 items-center justify-center bg-white/10 hover:bg-white/20 backdrop-blur-md text-white w-12 h-12 lg:w-14 lg:h-14 rounded-full transition-all duration-300 hover:scale-110 border border-white/20"
        aria-label="Next slide"
      >
        <ChevronRight className="w-6 h-6 lg:w-7 lg:h-7" />
      </button>

     
    </section>
  );
};

export default HeroSection;
