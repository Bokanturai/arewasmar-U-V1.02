<?php

namespace App\Services;

use App\Models\SupportTicket;
use App\Models\User;
use App\Models\Transaction;
use App\Models\Report;
use App\Models\AgentService;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class DeepSeekService
{
    public function generateReply(SupportTicket $ticket)
    {
        $user = $ticket->user;
        
        $transactions = Transaction::where('user_id', $user->id)->latest()->take(5)->get();
        $reports = Report::where('user_id', $user->id)->latest()->take(5)->get();
        $agencyServices = AgentService::where('user_id', $user->id)->latest()->take(5)->get();
        
        $messages = $ticket->messages()->orderBy('created_at', 'asc')->get();

        $termsAndConditions = "Arewa Smart Terms & Conditions:
1. All transactions are final, instant, and irreversible once processed.
2. Refunds are ONLY eligible for failed transactions caused by system errors from Arewa Smart.
3. Incorrect user input, third-party API failures, or successfully processed transactions are strictly non-refundable. NIN Validations are non-refundable.
4. Users must maintain account security. We act as an intermediary, not a bank.
5. Provide professional support while upholding strictly to these terms.";

        $systemPrompt = "You are the official AI Support Admin for Arewa Smart, an advanced digital service platform in Nigeria (Airtime, Data, Utility, Educational PINs, NIN/BVN services).
Your goal is to provide exceptional, professional, and rapid customer support to users. You must sound human, empathetic, and extremely helpful to bring more customers to the platform.
However, you must STRICTLY adhere to the Terms & Conditions provided. Do not promise refunds unless it explicitly meets the criteria.
Always keep responses concise, accurate, and highly professional.

User Context:
Name: {$user->name}
Email: {$user->email}
Phone: {$user->phone}
Recent Transactions: " . $transactions->toJson() . "
Recent Reports: " . $reports->toJson() . "
Recent Agency Services: " . $agencyServices->toJson() . "

Platform Terms & Conditions:
$termsAndConditions

Current Ticket Subject: {$ticket->subject}";

        $chatHistory = [
            ['role' => 'system', 'content' => $systemPrompt]
        ];

        foreach ($messages as $msg) {
            $chatHistory[] = [
                'role' => $msg->is_admin_reply ? 'assistant' : 'user',
                'content' => $msg->message
            ];
        }

        $apiKey = env('DEEPSEEK_API_KEY');
        $baseUrl = rtrim(env('DEEPSEEK_END_URL', 'https://api.deepseek.com'), '/');
        
        try {
            $response = Http::withToken($apiKey)
                ->timeout(30)
                ->post($baseUrl . '/chat/completions', [
                    'model' => 'deepseek-chat',
                    'messages' => $chatHistory,
                    'temperature' => 0.7,
                    'max_tokens' => 800
                ]);

            if ($response->successful()) {
                return $response->json('choices.0.message.content') ?? 'I apologize, but I am unable to process your request at the moment. Please try again later.';
            }

            Log::error('DeepSeek API Error', ['response' => $response->body()]);
            return 'I apologize, but our AI support system is currently experiencing technical difficulties. Please hold on; a human agent will review your ticket soon.';

        } catch (\Exception $e) {
            Log::error('DeepSeek API Exception', ['message' => $e->getMessage()]);
            return 'I apologize, but our AI support system is currently experiencing technical difficulties. Please hold on; a human agent will review your ticket soon.';
        }
    }
}
