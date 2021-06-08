<?php

namespace App\Http\Controllers;
use App\Models\Post;
use Illuminate\Http\Request;

class PostController extends Controller {

    public function all() {
        return response()->json(Post::all());
    }

    public function create(Request $request) {
        
        //validate 
            $this->validate($request, [
                'title'     => 'required',
                'content'   => 'required',
            ]);

        $author = Post::create($request->all());
        return response()->json($author, 201);
    }

    public function read($id) {
        return response()->json(Post::find($id));
    }

    public function update($id, Request $request) {
        $id = Post::findOrFail($id);
        $this->validate($request, [
            'title'     => 'required',
            'content'   => 'required',
        ]);
        $id->update($request->all());
        return response()->json($id, 200);
    }

    public function delete($id) {
        Post::findOrFail($id)->delete();
        return response('Deleted Successfully', 200);
    }
}
