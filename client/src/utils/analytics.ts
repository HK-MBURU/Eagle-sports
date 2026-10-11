// src/utils/analytics.ts

declare global {
  interface Window {
    gtag: any;
  }
}

export const GA_MEASUREMENT_ID = 'G-XXXXXXXXXX';

// Page view tracking
export const pageview = (url: string) => {
  if (typeof window.gtag !== 'undefined') {
    window.gtag('config', GA_MEASUREMENT_ID, {
      page_path: url,
    });
  }
};

// Event tracking
export const event = ({
  action,
  category,
  label,
  value,
}: {
  action: string;
  category: string;
  label?: string;
  value?: number;
}) => {
  if (typeof window.gtag !== 'undefined') {
    window.gtag('event', action, {
      event_category: category,
      event_label: label,
      value: value,
    });
  }
};

// Custom events
export const trackStoryView = (storyTitle: string, storyId: number) => {
  event({
    action: 'view_story',
    category: 'Stories',
    label: storyTitle,
    value: storyId,
  });
};

export const trackStoryLike = (storyTitle: string) => {
  event({
    action: 'like_story',
    category: 'Engagement',
    label: storyTitle,
  });
};

export const trackSearch = (searchTerm: string) => {
  event({
    action: 'search',
    category: 'Search',
    label: searchTerm,
  });
};

export const trackShare = (platform: string, storyTitle: string) => {
  event({
    action: 'share',
    category: 'Social',
    label: `${platform} - ${storyTitle}`,
  });
};

export const trackCategoryView = (category: string) => {
  event({
    action: 'view_category',
    category: 'Navigation',
    label: category,
  });
};

export const trackVideoPlay = (videoUrl: string) => {
  event({
    action: 'play_video',
    category: 'Media',
    label: videoUrl,
  });
};