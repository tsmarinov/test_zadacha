<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * @method static count()
 * @method static findOrFail($id)
 * @method static create(array $validatedData)
 */
class Project extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'title',
        'description',
        'status'
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
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'status' => 'string',
        'deleted_at' => 'datetime'
    ];

    protected $appends = ['duration'];

    /**
     * A project can have many tasks.
     */
    public function tasks(): HasMany
    {
        return $this->hasMany(Task::class);
    }

    /**
     * @return int|mixed
     */
    public function getDurationAttribute(): mixed
    {
        return $this->tasks()->sum('duration');
    }

}
