<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Bill;
use App\Models\Period;
use App\Models\PumpMeterRecord;
use App\Models\Resident;
use App\Models\Tariff;


class InfoController extends Controller
{

    public function index()
    {

        $periods = Period::all();
        $tariffs = Tariff::all();
        $records = PumpMeterRecord::orderBy('period_id', 'desc')->get();
        $bills = Bill::all();

        return array($periods, $tariffs, $records, $bills);
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
