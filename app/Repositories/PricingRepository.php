<?php

namespace App\Repositories;

use App\Models\Pricing;
use App\Repositories\PricingRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;

class PricingRepository implements PricingRepositoryInterface
{
    public function findByIdPricing(int $id): ?Pricing
    {
        return Pricing::query()->find($id);
    }


    public function getAllPricing(): Collection
    {
        return Pricing::query()->all();
    }
}
