<?php

namespace App\Livewire;

use App\Models\Post;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Support\Str;
use Livewire\Attributes\Url;

class PostsPage extends Component
{
    use WithPagination;

    #[Url()]
    public $cari = '';

    public function likeIt($postID)
    {
        if (Auth::check()) {
            $post = Post::find($postID);
            $likes = array_map('intval', explode(',', $post->likes));

            $existing_like = null;

            foreach ($likes as $like) {
                if ($like == auth()->user()->id) {
                    $existing_like = 1;
                    break;
                }
            }

            if ($existing_like == null) {
                if ($post->likes == null) {
                    $post->update(['likes' => auth()->user()->id]);
                } else {
                    $post->update(['likes' => $post->likes . ',' . auth()->user()->id]);
                }
            }
        } else {
            redirect('/login');
        }
    }
    public function dontlikeIt($postID)
    {
        if (Auth::check()) {
            $post = Post::find($postID);
            $likes = array_map('intval', explode(',', $post->likes));

            $existing_like = null;

            foreach ($likes as $like) {
                if ($like == auth()->user()->id) {
                    $existing_like = 1;
                    break;
                }
            }

            if ($existing_like !== null) {
                $update_likes = Str::replace(auth()->user()->id, '', $post->likes);
                if (Str::contains($update_likes, ',,')) {
                    $update_likes = Str::replace(',,', ',', $update_likes);
                }
                if (Str::startsWith($update_likes, ',')) {
                    $update_likes = Str::replaceFirst(',', '', $update_likes);
                }
                if (Str::endsWith($update_likes, ',')) {
                    $update_likes = Str::replaceEnd(',', '', $update_likes);
                }
                $post->update(['likes' => $update_likes]);
            }
        } else {
            redirect('/login');
        }
    }

    public function render()
    {
        $posts = Post::query()->whereNotNull('date_published')
            ->whereNowOrPast('date_published')
            ->orderByDesc('date_published');

        if (!empty($this->cari)) {
            $pencarian = $this->cari;
            $posts
                ->where(function ($query) use ($pencarian) {
                    $query->where('title', 'LIKE', '%' . $pencarian . '%');
                    $query->orWhere('subtitle', 'LIKE', '%' . $pencarian . '%');
                    $query->orWhere('body', 'LIKE', '%' . $pencarian . '%');
                    $query->orWhere('categories', 'LIKE', '%' . $pencarian . '%');
                    $query->orWhere('tags', 'LIKE', '%' . $pencarian . '%');
                });
        }

        return view('livewire.posts-page', [
            'posts' => $posts->paginate(100)->withQueryString(),
            'user' => User::all(),
        ]);
    }
}
