<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Period;
use DateTime;
use Illuminate\Foundation\Auth\AuthenticatesUsers;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected string $redirectTo = '/admin';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    protected function authenticated()
    {
        $period = Period::orderBy('id', 'desc')->first();

        $data = [];
        $now = date_parse(now());

        if (!$period)
        {
            $period = Period::create([
                'begin_date' =>
                    (new DateTime($now['day'] . '.' . $now['month'] . '.' . $now['year']))
                        ->modify('first day of this month')
                        ->modify('0 hour')
                        ->modify('0 minute')
                        ->modify('0 second'),
                'end_date' =>
                    (new DateTime($now['day'] . '.' . $now['month'] . '.' . $now['year']))
                        ->modify('last day of this month')
                        ->modify('23 hour')
                        ->modify('59 minute')
                        ->modify('59 second')
            ]);

            $period = Period::orderBy('id', 'desc')->first();

        }

        for ($d = 1; $d <= 12; $d++)
        {
            $month = $now['month'];
            $year = $now['year'];

            $data[] = "$month.$year";

            if ($now['month'] < 10)
            {
                $data[$d - 1] = "0$month.$year";
            }
            if($now['month'] === 12)
            {
                $now['month'] = 1;
                $now['year'] += 1;
            } else
            {
                $now['month'] += 1;
            }
        }
        $lastDateDB = date('m.Y', strtotime($period['end_date']));

        $index = array_search($lastDateDB, $data);

        $newDateBegin = [];
        $newDateEnd = [];

        for ($i = $index + 1; $i <= 11; $i++)
        {
            $t = "27.$data[$i]";
            $newDateBegin[] = (new DateTime($t))->modify('first day of this month')->modify('0 hour')->modify('0 minute')->modify('0 second');
            $newDateEnd[] = (new DateTime($t))->modify('last day of this month')->modify('23 hour')->modify('59 minute')->modify('59 second');

        }

        for($i = 0; $i < count($newDateBegin); $i++)
        {
            Period::create([
                'begin_date' => $newDateBegin[$i],
                'end_date' => $newDateEnd[$i]
            ]);
        }
    }
}
