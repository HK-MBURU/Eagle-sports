import { Dialog, DialogContent } from "@/components/ui/dialog";
import { X } from "lucide-react";
import { useEffect, useState } from "react";

interface VideoModalProps {
  isOpen: boolean;
  onClose: () => void;
  videoUrl: string;
  title: string;
  athleteName?: string;
}

export const VideoModal = ({ isOpen, onClose, videoUrl, title, athleteName }: VideoModalProps) => {
  const [videoId, setVideoId] = useState<string | null>(null);

  useEffect(() => {
    if (videoUrl) {
      const id = extractYouTubeVideoId(videoUrl);
      setVideoId(id);
    }
  }, [videoUrl]);

  const extractYouTubeVideoId = (url: string): string | null => {
    if (!url) return null;
    
    const patterns = [
      /(?:youtube\.com\/watch\?v=|youtu\.be\/|youtube\.com\/embed\/)([^&\n?#]+)/,
      /youtube\.com\/watch\?.*v=([^&\n?#]+)/
    ];

    for (const pattern of patterns) {
      const match = url.match(pattern);
      if (match && match[1]) {
        return match[1];
      }
    }
    return null;
  };

  return (
    <Dialog open={isOpen} onOpenChange={onClose}>
      <DialogContent className="max-w-5xl w-[95vw] p-0 overflow-hidden bg-black border-0">
        {/* Header */}
        <div className="relative bg-gradient-to-r from-accent-orange to-accent-terracotta p-4 md:p-6">
          <button
            onClick={onClose}
            className="absolute top-4 right-4 text-white hover:bg-white/20 rounded-full p-2 transition-colors z-10"
            aria-label="Close"
          >
            <X size={24} />
          </button>
          <div className="pr-12">
            <h2 className="text-xl md:text-2xl font-bold text-white mb-1">{title}</h2>
            {athleteName && (
              <p className="text-white/90 text-sm md:text-base">{athleteName}</p>
            )}
          </div>
        </div>

        {/* Video Container */}
        <div className="relative w-full bg-black" style={{ paddingBottom: '56.25%' }}>
          {videoId ? (
            <iframe
              className="absolute top-0 left-0 w-full h-full"
              src={`https://www.youtube.com/embed/${videoId}?autoplay=1&rel=0&modestbranding=1`}
              title={title}
              frameBorder="0"
              allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture"
              allowFullScreen
            ></iframe>
          ) : (
            <div className="absolute top-0 left-0 w-full h-full flex items-center justify-center">
              <p className="text-white">Unable to load video</p>
            </div>
          )}
        </div>

        {/* Footer */}
        <div className="bg-card p-4 md:p-6">
          <p className="text-sm text-muted-foreground text-center">
            {athleteName ? (
              <>
                Watching with <span className="font-semibold text-accent-orange">{athleteName}</span>
              </>
            ) : (
              "Enjoy the story—right here on Beyond the Finish Line."
            )}
          </p>
        </div>
      </DialogContent>
    </Dialog>
  );
};
