<?php

namespace App\Livewire;

use App\Models\Comment;
use App\Models\Donate;
use App\Models\Payment;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Livewire\Attributes\Title;
use Livewire\Attributes\Validate;
use Livewire\Component;
use Livewire\WithFileUploads;

#[Title('Donate Detail - TegarJaya')]
class DonateDetailPage extends Component
{
    use WithFileUploads;

    public $slug;

    #[Validate('image|max:1024|mimes:png,jpg,jpeg')] // 1MB Max
    public $comment_image;
    public $comment_body;

    #[Validate('image|max:5000|mimes:png,jpg,jpeg')] // -+5MB Max
    public $payment_image;
    public $payment_nominal;
    public $payment_method;

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

        $donateID = Donate::where('slug', $this->slug)->value('id');

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
        $comment->commentable_id = $donateID;
        $comment->commentable_type = 'App\Models\Donate';
        $comment->save();
        $this->comment_image = '';
        $this->comment_body = '';
    }

    public function cancelComment()
    {
        $this->comment_image = '';
        $this->comment_body = '';
    }

    public function addPayment()
    {

        $this->validate([
            'payment_nominal' => 'required',
            'payment_method' => 'required',
        ]);

        if ($this->payment_image != null) {
            $nameImg = md5($this->payment_image . microtime()) . '.' . $this->payment_image->extension();
            $this->payment_image->storeAs('payments/', $nameImg);
        } else {
            $nameImg = auth()->user()->image;
        }

        $donateID = Donate::where('slug', $this->slug)->value('id');

        $payment = new Payment();
        if ($this->payment_image != null) {
            $payment->image = 'payments/' . $nameImg;
        } else {
            $payment->image = null;
        }
        $payment->nominal_plus = Str::replace('.', '', $this->payment_nominal);
        $payment->payment_method  = $this->payment_method;
        $payment->currency  = 'idr';
        $payment->user_id  = auth()->user()->id;
        $payment->created_by  = auth()->user()->id;
        $payment->updated_by  = auth()->user()->id;
        $payment->branch_id  = auth()->user()->branch_id;
        $payment->paymentable_id = $donateID;
        $payment->paymentable_type = 'App\Models\Donate';
        $payment->mutation_type = 'Donation';
        $payment->save();

        $paymentsSUM = Payment::where('paymentable_id', $donateID)->where('paymentable_type', Donate::class)->sum('nominal_plus');
        Donate::where('id', $donateID)->update(['collected' => $paymentsSUM]);

        $this->payment_image = '';
        $this->payment_nominal = '';
        $this->payment_method = '';
    }

    public function cancelPayment()
    {
        $this->payment_image = '';
        $this->payment_nominal = '';
        $this->payment_method = '';
    }


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
        $donateID = Donate::where('slug', $this->slug)->value('id');
        $grup_comments = Comment::where('commentable_type', donate::class)->where('commentable_id', $donateID)
            // ->groupBy('branch_id')
            ->orderByDesc('updated_at',)
            ->get();
        $grup_payments = Payment::where('paymentable_type', donate::class)->where('paymentable_id', $donateID)
            // ->groupBy('branch_id')
            ->orderByDesc('updated_at',)
            ->get();
        $users = User::all();
        return view('livewire.donate-detail-page', [
            'donate' => donate::where('slug', $this->slug)->firstOrFail(),
            'grup_comments' => $grup_comments,
            'grup_payments' => $grup_payments,
            'users' => $users,
        ]);
    }
}
