<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

use App\Models\User;
use App\Models\CarSale;

class CarSaleController extends Controller
{
    public function getCarSale() {
        $array = ['error' => '', 'data' => ''];

        $getCarSale = CarSale::orderBy('datecreated', 'DESC')->orderBy('id', 'DESC')->get();

        foreach($getCarSale as $carsaleKey => $carsaleValue) {
            $getCarSale[$carsaleKey]['datecreated'] = date('d/m/Y', strtotime($carsaleValue['datecreated']));


            $photoList = [];
            $photos = explode(',', $carsaleValue['photos']);

            foreach($photos as $photo) {
                if(!empty($photo)){
                    $photoList[] = asset('storage/'.$photo);
                }
            }
            $getCarSale[$carsaleKey]['photos'] = $photoList;

        }

        $array['data'] = $getCarSale;

        return $array;
    }

    public function addFileCarSale(Request $request) {
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



    public function addCarSale(Request $request) {
        $array = ['error' => ''];

        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'title' => 'required',
            'description' => 'required',
            'phone' => 'required',
            'price' => 'required',
            'cover' => 'required'
        ]);

        if(!$validator->fails()) {

            $user = auth()->user();
            $userid = $user['id'];

            $name = $request->input('name');
            $title = $request->input('title');
            $description = $request->input('description');
            $phone = $request->input('phone');
            $price = $request->input('price');
            $cover = $request->input('cover');
            $list = $request->input('photos');


            $newCarSale = new CarSale();
            $newCarSale->userid = $userid;
            $newCarSale->name = $name;
            $newCarSale->title = $title;
            $newCarSale->description = $description;
            $newCarSale->datecreated = date('Y-m-d');
            $newCarSale->phone = $phone;
            $newCarSale->price = $price;
            $newCarSale->cover = $cover;

            if($list && is_array($list)) {
                $photos = [];

                foreach($list as $listItem) {
                    $url = explode('/', $listItem);
                    $photos[] = end($url);
                }

                $newCarSale->photos = implode(',', $photos);
            } else {
                $newCarSale->photos = '';
            }

            $newCarSale->save();

        } else {
            $array['error'] = $validator->errors()->first();
            return $array;
        }

        return $array;
    }


    public function setCarSale($id, Request $request) {
        $array = ['error' => ''];

        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'title' => 'required',
            'description' => 'required',
            'phone' => 'required',
            'price' => 'required',
            'cover' => 'required'
        ]);

        if(!$validator->fails()) {

            $user = auth()->user();
            $userid = $user['id'];

            $mePost = CarSale::where('id', $id)->where('userid', $userid)->count();

            if($mePost > 0 ) {
                $name = $request->input('name');
                $title = $request->input('title');
                $description = $request->input('description');
                $phone = $request->input('phone');
                $price = $request->input('price');
                $cover = $request->input('cover');
                $list = $request->input('photos');

                $setCarSale = CarSale::find($id);
                $setCarSale->userid = $userid;
                $setCarSale->name = $name;
                $setCarSale->title = $title;
                $setCarSale->description = $description;
                $setCarSale->phone = $phone;
                $setCarSale->price = $price;
                $setCarSale->datecreated = date('Y-m-d');
                $setCarSale->cover = $cover;

                if($list && is_array($list)) {
                    $photos = [];

                    foreach($list as $listItem) {
                        $url = explode('/', $listItem);
                        $photos[] = end($url);
                    }

                    $setCarSale->photos = implode(',', $photos);
                } else {
                    $setCarSale->photos = '';
                }

                $setCarSale->save();

            } else {
                $array['error'] = 'Post não encontrado';
            }
        } else {
            $array['error'] = $validator->errors()->first();
            return $array;
        }

        return $array;
    }


    public function removeCarSale($id) {
        $array = ['error' => ''];

        $user = auth()->user();
        $userid = $user['id'];

        $mePost = CarSale::where('id', $id)->where('userid', $userid)->count();

        if($mePost > 0 ) {

            CarSale::where('id', $id)->where('userid', $userid)->delete();


        } else {
            $array['error'] = 'Post não encontrado';
        }

        return $array;
    }




    public function setCarSaleAdmin($id, Request $request) {
        $array = ['error' => ''];

        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'title' => 'required',
            'description' => 'required',
            'phone' => 'required',
            'price' => 'required',
            'cover' => 'required'
        ]);

        if(!$validator->fails()) {

            $mePost = CarSale::where('id', $id)->count();
            $admin = CarSale::where('id', $id)->get();

            $userid = $admin[0]['userid'];

            if($mePost > 0 ) {
                $name = $request->input('name');
                $title = $request->input('title');
                $description = $request->input('description');
                $phone = $request->input('phone');
                $price = $request->input('price');
                $cover = $request->input('cover');
                $list = $request->input('photos');

                $setCarSale = CarSale::find($id);
                $setCarSale->userid = $userid;
                $setCarSale->name = $name;
                $setCarSale->title = $title;
                $setCarSale->description = $description;
                $setCarSale->phone = $phone;
                $setCarSale->price = $price;
                $setCarSale->datecreated = date('Y-m-d');
                $setCarSale->cover = $cover;

                if($list && is_array($list)) {
                    $photos = [];

                    foreach($list as $listItem) {
                        $url = explode('/', $listItem);
                        $photos[] = end($url);
                    }

                    $setCarSale->photos = implode(',', $photos);
                } else {
                    $setCarSale->photos = '';
                }

                $setCarSale->save();

            } else {
                $array['error'] = 'Post não encontrado';
            }
        } else {
            $array['error'] = $validator->errors()->first();
            return $array;
        }

        return $array;
    }


    public function removeCarSaleAdmin($id) {
        $array = ['error' => ''];

        $mePost = CarSale::where('id', $id)->count();

        if($mePost > 0 ) {

            CarSale::where('id', $id)->delete();


        } else {
            $array['error'] = 'Post não encontrado';
        }

        return $array;
    }
}
