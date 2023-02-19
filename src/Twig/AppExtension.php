<?php

namespace App\Twig;

use App\Service\DeviseService;
use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;

class AppExtension extends AbstractExtension
{
    private $deviseService;

    public function __construct(DeviseService $deviseService)
    {
        $this->deviseService = $deviseService;
    }

    public function getFilters(): array
    {
        return [
            new TwigFilter('currency_convert', [$this, 'convertCurrency']),
        ];
    }

    public function convertCurrency($amount): float
    {

        $convertedAmount = $this->deviseService->getExchangeRate($amount);

        return $convertedAmount;
    }
}