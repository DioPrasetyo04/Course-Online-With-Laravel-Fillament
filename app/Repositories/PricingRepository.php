<?php

namespace App\Repositories;

use App\Models\Pricing;
use App\Repositories\PricingRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;

class PricingRepository implements PricingRepositoryInterface
{
    // koneksi dengan model Pricing dan mengimplementasikan method pricing repository interface bisnis logic
    public function findByIdPricing(int $id): ?Pricing
    {
        return Pricing::query()->find($id);
    }


    public function getAllPricing(): Collection
    {
        // pake resource
        return Pricing::query()->all();
    }
}
