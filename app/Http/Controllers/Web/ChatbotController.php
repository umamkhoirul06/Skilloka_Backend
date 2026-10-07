<?php

namespace App\Http\Controllers\Web;

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
     * Handle incoming chatbot message from web user
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
            'status' => 'success',
            'reply' => $response['reply'],
            'source' => $response['source'],
            'timestamp' => now()->format('H:i')
        ]);
    }
}
