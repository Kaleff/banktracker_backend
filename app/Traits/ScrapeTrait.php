<?php

namespace App\Traits;

trait ScrapeTrait
{
    // Empty array to store further data
    private $ratesArray = [];
    // Scrape the data from xml document
    public function scrapeData()
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
        // Creating an array for each currency and their rate
        foreach($rates as $rate) {
            $rateParams = explode(' ', $rate);
            $this->ratesArray[] = ['currency' => $rateParams[0], 'rate' => $rateParams[1]];
        }
        return($this->ratesArray);
    }
}