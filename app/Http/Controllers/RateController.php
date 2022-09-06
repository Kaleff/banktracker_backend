<?php

namespace App\Http\Controllers;

use App\Models\Rate;
use App\Traits\ScrapeTrait;
use Illuminate\Http\Request;

class RateController extends Controller
{
    use ScrapeTrait;
    // Store the rates data in database
    public function store()
    {
        // Update or create the rates, currency is column with unique variables, while the rate is the column which variables are getting changed
        Rate::upsert($this->scrapeData(), ['currency'], ['rate']);
    }

    public function index()
    {
        $rates = Rate::all()
                    ->pluck('rate', 'currency');
        return response()->json($rates);
    }
}
