<?php

namespace App\Jobs;

use App\Models\SupportTicket;
use App\Models\SupportMessage;
use App\Services\DeepSeekService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Cache;

class ProcessAISupportReply implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $ticket;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(SupportTicket $ticket)
    {
        $this->ticket = $ticket;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle(DeepSeekService $deepSeekService)
    {
        // Indicate to the frontend that AI is typing
        Cache::put('admin_typing_' . $this->ticket->id, true, now()->addMinutes(5));

        try {
            $reply = $deepSeekService->generateReply($this->ticket);

            SupportMessage::create([
                'support_ticket_id' => $this->ticket->id,
                'user_id' => null, // AI system acts as admin
                'message' => $reply,
                'is_admin_reply' => true,
            ]);
            
            // Mark the ticket as answered by the admin
            $this->ticket->update([
                'status' => 'answered',
                'updated_at' => now(),
            ]);

        } finally {
            // Unset typing indicator
            Cache::forget('admin_typing_' . $this->ticket->id);
        }
    }
}
