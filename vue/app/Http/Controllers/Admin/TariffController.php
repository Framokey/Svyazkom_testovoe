<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\Period;
use App\Models\Resident;
use App\Models\Tariff;
use Illuminate\Http\Request;

class TariffController extends Controller
{

    public function index()
    {
        $periods = Period::all();
        $tariffs = Tariff::orderBy('id', 'desc')->get();

        return array($tariffs, $periods);
    }

    public function store()
    {
        return Tariff::create([
            'period_id' => request('period_id'),
            'tariff' => request('tariff'),
        ]);
    }


    public function update(Tariff $tariff)
    {

        $tariff->update([
            'tariff' => request('tariff')
        ]);

        return $tariff;
    }
}
