<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Events\MessageSent;
use App\Message;
use Illuminate\Support\Facades\Auth;
use App\User;

class ChatController extends Controller
{
    //
    public function __construct()
    {
    	$this->middleware('auth');
    }

    /**
     * fetch message
     *
     * @return message
     */ 
    public function getMessage() {
    	return Message::with('user')->get();
    }

    /**
     * send message (persist connection to database)
     *
     * @param Request $request 
     * @return Response
     */ 
    public function sendMessage(Request $request) {
    	$user = Auth::user();

    	$message = $user->messages()->create([
    		'message' => $request->input('message')
    	]); 

        broadcast(new MessageSent($user, $message))->toOthers();
    	// event(new MessageSent($user, $message));

    	return response()->json(['status' => 'Message sent!']);
    }

}
