<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * @method static count()
 * @method static findOrFail($id)
 * @method static create(mixed $validatedData)
 * @method static where(string $string, int $projectId)
 */
class Task extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * @var array<int, string>
     */
    protected $fillable = [
        'project_id',
        'title',
        'description',
        'status',
        'duration',
    ];

    /**
     * Define the possible statuses for a project.
     */
    public const STATUS_PENDING = 'pending';
    public const STATUS_IN_PROGRESS = 'in_progress';
    public const STATUS_COMPLETED = 'completed';
    public const MAX_LENGTH_DESCRIPTION = 500;
    public const MAX_LENGTH_TITLE = 100;

    /**
     * @var array
     */
    protected $casts = [
        'status' => 'string',
        'deleted_at' => 'datetime',
        'duration' => 'integer',
    ];

    /**
     * @return BelongsTo
     */
    public function project(): BelongsTo
    {
        return $this->belongsTo(Project::class);
    }
}
