<?php

namespace App\Services;

use App\Models\Conversation;
use Illuminate\Database\Eloquent\Model;

class ChatService
{
    private $conversation;
    private $tablePrefix = 'chat_';

    public function __construct($conversation = null)
    {
        $this->conversation = $conversation ?? new Conversation();
    }

    public static function from($conversation): self
    {
        return new self($conversation);
    }

    public function getMessages($cursor = null)
    {
        return $this->getConversationMessagesCursorPagination(auth()->user(), [
            'perPage' => 25,
            'sorting' => 'desc',
            'cursor' => $cursor,
        ], false)->through(function ($message) {
            return [
                'id' => $message->id,
                'sender' => $message->sender['id'] ?? null, // TODO: Sender will be null for users who have left or been removed from the ride. Handle this case.
                'body' => $message->body,
                'created_at' => $message->created_at,
            ];
        })->withPath(route('conversations.show', ['conversation' => $this->conversation->id]));
    }

    private function getConversationMessagesCursorPagination(Model $participant, $paginationParams, $deleted)
    {
        $messages = $this->conversation->messages()
            ->join($this->tablePrefix . 'message_notifications', $this->tablePrefix . 'message_notifications.message_id', '=', $this->tablePrefix . 'messages.id')
            ->where($this->tablePrefix . 'message_notifications.messageable_type', $participant->getMorphClass())
            ->where($this->tablePrefix . 'message_notifications.messageable_id', $participant->getKey());
        $messages = $deleted ? $messages->whereNotNull($this->tablePrefix . 'message_notifications.deleted_at') : $messages->whereNull($this->tablePrefix . 'message_notifications.deleted_at');
        $messages = $messages->orderBy($this->tablePrefix . 'messages.id', $paginationParams['sorting'])
            ->cursorPaginate(
                $paginationParams['perPage'],
                [
                    $this->tablePrefix . 'message_notifications.updated_at as read_at',
                    $this->tablePrefix . 'message_notifications.deleted_at as deleted_at',
                    $this->tablePrefix . 'message_notifications.messageable_id',
                    $this->tablePrefix . 'message_notifications.id as notification_id',
                    $this->tablePrefix . 'message_notifications.is_seen',
                    $this->tablePrefix . 'message_notifications.is_sender',
                    $this->tablePrefix . 'messages.*',
                ]
            );

        return $messages;
    }
}
