// scripts/generate-sitemap.ts
import fs from 'fs';
import path from 'path';
import { fileURLToPath } from 'url';

// Fix for __dirname in ES modules
const __filename = fileURLToPath(import.meta.url);
const __dirname = path.dirname(__filename);

const API_BASE_URL = 'https://admin.beyondfinishline.com/api';
const SITE_URL = 'https://beyondfinishline.com';

interface Story {
  id: number;
  slug: string;
  published_at: string;
  category?: string;
}

interface Athlete {
  id: number;
  name: string;
  updated_at?: string;
}

interface WomenRunningEntry {
  id: number;
  created_at: string;
  category?: string;
}

interface Video {
  id: number;
  created_at: string;
  category?: string;
}

interface RaceEvent {
  id: number;
  event_date: string;
}

interface RaceResult {
  id: number;
  race_date: string;
  category?: string;
}

interface Partner {
  id: number;
  created_at?: string;
}

// Static routes with priority and change frequency
const staticRoutes = [
  // Main pages
  { path: '/', priority: '1.0', changefreq: 'daily' },
  { path: '/about', priority: '0.8', changefreq: 'monthly' },
  { path: '/team', priority: '0.7', changefreq: 'monthly' },
  { path: '/partners', priority: '0.7', changefreq: 'monthly' },
  { path: '/contact', priority: '0.8', changefreq: 'monthly' },
  { path: '/submit-story', priority: '0.7', changefreq: 'monthly' },
  { path: '/mentorship', priority: '0.7', changefreq: 'monthly' },
  { path: '/hall-of-fame', priority: '0.7', changefreq: 'weekly' },
  
  // News & Features
  { path: '/news/athletics', priority: '0.9', changefreq: 'daily' },
  { path: '/news/race-reports', priority: '0.9', changefreq: 'daily' },
  { path: '/news/opinion-analysis', priority: '0.8', changefreq: 'daily' },
  
  // Athletes Stories
  { path: '/stories/profiles', priority: '0.9', changefreq: 'weekly' },
  { path: '/stories/inspire', priority: '0.9', changefreq: 'weekly' },
  { path: '/stories/rising-stars', priority: '0.9', changefreq: 'weekly' },
  { path: '/stories/legends', priority: '0.9', changefreq: 'weekly' },
  
  // Scholarships & Pathways
  { path: '/scholarships/ncaa-stories', priority: '0.8', changefreq: 'weekly' },
  { path: '/scholarships/guide', priority: '0.8', changefreq: 'monthly' },
  { path: '/scholarships/tips', priority: '0.8', changefreq: 'monthly' },
  { path: '/scholarships/success', priority: '0.8', changefreq: 'weekly' },
  
  // Integrity & Fair Play
  { path: '/integrity/sanctions-bans', priority: '0.7', changefreq: 'weekly' },
  { path: '/integrity/education-advocacy', priority: '0.7', changefreq: 'monthly' },
  { path: '/integrity/redemption-stories', priority: '0.7', changefreq: 'weekly' },
  
  // Rising Stars
  { path: '/rising-stars/kids', priority: '0.8', changefreq: 'weekly' },
  { path: '/rising-stars/juniors', priority: '0.8', changefreq: 'weekly' },
  { path: '/rising-stars/u20-prospects', priority: '0.8', changefreq: 'weekly' },
  { path: '/rising-stars/academies-camps', priority: '0.7', changefreq: 'monthly' },
  { path: '/rising-stars/profiles-interviews', priority: '0.8', changefreq: 'weekly' },
  
  // Impact
  { path: '/impact/women', priority: '0.8', changefreq: 'weekly' },
  { path: '/impact/climate', priority: '0.7', changefreq: 'monthly' },
  { path: '/impact/community', priority: '0.7', changefreq: 'weekly' },
  { path: '/impact/youth', priority: '0.7', changefreq: 'weekly' },
  
  // Races & Results
  { path: '/races/results', priority: '0.9', changefreq: 'daily' },
  { path: '/races/upcoming', priority: '0.9', changefreq: 'daily' },
  { path: '/races/calendar', priority: '0.9', changefreq: 'daily' },
  
  // Training & Performance
  { path: '/training/coaching', priority: '0.7', changefreq: 'weekly' },
  { path: '/training/science', priority: '0.7', changefreq: 'weekly' },
  { path: '/training/health', priority: '0.7', changefreq: 'weekly' },
  
  // Media
  { path: '/media/videos', priority: '0.8', changefreq: 'weekly' },
  { path: '/media/photos', priority: '0.8', changefreq: 'weekly' },
  { path: '/media/interviews', priority: '0.8', changefreq: 'weekly' },
  
  // Legal
  { path: '/terms-of-service', priority: '0.3', changefreq: 'yearly' },
  { path: '/privacy-policy', priority: '0.3', changefreq: 'yearly' },
];

async function fetchFromAPI<T>(endpoint: string, retries = 3): Promise<T> {
  for (let attempt = 1; attempt <= retries; attempt++) {
    try {
      console.log(`Fetching ${endpoint} (attempt ${attempt}/${retries})...`);
      
      const controller = new AbortController();
      const timeout = setTimeout(() => controller.abort(), 30000); // 30 second timeout
      
      const response = await fetch(`${API_BASE_URL}${endpoint}`, {
        signal: controller.signal,
        headers: {
          'Accept': 'application/json',
          'Content-Type': 'application/json',
        },
      });
      
      clearTimeout(timeout);
      
      if (!response.ok) {
        throw new Error(`HTTP error! status: ${response.status}`);
      }
      
      const data = await response.json();
      console.log(`✓ Successfully fetched ${endpoint}`);
      return data;
    } catch (error) {
      console.error(`✗ Attempt ${attempt} failed for ${endpoint}:`, error instanceof Error ? error.message : 'Unknown error');
      
      if (attempt === retries) {
        console.error(`Failed to fetch ${endpoint} after ${retries} attempts`);
        return { success: false, data: [] } as T;
      }
      
      // Wait before retrying (exponential backoff)
      await new Promise(resolve => setTimeout(resolve, 1000 * attempt));
    }
  }
  
  return { success: false, data: [] } as T;
}

function formatDate(date: string): string {
  try {
    return new Date(date).toISOString().split('T')[0];
  } catch {
    return new Date().toISOString().split('T')[0];
  }
}

function safeArray<T>(data: any): T[] {
  // Handle different response structures
  if (Array.isArray(data)) {
    return data;
  }
  if (data && typeof data === 'object') {
    if (Array.isArray(data.data)) {
      return data.data;
    }
    if (data.data && Array.isArray(data.data.data)) {
      return data.data.data;
    }
  }
  return [];
}

async function generateSitemap() {
  console.log('🚀 Starting sitemap generation for Beyond Finish Line...');
  console.log(`📍 API: ${API_BASE_URL}`);
  console.log(`🌐 Site: ${SITE_URL}`);
  console.log('');

  // Fetch data with smaller page sizes to avoid timeouts
  console.log('Fetching data from API...');
  const [
    storiesResponse,
    athletesResponse,
    womenRunningResponse,
    videosResponse,
    raceEventsResponse,
    raceResultsResponse,
    partnersResponse
  ] = await Promise.allSettled([
    fetchFromAPI<any>('/stories?per_page=500'),
    fetchFromAPI<any>('/athletes?per_page=500'),
    fetchFromAPI<any>('/women-running?per_page=500'),
    fetchFromAPI<any>('/videos?per_page=500'),
    fetchFromAPI<any>('/race-events?per_page=500'),
    fetchFromAPI<any>('/race-results?per_page=500'),
    fetchFromAPI<any>('/partners?per_page=500'),
  ]);

  // Safely extract data from responses
  const stories = safeArray<Story>(
    storiesResponse.status === 'fulfilled' ? storiesResponse.value : []
  );
  const athletes = safeArray<Athlete>(
    athletesResponse.status === 'fulfilled' ? athletesResponse.value : []
  );
  const womenRunning = safeArray<WomenRunningEntry>(
    womenRunningResponse.status === 'fulfilled' ? womenRunningResponse.value : []
  );
  const videos = safeArray<Video>(
    videosResponse.status === 'fulfilled' ? videosResponse.value : []
  );
  const raceEvents = safeArray<RaceEvent>(
    raceEventsResponse.status === 'fulfilled' ? raceEventsResponse.value : []
  );
  const raceResults = safeArray<RaceResult>(
    raceResultsResponse.status === 'fulfilled' ? raceResultsResponse.value : []
  );
  const partners = safeArray<Partner>(
    partnersResponse.status === 'fulfilled' ? partnersResponse.value : []
  );

  console.log('');
  console.log('📊 Data Summary:');
  console.log(`📰 Stories: ${stories.length}`);
  console.log(`🏃 Athletes: ${athletes.length}`);
  console.log(`👩 Women Running: ${womenRunning.length}`);
  console.log(`🎥 Videos: ${videos.length}`);
  console.log(`📅 Race Events: ${raceEvents.length}`);
  console.log(`🏆 Race Results: ${raceResults.length}`);
  console.log(`🤝 Partners: ${partners.length}`);
  console.log('');

  // Build sitemap XML
  console.log('Building sitemap XML...');
  let sitemap = '<?xml version="1.0" encoding="UTF-8"?>\n';
  sitemap += '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">\n';

  // Add static routes
  staticRoutes.forEach(route => {
    sitemap += '  <url>\n';
    sitemap += `    <loc>${SITE_URL}${route.path}</loc>\n`;
    sitemap += `    <changefreq>${route.changefreq}</changefreq>\n`;
    sitemap += `    <priority>${route.priority}</priority>\n`;
    sitemap += '  </url>\n';
  });

  // Add story routes
  stories.forEach(story => {
    sitemap += '  <url>\n';
    sitemap += `    <loc>${SITE_URL}/stories/${story.slug || story.id}</loc>\n`;
    sitemap += `    <lastmod>${formatDate(story.published_at)}</lastmod>\n`;
    sitemap += `    <changefreq>monthly</changefreq>\n`;
    sitemap += `    <priority>0.8</priority>\n`;
    sitemap += '  </url>\n';
  });

  // Add athlete routes
  athletes.forEach(athlete => {
    sitemap += '  <url>\n';
    sitemap += `    <loc>${SITE_URL}/athletes/${athlete.id}</loc>\n`;
    if (athlete.updated_at) {
      sitemap += `    <lastmod>${formatDate(athlete.updated_at)}</lastmod>\n`;
    }
    sitemap += `    <changefreq>monthly</changefreq>\n`;
    sitemap += `    <priority>0.7</priority>\n`;
    sitemap += '  </url>\n';
  });

  // Add women running routes
  womenRunning.forEach(entry => {
    sitemap += '  <url>\n';
    sitemap += `    <loc>${SITE_URL}/women-stories/${entry.id}</loc>\n`;
    sitemap += `    <lastmod>${formatDate(entry.created_at)}</lastmod>\n`;
    sitemap += `    <changefreq>monthly</changefreq>\n`;
    sitemap += `    <priority>0.7</priority>\n`;
    sitemap += '  </url>\n';
  });

  // Add race event routes
  raceEvents.forEach(event => {
    sitemap += '  <url>\n';
    sitemap += `    <loc>${SITE_URL}/races/events/${event.id}</loc>\n`;
    sitemap += `    <lastmod>${formatDate(event.event_date)}</lastmod>\n`;
    sitemap += `    <changefreq>weekly</changefreq>\n`;
    sitemap += `    <priority>0.8</priority>\n`;
    sitemap += '  </url>\n';
  });

  // Add race result routes
  raceResults.forEach(result => {
    sitemap += '  <url>\n';
    sitemap += `    <loc>${SITE_URL}/race-results/${result.id}</loc>\n`;
    sitemap += `    <lastmod>${formatDate(result.race_date)}</lastmod>\n`;
    sitemap += `    <changefreq>monthly</changefreq>\n`;
    sitemap += `    <priority>0.6</priority>\n`;
    sitemap += '  </url>\n';
  });

  sitemap += '</urlset>';

  // Write sitemap to public folder
  const publicDir = path.join(__dirname, '..', 'public');
  if (!fs.existsSync(publicDir)) {
    fs.mkdirSync(publicDir, { recursive: true });
  }

  const sitemapPath = path.join(publicDir, 'sitemap.xml');
  fs.writeFileSync(sitemapPath, sitemap);

  const totalUrls = staticRoutes.length + stories.length + athletes.length + 
                    womenRunning.length + raceEvents.length + raceResults.length;

  console.log('✅ Sitemap generated successfully!');
  console.log(`📄 Location: ${sitemapPath}`);
  console.log(`📊 Total URLs: ${totalUrls}`);
  console.log('');
  console.log('Next steps:');
  console.log('1. Deploy your site with the generated sitemap.xml');
  console.log('2. Submit to Google Search Console: https://search.google.com/search-console');
  console.log(`3. Sitemap URL: ${SITE_URL}/sitemap.xml`);
}

generateSitemap().catch(error => {
  console.error('❌ Error generating sitemap:', error);
  process.exit(1);
});