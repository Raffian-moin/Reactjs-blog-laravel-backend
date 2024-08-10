<?php

namespace App\Http\Controllers;

use App\Models\PostBookmark;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PostBookmarkController extends Controller
{
    public function get($userId)
    {
        $bookmarkedPosts = DB::table('post_bookmarks')
        ->crossJoin(DB::raw('JSON_TABLE(post_bookmarks.bookmarks->"$.post_ids", "$[*]" COLUMNS(post_id INT PATH "$")) as j'), 'j.post_id', '=', 'j.post_id')
        ->join('posts', 'posts.id', '=', 'j.post_id')
        ->where("post_bookmarks.user_id", $userId)
        ->select('posts.*');

        return $bookmarkedPosts;
    }

    public function store(Request $request, $userId)
    {
        $postBookmark = PostBookmark::where("user_id", $userId)->first();

        try {
            if (!empty($postBookmark)) {
                $bookmarks = $postBookmark->bookmarks;
                $bookmarks['post_ids'][] = $request->post_id;
                $postBookmark->bookmarks = $bookmarks;
                $postBookmark->save();
            } else {
                PostBookmark::create([
                    "user_id" => $userId,
                    "bookmarks" => ['post_ids' => [$request->post_id]]
                ]);
            }

            return json_encode(["status" => 200]);
        } catch (\Exception $e) {
            return $e->getMessage();
        }
    }
}
