<?php

namespace App\Repositories;

use App\Models\Pricing;
use Illuminate\Database\Eloquent\Collection;

interface PricingRepositoryInterface
{
    public function findByIdPricing(int $id): ?Pricing;

    public function getAllPricing(): Collection;
}
