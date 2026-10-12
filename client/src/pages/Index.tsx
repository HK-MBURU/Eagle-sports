import Navigation from "@/components/Navigation";
import HeroSection from "@/components/HeroSection";
import FeaturedAthletes from "@/components/FeaturedAthletes";
import Stories from "@/components/Stories";
import FlagshipEvent from "@/components/FlagshipEvent";
import ResultsCentre from "@/components/ResultsCentre";
import WomenSpotlight from "@/components/WomenSpotlight";
import ClimateInitiative from "@/components/ClimateInitiative";
import UpcomingRaces from "@/components/UpcomingRaces";
import CompetitionCalendar from "@/components/CompetitionCalendar";
import VideoGallery from "@/components/VideoGallery";
import GetInvolved from "@/components/GetInvolved";
import Partners from "@/components/Partners";
import Footer from "@/components/Footer";

const Index = () => {
  return (
    <div className="min-h-screen">
      <Navigation />
      <main>
        <HeroSection />
        <FeaturedAthletes />
        <Stories />
        <FlagshipEvent />
        <ResultsCentre />
        <WomenSpotlight />
        <ClimateInitiative />
        <UpcomingRaces />
        <CompetitionCalendar />
        <VideoGallery />
        <GetInvolved />
        <Partners />
      </main>
      <Footer />
    </div>
  );
};

export default Index;
