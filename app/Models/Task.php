<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    use HasFactory;

    public const PRIORITY_LOW = 'low';

    public const PRIORITY_MEDIUM = 'medium';

    public const PRIORITY_HIGH = 'high';

    public const STATUS_PENDING = 'pending';

    public const STATUS_IN_PROGRESS = 'in_progress';

    public const STATUS_DONE = 'done';

    public const PRIORITIES = [
        self::PRIORITY_LOW,
        self::PRIORITY_MEDIUM,
        self::PRIORITY_HIGH,
    ];

    public const STATUSES = [
        self::STATUS_PENDING,
        self::STATUS_IN_PROGRESS,
        self::STATUS_DONE,
    ];

    public const STATUS_TRANSITIONS = [
        self::STATUS_PENDING => self::STATUS_IN_PROGRESS,
        self::STATUS_IN_PROGRESS => self::STATUS_DONE,
    ];

    protected $fillable = [
        'title',
        'due_date',
        'priority',
        'status',
    ];

    protected function casts(): array
    {
        return [
            'due_date' => 'date:Y-m-d',
        ];
    }

    public static function prioritySortSql(): string
    {
        return "case priority when 'high' then 1 when 'medium' then 2 else 3 end";
    }
}
