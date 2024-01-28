<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\Bill;
use App\Models\Period;
use App\Models\PumpMeterRecord;
use App\Models\Resident;
use App\Models\Tariff;
use Illuminate\Http\Request;

class RecordController extends Controller
{
    public function index()
    {
        $periods = Period::all();
        $records = PumpMeterRecord::orderBy('id', 'desc')->get();
        $tariffs = Tariff::all();

        return array($records, $periods, $tariffs);
    }

    public function store()
    {
        $reqPeriodId = request('period_id');
        $reqAmountVolume = number_format(request('amount_volume'), 2,'.', '');

        $response = PumpMeterRecord::create([
            'period_id' => $reqPeriodId,
            'amount_volume' =>$reqAmountVolume
        ]);

        $this->updateResidentBills($reqPeriodId, $reqAmountVolume, false);

        return $response;

    }

    public function update(PumpMeterRecord $record)
    {
        request()->validate([
            'period_id' => 'required',
            'amount_volume' => 'required',
        ]);

        $reqPeriodId = request('period_id');
        $reqAmountVolume = number_format(request('amount_volume'), 2,'.', '');

        $record->update([
            'amount_volume' => $reqAmountVolume,
        ]);

        $this->updateResidentBills($reqPeriodId, $reqAmountVolume, true);

        return $record;
    }

    protected function updateResidentBills($reqPeriodId, $reqAmountVolume, $update)
    {
        $tariff = Tariff::firstWhere('period_id', $reqPeriodId);

        $residents = Resident::all();

        $fullArea = 0;
        $amoutRub = $tariff->tariff * $reqAmountVolume;


        foreach($residents as $resident)
        {
            $fullArea += $resident->area;
        }

        if($update)
        {
            $bills = Bill::all();

            foreach ($residents as $resident)
            {
                $billAmountRub = round(($amoutRub / $fullArea) * $resident->area, 2);

                $bill = $bills->where( 'resident_id', $resident->id)->where('period_id', $reqPeriodId)->first();
                $bill->update([
                    'amount_rub' => $billAmountRub
                ]);
            }
        } else {
            foreach($residents as $resident)
            {
                $billAmountRub = round(($amoutRub / $fullArea) * $resident->area, 2);

                Bill::Create([
                    'resident_id' => $resident->id,
                    'period_id' => $reqPeriodId,
                    'amount_rub' => $billAmountRub
                ]);
            }
        }
    }

}
