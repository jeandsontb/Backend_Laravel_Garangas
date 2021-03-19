<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

use App\Models\Historic;

class HistoricController extends Controller
{
    public function addFileHistoric(Request $request) {
        $array = ['error' => ''];

        $validator = Validator::make($request->all(), [
            'photo' => 'required|file|mimes:jpeg,jpg,png,gif'
        ]);

        if(!$validator->fails()) {

            $file = $request->file('photo')->store('public');

            $array['photo'] = asset(Storage::url($file));
        } else {
            $array['error'] = $validator->errors()->first();
            return $array;
        }

        return $array;
    }

    public function getHistoric() {
        $array = ['error' => '', 'data' => ''];

        $getHistoric = Historic::all();

        $array['data'] = $getHistoric;

        return $array;
    }


    public function addhistoric(Request $request) {
        $array = ['error' => ''];

        $validator = Validator::make($request->all(), [
            'title' => 'required',
            'description' => 'required'
        ]);

        if(!$validator->fails()){

            $title = $request->input('title');
            $description = $request->input('description');
            $image = $request->input('image');

            $newHistoric = new Historic();
            $newHistoric->title = $title;
            $newHistoric->description = $description;
            $newHistoric->image = $image;
            $newHistoric->save();

        } else {
            $array['error'] = $validator->errors()->first();
            return $array;
        }

        return $array;
    }


    public function sethistoric($id, Request $request) {
        $array = ['error' => ''];

        $validator = Validator::make($request->all(), [
            'title' => 'required',
            'description' => 'required'
        ]);

        if(!$validator->fails()){

            $title = $request->input('title');
            $description = $request->input('description');
            $image = $request->input('image');

            $setHistoric = Historic::find($id);
            $setHistoric->title = $title;
            $setHistoric->description = $description;
            $setHistoric->image = $image;
            $setHistoric->save();

        } else {
            $array['error'] = $validator->errors()->first();
            return $array;
        }

        return $array;
    }


    public function removehistoric($id) {
        $array = ['error' => ''];

        $removeHistoric = Historic::find($id);
        if($removeHistoric) {
            Historic::find($id)->delete();
        } else {
            $array['error'] = 'Post inexistente.';
            return $array;
        }

        return $array;
    }
}
