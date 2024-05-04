<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Musonza\Chat\Models\Conversation;
use Musonza\Chat\Facades\ChatFacade as Chat;
use App\Services\ChatService;

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

    public function show(Request $request, Conversation $conversation)
    {
        // Need to return CursorPaginator so that new messages don't cause paginator to return duplicates (I think)
        $paginator = ChatService::from($conversation)->getMessages($request->query('cursor', null));

        if ($request->expectsJson()) {
            return response()->json($paginator);
        }

        return view('conversations.show', [
            // 'conversation' => $conversation,
            'messages' => $messages
        ]);
    }

    public function update(Request $request, Conversation $conversation)
    {
        $message = $request->validate([
            'message' => 'required|string'
        ])['message'];

        try {
            $id = Chat::message($message)
                ->from(Auth::user())
                ->to($conversation)
                ->send()
                ->id;

            return response()->json([
                'id' => $id,
                'status' => 'success',
                'message' => 'Message sent.'
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

        return redirect()->route('conversations.index')->with(['status' => 'success', 'message' => 'Conversation deleted.']);
    }
}
