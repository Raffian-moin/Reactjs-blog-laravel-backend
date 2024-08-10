<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Http\Request;

class PostController extends Controller
{
    public function get()
    {
        return Post::where('is_published', 1)
        ->get();
    }

    public function store(Request $request)
    {
        try {
            $inputs = $request->all();

            Post::create($inputs);

            return json_encode(["status" => 200]);
        } catch (\Exception $e) {
            return $e->getMessage();
        }
    }

    public function edit($id)
    {
        return Post::find($id);
    }

    public function update(Request $request, $id)
    {
        try {
            $inputs = $request->all();

            $post = Post::find($id);

            if (!empty($post)) {
                Post::where('id', $id)
                ->update($inputs);
            }

            return json_encode(["status" => 200]);
        } catch (\Exception $e) {
            return $e->getMessage();
        }
    }
}
