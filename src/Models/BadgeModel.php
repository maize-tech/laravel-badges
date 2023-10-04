<?php

namespace Maize\Badges\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Maize\Badges\Events\BadgeAwarded;
use Maize\Badges\Support\Config;

/**
 * @property string $model_type
 * @property int $model_id
 * @property string $badge
 * @property ?array $metadata
 * @property Model $model
 */
class BadgeModel extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'badge_model';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<string>
     */
    protected $fillable = [
        'model_type',
        'model_id',
        'badge',
    ];

    /**
     * The event map for the model.
     *
     * Allows for object-based events for native Eloquent events.
     *
     * @var array
     */
    protected $dispatchesEvents = [
        'created' => BadgeAwarded::class,
    ];

    public function model(): MorphTo
    {
        return $this->morphTo();
    }

    public function metadata(): Attribute
    {
        return Attribute::make(
            get: fn (mixed $value, array $attributes) => Config::getBadge(
                data_get($attributes, 'badge')
            )?->metadata(),
        );
    }

    public function getMetadata(string $key): mixed
    {
        return data_get($this->metadata, $key);
    }
}
