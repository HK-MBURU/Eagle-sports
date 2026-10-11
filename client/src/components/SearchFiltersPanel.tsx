// components/SearchFiltersPanel.tsx
import React from 'react';
import { X } from 'lucide-react';
import { SearchFilters } from '@/types/search';

interface SearchFiltersPanelProps {
  filters: SearchFilters;
  onChange: (filters: SearchFilters) => void;
  onClose: () => void;
  compact?: boolean;
}

const SearchFiltersPanel: React.FC<SearchFiltersPanelProps> =({
  filters,
  onChange,
  onClose,
  compact = false
}) => {
  const contentTypes = [
    { id: 'story', label: 'Stories' },
    { id: 'athlete', label: 'Athletes' },
    { id: 'video', label: 'Videos' },
    { id: 'race-event', label: 'Events' },
    { id: 'women-running', label: 'Women Running' },
    { id: 'race-result', label: 'Race Results' },
    { id: 'partner', label: 'Partners' },
    { id: 'quote', label: 'Quotes' }
  ] as const;

  const handleTypeToggle = (typeId: string) => {
    const currentTypes = filters.types || [];
    const newTypes = currentTypes.includes(typeId)
      ? currentTypes.filter(t => t !== typeId)
      : [...currentTypes, typeId];

    onChange({
      ...filters,
      types: newTypes
    });
  };

  const handleDateRangeChange = (field: 'from' | 'to', value: string) => {
    const newDateRange = {
      ...filters.dateRange,
      [field]: value
    };

    onChange({
      ...filters,
      dateRange: newDateRange.from || newDateRange.to ? newDateRange : undefined
    });
  };

  const clearAllFilters = () => {
    onChange({
      types: contentTypes.map(t => t.id)
    });
  };

  if (compact) {
    return (
      <div className="p-3 bg-gray-50">
        <div className="flex items-center justify-between mb-3">
          <h3 className="text-sm font-semibold text-gray-900">Filters</h3>
          <button
            onClick={onClose}
            className="text-gray-400 hover:text-gray-600"
          >
            <X className="w-4 h-4" />
          </button>
        </div>

        <div className="space-y-2">
          <label className="block text-xs font-medium text-gray-700">
            Content Types
          </label>
          <div className="flex flex-wrap gap-1">
            {contentTypes.map((type) => (
              <button
                key={type.id}
                onClick={() => handleTypeToggle(type.id)}
                className={`px-2 py-1 text-xs rounded-full border transition-colors ${
                  filters.types?.includes(type.id)
                    ? 'bg-accent-orange text-white border-accent-orange'
                    : 'bg-white text-gray-700 border-gray-300 hover:border-gray-400'
                }`}
              >
                {type.label}
              </button>
            ))}
          </div>
        </div>
      </div>
    );
  }

  return (
    <div className="border-b bg-gray-50 p-4">
      <div className="flex items-center justify-between mb-4">
        <h3 className="font-semibold text-gray-900">Search Filters</h3>
        <button
          onClick={onClose}
          className="text-gray-400 hover:text-gray-600"
        >
          <X className="w-4 h-4" />
        </button>
      </div>

      {/* Content Types */}
      <div className="mb-4">
        <label className="block text-sm font-medium text-gray-700 mb-2">
          Content Types
        </label>
        <div className="grid grid-cols-2 gap-2">
          {contentTypes.map((type) => (
            <label key={type.id} className="flex items-center space-x-2">
              <input
                type="checkbox"
                checked={filters.types?.includes(type.id) || false}
                onChange={() => handleTypeToggle(type.id)}
                className="rounded border-gray-300 text-blue-600 focus:ring-blue-500"
              />
              <span className="text-sm text-gray-700">{type.label}</span>
            </label>
          ))}
        </div>
      </div>

      {/* Date Range */}
      <div className="mb-4">
        <label className="block text-sm font-medium text-gray-700 mb-2">
          Date Range
        </label>
        <div className="grid grid-cols-2 gap-2">
          <div>
            <label className="block text-xs text-gray-500 mb-1">From</label>
            <input
              type="date"
              value={filters.dateRange?.from || ''}
              onChange={(e) => handleDateRangeChange('from', e.target.value)}
              className="w-full px-2 py-1 border border-gray-300 rounded text-sm"
            />
          </div>
          <div>
            <label className="block text-xs text-gray-500 mb-1">To</label>
            <input
              type="date"
              value={filters.dateRange?.to || ''}
              onChange={(e) => handleDateRangeChange('to', e.target.value)}
              className="w-full px-2 py-1 border border-gray-300 rounded text-sm"
            />
          </div>
        </div>
      </div>

      {/* Clear Filters */}
      <button
        onClick={clearAllFilters}
        className="w-full py-2 text-sm text-gray-600 hover:text-gray-800 border border-gray-300 rounded-lg hover:bg-white transition-colors"
      >
        Clear All Filters
      </button>
    </div>
  );
};

export default SearchFiltersPanel;