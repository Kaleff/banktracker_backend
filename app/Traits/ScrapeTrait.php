<?php

namespace App\Traits;

trait ScrapeTrait
{
    // Scrape the data from xml document
    public function scrapeData()
    {
        // Parse contents of the XML page
        $xmlString = file_get_contents('https://www.bank.lv/vk/ecb_rss.xml');
        $xmlObject = simplexml_load_string($xmlString, 'SimpleXMLElement', LIBXML_NOCDATA);
        // Convert xml data to json and back to PHP array
        $json = json_encode($xmlObject);
        $phpArray = json_decode($json, true);
        return $phpArray;
    }
}