<?php

namespace App\Http\Controllers;

use App\Models\Countries;
use Illuminate\Http\Request;

class CovidController extends Controller
{
    public function list(Request $request){
        return ['results' => Countries::select(['id', 'name as text'])
            ->where('name', 'LIKE', '%'.$request->input('term').'%')
            ->take(20)
            ->get()];
    }

    public function countries(Request $request){
        return $request->user()->countries()->get();
    }

    public function addCountry(Request $request){
        $country = Countries::findOrFail($request->input('id'));
        $request->user()
            ->countries()
            ->attach($request->input('id'));
        $request->user()->save();
        return ['status' => 'success', 'country_code' => $country->code];
    }

    public function removeCountry(Request $request){
        $country = Countries::findOrFail($request->id);
        $request->user()
            ->countries()
            ->detach($request->id);
        $request->user()->save();
        return ['status' => 'success', 'country_code' => $country->code];
    }


}
