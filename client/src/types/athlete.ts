export interface Athlete {
  id: number;
  name: string;
  title: string;
  description: string;
  bio: string;
  image_url: string;
  badge: string | null;
  badge_color: string;
  is_featured: boolean;
  display_order: number;
}

export interface AthletesResponse {
  success: boolean;
  data: Athlete[];
  meta?: {
    current_page: number;
    last_page: number;
    per_page: number;
    total: number;
  };
}

export interface AthleteStatsResponse {
  success: boolean;
  data: {
    total: number;
    active: number;
    featured: number;
  };
}