export interface RaceEvent {
  id: number;
  title: string;
  event_date: string;
  event_date_formatted: string;
  location: string;
  start_time: string;
  start_time_formatted: string;
  expected_runners: string | null;
  distance_options: string;
  image: string | null;
  image_thumbnail: string | null;
  description: string | null;
  registration_link: string | null;
  is_featured: boolean;
  is_active: boolean;
  created_by: number;
  creator: {
    id: number;
    name: string;
    email: string;
  } | null;
  created_at: string;
  created_at_formatted: string;
  updated_at: string;
  updated_at_formatted: string;
  is_upcoming: boolean;
  days_until_event: number;
}

export interface RaceEventsResponse {
  success: boolean;
  data: {
    events: RaceEvent[];
    pagination: {
      current_page: number;
      last_page: number;
      per_page: number;
      total: number;
      from: number;
      to: number;
    };
  };
}

export interface RaceEventResponse {
  success: boolean;
  data: RaceEvent;
}

export interface FeaturedEventsResponse {
  success: boolean;
  data: RaceEvent[];
}

export interface UpcomingEventsResponse {
  success: boolean;
  data: RaceEvent[];
}