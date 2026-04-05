<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use App\Models\AgentService;
use App\Models\Transaction;

class AiCommentController extends Controller
{
    /**
     * Summarize an administrative comment using AI.
     */
    public function summarize(Request $request)
    {
        $request->validate([
            'comment' => 'required|string',
            'reference' => 'nullable|string',
        ]);

        $comment = $request->input('comment');
        $reference = $request->input('reference');

        $context = $this->fetchContext($reference);
        $systemPrompt = $this->getSystemPrompt('summarize', $context);

        $response = $this->callDeepseek($systemPrompt, "Please summarize and explain this administrative comment in a friendly, persuasive tone: \"$comment\"");

        return response()->json($response);
    }

    /**
     * Handle follow-up questions from the user.
     */
    public function ask(Request $request)
    {
        $request->validate([
            'comment' => 'required|string',
            'question' => 'required|string',
            'history' => 'nullable|array',
            'reference' => 'nullable|string',
        ]);

        $comment = $request->input('comment');
        $question = $request->input('question');
        $history = $request->input('history', []);
        $reference = $request->input('reference');

        $recordContext = $this->fetchContext($reference);
        $fullContext = "Admin Comment: \"$comment\"\n\n" . $recordContext;

        $systemPrompt = $this->getSystemPrompt('ask', $fullContext);

        $response = $this->callDeepseek($systemPrompt, $question, $history);

        return response()->json($response);
    }

    /**
     * Get the system prompt based on the action.
     */
    private function getSystemPrompt($action, $context = '')
    {
        $termsAndConditions = "
        AREWA SMART CORE RULES (TERMS & CONDITIONS):
        1. Nature: Digital service platform & intermediary (NOT a bank).
        2. Refunds: Only for system errors caused by Arewa Smart. NOT for user error or 3rd party failures.
        3. Transactions: Final and Irreversible once processed.
        4. Services: NIN (Validation, Modification, IPE), BVN (Search, Reports), Agency Banking, Airtime/Data.
        5. Charges: Apply once a request is successfully processed (e.g., successful validation).
        6. Etiquette: Professional, Nigerian business style, highly respectful.
        ";

        $basePrompt = "You are 'Arewa Smart AI Guide', a premium, highly professional, and empathetic virtual assistant for Arewa Smart Idea Ltd. 
        
        Your Mission:
        1. Explain administrative feedback in simple, encouraging terms.
        2. Build deep TRUST and BELIEF in Arewa Smart's expert administrators.
        3. Persuade users that even rejections are steps toward success ('Don't worry, we'll fix this!').
        4. Cross-sell related services (NIN/BVN mods, CAC, etc.) when appropriate.
        5. Tone: Expert, warm, premium. Use Nigerian business etiquette (respectful but firm on rules).
        
        Platform Context: $termsAndConditions
        
        Record Context: $context";

        if ($action === 'summarize') {
            return $basePrompt . "\n\nTask: Summarize the administrator's comment clearly. Focus on what it means for their service and what they should do next. Make them feel valued and confident.";
        }

        return $basePrompt . "\n\nTask: Answer the user's specific question using the provided context and Terms & Conditions. Be direct, helpful, and maintain the premium persona.";
    }

    /**
     * Fetch context from database based on reference.
     */
    private function fetchContext($reference)
    {
        if (!$reference)
            return "No specific record reference provided.";

        $service = AgentService::where('reference', $reference)->first();
        if ($service) {
            return "Service: {$service->service_name}\nStatus: {$service->status}\nAmount: {$service->amount}\nDescription: {$service->description}\nReference: {$service->reference}";
        }

        $transaction = Transaction::where('transaction_ref', $reference)->first();
        if ($transaction) {
            return "Transaction: {$transaction->description}\nStatus: {$transaction->status}\nAmount: {$transaction->amount}\nPayer: {$transaction->payer_name}\nRef: {$transaction->transaction_ref}";
        }

        return "Reference provided ($reference) but no matching service or transaction found.";
    }

    /**
     * Call the Deepseek API.
     */
    private function callDeepseek($systemPrompt, $userMessage, $history = [])
    {
        try {
            $apiKey = env('DEEPSEEK_API_KEY');
            $baseUrl = rtrim(env('DEEPSEEK_END_URL', 'https://api.deepseek.com'), '/');

            if (!$apiKey) {
                return ['success' => false, 'message' => 'AI Service temporarily unavailable. Please try again later or contact support.'];
            }

            $messages = [['role' => 'system', 'content' => $systemPrompt]];

            // Add history if available
            foreach ($history as $msg) {
                $messages[] = $msg;
            }

            $messages[] = ['role' => 'user', 'content' => $userMessage];

            $response = Http::withoutVerifying()
                ->timeout(45)
                ->withHeaders([
                    'Authorization' => 'Bearer ' . $apiKey,
                    'Content-Type' => 'application/json',
                ])
                ->post($baseUrl . '/chat/completions', [
                    'model' => 'deepseek-chat',
                    'messages' => $messages,
                    'temperature' => 0.7,
                    'max_tokens' => 1000,
                ]);

            if ($response->successful()) {
                $content = $response->json('choices.0.message.content');
                return [
                    'success' => true,
                    'answer' => $content
                ];
            }

            Log::error('Deepseek API Error in AiCommentController', [
                'status' => $response->status(),
                'body' => $response->body()
            ]);

            return ['success' => false, 'message' => 'Our AI is momentarily busy. Please try again in a few seconds.'];

        } catch (\Exception $e) {
            Log::error('Deepseek Exception in AiCommentController: ' . $e->getMessage());
            return ['success' => false, 'message' => 'Something went wrong with the AI service.'];
        }
    }
}
