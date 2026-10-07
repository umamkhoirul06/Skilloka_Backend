<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Services\ChatbotService;
use Illuminate\Http\Request;

class ChatbotController extends Controller
{
    protected ChatbotService $chatbotService;

    public function __construct(ChatbotService $chatbotService)
    {
        $this->chatbotService = $chatbotService;
    }

    /**
     * POST /api/chatbot/messages
     */
    public function sendMessage(Request $request)
    {
        $validated = $request->validate([
            'message' => 'required|string|max:600',
            'history' => 'nullable|array',
            'history.*.sender' => 'required_with:history|string|in:user,bot',
            'history.*.text' => 'required_with:history|string|max:1000',
        ]);

        $response = $this->chatbotService->reply(
            $validated['message'],
            $validated['history'] ?? []
        );

        return response()->json([
            'success' => true,
            'message' => 'Chatbot response generated',
            'data' => [
                'reply' => $response['reply'],
                'source' => $response['source'],
                'timestamp' => now()->toIso8601String()
            ]
        ]);
    }
}
