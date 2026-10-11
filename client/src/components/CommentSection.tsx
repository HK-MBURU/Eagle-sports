// src/components/CommentSection.tsx

import { useState, useEffect } from "react";
import { MessageCircle, Heart, Send, MoreVertical, Trash2, Edit2, Flag, Loader2 } from "lucide-react";
import { Button } from "@/components/ui/button";
import { useAuth } from "@/contexts/AuthContext";
import { useNavigate, useLocation } from "react-router-dom";
import { useToast } from "@/hooks/use-toast";
import { commentsAPI } from "@/services/comments";
import LoginModal from "@/components/LoginModal";
import type { Comment } from "@/types/story";

interface CommentSectionProps {
  storyId: number;
  commentsCount?: number;
  onCommentAdded?: (comment: string) => void;
  onCommentDeleted: () => void;
}

const CommentSection = ({ storyId, commentsCount, onCommentAdded, onCommentDeleted }: CommentSectionProps) => {
  const [comments, setComments] = useState<Comment[]>([]);
  const [newComment, setNewComment] = useState("");
  const [replyingTo, setReplyingTo] = useState<number | null>(null);
  const [replyText, setReplyText] = useState("");
  const [showActions, setShowActions] = useState<number | null>(null);
  const [loading, setLoading] = useState(true);
  const [submitting, setSubmitting] = useState(false);
  const [showLoginModal, setShowLoginModal] = useState(false);
  const [pendingComment, setPendingComment] = useState<{ text: string; parentId?: number } | null>(null);
  
  const { isAuthenticated } = useAuth();
  const navigate = useNavigate();
  const location = useLocation();
  const { toast } = useToast();

  // Fetch comments on mount
  useEffect(() => {
    if (storyId) {
      fetchComments();
    }
  }, [storyId]);

  const fetchComments = async () => {
    try {
      setLoading(true);
      const response = await commentsAPI.getComments(storyId);
      
      if (response.data.success) {
        setComments(response.data.data);
      }
    } catch (err) {
      console.error('Error fetching comments:', err);
      toast({
        title: "Error",
        description: "Failed to load comments",
        variant: "destructive",
      });
    } finally {
      setLoading(false);
    }
  };

  const handleSubmitComment = async () => {
    if (!newComment.trim()) return;

    // If not authenticated, show login modal and save comment
    if (!isAuthenticated) {
      setPendingComment({ text: newComment });
      setShowLoginModal(true);
      return;
    }

    try {
      setSubmitting(true);
      const response = await commentsAPI.postComment(storyId, newComment);
      
      if (response.data.success) {
        setComments([response.data.data, ...comments]);
        setNewComment("");
        
        toast({
          title: "Comment posted!",
          description: response.data.message,
        });

        if (onCommentAdded) {
          onCommentAdded(newComment);
        }
      }
    } catch (err: any) {
      toast({
        title: "Error",
        description: err.response?.data?.message || "Failed to post comment",
        variant: "destructive",
      });
    } finally {
      setSubmitting(false);
    }
  };

  const handleSubmitReply = async (parentId: number) => {
    if (!replyText.trim()) return;

    // If not authenticated, show login modal and save reply
    if (!isAuthenticated) {
      setPendingComment({ text: replyText, parentId });
      setShowLoginModal(true);
      return;
    }

    try {
      setSubmitting(true);
      const response = await commentsAPI.postComment(storyId, replyText, parentId);
      
      if (response.data.success) {
        setComments(comments.map(comment => {
          if (comment.id === parentId) {
            return {
              ...comment,
              replies: [...(comment.replies || []), response.data.data],
            };
          }
          return comment;
        }));

        setReplyText("");
        setReplyingTo(null);
        
        toast({
          title: "Reply posted!",
          description: response.data.message,
        });
      }
    } catch (err: any) {
      toast({
        title: "Error",
        description: err.response?.data?.message || "Failed to post reply",
        variant: "destructive",
      });
    } finally {
      setSubmitting(false);
    }
  };

  const handleLikeComment = async (commentId: number) => {
    if (!isAuthenticated) {
      setShowLoginModal(true);
      return;
    }

    try {
      const response = await commentsAPI.toggleLike(commentId);
      
      if (response.data.success) {
        const updateComment = (comment: Comment): Comment => {
          if (comment.id === commentId) {
            return {
              ...comment,
              is_liked: response.data.data.is_liked,
              likes: response.data.data.likes_count,
            };
          }
          if (comment.replies) {
            return {
              ...comment,
              replies: comment.replies.map(updateComment),
            };
          }
          return comment;
        };

        setComments(comments.map(updateComment));
      }
    } catch (err: any) {
      toast({
        title: "Error",
        description: err.response?.data?.message || "Failed to like comment",
        variant: "destructive",
      });
    }
  };

  const handleDeleteComment = async (commentId: number) => {
    try {
      const response = await commentsAPI.deleteComment(commentId);
      
      if (response.data.success) {
        setComments(comments.filter(comment => comment.id !== commentId));
        setShowActions(null);
        toast({
          title: "Comment deleted",
          description: response.data.message,
        });
      }
    } catch (err: any) {
      toast({
        title: "Error",
        description: err.response?.data?.message || "Failed to delete comment",
        variant: "destructive",
      });
    }
  };

  const handleReportComment = async (commentId: number) => {
    try {
      const response = await commentsAPI.reportComment(commentId, {
        reason: 'inappropriate',
        description: 'Reported by user',
      });
      
      if (response.data.success) {
        setShowActions(null);
        toast({
          title: "Report submitted",
          description: response.data.message,
        });
      }
    } catch (err: any) {
      toast({
        title: "Error",
        description: err.response?.data?.message || "Failed to report comment",
        variant: "destructive",
      });
    }
  };

  // Handle successful login - post the pending comment
  const handleLoginSuccess = async () => {
    if (!pendingComment) return;

    try {
      setSubmitting(true);
      
      if (pendingComment.parentId) {
        // It's a reply
        const response = await commentsAPI.postComment(storyId, pendingComment.text, pendingComment.parentId);
        
        if (response.data.success) {
          setComments(comments.map(comment => {
            if (comment.id === pendingComment.parentId) {
              return {
                ...comment,
                replies: [...(comment.replies || []), response.data.data],
              };
            }
            return comment;
          }));

          setReplyText("");
          setReplyingTo(null);
          
          toast({
            title: "Reply posted!",
            description: "Your reply has been posted successfully.",
          });
        }
      } else {
        // It's a main comment
        const response = await commentsAPI.postComment(storyId, pendingComment.text);
        
        if (response.data.success) {
          setComments([response.data.data, ...comments]);
          setNewComment("");
          
          toast({
            title: "Comment posted!",
            description: "Your comment has been posted successfully.",
          });

          if (onCommentAdded) {
            onCommentAdded(pendingComment.text);
          }
        }
      }
      
      // Clear pending comment
      setPendingComment(null);
    } catch (err: any) {
      toast({
        title: "Error",
        description: err.response?.data?.message || "Failed to post comment",
        variant: "destructive",
      });
    } finally {
      setSubmitting(false);
    }
  };

  const CommentItem = ({ comment, isReply = false }: { comment: Comment; isReply?: boolean }) => (
    <div className={`${isReply ? 'ml-12' : ''}`}>
      <div className="flex items-start gap-3">
        {/* Avatar */}
        <div className="w-10 h-10 rounded-full bg-accent-orange flex items-center justify-center font-semibold text-white flex-shrink-0">
          {comment.author.initials}
        </div>

        {/* Comment Content */}
        <div className="flex-1 min-w-0">
          <div className="bg-muted/50 rounded-lg p-4">
            <div className="flex items-center justify-between mb-2">
              <div className="flex items-center gap-2">
                <span className="font-semibold text-sm">{comment.author.name}</span>
                {comment.is_author && (
                  <span className="text-xs bg-primary/10 text-primary px-2 py-0.5 rounded-full">
                    You
                  </span>
                )}
                {comment.is_edited && (
                  <span className="text-xs text-muted-foreground italic">
                    (edited)
                  </span>
                )}
              </div>
              
              {/* Actions Menu */}
              {isAuthenticated && (
                <div className="relative">
                  <button
                    onClick={() => setShowActions(showActions === comment.id ? null : comment.id)}
                    className="p-1 hover:bg-background rounded transition-colors"
                  >
                    <MoreVertical className="w-4 h-4 text-muted-foreground" />
                  </button>
                  
                  {showActions === comment.id && (
                    <div className="absolute right-0 mt-1 w-40 bg-card border rounded-lg shadow-lg z-10">
                      {comment.is_author ? (
                        <>
                          <button
                            onClick={() => {
                              setShowActions(null);
                              toast({ title: "Edit feature coming soon!" });
                            }}
                            className="w-full flex items-center gap-2 px-3 py-2 text-sm hover:bg-accent"
                          >
                            <Edit2 className="w-3 h-3" />
                            Edit
                          </button>
                          <button
                            onClick={() => handleDeleteComment(comment.id)}
                            className="w-full flex items-center gap-2 px-3 py-2 text-sm hover:bg-accent text-red-600"
                          >
                            <Trash2 className="w-3 h-3" />
                            Delete
                          </button>
                        </>
                      ) : (
                        <button
                          onClick={() => handleReportComment(comment.id)}
                          className="w-full flex items-center gap-2 px-3 py-2 text-sm hover:bg-accent"
                        >
                          <Flag className="w-3 h-3" />
                          Report
                        </button>
                      )}
                    </div>
                  )}
                </div>
              )}
            </div>
            
            <p className="text-sm text-foreground leading-relaxed break-words">
              {comment.content}
            </p>
          </div>

          {/* Comment Actions */}
          <div className="flex items-center gap-4 mt-2 ml-2">
            <span className="text-xs text-muted-foreground">{comment.timestamp}</span>
            
            <button
              onClick={() => handleLikeComment(comment.id)}
              className={`flex items-center gap-1 text-xs ${
                comment.is_liked ? 'text-red-600' : 'text-muted-foreground'
              } hover:text-red-600 transition-colors`}
            >
              <Heart className={`w-3 h-3 ${comment.is_liked ? 'fill-current' : ''}`} />
              <span>{comment.likes}</span>
            </button>

            {!isReply && (
              <button
                onClick={() => setReplyingTo(replyingTo === comment.id ? null : comment.id)}
                className="text-xs text-muted-foreground hover:text-primary transition-colors"
              >
                Reply
              </button>
            )}
          </div>

          {/* Reply Input */}
          {replyingTo === comment.id && (
            <div className="mt-3 ml-2">
              <div className="flex gap-2">
                <input
                  type="text"
                  value={replyText}
                  onChange={(e) => setReplyText(e.target.value)}
                  placeholder="Write a reply..."
                  className="flex-1 px-3 py-2 text-sm border rounded-lg focus:outline-none focus:ring-2 focus:ring-primary"
                  onKeyPress={(e) => {
                    if (e.key === 'Enter' && !submitting) {
                      handleSubmitReply(comment.id);
                    }
                  }}
                  disabled={submitting}
                />
                <Button
                  size="sm"
                  onClick={() => handleSubmitReply(comment.id)}
                  className="bg-[#D43028] hover:bg-[#D43028]/90"
                  disabled={submitting}
                >
                  {submitting ? (
                    <Loader2 className="w-4 h-4 animate-spin" />
                  ) : (
                    <Send className="w-4 h-4" />
                  )}
                </Button>
              </div>
            </div>
          )}
        </div>
      </div>

      {/* Replies */}
      {comment.replies && comment.replies.length > 0 && (
        <div className="mt-4 space-y-4">
          {comment.replies.map((reply) => (
            <CommentItem key={reply.id} comment={reply} isReply />
          ))}
        </div>
      )}
    </div>
  );

  if (loading) {
    return (
      <div className="mt-12 pt-8 border-t">
        <div className="flex items-center justify-center py-12">
          <Loader2 className="w-8 h-8 animate-spin text-primary" />
        </div>
      </div>
    );
  }

  return (
    <>
      <div className="mt-12 pt-8 border-t">
        {/* Header */}
        <div className="flex items-center gap-2 mb-6">
          <MessageCircle className="w-6 h-6 text-primary" />
          <h3 className="text-2xl font-bold">
            Comments ({commentsCount || comments.length})
          </h3>
        </div>

        {/* New Comment Input */}
        <div className="mb-8">
          <textarea
            value={newComment}
            onChange={(e) => setNewComment(e.target.value)}
            placeholder="Share your thoughts..."
            className="w-full p-4 border rounded-lg resize-none focus:outline-none focus:ring-2 focus:ring-primary"
            rows={4}
            disabled={submitting}
          />
          <div className="flex justify-between items-center mt-3">
            <p className="text-xs text-muted-foreground">
              {!isAuthenticated && "You'll be asked to log in before posting"}
            </p>
            <Button
              onClick={handleSubmitComment}
              disabled={!newComment.trim() || submitting}
              className="bg-[#D43028] hover:bg-[#D43028]/90"
            >
              {submitting ? (
                <>
                  <Loader2 className="w-4 h-4 mr-2 animate-spin" />
                  Posting...
                </>
              ) : (
                <>
                  <Send className="w-4 h-4 mr-2" />
                  Post Comment
                </>
              )}
            </Button>
          </div>
        </div>

        {/* Comments List */}
        <div className="space-y-6">
          {comments.length > 0 ? (
            comments.map((comment) => (
              <CommentItem key={comment.id} comment={comment} />
            ))
          ) : (
            <div className="text-center py-12">
              <MessageCircle className="w-12 h-12 text-muted-foreground mx-auto mb-3" />
              <p className="text-muted-foreground">No comments yet. Be the first to share your thoughts!</p>
            </div>
          )}
        </div>
      </div>

      {/* Login Modal */}
      <LoginModal
        isOpen={showLoginModal}
        onClose={() => {
          setShowLoginModal(false);
          setPendingComment(null);
        }}
        onSuccess={handleLoginSuccess}
        message="Sign in to post your comment"
      />
    </>
  );
};

export default CommentSection;