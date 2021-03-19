<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

use App\Models\Project;
use App\Models\User;
use App\Models\Member;

class ProjectController extends Controller
{
    public function addFileProject(Request $request) {
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

    public function getProject() {
        $array = ['error' => '', 'data' => ''];

        $getProject = Project::orderBy('datecreated', 'DESC')->orderBy('id', 'DESC')->get();

        foreach($getProject as $projectKey => $projectValue) {
            $getProject[$projectKey]['datecreated'] = date('d/m/Y', strtotime($projectValue['datecreated']));


            $photoList = [];
            $photos = explode(',', $projectValue['photos']);

            foreach($photos as $photo) {
                if(!empty($photo)){
                    $photoList[] = asset('storage/'.$photo);
                }
            }
            $getProject[$projectKey]['photos'] = $photoList;

        }

        $array['data'] = $getProject;

        return $array;
    }

    public function getProjectUser() {
        $array = ['error' => '', 'data' => ''];

        $user = auth()->user();
        $userid = $user['id'];

        $getProject = Project::where('userid', $userid)
                        ->orderBy('datecreated', 'DESC')
                        ->orderBy('id', 'DESC')
                        ->get();

        foreach($getProject as $projectKey => $projectValue) {
            $getProject[$projectKey]['datecreated'] = date('d/m/Y', strtotime($projectValue['datecreated']));

            $getProject[$projectKey]['cover'] = asset('storage/'.$projectValue['cover']);
            $photoList = [];
            $photos = explode(',', $projectValue['photos']);

            foreach($photos as $photo) {
                if(!empty($photo)){
                    $photoList[] = asset('storage/'.$photo);
                }
            }
            $getProject[$projectKey]['photos'] = $photoList;

        }

        $array['data'] = $getProject;

        return $array;
    }


    public function addProject(Request $request) {
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
            $futureprojects = $request->input('futureprojects');
            $cover = $request->input('cover');
            $list = $request->input('photos');


            $newProject = new Project();
            $newProject->userid = $userid;
            $newProject->name = $name;
            $newProject->title = $title;
            $newProject->description = $description;
            $newProject->futureprojects = $futureprojects;
            $newProject->datecreated = date('Y-m-d');
            $newProject->cover = $cover;

            if($list && is_array($list)) {
                $photos = [];

                foreach($list as $listItem) {
                    $url = explode('/', $listItem);
                    $photos[] = end($url);
                }

                $newProject->photos = implode(',', $photos);
            } else {
                $newProject->photos = '';
            }

            $newProject->save();

        } else {
            $array['error'] = $validator->errors()->first();
            return $array;
        }

        return $array;
    }

    public function setProject($id, Request $request) {
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

            $mePost = Project::where('id', $id)->where('userid', $userid)->count();

            if($mePost > 0 ) {
                $name = $request->input('name');
                $title = $request->input('title');
                $description = $request->input('description');
                $futureprojects = $request->input('futureprojects');
                $cover = $request->input('cover');
                $list = $request->input('photos');

                $setProject = Project::find($id);
                $setProject->userid = $userid;
                $setProject->name = $name;
                $setProject->title = $title;
                $setProject->description = $description;
                $setProject->futureprojects = $futureprojects;
                $setProject->datecreated = date('Y-m-d');
                $setProject->cover = $cover;

                if($list && is_array($list)) {
                    $photos = [];

                    foreach($list as $listItem) {
                        $url = explode('/', $listItem);
                        $photos[] = end($url);
                    }

                    $setProject->photos = implode(',', $photos);
                } else {
                    $setProject->photos = '';
                }

                $setProject->save();

            } else {
                $array['error'] = 'Post não encontrado';
            }
        } else {
            $array['error'] = $validator->errors()->first();
            return $array;
        }

        return $array;
    }


    public function removeProject($id) {
        $array = ['error' => ''];

        $user = auth()->user();
        $userid = $user['id'];

        $mePost = Project::where('id', $id)->where('userid', $userid)->count();

        if($mePost > 0 ) {

            Project::where('id', $id)->where('userid', $userid)->delete();


        } else {
            $array['error'] = 'Post não encontrado';
        }

        return $array;
    }



    public function setProjectAdmin($id, Request $request) {
        $array = ['error' => ''];

        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'title' => 'required',
            'description' => 'required',
            'cover' => 'required'
        ]);

        if(!$validator->fails()) {

            $mePost = Project::where('id', $id)->count();
            $admin = Project::where('id', $id)->get();

            $userid = $admin[0]['userid'];

            if($mePost > 0 ) {
                $name = $request->input('name');
                $title = $request->input('title');
                $description = $request->input('description');
                $futureprojects = $request->input('futureprojects');
                $cover = $request->input('cover');
                $list = $request->input('photos');

                $setProject = Project::find($id);
                $setProject->userid = $userid;
                $setProject->name = $name;
                $setProject->title = $title;
                $setProject->description = $description;
                $setProject->futureprojects = $futureprojects;
                $setProject->datecreated = date('Y-m-d');
                $setProject->cover = $cover;

                if($list && is_array($list)) {
                    $photos = [];

                    foreach($list as $listItem) {
                        $url = explode('/', $listItem);
                        $photos[] = end($url);
                    }

                    $setProject->photos = implode(',', $photos);
                } else {
                    $setProject->photos = '';
                }

                $setProject->save();

            } else {
                $array['error'] = 'Post não encontrado';
            }
        } else {
            $array['error'] = $validator->errors()->first();
            return $array;
        }

        return $array;
    }

    public function removeProjectAdmin($id) {
        $array = ['error' => ''];

        $mePost = Project::where('id', $id)->count();

        if($mePost > 0 ) {

            Project::where('id', $id)->delete();


        } else {
            $array['error'] = 'Post não encontrado';
        }

        return $array;
    }
}
