<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

use App\Models\User;
use App\Models\Member;

class MemberController extends Controller
{
    public function getMember() {
        $array = ['error' => '', 'data' => ''];

        $getMember = Member::orderBy('datecreated', 'DESC')->orderBy('id', 'DESC')->get();

        foreach($getMember as $memberKey => $memberValue) {
            $getMember[$memberKey]['datecreated'] = date('d/m/Y', strtotime($memberValue['datecreated']));


            $photoList = [];
            $photos = explode(',', $memberValue['photos']);

            foreach($photos as $photo) {
                if(!empty($photo)){
                    $photoList[] = asset('storage/'.$photo);
                }
            }
            $getMember[$memberKey]['photos'] = $photoList;

        }

        $array['data'] = $getMember;

        return $array;
    }

    public function addFileMember(Request $request) {
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

    public function addMember(Request $request) {
        $array = ['error' => ''];

        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'title' => 'required',
            'description' => 'required',
            'cover' => 'required'
        ]);

        if(!$validator->fails()) {

            $user = auth()->user();
            $userid = $user['id'];

            $name = $request->input('name');
            $title = $request->input('title');
            $description = $request->input('description');
            $cover = $request->input('cover');
            $list = $request->input('photos');


            $newMember = new Member();
            $newMember->userid = $userid;
            $newMember->name = $name;
            $newMember->title = $title;
            $newMember->description = $description;
            $newMember->datecreated = date('Y-m-d');
            $newMember->cover = $cover;

            if($list && is_array($list)) {
                $photos = [];

                foreach($list as $listItem) {
                    $url = explode('/', $listItem);
                    $photos[] = end($url);
                }

                $newMember->photos = implode(',', $photos);
            } else {
                $newMember->photos = '';
            }

            $newMember->save();

        } else {
            $array['error'] = $validator->errors()->first();
            return $array;
        }

        return $array;
    }


    public function setMember($id, Request $request) {
        $array = ['error' => ''];

        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'title' => 'required',
            'description' => 'required',
            'cover' => 'required'
        ]);

        if(!$validator->fails()) {

            $user = auth()->user();
            $userid = $user['id'];

            $mePost = Member::where('id', $id)->where('userid', $userid)->count();

            if($mePost > 0 ) {
                $name = $request->input('name');
                $title = $request->input('title');
                $description = $request->input('description');
                $cover = $request->input('cover');
                $list = $request->input('photos');

                $setMember = Member::find($id);
                $setMember->userid = $userid;
                $setMember->name = $name;
                $setMember->title = $title;
                $setMember->description = $description;
                $setMember->datecreated = date('Y-m-d');
                $setMember->cover = $cover;

                if($list && is_array($list)) {
                    $photos = [];

                    foreach($list as $listItem) {
                        $url = explode('/', $listItem);
                        $photos[] = end($url);
                    }

                    $setMember->photos = implode(',', $photos);
                } else {
                    $setMember->photos = '';
                }

                $setMember->save();

            } else {
                $array['error'] = 'Post não encontrado';
            }
        } else {
            $array['error'] = $validator->errors()->first();
            return $array;
        }

        return $array;
    }

    public function removeMember($id) {
        $array = ['error' => ''];

        $user = auth()->user();
        $userid = $user['id'];

        $mePost = Member::where('id', $id)->where('userid', $userid)->count();

        if($mePost > 0 ) {

            Member::where('id', $id)->where('userid', $userid)->delete();


        } else {
            $array['error'] = 'Post não encontrado';
        }

        return $array;
    }



    public function setMemberAdmin($id, Request $request) {
        $array = ['error' => ''];

        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'title' => 'required',
            'description' => 'required',
            'cover' => 'required'
        ]);

        if(!$validator->fails()) {

            $mePost = Member::where('id', $id)->count();
            $admin = Member::where('id', $id)->get();

            $userid = $admin[0]['userid'];

            if($mePost > 0 ) {
                $name = $request->input('name');
                $title = $request->input('title');
                $description = $request->input('description');
                $cover = $request->input('cover');
                $list = $request->input('photos');

                $setMember = Member::find($id);
                $setMember->userid = $userid;
                $setMember->name = $name;
                $setMember->title = $title;
                $setMember->description = $description;
                $setMember->datecreated = date('Y-m-d');
                $setMember->cover = $cover;

                if($list && is_array($list)) {
                    $photos = [];

                    foreach($list as $listItem) {
                        $url = explode('/', $listItem);
                        $photos[] = end($url);
                    }

                    $setMember->photos = implode(',', $photos);
                } else {
                    $setMember->photos = '';
                }

                $setMember->save();

            } else {
                $array['error'] = 'Post não encontrado';
            }
        } else {
            $array['error'] = $validator->errors()->first();
            return $array;
        }

        return $array;
    }


    public function removeMemberAdmin($id) {
        $array = ['error' => ''];

        $mePost = Member::where('id', $id)->count();

        if($mePost > 0 ) {

            Member::where('id', $id)->delete();


        } else {
            $array['error'] = 'Post não encontrado';
        }

        return $array;
    }
}
