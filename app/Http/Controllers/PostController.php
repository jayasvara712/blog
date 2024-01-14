<?php

namespace App\Http\Controllers;

use App\Models\Like;
use App\Models\Post;
use App\Models\User;
use App\Models\Comment;
use App\Models\Category;
use Illuminate\Http\Request;

class PostController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $title = '';
        if(request('category')){
            $category = Category::firstWhere('slug',request('category'));
            $title = ' in ' . $category->name;
        }
        if(request('author')){
            $author = User::firstWhere('username',request('author'));
            $title = ' by ' . $author->name;
        }

        return view('posts', [
            'title'     => 'All Posts' . $title,
            'active'    => 'posts',
            'posts'     => Post::latest()->filter(request(['search', 'category', 'author']))->paginate(7)->withQueryString()
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(Post $post)
    {
        if(auth()->user()){
            $status = $post->likes->where('user_id', auth()->user()->id)->first();
        }else{
            $status = false;
        }

        return view('post', [
            'title'         => 'Single Post',
            'active'        => 'posts',
            'post'          => $post,
            'relates'       => Post::where('category_id',$post->category_id)->whereNotIn('id', [$post->id])->latest()->limit(3)->get(),
            'comments'      => Comment::where('post_id',$post->id)->latest()->paginate(5),
            'like_status'  => $status,
            'isLogged'      => auth()->user() ? true : false
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
