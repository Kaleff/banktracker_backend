<?php

namespace App\Http\Controllers;

use App\Models\Rate;
use App\Traits\ScrapeTrait;
use Illuminate\Http\Request;

class RateController extends Controller
{
    use ScrapeTrait;
    // Empty array to store further data
    private $ratesArray = [];
    // Store the rates data in database
    public function store()
    {
        // Get the latest exchange rates (last element of the array)
        $phpArray = $this->scrapeData();
        $rates = end($phpArray['channel']['item'])['description'];
        // Separeate the string data into an array and delete the last element since it's empty 
        $rates = explode('0 ', $rates);
        if (end($rates) == '') {
            array_pop($rates);
        }
        // Creating an array for each currency and their rate
        foreach($rates as $rate) {
            $rateParams = explode(' ', $rate);
            $this->ratesArray[] = ['currency' => $rateParams[0], 'rate' => $rateParams[1]];
        }
        // Update or create the rates, currency is column with unique variables, while the rate is the column which variables are getting changed
        Rate::upsert($this->ratesArray, ['currency'], ['rate']);
    }

    public function index()
    {
        // Return the information about all the currencies
        $rates = Rate::all()
                    ->pluck('rate', 'currency');
        return response()->json($rates);
    }

    public function show($currency)
    {
        // Return current information about specific currency
        $currency = strtoupper($currency);
        $currencyRate = Rate::where('currency', $currency)->firstOr(function () {
            abort(404);
        });
        // Scrape the xml data for the currency history
        $phpArray = $this->scrapeData();
        $rates = $phpArray['channel']['item'];
        // Create an indexed array for each date and the value based on the date
        foreach($rates as $rate) {
            // Extract data about specific currency from all the data. 
            $dateRate = substr($rate['description'], strpos($rate['description'], $currency));
            $dateRate = substr($dateRate, 0, strpos($dateRate, '0 '));
            // Convert data string to a simple array
            $dateRateArray = explode(' ', $dateRate);
            $this->ratesArray[$rate['pubDate']] = [$dateRateArray[0] => (float) $dateRateArray[1]];
            // Add history to the data
            $currencyRate['history'] = $this->ratesArray;
        }
        return response()->json($currencyRate);
    }
}
