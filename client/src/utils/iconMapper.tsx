import { 
  TrendingUp, 
  Users2, 
  Sparkles, 
  Trophy, 
  Heart, 
  Medal,
  Circle,
  LucideIcon 
} from "lucide-react";

export const iconMapper: Record<string, LucideIcon> = {
  'trending-up': TrendingUp,
  'trendingup': TrendingUp,
  'users': Users2,
  'users2': Users2,
  'star': Sparkles,
  'sparkles': Sparkles,
  'trophy': Trophy,
  'heart': Heart,
  'medal': Medal,
  'circle': Circle,
};

export const getIconComponent = (iconName: string | null): LucideIcon => {
  if (!iconName) return Sparkles; // default icon
  
  const normalizedName = iconName.toLowerCase().replace(/[_-]/g, '');
  return iconMapper[normalizedName] || Sparkles;
};