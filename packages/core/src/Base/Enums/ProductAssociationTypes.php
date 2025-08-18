<?php

namespace Lunar\Base\Enums;

use Lunar\Base\Enums\Concerns\ProductAssociationTypesProvider;

enum ProductAssociationTypes: string implements ProductAssociationTypesProvider
{
    case UP_SELL = 'up-sell';
    case CROSS_SELL = 'cross-sell';
    case ALTERNATE = 'alternate';

    public function label(): string
    {
        return match ($this) {
            self::UP_SELL => 'Up Sell',
            self::CROSS_SELL => 'Cross Sell',
            self::ALTERNATE => 'Alternate',
        };
    }
}
