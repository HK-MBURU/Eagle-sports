// src/pages/races/RaceResultDetail.tsx

import { useState, useEffect } from 'react';
import { useParams, useNavigate } from 'react-router-dom';
import { raceResultsAPI } from '@/services/api';
import { RaceResult } from '@/types/raceResult';
import {
  Download,
  Eye,
  Calendar,
  MapPin,
  FileText,
  Loader2,
  Star,
  ArrowLeft,
  ExternalLink,
  File,
  Share2,
  Facebook,
  Twitter,
  Linkedin,
  Mail,
  Link2,
  Check,
  MessageCircle
} from 'lucide-react';
import { useToast } from '@/hooks/use-toast';
import PageTemplate from "@/components/PageTemplate";

const RaceResultDetail = () => {
  const { id } = useParams<{ id: string }>();
  const navigate = useNavigate();
  const [result, setResult] = useState<RaceResult | null>(null);
  const [loading, setLoading] = useState(true);
  const [showShareMenu, setShowShareMenu] = useState(false);
  const [copied, setCopied] = useState(false);
  const { toast } = useToast();

  // Fetch race result details
  useEffect(() => {
    const fetchResult = async () => {
      if (!id) return;

      try {
        setLoading(true);
        const response = await raceResultsAPI.getResult(parseInt(id));
        
        if (response.success) {
          setResult(response.data);
        }
      } catch (error) {
        console.error('Error fetching result:', error);
        toast({
          title: 'Error',
          description: 'Failed to load race result',
          variant: 'destructive',
        });
      } finally {
        setLoading(false);
      }
    };

    fetchResult();
  }, [id, toast]);

  // Get current page URL
  const getShareUrl = () => {
    return window.location.href;
  };

  // Get share text
  const getShareText = () => {
    if (!result) return '';
    return `Check out these race results: ${result.title}`;
  };

  // Copy link to clipboard
  const handleCopyLink = async () => {
    try {
      await navigator.clipboard.writeText(getShareUrl());
      setCopied(true);
      toast({
        title: 'Link Copied!',
        description: 'Race result link copied to clipboard',
      });
      
      setTimeout(() => setCopied(false), 2000);
    } catch (error) {
      toast({
        title: 'Error',
        description: 'Failed to copy link',
        variant: 'destructive',
      });
    }
  };

  // Share to Facebook
  const shareToFacebook = () => {
    const url = encodeURIComponent(getShareUrl());
    window.open(
      `https://www.facebook.com/sharer/sharer.php?u=${url}`,
      '_blank',
      'width=600,height=400'
    );
    setShowShareMenu(false);
  };

  // Share to Twitter/X
  const shareToTwitter = () => {
    const url = encodeURIComponent(getShareUrl());
    const text = encodeURIComponent(getShareText());
    window.open(
      `https://twitter.com/intent/tweet?url=${url}&text=${text}`,
      '_blank',
      'width=600,height=400'
    );
    setShowShareMenu(false);
  };

  // Share to LinkedIn
  const shareToLinkedIn = () => {
    const url = encodeURIComponent(getShareUrl());
    window.open(
      `https://www.linkedin.com/sharing/share-offsite/?url=${url}`,
      '_blank',
      'width=600,height=400'
    );
    setShowShareMenu(false);
  };

  // Share to WhatsApp
  const shareToWhatsApp = () => {
    const text = encodeURIComponent(`${getShareText()} ${getShareUrl()}`);
    window.open(
      `https://wa.me/?text=${text}`,
      '_blank'
    );
    setShowShareMenu(false);
  };

  // Share via Email
  const shareViaEmail = () => {
    if (!result) return;
    const subject = encodeURIComponent(`Race Results: ${result.title}`);
    const body = encodeURIComponent(
      `I wanted to share these race results with you:\n\n${result.title}\n${result.category}\n\nView the results here: ${getShareUrl()}`
    );
    window.location.href = `mailto:?subject=${subject}&body=${body}`;
    setShowShareMenu(false);
  };

  // Native Share API (for mobile)
  const handleNativeShare = async () => {
    if (!result) return;

    if (navigator.share) {
      try {
        await navigator.share({
          title: result.title,
          text: getShareText(),
          url: getShareUrl(),
        });
      } catch (error) {
        // User cancelled or error occurred
        console.log('Share cancelled');
      }
    } else {
      // Fallback to custom share menu
      setShowShareMenu(true);
    }
  };

  // Handle download
  const handleDownload = async () => {
    if (!result) return;

    try {
      const downloadUrl = result.full_file_url || result.file_url;
      window.open(downloadUrl, '_blank');

      toast({
        title: 'Download Started',
        description: `Downloading ${result.file_name}`,
      });
    } catch (error) {
      toast({
        title: 'Error',
        description: 'Failed to download file',
        variant: 'destructive',
      });
    }
  };

  // Check if file is image
  const isImage = (fileType: string) => {
    return ['jpg', 'jpeg', 'png', 'gif', 'webp'].includes(fileType.toLowerCase());
  };

  // Check if file is PDF
  const isPDF = (fileType: string) => {
    return fileType.toLowerCase() === 'pdf';
  };

  // Get file icon color
  const getFileIconColor = (fileType: string) => {
    const type = fileType.toLowerCase();
    if (isImage(type)) return 'text-blue-500';
    if (isPDF(type)) return 'text-red-500';
    if (['doc', 'docx'].includes(type)) return 'text-blue-600';
    if (['xls', 'xlsx'].includes(type)) return 'text-green-600';
    return 'text-gray-500';
  };

  // Close share menu when clicking outside
  useEffect(() => {
    const handleClickOutside = (event: MouseEvent) => {
      const target = event.target as HTMLElement;
      if (showShareMenu && !target.closest('.share-menu-container')) {
        setShowShareMenu(false);
      }
    };

    document.addEventListener('mousedown', handleClickOutside);
    return () => document.removeEventListener('mousedown', handleClickOutside);
  }, [showShareMenu]);

  if (loading) {
    return (
      <PageTemplate title="Loading..." description="">
        <div className="min-h-screen flex items-center justify-center">
          <Loader2 className="w-8 h-8 animate-spin text-primary" />
        </div>
      </PageTemplate>
    );
  }

  if (!result) {
    return (
      <PageTemplate title="Not Found" description="">
        <div className="container mx-auto px-4 py-12 max-w-2xl text-center">
          <h2 className="text-2xl font-bold text-gray-900 mb-4">Result Not Found</h2>
          <p className="text-gray-600 mb-6">The race result you're looking for doesn't exist.</p>
          <button
            onClick={() => navigate('/races/results')}
            className="inline-flex items-center gap-2 px-4 py-2 bg-primary text-white rounded-lg hover:bg-primary/90 transition-colors"
          >
            <ArrowLeft className="w-4 h-4" />
            Back to Results
          </button>
        </div>
      </PageTemplate>
    );
  }

  return (
    <PageTemplate >
      <div className="container mx-auto px-4 py-6 max-w-5xl">
        {/* Back Button */}
        <button
          onClick={() => navigate('/races/results')}
          className="inline-flex items-center gap-2 text-sm text-gray-600 hover:text-primary mb-6 transition-colors"
        >
          <ArrowLeft className="w-4 h-4" />
          Back to all results
        </button>

        {/* Header Section */}
        <div className="bg-white rounded-lg border border-gray-100 p-6 sm:p-8 mb-6">
          <div className="flex items-start justify-between gap-4 mb-6">
            <div className="flex-1">
              <div className="flex items-center gap-3 mb-3">
                <span className="inline-flex items-center px-3 py-1 rounded-full bg-primary/10 text-primary font-medium text-sm">
                  {result.category}
                </span>
                {result.is_featured && (
                  <span className="inline-flex items-center gap-1 px-3 py-1 rounded-full bg-yellow-100 text-yellow-800 font-medium text-sm">
                    <Star className="w-3.5 h-3.5 fill-current" />
                    Featured
                  </span>
                )}
              </div>
              
              <h1 className="text-2xl sm:text-3xl lg:text-4xl font-bold text-gray-900 mb-4">
                {result.title}
              </h1>

              {result.description && (
                <p className="text-gray-600 leading-relaxed">
                  {result.description}
                </p>
              )}
            </div>
          </div>

          {/* Meta Information */}
          <div className="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 py-4 border-t border-gray-100">
            {result.race_date && (
              <div className="flex items-center gap-3">
                <div className="p-2 bg-primary/10 rounded-lg">
                  <Calendar className="w-5 h-5 text-primary" />
                </div>
                <div>
                  <p className="text-xs text-gray-500">Race Date</p>
                  <p className="text-sm font-semibold text-gray-900">{result.formatted_date}</p>
                </div>
              </div>
            )}

            {result.location && (
              <div className="flex items-center gap-3">
                <div className="p-2 bg-primary/10 rounded-lg">
                  <MapPin className="w-5 h-5 text-primary" />
                </div>
                <div>
                  <p className="text-xs text-gray-500">Location</p>
                  <p className="text-sm font-semibold text-gray-900">{result.location}</p>
                </div>
              </div>
            )}

            <div className="flex items-center gap-3">
              <div className="p-2 bg-primary/10 rounded-lg">
                <FileText className="w-5 h-5 text-primary" />
              </div>
              <div>
                <p className="text-xs text-gray-500">File Type</p>
                <p className="text-sm font-semibold text-gray-900">{result.file_type.toUpperCase()}</p>
              </div>
            </div>

            <div className="flex items-center gap-3">
              <div className="p-2 bg-primary/10 rounded-lg">
                <Eye className="w-5 h-5 text-primary" />
              </div>
              <div>
                <p className="text-xs text-gray-500">Views</p>
                <p className="text-sm font-semibold text-gray-900">{result.views_count}</p>
              </div>
            </div>
          </div>

          {/* Action Buttons */}
          <div className="flex flex-col sm:flex-row gap-3 mt-6 pt-6 border-t border-gray-100">
            <button
              onClick={handleDownload}
              className="flex-1 sm:flex-none inline-flex items-center justify-center gap-2 px-6 py-3 bg-[#D43028] hover:bg-[#B52820] text-white font-semibold rounded-lg transition-all duration-200 shadow-md hover:shadow-lg"
            >
              <Download className="w-4 h-4" />
              Download Result
            </button>
            
            <button
              onClick={() => window.open(result.file_url, '_blank')}
              className="flex-1 sm:flex-none inline-flex items-center justify-center gap-2 px-6 py-3 border-2 border-gray-300 text-gray-700 font-semibold rounded-lg hover:bg-gray-50 transition-all duration-200"
            >
              <ExternalLink className="w-4 h-4" />
              Open in New Tab
            </button>

            {/* Share Button */}
            <div className="relative share-menu-container flex-1 sm:flex-none">
              <button
                onClick={handleNativeShare}
                className="w-full inline-flex items-center justify-center gap-2 px-6 py-3 border-2 border-primary text-primary font-semibold rounded-lg hover:bg-primary/5 transition-all duration-200"
              >
                <Share2 className="w-4 h-4" />
                Share
              </button>

              {/* Share Menu Dropdown */}
              {showShareMenu && (
                <div className="absolute right-0 mt-2 w-64 bg-white rounded-lg shadow-xl border border-gray-200 py-2 z-50 animate-fade-in">
                  <div className="px-4 py-2 border-b border-gray-100">
                    <p className="text-sm font-semibold text-gray-900">Share via</p>
                  </div>

                  {/* Copy Link */}
                  <button
                    onClick={handleCopyLink}
                    className="w-full flex items-center gap-3 px-4 py-3 hover:bg-gray-50 transition-colors"
                  >
                    {copied ? (
                      <Check className="w-5 h-5 text-green-500" />
                    ) : (
                      <Link2 className="w-5 h-5 text-gray-600" />
                    )}
                    <span className="text-sm font-medium text-gray-900">
                      {copied ? 'Link Copied!' : 'Copy Link'}
                    </span>
                  </button>

                  {/* Facebook */}
                  <button
                    onClick={shareToFacebook}
                    className="w-full flex items-center gap-3 px-4 py-3 hover:bg-gray-50 transition-colors"
                  >
                    <Facebook className="w-5 h-5 text-[#1877F2]" />
                    <span className="text-sm font-medium text-gray-900">Facebook</span>
                  </button>

                  {/* Twitter/X */}
                  <button
                    onClick={shareToTwitter}
                    className="w-full flex items-center gap-3 px-4 py-3 hover:bg-gray-50 transition-colors"
                  >
                    <Twitter className="w-5 h-5 text-[#1DA1F2]" />
                    <span className="text-sm font-medium text-gray-900">Twitter</span>
                  </button>

                  {/* LinkedIn */}
                  <button
                    onClick={shareToLinkedIn}
                    className="w-full flex items-center gap-3 px-4 py-3 hover:bg-gray-50 transition-colors"
                  >
                    <Linkedin className="w-5 h-5 text-[#0A66C2]" />
                    <span className="text-sm font-medium text-gray-900">LinkedIn</span>
                  </button>

                  {/* WhatsApp */}
                  <button
                    onClick={shareToWhatsApp}
                    className="w-full flex items-center gap-3 px-4 py-3 hover:bg-gray-50 transition-colors"
                  >
                    <MessageCircle className="w-5 h-5 text-[#25D366]" />
                    <span className="text-sm font-medium text-gray-900">WhatsApp</span>
                  </button>

                  {/* Email */}
                  <button
                    onClick={shareViaEmail}
                    className="w-full flex items-center gap-3 px-4 py-3 hover:bg-gray-50 transition-colors border-t border-gray-100"
                  >
                    <Mail className="w-5 h-5 text-gray-600" />
                    <span className="text-sm font-medium text-gray-900">Email</span>
                  </button>
                </div>
              )}
            </div>
          </div>
        </div>

        {/* File Preview Section */}
        <div className="bg-white rounded-lg border border-gray-100 overflow-hidden">
          <div className="px-6 py-4 border-b border-gray-100 bg-gray-50">
            <h2 className="text-lg font-semibold text-gray-900">Preview</h2>
            <p className="text-sm text-gray-500 mt-1">
              File size: {result.formatted_file_size}
            </p>
          </div>

          <div className="p-6">
            {isImage(result.file_type) ? (
              <div className="flex items-center justify-center bg-gray-50 rounded-lg p-4">
                <img
                  src={result.file_url}
                  alt={result.title}
                  className="max-w-full h-auto rounded-lg shadow-lg"
                />
              </div>
            ) : isPDF(result.file_type) ? (
              <div className="w-full h-[600px] sm:h-[700px] lg:h-[800px] rounded-lg overflow-hidden border border-gray-200">
                <iframe
                  src={`${result.file_url}#toolbar=0`}
                  className="w-full h-full"
                  title={result.title}
                />
              </div>
            ) : (
              <div className="text-center py-12">
                <File className={`w-16 h-16 mx-auto mb-4 ${getFileIconColor(result.file_type)}`} />
                <h3 className="text-lg font-semibold text-gray-900 mb-2">
                  Preview not available
                </h3>
                <p className="text-gray-600 mb-6 max-w-md mx-auto">
                  This file type cannot be previewed in the browser. Please download the file to view it.
                </p>
                <button
                  onClick={handleDownload}
                  className="inline-flex items-center gap-2 px-6 py-3 bg-primary text-white rounded-lg font-semibold hover:bg-primary/90 transition-colors"
                >
                  <Download className="w-4 h-4" />
                  Download to View
                </button>
              </div>
            )}
          </div>
        </div>

        {/* Additional Info */}
        <div className="mt-6 text-center text-sm text-gray-500">
          <p>Downloaded {result.downloads_count} times</p>
        </div>
      </div>
    </PageTemplate>
  );
};

export default RaceResultDetail;