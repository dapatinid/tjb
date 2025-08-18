<?php

namespace App\Livewire;

use App\Models\Donate;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Support\Str;
use Livewire\Attributes\Url;

class DonatesPage extends Component
{
    use WithPagination;

    #[Url()]
    public $cari = '';

    public function likeIt($donateID)
    {
        if (Auth::check()) {
            $donate = donate::find($donateID);
            $likes = array_map('intval', explode(',', $donate->likes));

            $existing_like = null;

            foreach ($likes as $like) {
                if ($like == auth()->user()->id) {
                    $existing_like = 1;
                    break;
                }
            }

            if ($existing_like == null) {
                if ($donate->likes == null) {
                    $donate->update(['likes' => auth()->user()->id]);
                } else {
                    $donate->update(['likes' => $donate->likes . ',' . auth()->user()->id]);
                }
            }
        } else {
            redirect('/login');
        }
    }
    public function dontlikeIt($donateID)
    {
        if (Auth::check()) {
            $donate = donate::find($donateID);
            $likes = array_map('intval', explode(',', $donate->likes));

            $existing_like = null;

            foreach ($likes as $like) {
                if ($like == auth()->user()->id) {
                    $existing_like = 1;
                    break;
                }
            }

            if ($existing_like !== null) {
                $update_likes = Str::replace(auth()->user()->id, '', $donate->likes);
                if (Str::contains($update_likes, ',,')) {
                    $update_likes = Str::replace(',,', ',', $update_likes);
                }
                if (Str::startsWith($update_likes, ',')) {
                    $update_likes = Str::replaceFirst(',', '', $update_likes);
                }
                if (Str::endsWith($update_likes, ',')) {
                    $update_likes = Str::replaceEnd(',', '', $update_likes);
                }
                $donate->update(['likes' => $update_likes]);
            }
        } else {
            redirect('/login');
        }
    }

    public function render()
    {
        $donates = Donate::query()->whereNotNull('date_published')
            ->whereNowOrPast('date_published')
            ->orderByDesc('date_published');

        if (!empty($this->cari)) {
            $pencarian = $this->cari;
            $donates
                ->where(function ($query) use ($pencarian) {
                    $query->where('title', 'LIKE', '%' . $pencarian . '%');
                    $query->orWhere('subtitle', 'LIKE', '%' . $pencarian . '%');
                    $query->orWhere('body', 'LIKE', '%' . $pencarian . '%');
                    $query->orWhere('type', 'LIKE', '%' . $pencarian . '%');
                    $query->orWhere('categories', 'LIKE', '%' . $pencarian . '%');
                    $query->orWhere('tags', 'LIKE', '%' . $pencarian . '%');
                });
        }

        return view('livewire.donates-page', [
            'donates' => $donates->paginate(100)->withQueryString(),
            'user' => User::all(),
        ]);
    }
}
