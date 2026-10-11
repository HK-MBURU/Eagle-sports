export interface WomenRunningEntry {
  id: number;
  name: string;
  title: string;
  description: string;
  category: 'mentorship' | 'coaching' | 'advocacy' | 'featured_story';
  category_label: string;
  icon: string | null;
  image: string | null;
  image_path: string | null;
  featured: boolean;
  status: boolean;
  story_content: string | null;
  video_url: string | null;
  order: number;
  created_at: string;
  updated_at: string;
  created_at_human: string;
  updated_at_human: string;
}

export interface ApiResponse<T> {
  success: boolean;
  message: string;
  data: T;
}

export interface PaginatedResponse<T> {
  data: T[];
  current_page: number;
  last_page: number;
  per_page: number;
  total: number;
  from: number;
  to: number;
}