// src/types/raceResult.ts

export interface RaceResult {
  id: number;
  title: string;
  category: string;
  file_path: string;
  file_name: string;
  file_type: string;
  file_size: number;
  uploaded_by: number;
  description: string | null;
  race_date: string | null;
  location: string | null;
  is_featured: boolean;
  is_published: boolean;
  views_count: number;
  downloads_count: number;
  created_at: string;
  updated_at: string;
  
  // Computed attributes
  file_url: string;
  full_file_url: string;
  formatted_file_size: string;
  formatted_date: string | null;
  
  // Relationships
  uploader?: {
    id: number;
    name: string;
    email?: string;
  };
}

export interface RaceResultsResponse {
  success: boolean;
  data: RaceResult[];
  meta: {
    current_page: number;
    last_page: number;
    per_page: number;
    total: number;
    from: number | null;
    to: number | null;
  };
}

export interface SingleResultResponse {
  success: boolean;
  data: RaceResult;
}

export interface CategoryResponse {
  success: boolean;
  data: {
    category: string;
    count: number;
  }[];
}

export interface StatsResponse {
  success: boolean;
  data: {
    total: number;
    featured: number;
    total_views: number;
    total_downloads: number;
    by_category: {
      category: string;
      count: number;
    }[];
  };
}

export interface DownloadLinkResponse {
  success: boolean;
  data: {
    download_url: string;
    file_name: string;
    file_size: string;
  };
}