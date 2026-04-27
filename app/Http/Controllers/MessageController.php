<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Chat\MessageValidator;
use App\Chat\ValidationException;
use App\Events\MessageSent;
use App\Models\Message;
use App\Models\Room;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class MessageController extends Controller
{
    public function index(Room $room): JsonResponse
    {
        $messages = $room->messages()
            ->with('user:id,name')
            ->latest()
            ->paginate(50);

        return response()->json($messages);
    }

    public function store(Request $request, Room $room): JsonResponse
    {
        $validator = new MessageValidator();

        try {
            $validator->validate([
                'content' => $request->input('content'),
                'room_id' => $room->id,
            ]);
        } catch (ValidationException $e) {
            return response()->json(['errors' => $e->errors()], 422);
        }

        $user = $request->user();
        assert($user !== null);

        $message = Message::create([
            'room_id' => $room->id,
            'user_id' => $user->id,
            'content' => trim($request->input('content')),
        ]);

        $message->load('user:id,name');

        broadcast(new MessageSent($message));

        return response()->json($message, 201);
    }
}
