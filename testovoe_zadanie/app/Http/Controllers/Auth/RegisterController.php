<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Period;
use App\Models\User;
use DateTime;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class RegisterController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Register Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users as well as their
    | validation and creation. By default this controller uses a trait to
    | provide this functionality without requiring any additional code.
    |
    */

    use RegistersUsers;

    /**
     * Where to redirect users after registration.
     *
     * @var string
     */
    protected $redirectTo = '/home';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest');
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        return Validator::make($data, [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return \App\Models\User
     */
    protected function create(array $data)
    {
        return User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
        ]);
    }

    protected function registered()
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
