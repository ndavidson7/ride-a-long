<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Musonza\Chat\Models\Conversation;
use Musonza\Chat\Facades\ChatFacade as Chat;

class ConversationController extends Controller
{
    public function index()
    {
        return view('conversations.index', [
            'conversations' => Chat::conversations()->setPaginationParams(['sorting' => 'desc'])
                ->setParticipant(Auth::user())
                ->get(),
            'entries' => ['resources/js/views/conversations/index.js']
        ]);
    }

    public function create(User $user)
    {
        return view('conversations.create', [
            'user' => $user,
            'entries' => ['resources/js/views/conversations/create.js']
        ]);
    }

    public function store(User $user)
    {
        $conversation = Chat::createConversation([Auth::user(), $user])->makeDirect();

        return redirect()->route('conversations.show', ['conversation' => $conversation]);
    }

    public function show(Conversation $conversation)
    {
        $messages = Chat::conversation($conversation)->setParticipant(Auth::user())->getMessages();

        return view('conversations.show', [
            'conversation' => $conversation,
            'messages' => $messages,
            'entries' => ['resources/js/views/conversations/show.js']
        ]);
    }

    public function update(Request $request, Conversation $conversation)
    {
        $message = $request->validate([
            'message' => 'required|string'
        ])['message'];

        try {
            Chat::message($message)
                ->from(Auth::user())
                ->to($conversation)
                ->send();

            return response()->json([
                'status' => 'success',
                'message' => 'Message sent successfully.'
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to send message. ' . $e->getMessage()
            ], 500);
        }
    }

    public function destroy(Conversation $conversation)
    {
        Chat::conversation($conversation)->setParticipant(Auth::user())->clear();

        return redirect()->route('conversations.index')->with(['status' => 'success', 'message' => 'Conversation deleted successfully.']);
    }
}
