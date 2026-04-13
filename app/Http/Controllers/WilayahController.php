<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Http;


class WilayahController extends Controller
{
    public function provinsi()
    {
        return Http::get('https://api.binderbyte.com/wilayah/provinsi', [
            'api_key' => env('BINDERBYTE_KEY')
        ])->json();
        return response()->json($response->json());
    }

    public function kabupaten($id)
    {
        return Http::get('https://api.binderbyte.com/wilayah/kabupaten', [
            'api_key' => env('BINDERBYTE_KEY'),
            'id_provinsi' => $id
        ]);
        return response()->json($response->json());
    }

    

    public function kecamatan($id)
    {
        return Http::get('https://api.binderbyte.com/wilayah/kecamatan', [
            'api_key' => env('BINDERBYTE_KEY'),
            'id_kabupaten' => $id
        ]);
        return response()->json($response->json());
    }

    public function kelurahan($id)
    {
        return Http::get('https://api.binderbyte.com/wilayah/kelurahan', [
            'api_key' => env('BINDERBYTE_KEY'),
            'id_kecamatan' => $id
        ]);
        return response()->json($response->json());
    }
}
