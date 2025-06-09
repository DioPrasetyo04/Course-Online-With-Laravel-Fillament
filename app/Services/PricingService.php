<?php

namespace App\Services;

use App\Repositories\PricingRepositoryInterface;

class PricingService
{
    protected $pricingRepository;

    public function __construct(PricingRepositoryInterface $pricingRepositoryInterface)
    {
        $this->pricingRepository = $pricingRepositoryInterface;
    }
    public function getAllPricing()
    {
        return $this->pricingRepository->getAllPricing();
    }

    public function findByIdPricing(int $id)
    {
        return $this->pricingRepository->findByIdPricing($id);
    }
}
