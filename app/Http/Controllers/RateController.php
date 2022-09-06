<?php

namespace App\Http\Controllers;

use App\Models\Rate;
use App\Traits\ScrapeTrait;
use Illuminate\Http\Request;

class RateController extends Controller
{
    use ScrapeTrait;

    private $ratesArray = [];
    // Store the rates data in database
    public function store()
    {
        dd($this->scrapeData());
        // Update or create the rates, currency is column with unique variables, while the rate is the column which variables are getting changed
        Rate::upsert($this->ratesArray, ['currency'], ['rate']);
    }
}
