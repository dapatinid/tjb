<?php

namespace App\Livewire;

use App\Models\Event;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Support\Str;
use Livewire\Attributes\Url;

class EventsPage extends Component
{

    use WithPagination;

    #[Url()]
    public $cari = '';

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
        $events = Event::query()->whereNotNull('date_published')
            ->whereNowOrPast('date_published')
            ->orderByDesc('date_from');

        if (!empty($this->cari)) {
            $pencarian = $this->cari;
            $events
                ->where(function ($query) use ($pencarian) {
                    $query->where('title', 'LIKE', '%' . $pencarian . '%');
                    $query->orWhere('subtitle', 'LIKE', '%' . $pencarian . '%');
                    $query->orWhere('body', 'LIKE', '%' . $pencarian . '%');
                    $query->orWhere('categories', 'LIKE', '%' . $pencarian . '%');
                    $query->orWhere('tags', 'LIKE', '%' . $pencarian . '%');
                });
        }

        return view('livewire.events-page', [
            'events' => $events->paginate(100)->withQueryString(),
            'user' => User::all(),
        ]);
    }
}
