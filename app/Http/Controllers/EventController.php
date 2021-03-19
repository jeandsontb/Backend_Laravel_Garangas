<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

use App\Models\Event;

class EventController extends Controller
{
    public function addFileEvent(Request $request) {
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

    public function getEvent() {
        $array = ['error' => '', 'data' => []];

        $getEvent = Event::all();

        $array['data'] = $getEvent;

        return $array;
    }

    public function addEvent(Request $request) {
        $array = ['error' => ''];

        $validator = Validator::make($request->all(), [
            'title' => 'required',
            'description' => 'required',
            'date' => 'required'
        ]);
        if(!$validator->fails()) {

            $img = $request->input('img');
            $title = $request->input('title');
            $description = $request->input('description');
            $date = $request->input('date');

            $newEvent = new Event();
            $newEvent->img = $img;
            $newEvent->title = $title;
            $newEvent->description = $description;
            $newEvent->date = $date;
            $newEvent->save();

        } else {
            $array['error'] = $validator->errors()->first();
            return $array;
        }

        return $array;
    }

    public function setEvent($id, Request $request) {
        $array = ['error' => ''];

        $validator = Validator::make($request->all(), [
            'title' => 'required',
            'description' => 'required',
            'date' => 'required'
        ]);
        if(!$validator->fails()) {

            $img = $request->input('img');
            $title = $request->input('title');
            $description = $request->input('description');
            $date = $request->input('date');

             $setEvent = Event::find($id);
             if($setEvent) {
                $setEvent->img = $img;
                $setEvent->title = $title;
                $setEvent->description = $description;
                $setEvent->date = $date;
                $setEvent->save();
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

    public function removeEvent($id) {
        $array = ['error' => ''];

        $removeEvent = Event::find($id);
        if($removeEvent) {
            Event::find($id)->delete();
        } else {
            $array['error'] = 'Post inexistente.';
            return $array;
        }

        return $array;
    }
}

