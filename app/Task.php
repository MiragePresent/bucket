<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Task
 *
 * @property-read int $id Task ID
 * @property-read int $file_id Related file ID
 * @property int $type Task type
 * @property bool $is_complete Task status
 *
 * // Relations
 * @property-read File $file Related file entity
 */
class Task extends Model
{
    // Task types
    public const TYPE_PROCESSING = 20;
    public const TYPE_DOWNLOADING = 21;

    protected $casts = [
        "is_complete" => "bool",
    ];

    protected $fillable = [
        "file_id",
        "type",
        "is_complete",
        "created_at",
        "updated_at",
    ];

    public function file()
    {
        return $this->belongsTo(File::class);
    }
}
