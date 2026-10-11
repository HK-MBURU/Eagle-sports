export interface Video {
  id: number;
  youtube_url: string;
  youtube_embed_url: string | null;
  youtube_thumbnail: string | null;
  title: string;
  description: string | null;
  category: string;
  category_name: string;
  is_featured: boolean;
  added_by: number;
  creator: {
    id: number;
    name: string;
    email: string;
  } | null;
  created_at: string;
  created_at_formatted: string;
  updated_at: string;
  updated_at_formatted: string;
}

export interface VideosResponse {
  success: boolean;
  data: {
    videos: Video[];
    pagination: {
      current_page: number;
      last_page: number;
      per_page: number;
      total: number;
      from: number;
      to: number;
    };
    categories: Record<string, string>;
  };
}

export interface VideoResponse {
  success: boolean;
  data: Video;
}

export interface FeaturedVideosResponse {
  success: boolean;
  data: Video[];
}

export interface VideoCategoriesResponse {
  success: boolean;
  data: Record<string, string>;
}

export interface CategoryVideosResponse {
  success: boolean;
  data: {
    videos: Video[];
    category: string;
    category_name: string;
  };
}

export interface SearchVideosResponse {
  success: boolean;
  data: {
    videos: Video[];
    search_term: string;
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