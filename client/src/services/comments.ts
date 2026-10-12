// src/services/comments.ts

import type { 
  CommentsResponse, 
  SingleCommentResponse, 
  CommentStatsResponse,
  CommentLikeResponse,
  CommentReportResponse,
  CommentActionResponse,
  CommentReportPayload
} from '@/types/story';

const API_BASE_URL = import.meta.env.VITE_API_URL || 'https://eaglesports.co.ke/api';

const getAuthToken = () => {
  return localStorage.getItem('auth_token');
};

const getAuthHeaders = () => {
  const token = getAuthToken();
  return {
    'Content-Type': 'application/json',
    'Accept': 'application/json',
    ...(token && { 'Authorization': `Bearer ${token}` }),
  };
};

const api = {
  get: async (url: string) => {
    const response = await fetch(`${API_BASE_URL}${url}`, {
      headers: getAuthHeaders(),
    });
    if (!response.ok) {
      const error = await response.json().catch(() => ({ message: 'Request failed' }));
      throw new Error(error.message || 'Request failed');
    }
    return { data: await response.json() };
  },
  
  post: async (url: string, data?: any) => {
    const response = await fetch(`${API_BASE_URL}${url}`, {
      method: 'POST',
      headers: getAuthHeaders(),
      body: JSON.stringify(data),
    });
    if (!response.ok) {
      const error = await response.json().catch(() => ({ message: 'Request failed' }));
      throw new Error(error.message || 'Request failed');
    }
    return { data: await response.json() };
  },
  
  patch: async (url: string, data?: any) => {
    const response = await fetch(`${API_BASE_URL}${url}`, {
      method: 'PATCH',
      headers: getAuthHeaders(),
      body: JSON.stringify(data),
    });
    if (!response.ok) {
      const error = await response.json().catch(() => ({ message: 'Request failed' }));
      throw new Error(error.message || 'Request failed');
    }
    return { data: await response.json() };
  },
  
  delete: async (url: string) => {
    const response = await fetch(`${API_BASE_URL}${url}`, {
      method: 'DELETE',
      headers: getAuthHeaders(),
    });
    if (!response.ok) {
      const error = await response.json().catch(() => ({ message: 'Request failed' }));
      throw new Error(error.message || 'Request failed');
    }
    return { data: await response.json() };
  },
};

export const commentsAPI = {
  /**
   * Get all comments for a story
   * @param storyId - The ID of the story
   */
  getComments: (storyId: number): Promise<{ data: CommentsResponse }> => 
    api.get(`/stories/${storyId}/comments`),
  
  /**
   * Get comment statistics for a story
   * @param storyId - The ID of the story
   */
  getStats: (storyId: number): Promise<{ data: CommentStatsResponse }> => 
    api.get(`/stories/${storyId}/comments/stats`),
  
  /**
   * Post a new comment or reply
   * @param storyId - The ID of the story
   * @param content - The comment content
   * @param parentId - Optional parent comment ID for replies
   */
  postComment: (
    storyId: number, 
    content: string, 
    parentId?: number
  ): Promise<{ data: SingleCommentResponse }> =>
    api.post(`/stories/${storyId}/comments`, { 
      content, 
      parent_id: parentId 
    }),
  
  /**
   * Update an existing comment
   * @param commentId - The ID of the comment to update
   * @param content - The new comment content
   */
  updateComment: (
    commentId: number, 
    content: string
  ): Promise<{ data: SingleCommentResponse }> =>
    api.patch(`/comments/${commentId}`, { content }),
  
  /**
   * Delete a comment
   * @param commentId - The ID of the comment to delete
   */
  deleteComment: (commentId: number): Promise<{ data: CommentActionResponse }> =>
    api.delete(`/comments/${commentId}`),
  
  /**
   * Toggle like on a comment
   * @param commentId - The ID of the comment to like/unlike
   */
  toggleLike: (commentId: number): Promise<{ data: CommentLikeResponse }> =>
    api.post(`/comments/${commentId}/like`),
  
  /**
   * Report a comment
   * @param commentId - The ID of the comment to report
   * @param payload - Report reason and optional description
   */
  reportComment: (
    commentId: number, 
    payload: CommentReportPayload
  ): Promise<{ data: CommentReportResponse }> =>
    api.post(`/comments/${commentId}/report`, payload),
};