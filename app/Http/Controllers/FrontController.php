<?php

namespace App\Http\Controllers;

use App\Models\NcmBranch;
use Illuminate\Support\Facades\Http;
use Illuminate\Http\Client\Response;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;

class FrontController extends Controller
{
    public function  home()
    {
        return redirect()->route('admin.dashboard');
    }

    public function test()
    {
        // NcmBranch::getQuery()->delete();
        // $response = json_decode(Http::get('https://portal.nepalcanmove.com/api/v1/branchlist')->json()['data']);
        // // return count($response);
        // foreach($response as $data){
        //     $ncm = new NcmBranch();
        //     $ncm->branch_name = $data[0];
        //     $ncm->branch_code = $data[1];
        //     $ncm->areas_covered = $data[2];
        //     $ncm->muncipality = $data[3];
        //     $ncm->district = $data[4];
        //     $ncm->region = $data[5];
        //     $ncm->phone = $data[6];
        //     $ncm->secondary_phone = $data[7];
        //     $ncm->display_name = $data[8];
        //     $ncm->save();
        // }
        // $contents = File::get(base_path('public/json/data.json'));
        // $json = json_decode(json: $contents, associative: true);
        // // return count($json);
        // foreach ($json as $index => $j) {
        //     $ncm = NcmBranch::where('branch_name', $j['To'])->first();
        //     if ($ncm) {
        //         $ncm->home_delivery_fee = $j['Door Delivery'];
        //         $ncm->branch_delivery_fee = $j['Branch Delivery'];
        //     }
        //     $ncm->save();
        // }

        return NcmBranch::get();
    }
}
