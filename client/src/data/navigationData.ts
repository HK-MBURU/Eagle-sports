export interface NavItem {
  name: string;
  href?: string;
  children?: NavItem[];
}

export const navigationData: NavItem[] = [
  {
    name: "News & Features",
    children: [
      { name: "Athletics", href: "/news/athletics" },
      { name: "Race Reports", href: "/news/race-reports" },
      { name: "Opinion & Analysis", href: "/news/opinion-analysis" },
    ],
  },
  {
    name: "Athletes Stories",
    children: [
      { name: "Athlete Profiles", href: "/#athletes" },
      { name: "Stories that Inspire", href: "/#stories" },
      { name: "Rising Stars/Juniors", href: "/stories/rising-stars" },
      { name: "Legends Corner", href: "/stories/legends" },
    ],
  },
  {
    name: "Scholarships & Pathways",
    children: [
      { name: "NCAA Stories", href: "/scholarships/ncaa-stories" },
      { name: "Scholarship Guide", href: "/scholarships/guide" },
      { name: "Application Tips", href: "/scholarships/tips" },
      { name: "Success Stories", href: "/scholarships/success" },
    ],
  },
  {
    name: "More",
    children: [
      {
        name: "About",
        children: [
          { name: "Our Mission", href: "/about" },
          { name: "Team", href: "/team" },
          { name: "Partners", href: "/partners" },
          { name: "Contact", href: "/contact" },
        ],
      },
      {
        name: "Integrity & Fair Play",
        children: [
          { name: "Sanctions & Bans", href: "/integrity/sanctions-bans" },
          { name: "Education & Advocacy", href: "/integrity/education-advocacy" },
          { name: "Redemption Stories", href: "/integrity/redemption-stories" },
        ],
      },
      {
        name: "Rising Stars",
        children: [
          { name: "Kids Athletics", href: "/rising-stars/kids" },
          { name: "Junior Athletics", href: "/rising-stars/juniors" },
          { name: "U-20 Prospects", href: "/rising-stars/u20-prospects" },
          { name: "Talent Academies & Camps", href: "/rising-stars/academies-camps" },
          { name: "Profiles & Interviews", href: "/rising-stars/profiles-interviews" },
        ],
      },
      {
        name: "Athletics & Impact",
        children: [
          { name: "Women in Athletics", href: "/#women" },
          { name: "Climate & Environment", href: "/#climate" },
          { name: "Community Projects", href: "/impact/community" },
          { name: "Youth Empowerment", href: "/impact/youth" },
        ],
      },
      {
        name: "Get Involved",
        children: [
          { name: "Submit Your Story", href: "/submit-story" },
          { name: "Mentorship", href: "/mentorship" },
          { name: "Hall of Fame", href: "/hall-of-fame" },
        ],
      },
      {
        name: "Races & Results",
        children: [
          { name: "Race Results", href: "/races/results" },
          { name: "Upcoming Events", href: "/#races" },
          { name: "Race Calendar", href: "/races/calendar" },
        ],
      },
      {
        name: "Training & Performance",
        children: [
          { name: "Coaching Tips", href: "/training/coaching" },
          { name: "Sports Science", href: "/training/science" },
          { name: "Athlete Health and Mindset", href: "/training/health" },
        ],
      },
      {
        name: "Media",
        children: [
          { name: "Videos", href: "/#videos" },
          { name: "Photo Gallery", href: "/media/photos" },
          { name: "Interviews", href: "/media/interviews" },
        ],
      },
      {
        name: "Legal",
        children: [
          { name: "Privacy Policy", href: "/privacy-policy" },
          { name: "Terms of Service", href: "/terms-of-service" },
        ],
      },
    ],
  },
];
