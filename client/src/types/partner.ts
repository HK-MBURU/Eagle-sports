export interface Partner {
  id: number;
  name: string;
  logo: string | null;
  logo_thumbnail: string | null;
  is_featured: boolean;
  added_by: number;
  creator: {
    id: number;
    name: string;
    email: string;
  } | null;
  created_at: string;
  created_at_formatted: string;
  updated_at: string;
  updated_at_formatted: string;
}

export interface PartnersResponse {
  success: boolean;
  data: {
    partners: Partner[];
    pagination: {
      current_page: number;
      last_page: number;
      per_page: number;
      total: number;
      from: number;
      to: number;
    };
  };
}

export interface PartnerResponse {
  success: boolean;
  data: Partner;
}

export interface FeaturedPartnersResponse {
  success: boolean;
  data: Partner[];
}

export interface AlphabeticalPartnersResponse {
  success: boolean;
  data: Partner[];
}

export interface SearchPartnersResponse {
  success: boolean;
  data: {
    partners: Partner[];
    search_term: string;
    pagination: {
      current_page: number;
      last_page: number;
      per_page: number;
      total: number;
      from: number;
      to: number;
    };
  };
}