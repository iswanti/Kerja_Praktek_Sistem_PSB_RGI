<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Http;

use Laravolt\Indonesia\Models\Province;
use Laravolt\Indonesia\Models\City;
use Laravolt\Indonesia\Models\District;
use Laravolt\Indonesia\Models\Village;

class WilayahController extends Controller
{
    public function provinsi()
    {
        return Province::all();
    }

    public function kabupaten($id)
    {
        return City::where('province_code', $id)->get();
    }

    public function kecamatan($id)
    {
        return District::where('city_code', $id)->get();
    }

    public function kelurahan($id)
    {
        return Village::where('district_code', $id)->get();
    }
}