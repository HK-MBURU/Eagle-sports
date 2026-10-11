// src/types/story.ts


export interface Story {
  id: number;
  title: string;
  slug: string;
  excerpt: string;
  content: string;
  image_url: string;
  image_attribution?: string | null;
  category: string;
  is_featured: boolean;
  author_name: string;
  read_time: number;
  published_date: string;
  formatted_date: string;
  likes_count: number;
  is_liked: boolean;

  // SEO fields
  meta_title?: string;
  meta_description?: string;
  meta_keywords?: string;
  og_image_url?: string;

  // Media fields
  youtube_url?: string | null;
  youtube_embed_url?: string | null;
  youtube_video_id?: string | null;

  // Social media hashtags
  hashtags?: string | null;
  hashtags_array?: string[];
  formatted_hashtags?: string;

  // Comments
  comments_count?: number;

  // Timestamps (for full story details)
  created_at?: string;
  updated_at?: string;

  // Add this new property for meta tags
  meta_tags?: {
    title: string;
    description: string;
    image: string;
    url: string;
    type: string;
    published_time: string;
    modified_time: string;
    author: string;
    section: string;
    keywords?: string;
  };
}

export interface StoriesResponse {
  success: boolean;
  data: Story[];
  meta: {
    current_page: number;
    last_page: number;
    per_page: number;
    total: number;
    from?: number;
    to?: number;
  };
}

export interface SingleStoryResponse {
  success: boolean;
  data: Story;
}

export interface CategoryWithCount {
  name: string;
  count: number;
}

export interface CategoriesResponse {
  success: boolean;
  data: CategoryWithCount[];
}

export interface StoryStats {
  total_stories: number;
  total_likes: number;
  featured_stories: number;
  stories_with_video: number;
  categories: Record<string, number>;
  latest_story_date: string;
}

export interface StatsResponse {
  success: boolean;
  data: StoryStats;
}

export interface SEOData {
  story_id?: number;
  story_slug?: string;
  meta: {
    title: string;
    description: string;
    keywords?: string;
    og_title: string;
    og_description: string;
    og_image: string;
    og_type: string;
    article_published_time: string;
    article_modified_time: string;
    article_author: string;
    article_section: string;
    canonical_url: string;
    // Social media hashtags
    hashtags?: string | null;
    hashtags_array?: string[];
    formatted_hashtags?: string;
  };
}

export interface SEOResponse {
  success: boolean;
  data: SEOData;
}

// Helper type for social media sharing
export interface SocialShareData {
  title: string;
  description: string;
  url: string;
  hashtags: string;
  image?: string;
}

// ============================================
// COMMENT TYPES
// ============================================

export interface Comment {
  id: number;
  author: {
    name: string;
    avatar?: string;
    initials: string;
  };
  content: string;
  timestamp: string;
  likes: number;
  is_liked: boolean;
  is_author: boolean;
  is_edited: boolean;
  edited_at?: string;
  created_at: string;
  replies?: Comment[];
}

export interface CommentsResponse {
  success: boolean;
  data: Comment[];
  meta: {
    total: number;
    story_id: number;
  };
}

export interface SingleCommentResponse {
  success: boolean;
  message: string;
  data: Comment;
}

export interface CommentStatsResponse {
  success: boolean;
  data: {
    total_comments: number;
    parent_comments: number;
    replies: number;
    story_id: number;
  };
}

export interface CommentLikeResponse {
  success: boolean;
  message: string;
  data: {
    is_liked: boolean;
    likes_count: number;
    comment_id: number;
  };
}

export interface CommentReportResponse {
  success: boolean;
  message: string;
}

export interface CommentActionResponse {
  success: boolean;
  message: string;
  data?: Comment;
}

// Comment report reasons
export type CommentReportReason =
  | 'spam'
  | 'harassment'
  | 'inappropriate'
  | 'misinformation'
  | 'other';

export interface CommentReportPayload {
  reason: CommentReportReason;
  description?: string;
}

export interface ShareData {
  share_url: string;
  title: string;
  description: string;
  image: string;
  hashtags: string;
  hashtags_array: string[];
  social_links: {
    facebook: string;
    twitter: string;
    linkedin: string;
    whatsapp: string;
    email: string;
  };
}

export interface ShareDataResponse {
  success: boolean;
  data: ShareData;
}
