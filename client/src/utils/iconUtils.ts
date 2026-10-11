import { 
  TrendingUp, 
  Users2, 
  Sparkles, 
  Trophy, 
  Heart, 
  Medal,
  Circle,
  Star,
  LucideIcon 
} from "lucide-react";

export const iconMapper: Record<string, LucideIcon> = {
  'users': Users2,
  'star': Star,
  'sparkles': Sparkles,
  'trophy': Trophy,
  'heart': Heart,
  'medal': Medal,
  'circle': Circle,
  'trending': TrendingUp,
  'trendingup': TrendingUp,
  'users2': Users2,
  'trending-up': TrendingUp,
  'trend': TrendingUp,
};

export const getIconComponent = (iconName: string | null): LucideIcon => {
  if (!iconName) return Sparkles;
  const normalizedName = iconName.toLowerCase().trim();
  return iconMapper[normalizedName] || Sparkles;
};