<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use App\Models\ChatHistory;

class ChatbotController extends Controller
{
    /**
     * Display the chatbot interface
     */
    public function index()
    {
       return view('pages.chatbot');
    }

    /**
     * Process chatbot message
     */
    public function send(Request $request)
    {
        $request->validate([
            'message' => 'required|string|max:1000',
        ]);

        $userMessage = $request->input('message');

        try {
            // Save user message to database (optional)
            $this->saveChatHistory($userMessage, 'user');

            // Get AI response
            $aiResponse = $this->getAIResponse($userMessage);

            // Save AI response to database (optional)
            $this->saveChatHistory($aiResponse, 'bot');

            return response()->json([
                'success' => true,
                'response' => $aiResponse
            ]);

        } catch (\Exception $e) {
            Log::error('Chatbot Error: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'Sorry, I encountered an error. Please try again.'
            ], 500);
        }
    }

    /**
     * Get AI response from OpenAI API
     * You can also use: Google Gemini, Anthropic Claude, or build custom responses
     */
    private function getAIResponse($message)
    {
        $apiKey = env('OPENAI_API_KEY'); // or use config('services.openai.key')

        // If no API key, use rule-based responses
        if (empty($apiKey)) {
            return $this->getRuleBasedResponse($message);
        }

        // Call OpenAI API
        try {
            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $apiKey,
                'Content-Type' => 'application/json',
            ])->timeout(30)->post('https://api.openai.com/v1/chat/completions', [
                'model' => 'gpt-3.5-turbo', // or 'gpt-4'
                'messages' => [
                    [
                        'role' => 'system',
                        'content' => 'You are a helpful customer service assistant for an e-commerce store. Be friendly, concise, and helpful. Keep responses under 150 words.'
                    ],
                    [
                        'role' => 'user',
                        'content' => $message
                    ]
                ],
                'max_tokens' => 200,
                'temperature' => 0.7,
            ]);

            if ($response->successful()) {
                $data = $response->json();
                return $data['choices'][0]['message']['content'] ?? 'I apologize, but I couldn\'t generate a response.';
            }

            return $this->getRuleBasedResponse($message);

        } catch (\Exception $e) {
            Log::error('OpenAI API Error: ' . $e->getMessage());
            return $this->getRuleBasedResponse($message);
        }
    }

    /**
     * Rule-based responses (fallback when no AI API is configured)
     */
    private function getRuleBasedResponse($message)
    {
        $message = strtolower($message);

        // Business hours
        if (str_contains($message, 'business hours') || str_contains($message, 'opening hours') || str_contains($message, 'open')) {
            return "We're open Monday to Friday from 9:00 AM to 6:00 PM, and Saturdays from 10:00 AM to 4:00 PM. We're closed on Sundays and public holidays. 🕐";
        }

        // Products
        if (str_contains($message, 'product') || str_contains($message, 'catalog') || str_contains($message, 'sell')) {
            return "We offer a wide range of products including electronics, fashion, home goods, and more! Browse our catalog on our website or let me know what you're looking for. 🛍️";
        }

        // Shipping
        if (str_contains($message, 'shipping') || str_contains($message, 'delivery') || str_contains($message, 'ship')) {
            return "We offer free shipping on orders over ₱1,000 within Metro Manila! Standard delivery takes 3-5 business days, and express delivery is 1-2 business days. 📦";
        }

        // Order tracking
        if (str_contains($message, 'track') || str_contains($message, 'order status') || str_contains($message, 'where is my order')) {
            return "You can track your order by visiting the 'Track Order' page on our website and entering your order number. You'll receive a tracking link via email once your order ships. 📍";
        }

        // Returns
        if (str_contains($message, 'return') || str_contains($message, 'refund') || str_contains($message, 'exchange')) {
            return "We have a 30-day return policy! Items must be unused and in original packaging. Visit our Returns page or contact our support team to initiate a return. We'll process refunds within 5-7 business days. 🔄";
        }

        // Payment
        if (str_contains($message, 'payment') || str_contains($message, 'pay') || str_contains($message, 'accept')) {
            return "We accept credit/debit cards (Visa, Mastercard), GCash, PayMaya, bank transfers, and cash on delivery. All transactions are secure and encrypted. 💳";
        }

        // Contact
        if (str_contains($message, 'contact') || str_contains($message, 'reach') || str_contains($message, 'support')) {
            return "You can reach us at support@yourstore.com or call +63 (33) 123-4567. Our support team is available during business hours. We usually respond within 24 hours! 📞";
        }

        // Greeting
        if (str_contains($message, 'hello') || str_contains($message, 'hi') || str_contains($message, 'hey')) {
            return "Hello! 👋 Welcome to our store. How can I assist you today? Feel free to ask about products, orders, shipping, or anything else!";
        }

        // Thank you
        if (str_contains($message, 'thank') || str_contains($message, 'thanks')) {
            return "You're welcome! Happy to help. Is there anything else you'd like to know? 😊";
        }

        // Default response
        return "I'm here to help! You can ask me about:\n\n• Business hours\n• Products and catalog\n• Shipping and delivery\n• Order tracking\n• Returns and refunds\n• Payment methods\n• Contact information\n\nWhat would you like to know? 🤔";
    }

    /**
     * Save chat history to database (optional)
     */
    private function saveChatHistory($message, $sender)
    {
        try {
            ChatHistory::create([
                'user_id' => auth()->id() ?? null,
                'session_id' => session()->getId(),
                'message' => $message,
                'sender' => $sender,
            ]);
        } catch (\Exception $e) {
            Log::warning('Failed to save chat history: ' . $e->getMessage());
        }
    }
}