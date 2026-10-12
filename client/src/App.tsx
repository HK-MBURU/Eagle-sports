import { useEffect } from 'react';
import { pageview } from '@/utils/analytics';
import { Toaster } from "@/components/ui/toaster";
import { Toaster as Sonner } from "@/components/ui/sonner";
import { TooltipProvider } from "@/components/ui/tooltip";
import { QueryClient, QueryClientProvider } from "@tanstack/react-query";
import { BrowserRouter, Routes, Route, useLocation } from "react-router-dom";
import { HelmetProvider } from "react-helmet-async";
import { AuthProvider } from "@/contexts/AuthContext";
import { StoriesProvider } from "@/contexts/StoriesContext";
import { ProtectedRoute } from "@/components/ProtectedRoute";
import Index from "./pages/Index";
import StoryDetail from "./pages/StoryDetail";
import Login from "./pages/Login";
import NotFound from "./pages/NotFound";
import AthleteDetail from "./pages/AthleteDetail";
import WomenStoryDetail from "./pages/WomenStoryDetail";
import Athletics from "./pages/news/Athletics";
import RaceReports from "./pages/news/RaceReports";
import OpinionAnalysis from "./pages/news/OpinionAnalysis";
import AthleteProfiles from "./pages/stories/AthleteProfiles";
import StoriesInspire from "./pages/stories/StoriesInspire";
import RisingStars from "./pages/stories/RisingStars";
import Legends from "./pages/stories/Legends";
import NCAAStories from "./pages/scholarships/NCAAStories";
import ScholarshipGuide from "./pages/scholarships/ScholarshipGuide";
import ApplicationTips from "./pages/scholarships/ApplicationTips";
import SuccessStories from "./pages/scholarships/SuccessStories";
import About from "./pages/About";
import Interviews from "./pages/media/Interviews";
import PhotoGallery from "./pages/media/PhotoGallery";
import Videos from "./pages/media/Videos";
import AthleteHealth from "./pages/training/AthleteHealth";
import SportsScience from "./pages/training/SportsScience";
import CoachingTips from "./pages/training/CoachingTips";
import RaceCalendar from "./pages/races/RaceCalendar";
import UpcomingEvents from "./pages/races/UpcomingEvents";
import RaceResults from "./pages/races/RaceResults";
import Youth from "./pages/impact/Youth";
import Community from "./pages/impact/Community";
import Climate from "./pages/impact/Climate";
import WomenInAthletics from "./pages/impact/WomenInAthletics";
import Register from './pages/Register';
import RaceResultDetail from './pages/races/RaceResultDetail';
import EventDetail from "./pages/races/RaceEventDetail";

// New pages for Integrity & Fair Play
import SanctionsBans from "./pages/integrity/SanctionsBans";
import EducationAdvocacy from "./pages/integrity/EducationAdvocacy";
import RedemptionStories from "./pages/integrity/RedemptionStories";

// New pages for Rising Stars
import KidsAthletics from "./pages/rising-stars/KidsAthletics";
import JuniorAthletics from "./pages/rising-stars/JuniorAthletics";
import U20Prospects from "./pages/rising-stars/U20Prospects";
import TalentAcademies from "./pages/rising-stars/TalentAcademies";
import ProfilesInterviews from "./pages/rising-stars/ProfilesInterviews";
import Team from "./pages/Team";
import PartnersPage from "./pages/PartnersPage";
import Contact from "./pages/Contact";
import SubmitStory from "./pages/SubmitStory";
import Mentorship from "./pages/Mentorship";
import HallOfFame from "./pages/HallOfFame";
import TermsOfService from "./pages/TermsOfService";
import PrivacyPolicy from "./pages/PrivacyPolicy";

const queryClient = new QueryClient();

const AnalyticsTracker = () => {
  const location = useLocation();

  useEffect(() => {
    window.scrollTo({ top: 0, left: 0, behavior: "instant" });

    if (typeof window !== 'undefined' && window.gtag) {
      pageview(location.pathname + location.search);
    }
  }, [location.pathname, location.search]);

  return null;
};

const App = () => {
  return (
    <QueryClientProvider client={queryClient}>
      <HelmetProvider>
        <TooltipProvider>
          <Toaster />
          <Sonner />
          <BrowserRouter>
            <AuthProvider>
              <StoriesProvider>
                <AnalyticsTracker />
                <Routes>
                  <Route path="/" element={<Index />} />
                  <Route path="/login" element={<Login />} />
                  <Route path="/register" element={<Register />} />
                  <Route path="/stories/:slug" element={<StoryDetail />} />
                  <Route path="/athletes/:id" element={<AthleteDetail />} />
                  <Route path="/women-stories/:id" element={<WomenStoryDetail />} />

                  {/* News & Features Routes */}
                  <Route path="/news/athletics" element={<Athletics />} />
                  <Route path="/news/race-reports" element={<RaceReports />} />
                  <Route path="/news/opinion-analysis" element={<OpinionAnalysis />} />

                  {/* Athletes Stories Routes */}
                  <Route path="/stories/profiles" element={<AthleteProfiles />} />
                  <Route path="/stories/inspire" element={<StoriesInspire />} />
                  <Route path="/stories/rising-stars" element={<RisingStars />} />
                  <Route path="/stories/legends" element={<Legends />} />

                  {/* Scholarships & Pathways Routes */}
                  <Route path="/scholarships/ncaa-stories" element={<NCAAStories />} />
                  <Route path="/scholarships/guide" element={<ScholarshipGuide />} />
                  <Route path="/scholarships/tips" element={<ApplicationTips />} />
                  <Route path="/scholarships/success" element={<SuccessStories />} />

                  {/* About Routes */}
                  <Route path="/about" element={<About />} />
                  <Route path="/team" element={<Team />} />
                  <Route path="/partners" element={<PartnersPage />} />
                  <Route path="/contact" element={<Contact />} />
                  <Route path="/submit-story" element={<SubmitStory />} />
                  <Route path="/mentorship" element={<Mentorship />} />
                  <Route path="/hall-of-fame" element={<HallOfFame />} />
                  <Route path="/terms-of-service" element={<TermsOfService />} />
                  <Route path="/privacy-policy" element={<PrivacyPolicy />} />

                  {/* Integrity & Fair Play Routes */}
                  <Route path="/integrity/sanctions-bans" element={<SanctionsBans />} />
                  <Route path="/integrity/education-advocacy" element={<EducationAdvocacy />} />
                  <Route path="/integrity/redemption-stories" element={<RedemptionStories />} />

                  {/* Rising Stars Routes */}
                  <Route path="/rising-stars/kids" element={<KidsAthletics />} />
                  <Route path="/rising-stars/juniors" element={<JuniorAthletics />} />
                  <Route path="/rising-stars/u20-prospects" element={<U20Prospects />} />
                  <Route path="/rising-stars/academies-camps" element={<TalentAcademies />} />
                  <Route path="/rising-stars/profiles-interviews" element={<ProfilesInterviews />} />

                  {/* Athletics & Impact Routes */}
                  <Route path="/impact/women" element={<WomenInAthletics />} />
                  <Route path="/impact/climate" element={<Climate />} />
                  <Route path="/impact/community" element={<Community />} />
                  <Route path="/impact/youth" element={<Youth />} />

                  {/* Races & Results Routes */}
                  <Route path="/races/results" element={<RaceResults />} />
                  <Route path="/race-results/:id" element={<RaceResultDetail />} />
                  <Route path="/races/upcoming" element={<UpcomingEvents />} />
                  <Route path="/races/calendar" element={<RaceCalendar />} />
                  <Route path="/races/events/:id" element={<EventDetail />} />

                  {/* Training & Performance Routes */}
                  <Route path="/training/coaching" element={<CoachingTips />} />
                  <Route path="/training/science" element={<SportsScience />} />
                  <Route path="/training/health" element={<AthleteHealth />} />

                  {/* Media Routes */}
                  <Route path="/media/videos" element={<Videos />} />
                  <Route path="/media/photos" element={<PhotoGallery />} />
                  <Route path="/media/interviews" element={<Interviews />} />

                  {/* Catch-all 404 route - MUST BE LAST */}
                  <Route path="*" element={<NotFound />} />
                </Routes>
              </StoriesProvider>
            </AuthProvider>
          </BrowserRouter>
        </TooltipProvider>
      </HelmetProvider>
    </QueryClientProvider>
  );
};

export default App;
