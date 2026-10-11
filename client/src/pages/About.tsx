import PageTemplate from "@/components/PageTemplate";
import { Card, CardContent } from "@/components/ui/card";
import { Button } from "@/components/ui/button";
import { 
  Target, 
  Eye, 
  Heart, 
  Users, 
  Globe, 
  Award, 
  BookOpen,
  Lightbulb,
  Mail,
  Phone,
  MapPin,
  Facebook,
  Twitter,
  Instagram,
  Youtube
} from "lucide-react";
import { Link } from "react-router-dom";

const About = () => {
  return (
    <PageTemplate
      title="About Eagle Sports"
      description="Beyond the Finish Line — Where Every Athlete's Story Matters"
    >
      <div className="max-w-6xl mx-auto space-y-16">
        {/* Introduction Section */}
        <section className="text-center">
        
          <p className="text-lg text-gray-600 max-w-3xl mx-auto leading-relaxed">
            Eagle Sports is a digital storytelling platform that highlights athletics beyond competition.
            We focus on athlete journeys, education pathways, human impact stories, and comprehensive track & field coverage
            from Kenya and around the world — always going beyond the finish line.
          </p>
        </section>

        {/* Mission & Vision Cards */}
        <section className="grid md:grid-cols-2 gap-8">
          <Card className="border-2 border-gray-200">
            <CardContent className="p-8">
              <div className="flex items-center gap-3 mb-4">
                <div className="w-12 h-12 bg-[#D43028] flex items-center justify-center">
                  <Target className="text-white" size={24} />
                </div>
                <h3 className="text-2xl font-bold text-gray-900">Our Mission</h3>
              </div>
              <p className="text-gray-700 leading-relaxed">
                To champion athlete stories beyond medals – highlighting opportunity, education, resilience, 
                and impact through athletics. We believe every athlete's journey deserves to be told and celebrated.
              </p>
            </CardContent>
          </Card>

          <Card className="border-2 border-gray-200">
            <CardContent className="p-8">
              <div className="flex items-center gap-3 mb-4">
                <div className="w-12 h-12 bg-[#4A7CC0] flex items-center justify-center">
                  <Eye className="text-white" size={24} />
                </div>
                <h3 className="text-2xl font-bold text-gray-900">Our Vision</h3>
              </div>
              <p className="text-gray-700 leading-relaxed">
                To become Africa's leading online platform for empowering athletes through storytelling, 
                education access, and global exposure – connecting dreams with opportunities.
              </p>
            </CardContent>
          </Card>
        </section>

        {/* What We Do Section */}
        <section>
          <h2 className="text-3xl font-bold text-gray-900 mb-8 text-center">What We Do</h2>
          <div className="grid md:grid-cols-3 gap-6">
            <Card className="border border-gray-200">
              <CardContent className="p-6 text-center">
                <div className="w-16 h-16 bg-[#D43028] flex items-center justify-center mx-auto mb-4">
                  <BookOpen className="text-white" size={28} />
                </div>
                <h3 className="text-xl font-bold text-gray-900 mb-3">Storytelling Platform</h3>
                <p className="text-gray-600 text-sm">
                  Building a trusted athletics storytelling platform that shares athlete journeys, achievements, 
                  and the human stories behind the medals.
                </p>
              </CardContent>
            </Card>

            <Card className="border border-gray-200">
              <CardContent className="p-6 text-center">
                <div className="w-16 h-16 bg-[#4A7CC0] flex items-center justify-center mx-auto mb-4">
                  <Award className="text-white" size={28} />
                </div>
                <h3 className="text-xl font-bold text-gray-900 mb-3">Scholarship Pathways</h3>
                <p className="text-gray-600 text-sm">
                  Providing comprehensive scholarship information and connecting runners with global educational 
                  and training opportunities, particularly NCAA programs.
                </p>
              </CardContent>
            </Card>

            <Card className="border border-gray-200">
              <CardContent className="p-6 text-center">
                <div className="w-16 h-16 bg-[#1A3470] flex items-center justify-center mx-auto mb-4">
                  <Globe className="text-white" size={28} />
                </div>
                <h3 className="text-xl font-bold text-gray-900 mb-3">Global Exposure</h3>
                <p className="text-gray-600 text-sm">
                  Publishing race results, athletics news, and providing a platform for athletes to gain 
                  visibility and connect with scouts, coaches, and opportunities worldwide.
                </p>
              </CardContent>
            </Card>
          </div>
        </section>

        {/* Our Focus Areas */}
        <section className="bg-gray-50 -mx-4 px-4 py-12 md:-mx-8 md:px-8">
          <div className="max-w-6xl mx-auto">
            <h2 className="text-3xl font-bold text-gray-900 mb-8 text-center">Our Focus Areas</h2>
            <div className="grid md:grid-cols-2 lg:grid-cols-4 gap-6">
              <div className="text-center">
                <div className="w-12 h-12 bg-[#D43028] flex items-center justify-center mx-auto mb-3">
                  <Users className="text-white" size={24} />
                </div>
                <h4 className="font-bold text-gray-900 mb-2">Women in Athletics</h4>
                <p className="text-sm text-gray-600">
                  Highlighting and empowering female athletes in track and field
                </p>
              </div>

              <div className="text-center">
                <div className="w-12 h-12 bg-[#4A7CC0] flex items-center justify-center mx-auto mb-3">
                  <Heart className="text-white" size={24} />
                </div>
                <h4 className="font-bold text-gray-900 mb-2">Youth Development</h4>
                <p className="text-sm text-gray-600">
                  Supporting rising stars and junior athletes in their journey
                </p>
              </div>

              <div className="text-center">
                <div className="w-12 h-12 bg-[#1A3470] flex items-center justify-center mx-auto mb-3">
                  <Lightbulb className="text-white" size={24} />
                </div>
                <h4 className="font-bold text-gray-900 mb-2">Social Impact</h4>
                <p className="text-sm text-gray-600">
                  Advocating for sports development and community projects
                </p>
              </div>

              <div className="text-center">
                <div className="w-12 h-12 bg-[#D43028] flex items-center justify-center mx-auto mb-3">
                  <Globe className="text-white" size={24} />
                </div>
                <h4 className="font-bold text-gray-900 mb-2">Climate & Environment</h4>
                <p className="text-sm text-gray-600">
                  Addressing environmental issues in athletics and sports
                </p>
              </div>
            </div>
          </div>
        </section>

        {/* Who We Serve */}
        <section>
          <h2 className="text-3xl font-bold text-gray-900 mb-8 text-center">Who We Serve</h2>
          <div className="grid md:grid-cols-3 gap-4 max-w-4xl mx-auto">
            <div className="border-l-4 border-[#D43028] pl-4 py-2">
              <h4 className="font-bold text-gray-900">Athletes</h4>
              <p className="text-sm text-gray-600">Track & field competitors at all levels</p>
            </div>
            <div className="border-l-4 border-[#4A7CC0] pl-4 py-2">
              <h4 className="font-bold text-gray-900">Coaches & Scouts</h4>
              <p className="text-sm text-gray-600">Talent developers and recruiters</p>
            </div>
            <div className="border-l-4 border-[#1A3470] pl-4 py-2">
              <h4 className="font-bold text-gray-900">Sports Fans</h4>
              <p className="text-sm text-gray-600">Athletics enthusiasts worldwide</p>
            </div>
            <div className="border-l-4 border-[#D43028] pl-4 py-2">
              <h4 className="font-bold text-gray-900">Media</h4>
              <p className="text-sm text-gray-600">Journalists and sports writers</p>
            </div>
            <div className="border-l-4 border-[#4A7CC0] pl-4 py-2">
              <h4 className="font-bold text-gray-900">Scholarship Seekers</h4>
              <p className="text-sm text-gray-600">Athletes pursuing education opportunities</p>
            </div>
            <div className="border-l-4 border-[#1A3470] pl-4 py-2">
              <h4 className="font-bold text-gray-900">Development Partners</h4>
              <p className="text-sm text-gray-600">Organizations supporting sports growth</p>
            </div>
          </div>
        </section>

        {/* Contact Information */}
        <section className="bg-gray-50 -mx-4 px-4 py-12 md:-mx-8 md:px-8">
          <div className="max-w-4xl mx-auto">
            <h2 className="text-3xl font-bold text-gray-900 mb-8 text-center">Get In Touch</h2>
            <div className="grid md:grid-cols-3 gap-8">
              <div className="text-center">
                <div className="w-12 h-12 bg-[#D43028] flex items-center justify-center mx-auto mb-3">
                  <Mail className="text-white" size={24} />
                </div>
                <h4 className="font-bold text-gray-900 mb-2">Email</h4>
                <a href="mailto:kibetesq@gmail.com" className="text-sm text-gray-600 hover:text-[#FF6600]">
                  kibetesq@gmail.com
                </a>
              </div>

              <div className="text-center">
                <div className="w-12 h-12 bg-[#4A7CC0] flex items-center justify-center mx-auto mb-3">
                  <Phone className="text-white" size={24} />
                </div>
                <h4 className="font-bold text-gray-900 mb-2">WhatsApp</h4>
                <a href="https://wa.me/254722851458" className="text-sm text-gray-600 hover:text-[#FF6600]">
                  +254 722 851458
                </a>
              </div>

              <div className="text-center">
                <div className="w-12 h-12 bg-[#1A3470] flex items-center justify-center mx-auto mb-3">
                  <MapPin className="text-white" size={24} />
                </div>
                <h4 className="font-bold text-gray-900 mb-2">Location</h4>
                <p className="text-sm text-gray-600">Nairobi, Kenya</p>
              </div>
            </div>
          </div>
        </section>

        {/* Social Media */}
        <section>
          <h2 className="text-3xl font-bold text-gray-900 mb-8 text-center">Follow Our Journey</h2>
          <div className="flex justify-center gap-6">
            <a 
              href="#" 
              className="w-14 h-14 border-2 border-gray-300 flex items-center justify-center"
              aria-label="Facebook"
            >
              <Facebook className="text-gray-600" size={24} />
            </a>
            <a 
              href="#" 
              className="w-14 h-14 border-2 border-gray-300 flex items-center justify-center"
              aria-label="Twitter"
            >
              <Twitter className="text-gray-600" size={24} />
            </a>
            <a 
              href="#" 
              className="w-14 h-14 border-2 border-gray-300 flex items-center justify-center"
              aria-label="Instagram"
            >
              <Instagram className="text-gray-600" size={24} />
            </a>
            <a 
              href="#" 
              className="w-14 h-14 border-2 border-gray-300 flex items-center justify-center"
              aria-label="YouTube"
            >
              <Youtube className="text-gray-600" size={24} />
            </a>
          </div>
        </section>

        {/* Call to Action */}
        <section className="text-center border-t-2 border-gray-200 pt-12">
          <h2 className="text-3xl font-bold text-gray-900 mb-4">Join Our Mission</h2>
          <p className="text-lg text-gray-600 mb-8 max-w-2xl mx-auto">
            Whether you're an athlete with a story to share, a partner looking to support sports development, 
            or a fan of athletics, we'd love to hear from you.
          </p>
          <div className="flex flex-wrap justify-center gap-4">
            <Button asChild className="bg-[#D43028] text-white px-8 py-6 text-base">
              <Link to="/submit-story">Share Your Story</Link>
            </Button>
            <Button asChild variant="outline" className="border-2 border-[#D43028] text-[#FF6600] px-8 py-6 text-base">
              <Link to="/partners">Partner With Us</Link>
            </Button>
          </div>
        </section>
      </div>
    </PageTemplate>
  );
};

export default About;
