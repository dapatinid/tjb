<?php

namespace App\Livewire;

use App\Models\Comment;
use App\Models\Event;
use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Title;
use Livewire\Attributes\Validate;
use Livewire\Component;
use Livewire\WithFileUploads;

#[Title('Event Detail - TegarJaya')]
class EventDetailPage extends Component
{
    use WithFileUploads;

    public $slug;

    #[Validate('image|max:1024|mimes:png,jpg,jpeg')] // 1MB Max
    public $comment_image;
    public $comment_body;

    public function mount($slug)
    {
        $this->slug = $slug;
    }

    public function addComment()
    {

        $this->validate([
            'comment_body' => 'required',
        ]);

        if ($this->comment_image != null) {
            $nameImg = md5($this->comment_image . microtime()) . '.' . $this->comment_image->extension();
            $this->comment_image->storeAs('comments/', $nameImg);
        } else {
            $nameImg = auth()->user()->image;
        }

        $eventID = Event::where('slug', $this->slug)->value('id');

        $comment = new Comment();
        if ($this->comment_image != null) {
            $comment->image = 'comments/' . $nameImg;
        } else {
            $comment->image = null;
        }
        $comment->body = $this->comment_body;
        $comment->date_created = date('Y-m-d H:i:s');
        $comment->created_by  = auth()->user()->id;
        $comment->updated_by  = auth()->user()->id;
        $comment->branch_id  = auth()->user()->branch_id;
        $comment->commentable_id = $eventID;
        $comment->commentable_type = 'App\Models\Event';
        $comment->save();
        $this->comment_image = '';
        $this->comment_body = '';
    }

    public function cancelComment()
    {
        $this->comment_image = '';
        $this->comment_body = '';
    }


    public function likeIt($eventID)
    {
        if (Auth::check()) {
            $event = Event::find($eventID);
            $likes = array_map('intval', explode(',', $event->likes));

            $existing_like = null;

            foreach ($likes as $like) {
                if ($like == auth()->user()->id) {
                    $existing_like = 1;
                    break;
                }
            }

            if ($existing_like == null) {
                if ($event->likes == null) {
                    $event->update(['likes' => auth()->user()->id]);
                } else {
                    $event->update(['likes' => $event->likes . ',' . auth()->user()->id]);
                }
            }
        } else {
            redirect('/login');
        }
    }
    public function dontlikeIt($eventID)
    {
        if (Auth::check()) {
            $event = Event::find($eventID);
            $likes = array_map('intval', explode(',', $event->likes));

            $existing_like = null;

            foreach ($likes as $like) {
                if ($like == auth()->user()->id) {
                    $existing_like = 1;
                    break;
                }
            }

            if ($existing_like !== null) {
                $update_likes = Str::replace(auth()->user()->id, '', $event->likes);
                if (Str::contains($update_likes, ',,')) {
                    $update_likes = Str::replace(',,', ',', $update_likes);
                }
                if (Str::startsWith($update_likes, ',')) {
                    $update_likes = Str::replaceFirst(',', '', $update_likes);
                }
                if (Str::endsWith($update_likes, ',')) {
                    $update_likes = Str::replaceEnd(',', '', $update_likes);
                }
                $event->update(['likes' => $update_likes]);
            }
        } else {
            redirect('/login');
        }
    }


    public function render()
    {
        $eventID = Event::where('slug', $this->slug)->value('id');
        $grup_comments = Comment::where('commentable_type', Event::class)->where('commentable_id', $eventID)
            // ->groupBy('branch_id')
            ->orderByDesc('updated_at',)
            ->get();
        $users = User::all();
        return view('livewire.event-detail-page', [
            'event' => Event::where('slug', $this->slug)->firstOrFail(),
            'grup_comments' => $grup_comments,
            'users' => $users,
        ]);
    }
}
