// src/utils/performance.ts

// Lazy load images with intersection observer
export const setupLazyLoading = () => {
  if (typeof window === 'undefined') return;

  const lazyImages = Array.from(document.querySelectorAll('img[data-src]'));
  
  if ('IntersectionObserver' in window) {
    const imageObserver = new IntersectionObserver((entries) => {
      entries.forEach((entry) => {
        if (entry.isIntersecting) {
          const img = entry.target as HTMLImageElement;
          img.src = img.dataset.src || '';
          img.removeAttribute('data-src');
          imageObserver.unobserve(img);
        }
      });
    });

    lazyImages.forEach((img) => imageObserver.observe(img));
  } else {
    // Fallback for older browsers
    lazyImages.forEach((img) => {
      const image = img as HTMLImageElement;
      image.src = image.dataset.src || '';
    });
  }
};

// Preload critical resources
export const preloadCriticalResources = () => {
  if (typeof window === 'undefined') return;

  const criticalUrls = [
    // Add URLs of critical images, fonts, etc.
  ];

  criticalUrls.forEach(url => {
    const link = document.createElement('link');
    link.rel = 'preload';
    link.href = url;
    link.as = url.includes('.css') ? 'style' : 
              url.includes('.woff') ? 'font' : 'image';
    document.head.appendChild(link);
  });
};