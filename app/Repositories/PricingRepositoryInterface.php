<?php

namespace App\Repositories;

use App\Models\Pricing;
use Illuminate\Database\Eloquent\Collection;

interface PricingRepositoryInterface
{
    // method yang akan digunakan dalam pricing repository
    public function findByIdPricing(int $id): ?Pricing;

    public function getAllPricing(): Collection;
}
