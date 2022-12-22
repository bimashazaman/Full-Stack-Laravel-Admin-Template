<?php

namespace App\Http\Controllers\App\Chat;

use App\Http\Controllers\Controller;
use App\Models\Core\Auth\User;
use Illuminate\Http\Request;

class ChatUserController extends Controller
{


    public function index()
    {
        return User::with(['profilePicture', 'messages' => function($query){
            $query->orderByDesc('messages.created_at');
        }])
                    ->where('id', '!=', auth()->id())
                    ->select('id', 'first_name', 'last_name', 'email')
                    ->get();
    }
}
