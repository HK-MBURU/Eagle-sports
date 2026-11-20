export interface Quote {
  id: number;
  content: string;
  owner: string;
  owner_title: string | null;
  owner_image: string | null;
  owner_image_url: string | null;
  added_by: number;
  created_at: string;
  updated_at: string;
  addedBy?: {
    id: number;
    name: string;
    email: string;
  };
}

export interface QuotesResponse {
  success: boolean;
  data: {
    quotes: Quote[];
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

export interface QuoteResponse {
  success: boolean;
  data: Quote;
  message?: string;
}

export interface RandomQuoteResponse {
  success: boolean;
  data: Quote;
}
