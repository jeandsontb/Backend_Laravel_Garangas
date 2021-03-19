<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

use App\Models\User;
use App\Models\LinkMovie;

class LinkMovieController extends Controller
{
    public function getLinkMovie() {
        $array = ['error' => '', 'data' => []];

        $linkMovie = LinkMovie::all();
        //$user = auth()->user();

        $array['data'] = $linkMovie;
        //$array['data']['user'] = $user['email']; -> para mostrar também os dados do usuário

        return $array;
    }

    public function addLinkMovie(Request $request) {
        $array = ['error' => ''];

        $validator = Validator::make($request->all(), [
            'title' => 'required',
            'url' => 'required'
        ]);

        if(!$validator->fails()) {

            $title = $request->input('title');
            $url = $request->input('url');

            $newLink = new LinkMovie();
            $newLink->title = $title;
            $newLink->url = $url;
            $newLink->save();

        } else {
            $array['error'] = $validator->errors()->first();
            return $array;
        }

        return $array;
    }

    public function setLinkMovie($id, Request $request) {
        $array = ['error' => ''];

        $validator = Validator::make($request->all(), [
            'title' => 'required',
            'url' => 'required'
        ]);

        if(!$validator->fails()) {

            $title = $request->input('title');
            $url = $request->input('url');

            $setLink = LinkMovie::find($id);
            if($setLink) {
                $setLink->title = $title;
                $setLink->url = $url;
                $setLink->save();
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

    public function removeLinkMovie($id) {
        $array = ['error' => ''];

        $removeLink = LinkMovie::find($id);
        if($removeLink) {
            LinkMovie::find($id)->delete();
        } else {
            $array['error'] = 'Post inexistente.';
            return $array;
        }

        return $array;
    }

}
