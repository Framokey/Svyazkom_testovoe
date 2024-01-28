<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Bill;
use App\Models\Period;
use App\Models\Resident;
use http\Env\Response;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\View\View;

class ResidentController extends Controller
{

    public function index()
    {
        return Resident::orderBy('id', 'desc')->get();
    }

    public function store()
    {
        return Resident::create([
            'fio' => request('fio'),
            'area' => number_format(request('area'), 2,'.', ''),
        ]);
    }

    public function show(string $fio)
    {
        $resident = Resident::firstWhere('fio', $fio);
        $bills = Bill::where('resident_id', $resident->id)->get();
        $periods = Period::all();

        foreach ($bills as $bill) {
            $bill->resident_id = $resident;
            foreach($periods as $period){
                if ($period->id === $bill->period_id){
                    $bill->period_id = $period;
                }
            }
        }
        return $bills;
    }

    public function update(Resident $resident)
    {
        request()->validate([
            'fio' => 'required',
            'area' => 'required',
        ]);

        $resident->update([
            'fio' => request('fio'),
            'area' => number_format(request('area'), 2,'.', ''),
        ]);

        return $resident;
    }

    public function destroy(Resident $resident)
    {
        $resident->delete();

        return response()->noContent();
    }

}
