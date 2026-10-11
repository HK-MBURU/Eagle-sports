import { useEffect } from 'react';
import { Helmet } from 'react-helmet-async';
import type { Story } from '@/types/story';

interface StorySEOProps {
  story: Story;
}

const StorySEO = ({ story }: StorySEOProps) => {
  const title = story.meta_title || story.title;
  const description = story.meta_description || story.excerpt;
  const image = story.og_image_url || story.image_url;
  const url = window.location.href;
  const canonicalUrl = `${window.location.origin}/stories/${story.slug}`;

  // Update meta tags dynamically
  useEffect(() => {
    // Update meta tags
    const updateMetaTag = (name: string, content: string) => {
      let tag = document.querySelector(`meta[name="${name}"]`) as HTMLMetaElement;
      if (!tag) {
        tag = document.createElement('meta');
        tag.name = name;
        document.head.appendChild(tag);
      }
      tag.content = content;
    };

    const updateOGTag = (property: string, content: string) => {
      let tag = document.querySelector(`meta[property="${property}"]`) as HTMLMetaElement;
      if (!tag) {
        tag = document.createElement('meta');
        tag.setAttribute('property', property);
        document.head.appendChild(tag);
      }
      tag.content = content;
    };

    // Update basic meta tags
    updateMetaTag('description', description);
    updateMetaTag('keywords', story.meta_keywords || '');

    // Update OG tags
    updateOGTag('og:title', title);
    updateOGTag('og:description', description);
    updateOGTag('og:image', image);
    updateOGTag('og:url', url);
    updateOGTag('og:type', 'article');

    // Update Twitter tags
    updateOGTag('twitter:title', title);
    updateOGTag('twitter:description', description);
    updateOGTag('twitter:image', image);

    // Update canonical URL
    let canonical = document.querySelector('link[rel="canonical"]') as HTMLLinkElement;
    if (!canonical) {
      canonical = document.createElement('link');
      canonical.rel = 'canonical';
      document.head.appendChild(canonical);
    }
    canonical.href = canonicalUrl;

  }, [title, description, image, url, canonicalUrl, story]);

  return (
    <Helmet>
      <title>{title}</title>
      <meta name="description" content={description} />
      <meta name="keywords" content={story.meta_keywords} />
      
      {/* Open Graph */}
      <meta property="og:title" content={title} />
      <meta property="og:description" content={description} />
      <meta property="og:image" content={image} />
      <meta property="og:url" content={url} />
      <meta property="og:type" content="article" />
      <meta property="og:site_name" content="Beyond the Finish Line" />
      
      {/* Article Specific OG Tags */}
      <meta property="article:published_time" content={story.published_date} />
      <meta property="article:modified_time" content={story.updated_at} />
      <meta property="article:author" content={story.author_name} />
      <meta property="article:section" content={story.category} />
      
      {/* Twitter */}
      <meta name="twitter:card" content="summary_large_image" />
      <meta name="twitter:title" content={title} />
      <meta name="twitter:description" content={description} />
      <meta name="twitter:image" content={image} />
      <meta name="twitter:creator" content="@beyondfinishline" />
      
      {/* Canonical URL */}
      <link rel="canonical" href={canonicalUrl} />

      {/* JSON-LD Structured Data */}
      <script type="application/ld+json">
        {JSON.stringify({
          "@context": "https://schema.org",
          "@type": "Article",
          "headline": title,
          "description": description,
          "image": image,
          "author": {
            "@type": "Person",
            "name": story.author_name
          },
          "publisher": {
            "@type": "Organization",
            "name": "Beyond the Finish Line",
            "logo": {
              "@type": "ImageObject",
              "url": "https://beyondfinishline.com/logo.png"
            }
          },
          "datePublished": story.published_date,
          "dateModified": story.updated_at,
          "mainEntityOfPage": {
            "@type": "WebPage",
            "@id": canonicalUrl
          },
          "articleSection": story.category
        })}
      </script>
    </Helmet>
  );
};

export default StorySEO;