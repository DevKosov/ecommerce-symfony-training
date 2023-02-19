<?php

namespace App\Service;

use Symfony\Component\HttpFoundation\Session\SessionInterface;

class DeviseService
{
    private $session;
    public function __construct(SessionInterface $session)
    {
        $this->session = $session;
    }

    const CURRENCIES = ["EUR","CAD","USD","GBP","PHP","IDR"];

    public function getExchangeRate(float $price_value_in_euro): float
    {
        if (!$this->session->has('currencyLayer')){
            $data = file_get_contents("https://api.apilayer.com/currency_data/historical?source=EUR&date=2023-02-19&apikey=gzXN1fMiBXB8o7YZTqTVS8yX2QyIdlvs&currencies=EUR,CAD,USD,GBP,PHP,IDR");
            $currencyLayer = json_decode($data, true);
            $this->session->set('currencyLayer',$currencyLayer);
        }else{
            $currencyLayer = $this->session->get('currencyLayer');
        }
        if (!$this->session->has("chosenCurrency")){
            $currency = "EUR";
            $this->session->set('chosenCurrency', $currency);
        }else{
            $currency = $this->session->get('chosenCurrency');
        }

        if ($currency == "EUR")
            return $price_value_in_euro;

        return $price_value_in_euro * $currencyLayer['quotes']["EUR$currency"];
    }

}