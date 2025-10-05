<?php

namespace App\Http\Controllers;

use App\Models\Tag;
use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PostController extends Controller
{
   public function index(Request $request)
   {
        $posts = Post::with('tags')->where(function($query) {
            if (request()->has('search')) {
                $query->Where('title', 'LIKE', "%".request()->get('search')."%");
            }
            if (request()->has('tags')) {
                $tags = explode(',', request()->get('tags'));
                $query->whereHas('tags', function($query) use ($tags) {
                    $query->whereIn('name', $tags);
                });
            }
        })->paginate(9);

       return view('posts.index', compact('posts'));
   }

   public function create()
   {
       return view('posts.create_edit');
   }

   public function store(Request $request)
   {
       $request->validate([
           'title' => 'required|string|max:255',
           'content' => 'required|string',
           'tags' => 'required|string',
           'image' => 'required|mimes:jpg,jpeg,png'
       ]);

        $imagePath = null;
        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $imageName = time() . '.' . $image->getClientOriginalExtension();
            $image->move(public_path('assets/img/postimages/'), $imageName);
            $imagePath = 'assets/img/postimages/' . $imageName;
        }

       $post = Post::create([
           'title' => $request->input('title'),
           'content' => $request->input('content'),
           'user_id' => Auth::id(),
           'image' => $imagePath
       ]);

       $this->addTag(explode(',', $request->tags), $post->id);

       return redirect()->route('post.myposts');
   }

     private function addTag($tags, $postId)
    {
        foreach ($tags as $tagName) {
            $tag = Tag::firstOrCreate(['name' => $tagName]);

            $post = Post::findOrFail($postId);

            if (!$post->tags->contains($tag->id)) {
                $post->tags()->attach($tag->id);
            }
        }

        return true;
    }


   public function show($id)
   {
       $post = Post::with('tags', 'comments.user')->findOrFail($id);

        $relatedPosts = Post::where('id', '!=', $post->id)
                        ->whereHas('tags', function ($query) use ($post){
                            $query->whereIn('id', $post->tags->pluck('id'));
                        })
                        ->take(3)
                        ->get();

       return view('posts.show', compact('post', 'relatedPosts'));
   }


   public function edit($id)
   {
       $post = Post::findOrFail($id);

       return view('posts.create_edit', compact('post'));
   }

   public function update(Request $request, $id)
   {
       $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'tags' => 'required|string',
            'image' => 'mimes:jpg,jpeg,png'
       ]);

        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $imageName = time() . '.' . $image->getClientOriginalExtension();
            $image->move(public_path('assets/img/postimages/'), $imageName);
            $imagePath = 'assets/img/postimages/' . $imageName;
        }

        $data = $request->all();
       $post = Post::findOrFail($id);
       $post->title = $data['title'];
       $post->content = $data['content'];
       $post->image = isset($imagePath) ? $imagePath : $post->image;
       $post->save();

       $this->addTag(explode(',', $request->tags), $post->id);

       return redirect()->route('post.myposts');
   }

   public function destroy($id)
   {
       $post = Post::findOrFail($id);
       $post->tags()->detach();
       $post->delete();

       return redirect()->route('post.myposts');
   }
   public function myposts()
   {
       $posts = Post::with('tags', 'comments')->where('user_id', Auth::id())->paginate(3);
       return view('posts.myposts', compact('posts'));
   }
}
