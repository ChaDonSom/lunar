<?php

namespace Lunar\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Lunar\Base\BaseModel;
use Lunar\Base\Enums\Concerns\ProductAssociationTypesProvider;
use Lunar\Base\Enums\ProductAssociationTypes;
use Lunar\Base\Traits\HasMacros;
use Lunar\Database\Factories\ProductAssociationFactory;

/**
 * @property int $id
 * @property int $product_parent_id
 * @property int $product_target_id
 * @property string $type
 * @property ?\Illuminate\Support\Carbon $created_at
 * @property ?\Illuminate\Support\Carbon $updated_at
 */
class ProductAssociation extends BaseModel implements Contracts\ProductAssociation
{
    use HasFactory;
    use HasMacros;

    /**
     * Define the fillable attributes.
     *
     * @var array
     */
    protected $fillable = [
        'product_parent_id',
        'product_target_id',
        'type',
    ];

    /**
     * Return a new factory instance for the model.
     */
    protected static function newFactory()
    {
        return ProductAssociationFactory::new();
    }

    /**
     * Return the parent relationship.
     */
    public function parent(): BelongsTo
    {
        return $this->belongsTo(Product::modelClass(), 'product_parent_id');
    }

    /**
     * Return the target relationship.
     */
    public function target(): BelongsTo
    {
        return $this->belongsTo(Product::modelClass(), 'product_target_id');
    }

    /**
     * Apply the cross-sell scope.
     */
    public function scopeCrossSell(Builder $query): Builder
    {
        $typesProvider = config('lunar.products.association_types', ProductAssociationTypes::class);
        return $query->type($typesProvider::CROSS_SELL);
    }

    /**
     * Apply the upsell scope.
     */
    public function scopeUpSell(Builder $query): Builder
    {
        $typesProvider = config('lunar.products.association_types', ProductAssociationTypes::class);
        return $query->type($typesProvider::UP_SELL);
    }

    /**
     * Apply the up alternate scope.
     */
    public function scopeAlternate(Builder $query): Builder
    {
        $typesProvider = config('lunar.products.association_types', ProductAssociationTypes::class);
        return $query->type($typesProvider::ALTERNATE);
    }

    /**
     * Apply the type scope.
     */
    public function scopeType(Builder $query, ProductAssociationTypesProvider|string $type): Builder
    {
        if ($type instanceof ProductAssociationTypesProvider) {
            $type = $type->value;
        }

        return $query->whereType($type);
    }
}
