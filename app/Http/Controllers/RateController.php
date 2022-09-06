<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class RateController extends Controller
{
    protected $currencies = [];
    public function index()
    {
        // Parse contents of the XML page
        $xmlString = file_get_contents('https://www.bank.lv/vk/ecb_rss.xml');
        $xmlObject = simplexml_load_string($xmlString, 'SimpleXMLElement', LIBXML_NOCDATA);
        // Convert xml data to json and back to PHP array
        $json = json_encode($xmlObject);
        $phpArray = json_decode($json, true);
        // Get the latest exchange rates (last element of the array)
        $rates = end($phpArray['channel']['item'])['description'];
        // Separeate the string data into an array and delete the last element since it's empty 
        $rates = explode('0 ', $rates);
        if (end($rates) == '') {
            array_pop($rates);
        }
        // Creating an indexed array of currencies and their rates
        foreach($rates as $rate) {
            $rateParams = explode(' ', $rate);
            $this->currencies[$rateParams[0]] = (float) $rateParams[1];
        }
        dd($this->currencies);
    }
}
