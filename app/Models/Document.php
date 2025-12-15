<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Carbon;
use Mattiverse\Userstamps\Traits\Userstamps;

/**
 * @property int $id
 * @property string $uuid
 * @property string|null $title
 * @property string|null $description
 * @property string $original_name
 * @property string $path
 * @property string|null $preview_image
 * @property array<string, mixed>|null $metadata
 * @property int $download_count
 * @property Carbon|null $last_downloaded_at
 * @property Carbon $created_at
 * @property Carbon $updated_at
 * @property Carbon $deleted_at
 * @property int|null $created_by
 * @property int|null $updated_by
 * @property int|null $deleted_by
 */
class Document extends Model
{
    /** @use HasFactory<\Database\Factories\DocumentFactory> */
    use HasFactory, SoftDeletes, Userstamps;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'uuid',
        'title',
        'description',
        'original_name',
        'path',
        'preview_image',
        'metadata',
        'download_count',
        'last_downloaded_at',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'metadata' => 'array',
            'last_downloaded_at' => 'datetime',
        ];
    }

    public function uploadedBy()
    {
        return $this->belongsTo(User::class, 'created_by')->withTrashed();
    }
}
