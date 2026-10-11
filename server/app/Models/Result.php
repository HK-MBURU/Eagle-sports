<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Storage;

class Result extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'race_results';

    protected $fillable = [
        'title',
        'category',
        'file_path',
        'file_name',
        'file_type',
        'file_size',
        'uploaded_by',
        'description',
        'race_date',
        'location',
        'is_featured',
        'is_published',
        'views_count',
        'downloads_count',
    ];

    protected $casts = [
        'race_date' => 'date',
        'is_featured' => 'boolean',
        'is_published' => 'boolean',
        'views_count' => 'integer',
        'downloads_count' => 'integer',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'deleted_at' => 'datetime',
    ];

    protected $appends = [
        'file_url',
        'formatted_file_size',
        'formatted_date',
    ];

    /**
     * Get the user who uploaded this result
     */
    public function uploader()
    {
        return $this->belongsTo(User::class, 'uploaded_by');
    }

    /**
     * Get the file URL
     */
    public function getFileUrlAttribute()
    {
        if ($this->file_path) {
            return Storage::url($this->file_path);
        }
        return null;
    }

    /**
     * Get the full file URL (with domain)
     */
    public function getFullFileUrlAttribute()
    {
        if ($this->file_path) {
            return url(Storage::url($this->file_path));
        }
        return null;
    }

    /**
     * Get formatted file size (e.g., 2.5 MB)
     */
    public function getFormattedFileSizeAttribute()
    {
        if (!$this->file_size) {
            return 'Unknown';
        }

        $bytes = $this->file_size;
        $units = ['B', 'KB', 'MB', 'GB'];

        for ($i = 0; $bytes > 1024 && $i < count($units) - 1; $i++) {
            $bytes /= 1024;
        }

        return round($bytes, 2) . ' ' . $units[$i];
    }

    /**
     * Get formatted race date
     */
    public function getFormattedDateAttribute()
    {
        if (!$this->race_date) {
            return null;
        }
        return $this->race_date->format('M d, Y');
    }

    /**
     * Check if file is an image
     */
    public function isImage()
    {
        $imageExtensions = ['jpg', 'jpeg', 'png', 'gif', 'webp', 'svg'];
        return in_array(strtolower($this->file_type), $imageExtensions);
    }

    /**
     * Check if file is a PDF
     */
    public function isPdf()
    {
        return strtolower($this->file_type) === 'pdf';
    }

    /**
     * Check if file is a document
     */
    public function isDocument()
    {
        $documentExtensions = ['doc', 'docx', 'xls', 'xlsx', 'txt'];
        return in_array(strtolower($this->file_type), $documentExtensions);
    }

    /**
     * Get file icon based on type
     */
    public function getFileIcon()
    {
        if ($this->isImage()) {
            return 'image';
        } elseif ($this->isPdf()) {
            return 'file-pdf';
        } elseif ($this->isDocument()) {
            return 'file-text';
        }
        return 'file';
    }

    /**
     * Increment views count
     */
    public function incrementViews()
    {
        $this->increment('views_count');
    }

    /**
     * Increment downloads count
     */
    public function incrementDownloads()
    {
        $this->increment('downloads_count');
    }

    /**
     * Scope for published results
     */
    public function scopePublished($query)
    {
        return $query->where('is_published', true);
    }

    /**
     * Scope for featured results
     */
    public function scopeFeatured($query)
    {
        return $query->where('is_featured', true);
    }

    /**
     * Scope for results by category
     */
    public function scopeByCategory($query, $category)
    {
        return $query->where('category', $category);
    }

    /**
     * Scope for results by uploader
     */
    public function scopeByUploader($query, $userId)
    {
        return $query->where('uploaded_by', $userId);
    }

    /**
     * Scope for recent results
     */
    public function scopeRecent($query, $limit = 10)
    {
        return $query->latest()->limit($limit);
    }

    /**
     * Scope for results by date range
     */
    public function scopeDateRange($query, $startDate, $endDate)
    {
        return $query->whereBetween('race_date', [$startDate, $endDate]);
    }

    /**
     * Delete file when model is deleted
     */
    protected static function boot()
    {
        parent::boot();

        static::deleting(function ($result) {
            // Delete file from storage when result is deleted
            if ($result->file_path && Storage::exists($result->file_path)) {
                Storage::delete($result->file_path);
            }
        });
    }
}
