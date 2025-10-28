<?php
declare(strict_types=1);

namespace App\Services;

use App\Repositories\ServicePriceRepository;
use App\Repositories\SettingsRepository;

final class PricingService
{
    private ServicePriceRepository $prices;
    private SettingsRepository $settings;

    public function __construct()
    {
        $this->prices = new ServicePriceRepository();
        $this->settings = new SettingsRepository();
    }

    public function quote(int $serviceId, int $countryId, ?int $operatorId = null): ?array
    {
        $price = $this->prices->quote($serviceId, $countryId, $operatorId);
        if ($price === null) {
            return null;
        }

        $settings = $this->settings->getAll();
        $markup = (float) ($settings['pricing_markup_percent'] ?? 0);
        $sell = (float) $price['sell_price'];
        if ($markup > 0) {
            $sell += $sell * ($markup / 100);
        }

        $price['final_price'] = round($sell, 4);

        return $price;
    }
}
