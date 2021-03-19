<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

use App\Models\Partner;

class PartnerController extends Controller
{
    public function addFilePartner(Request $request) {
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


    public function getPartner() {
        $array = ['error' => '', 'data' => []];

        $getPartner = Partner::orderBy('id', 'DESC')->get();

        $array['data'] = $getPartner;

        return $array;
    }


    public function addPartner(Request $request) {
        $array = ['error' => ''];

        $validator = Validator::make($request->all(), [
            'photos' => 'required',
            'title' => 'required'
        ]);
        if(!$validator->fails()) {

            $photos = $request->input('photos');
            $title = $request->input('title');

            $newPartner = new Partner();
            $newPartner->photos = $photos;
            $newPartner->title = $title;
            $newPartner->save();

        } else {
            $array['error'] = $validator->errors()->first();
            return $array;
        }

        return $array;
    }


    public function setPartner($id, Request $request) {
        $array = ['error' => '', 'data' => []];

        $validator = Validator::make($request->all(), [
            'photos' => 'required',
            'title' => 'required'
        ]);
        if(!$validator->fails()) {

            $photos = $request->input('photos');
            $title = $request->input('title');

             $setPartner = Partner::find($id);
             if($setPartner) {
                $setPartner->photos = $photos;
                $setPartner->title = $title;
                $setPartner->save();
            } else {
                $array['error'] = 'Post inexistente.';
                return $array;
            }

        } else {
            $array['error'] = $validator->errors()->first();
            return $array;
        }

        return $array;
    }


    public function removePartner($id) {
        $array = ['error' => '', 'data' => []];

        $removePartner = Partner::find($id);
        if($removePartner) {
            Partner::find($id)->delete();
        } else {
            $array['error'] = 'Post inexistente.';
            return $array;
        }

        return $array;
    }
}
