<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Period;
use App\Models\PumpMeterRecord;
use App\Models\Tariff;
use App\Models\Resident;
use App\Models\Bill;

class TariffController extends Controller
{

    public function index()
    {
        $periods = Period::all();
        $tariffs = Tariff::orderBy('period_id', 'desc')->get();

        return array($tariffs, $periods);
    }

    public function store()
    {
        return Tariff::create([
            'period_id' => request('period_id'),
            'tariff' => number_format(request('tariff'), 2,'.', '')
        ]);
    }


    public function update(Tariff $tariff)
    {

        $tariff->update([
            'tariff' => number_format(request('tariff'), 2,'.', '')
        ]);

        $record = PumpMeterRecord::firstWhere('period_id', $tariff['period_id']);

        if($record)
        {
            $reqAmountVolume = $record['amount_volume'];
            $this->updateResidentBills($reqAmountVolume, $tariff);
        }

        return $tariff;

    }

    public function updateResidentBills($reqAmountVolume, $tariff)
    {
        $reqPeriodId = Period::firstWhere('id', $tariff['period_id'])['id'];
        $bills = Bill::all()->where('period_id', $reqPeriodId);
        $residentsDB = Resident::all();

        $fullArea = 0;
        $amoutRub = $tariff->tariff * $reqAmountVolume;
        $residents = [];

        foreach($bills as $bill)
        {

            $resident = $residentsDB->firstWhere('id', $bill['resident_id']);

            $fullArea += $resident['area'];
            array_push($residents, $resident);

        }

        foreach ($residents as $resident)
        {
            $billAmountRub = round(($amoutRub / $fullArea) * $resident->area, 2);

            $bill = $bills->where('resident_id', $resident->id)->where('period_id', $reqPeriodId)->first();

            $bill->update([
                'amount_rub' => $billAmountRub
            ]);
        }

    }

}
