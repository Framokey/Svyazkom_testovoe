<?php

namespace App\Http\Controllers;

use App\Models\Bill;
use App\Models\Period;
use App\Models\PumpMeterRecord;
use App\Models\Resident;
use App\Models\Tariff;
use Illuminate\Http\Request;
use NumberFormatter;

class InfoController extends Controller
{

    public function index()
    {

        $periods = Period::all();
        $tariffs = Tariff::all();
        $records = PumpMeterRecord::all();

        return array($periods, $tariffs, $records);
    }

    public function show(string $period_id)
    {

        $period = Period::firstWhere('id', $period_id);
        $tariff = Tariff::firstWhere('period_id', $period_id);
        $record = PumpMeterRecord::firstWhere('period_id', $period_id);

        $bills = Bill::where('period_id', $period_id)->get();
        $residents = Resident::all();




        return array($period, $tariff, $record, $bills, $residents);
    }


}
