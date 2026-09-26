<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Message;

class MessageController extends Controller
{
    public function index()
    {
        $messages = Message::latest()->paginate(15);
        $nonLus = Message::where('lu', false)->count();
        return view('admin.messages.index', compact('messages', 'nonLus'));
    }

    public function show(Message $message)
    {
        if (!$message->lu) {
            $message->update(['lu' => true, 'lu_le' => now()]);
        }
        return view('admin.messages.show', compact('message'));
    }

    public function destroy(Message $message)
    {
        $message->delete();
        return redirect()->route('admin.messages.index')
            ->with('success', 'Message supprimé.');
    }
}