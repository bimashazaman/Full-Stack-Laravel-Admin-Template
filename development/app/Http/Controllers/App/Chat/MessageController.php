<?php

namespace App\Http\Controllers\App\Chat;

use App\Events\ChatEvent;
use App\Helpers\Core\Traits\FileHandler;
use App\Http\Controllers\Controller;
use App\Models\App\Chat\Message;
use Illuminate\Http\Request;

class MessageController extends Controller
{
    use FileHandler;

    public function __construct()
    {
    }

    public function index()
    {
    }

    public function userMessage($id)
    {
        return Message::with('user.profilePicture', 'attachments')
            ->where(function ($query) use ($id) {
                $query->where('sender_id', auth()->id());
                $query->where('receiver_id', $id);
            })->orWhere(function ($query) use ($id) {
                $query->where('sender_id', $id);
                $query->where('receiver_id', auth()->id());
            })->orderBy('created_at')->get();
    }

    public function store(Request $request)
    {
        $message = Message::create($request->only('message', 'receiver_id'));
        if ($request->file_upload) {
            $file_path = $this->uploadImage(
                request()->file('file_upload'),
                'chat'
            );

            $message->attachments()->updateOrCreate([
                'message_id' => $message->id,
                'path' => $file_path
            ]);
        }
//        event(new ChatEvent($request->message, $user));

            broadcast(new ChatEvent($message));

        return created_responses('send', ['message' => $message]);
    }


    public function show($id)
    {
        //
    }


    public function update(Request $request, $id)
    {
        //
    }


    public function destroy($id)
    {
        //
    }
}
