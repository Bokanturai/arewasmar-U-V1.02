<?php

namespace App\Http\Controllers\Action;

use App\Http\Controllers\Controller;
use App\Models\GiftCard;
use App\Models\Report;
use App\Models\Service;
use App\Models\Transaction;
use App\Models\Wallet;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;

class GiftCardController extends Controller
{
    /**
     * Generate a secure, unambiguous gift card code.
     * Excludes confusing characters: 0, O, 1, I, L
     */
    private function generateSecureCode(): string
    {
        $chars = 'ABCDEFGHJKMNPQRSTUVWXYZ23456789';
        $segment = fn() => implode('', array_map(fn() => $chars[random_int(0, strlen($chars) - 1)], range(1, 5)));
        return $segment() . '-' . $segment() . '-' . $segment();
    }

    /**
     * Normalize a gift card code: strip hyphens/spaces, uppercase.
     * Ensures consistent hash matching regardless of how user types it.
     */
    private function normalizeCode(string $code): string
    {
        return strtoupper(preg_replace('/[^A-Z0-9]/i', '', $code));
    }

    /**
     * Display a listing of gift cards (Created and Redeemed by the user).
     */
    public function index()
    {
        $user = Auth::user();

        $createdCards = GiftCard::where('created_by', $user->id)
            ->orderBy('created_at', 'desc')
            ->paginate(10, ['*'], 'created_page');

        $redeemedCards = GiftCard::where('used_by', $user->id)
            ->orderBy('used_at', 'desc')
            ->paginate(10, ['*'], 'redeemed_page');

        return view('gift-cards.index', compact('createdCards', 'redeemedCards'));
    }

    /**
     * Show the form for generating a new gift card.
     */
    public function create()
    {
        $user = Auth::user();
        $fees = $this->getServiceFees($user);
        $creationFee = $fees['creation_fee'];

        return view('gift-cards.create', compact('creationFee'));
    }

    /**
     * Store and generate a new gift card securely.
     */
    public function store(Request $request)
    {
        $request->validate([
            'amount'           => 'required|numeric|min:100|max:500000',
            'title'            => 'required|string|max:25',
            'message'          => 'nullable|string|max:500',
            'style'            => 'required|string|in:' .
                'birthday,wedding,anniversary,graduation,naming,housewarming,engagement,babyshower,' .
                'romantic,valentine,apology,thankyou,missyou,friendship,' .
                'fordad,formom,forbrother,forsister,family,care,' .
                'christmas,newyear,eid,ramadan,easter,independence,' .
                'reward,bonus,customerapp,promotion,salary,loyalty,' .
                'gaming,shopping,food,travel,surprise,' .
                'getwell,condolence,support,' .
                'general,cash,custom',
            'text_color'       => ['nullable', 'regex:/^#([0-9a-fA-F]{6})$/'],
            'pin_confirmation' => 'required|string|max:20',
        ]);

        // ── PIN Verification ─────────────────────────────────────────────────
        $user = Auth::user();
        if (!$user->pin || !Hash::check($request->pin_confirmation, $user->pin)) {
            return back()->withInput()->with('error', 'Incorrect wallet PIN. Please try again.');
        }

        $amount = $request->amount;
        $fees = $this->getServiceFees($user);
        $creationFee = $fees['creation_fee'];
        $totalCharge = $amount + $creationFee;

        DB::beginTransaction();
        try {
            // Lock wallet for safety
            $wallet = Wallet::where('user_id', $user->id)->lockForUpdate()->first();

            if (!$wallet || $wallet->status !== 'active') {
                throw new \Exception('Your wallet is not active or could not be found.');
            }

            if ($wallet->balance < $totalCharge) {
                throw new \Exception('Insufficient wallet balance to generate this gift card. Total required: ₦' . number_format($totalCharge, 2));
            }

            // Deduct from wallet
            $oldBalance = $wallet->balance;
            $newBalance = $oldBalance - $totalCharge;
            $wallet->balance = $newBalance;
            $wallet->save();

            // Service Reference
            $service = Service::where('name', 'Gift Card')->first();

            $transactionRef = 'GFC' . strtoupper(Str::random(12));
            $performedBy = trim($user->first_name . ' ' . ($user->middle_name ?? '') . ' ' . $user->last_name);

            // Log Transaction (Debit)
            $transaction = Transaction::create([
                'transaction_ref' => $transactionRef,
                'user_id' => $user->id,
                'amount' => $totalCharge, // Save Amount + Fee
                'fee' => $creationFee,
                'net_amount' => $totalCharge,
                'description' => "Generated Gift Card: {$request->title}",
                'type' => 'debit',
                'status' => 'completed',
                'performed_by' => $performedBy,
                'metadata' => [
                    'service' => 'gift_card_creation',
                    'base_amount' => $amount,
                    'title' => $request->title,
                    'style' => $request->style,
                    'fee_applied' => $creationFee,
                ],
            ]);

            // Log Report
            Report::create([
                'user_id' => $user->id,
                'phone_number' => $user->phone_number ?? 'N/A',
                'account_number' => 'N/A',
                'account_name' => $performedBy,
                'network' => 'GiftCard',
                'ref' => $transactionRef,
                'amount' => $totalCharge,
                'status' => 'completed',
                'type' => 'giftcard_debit',
                'description' => "Generated Gift Card: {$request->title} (Fee: ₦{$creationFee})",
                'old_balance' => $oldBalance,
                'new_balance' => $newBalance,
                'service_id' => $service->id ?? null,
            ]);

            // GENERATE SECURE CODE (no ambiguous chars: 0/O, 1/I/L)
            $rawCode = $this->generateSecureCode();
            // Hash the NORMALIZED form (no hyphens) for consistent lookup
            $codeHash = hash('sha256', $this->normalizeCode($rawCode));

            // Create Gift Card Record with encrypted raw token
            $giftCard = GiftCard::create([
                'code_hash' => $codeHash,
                'code_token_encrypted' => encrypt($rawCode),
                'amount' => $amount,
                'title' => $request->title,
                'title_color' => $request->title_color ?: '#a83535',
                'message' => $request->message,
                'style' => $request->style,
                'text_color' => $request->text_color ?: '#ffffff',
                'image_path' => null,
                'status' => 'unused',
                'created_by' => $user->id,
            ]);

            DB::commit();

            // Return success with RAW CODE explicitly passed as flash data (Never stored)
            return back()->with('success', 'Gift Card generated successfully!')
                ->with('generated_code', $rawCode)
                ->with('gift_card', $giftCard);

        } catch (QueryException $e) {
            DB::rollBack();
            Log::error('GiftCard Creation Database Error: ' . $e->getMessage(), [
                'user_id' => $user->id,
                'amount' => $amount,
                'trace' => $e->getTraceAsString()
            ]);
            return back()->withInput()->with('error', 'A database error occurred. Your wallet was not charged. Please contact support if this persists.');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('GiftCard Creation General Error: ' . $e->getMessage(), [
                'user_id' => $user->id,
                'trace' => $e->getTraceAsString()
            ]);
            return back()->withInput()->with('error', 'An unexpected error occurred while processing your request. Please try again.');
        }
    }

    /**
     * Show the form for redeeming a gift card.
     */
    public function redeemPage()
    {
        $user = Auth::user();
        $createdCards = GiftCard::where('created_by', $user->id)
            ->orderBy('created_at', 'desc')
            ->get();

        $redeemedCards = GiftCard::where('used_by', $user->id)
            ->orderBy('used_at', 'desc')
            ->get();

        return view('gift-cards.index', compact('createdCards', 'redeemedCards'))
            ->with('showRedeemModal', true);
    }

    /**
     * Process gift card redemption securely.
     */
    public function processRedeem(Request $request)
    {
        $request->validate([
            // Accept 15 alphanumeric or XXXXX-XXXXX-XXXXX (17 chars with dashes)
            // normalizeCode() will strip dashes and uppercase before hashing
            'code' => ['required', 'string', 'min:15', 'max:17', 'regex:/^[A-Za-z0-9\-]+$/'],
        ]);

        $user = Auth::user();

        // 1. Strict Rate Limiting (Prevent Brute Force)
        // We limit by both User ID and IP Address to prevent distributed attacks or single-user spam
        $limiterKey = 'giftcard_redeem_' . $user->id;
        $ipLimiterKey = 'giftcard_redeem_ip_' . $request->ip();

        if (RateLimiter::tooManyAttempts($limiterKey, 5)) {
            $seconds = RateLimiter::availableIn($limiterKey);
            return back()->with('error', "Too many failed attempts. Please try again in {$seconds} seconds.");
        }

        if (RateLimiter::tooManyAttempts($ipLimiterKey, 10)) {
            $seconds = RateLimiter::availableIn($ipLimiterKey);
            return back()->with('error', "Too many requests from your IP. Please try again in {$seconds} seconds.");
        }

        // Normalize input: strip hyphens/spaces and uppercase for consistent hash
        $inputCode = $this->normalizeCode(trim($request->code));
        $hashedInput = hash('sha256', $inputCode);

        DB::beginTransaction();
        try {
            // Find Matching Card securely exclusively inside DB transaction lock 
            // Also pessimistic lock the gift_card itself to prevent double redemption race conditions!
            $card = GiftCard::where('code_hash', $hashedInput)->lockForUpdate()->first();

            if (!$card) {
                RateLimiter::hit($limiterKey, 300); // 5 mins lockout for user
                RateLimiter::hit($ipLimiterKey, 300); // 5 mins lockout for IP
                throw new \Exception('Invalid or unrecognized gift card code.');
            }

            if ($card->status !== 'unused') {
                RateLimiter::hit($limiterKey, 60); // 1 min penalty
                throw new \Exception('This gift card has already been redeemed.');
            }

            if ($card->expires_at && now()->greaterThan($card->expires_at)) {
                throw new \Exception('This gift card has expired.');
            }

            // Lock Wallet for Credit
            $wallet = Wallet::where('user_id', $user->id)->lockForUpdate()->first();
            if (!$wallet || $wallet->status !== 'active') {
                throw new \Exception('Your wallet is not active or could not be found.');
            }

            $cardAmount = $card->amount;
            $fees = $this->getServiceFees($user);
            // $redemptionFee is now a PERCENTAGE (e.g. 2 means 2%)
            $redemptionFeePercent = $fees['redemption_fee'];
            $redemptionFee = round(($redemptionFeePercent / 100) * $cardAmount, 2);
            $creditAmount = round($cardAmount - $redemptionFee, 2);

            if ($creditAmount <= 0) {
                throw new \Exception('This gift card value is too low to cover redemption fees.');
            }

            $oldBalance = $wallet->balance;
            $newBalance = round($oldBalance + $creditAmount, 2);
            $wallet->balance = $newBalance;
            $wallet->save();

            // Mark Card as Used
            $card->status = 'used';
            $card->used_by = $user->id;
            $card->used_at = now();
            $card->save();

            // Service Reference
            $service = Service::where('name', 'Gift Card')->first();

            $transactionRef = 'GFR' . strtoupper(Str::random(12));
            $performedBy = trim($user->first_name . ' ' . ($user->middle_name ?? '') . ' ' . $user->last_name);

            // Log Transaction (Credit)
            Transaction::create([
                'transaction_ref' => $transactionRef,
                'user_id' => $user->id,
                'amount' => $creditAmount, // Save Amount - Fee
                'fee' => $redemptionFee,
                'net_amount' => $creditAmount,
                'description' => "Redeemed Gift Card: {$card->title}",
                'type' => 'credit',
                'status' => 'completed',
                'performed_by' => $performedBy,
                'metadata' => [
                    'service' => 'gift_card_redemption',
                    'base_card_amount' => $cardAmount,
                    'gift_card_id' => $card->id,
                    'fee_applied' => $redemptionFee,
                ],
            ]);

            // Log Report
            Report::create([
                'user_id' => $user->id,
                'phone_number' => $user->phone_number ?? 'N/A',
                'account_number' => 'N/A',
                'account_name' => $performedBy,
                'network' => 'GiftCard',
                'ref' => $transactionRef,
                'amount' => $creditAmount,
                'status' => 'completed',
                'type' => 'giftcard_credit',
                'description' => "Redeemed Gift Card: {$card->title} (Net added: ₦{$creditAmount})",
                'old_balance' => $oldBalance,
                'new_balance' => $newBalance,
                'service_id' => $service->id ?? null,
            ]);

            DB::commit();

            // Clear rate limiters upon successful redemption
            RateLimiter::clear($limiterKey);
            RateLimiter::clear($ipLimiterKey);

            // Redirect to a receipt page with all the details
            return redirect()->route('gift-card.thankyou3', [
                'card_title'        => $card->title,
                'credited'          => $creditAmount,
                'fee'               => $redemptionFee,
                'fee_percent'       => $redemptionFeePercent,
                'card_amount'       => $cardAmount,
                'new_balance'       => $newBalance,
                'transaction_ref'   => $transactionRef,
            ]);

        } catch (QueryException $e) {
            DB::rollBack();
            Log::error('GiftCard Redemption Database Error: ' . $e->getMessage(), ['user_id' => $user->id]);
            return back()->with('error', 'The system is currently busy. Please try redeeming your card again in a moment.');
        } catch (\Exception $e) {
            DB::rollBack();
            // We only show the exception message if it's one we manually threw
            $isKnownException = in_array($e->getMessage(), [
                'Invalid or unrecognized gift card code.',
                'This gift card has already been redeemed.',
                'This gift card has expired.',
                'Your wallet is not active or could not be found.',
                'This gift card value is too low to cover redemption fees.'
            ]);

            if (!$isKnownException) {
                Log::error('GiftCard Redemption General Error: ' . $e->getMessage(), ['user_id' => $user->id, 'trace' => $e->getTraceAsString()]);
            }

            $message = $isKnownException ? $e->getMessage() : 'An unexpected error occurred during redemption. Please try again.';
            return back()->with('error', $message);
        }
    }

    /**
     * Retrieve the gift card code securely.
     * Only the creator can see the code.
     */
    public function showCode(GiftCard $giftCard)
    {
        $user = Auth::user();

        // Security: Only the creator can see the raw code
        if ($giftCard->created_by !== $user->id) {
            return response()->json(['error' => 'Unauthorized access.'], 403);
        }

        // Security: Don't show code if already used/claimed
        if ($giftCard->status === 'used') {
            return response()->json(['error' => 'CLAIMED', 'status' => 'used'], 403);
        }

        try {
            $token = decrypt($giftCard->code_token_encrypted);
        } catch (\Exception $e) {
            Log::error('GiftCard decrypt failed for card #' . $giftCard->id . ': ' . $e->getMessage());
            return response()->json(['error' => 'Unable to retrieve code. Please contact support.'], 500);
        }

        return response()->json(['token' => $token]);
    }

    /**
     * Gift Card Redemption Thank You (Receipt) page.
     */
    public function thankYou3(Request $request)
    {
        // Validate all query params are present
        $data = $request->validate([
            'card_title'      => 'required|string|max:200',
            'credited'        => 'required|numeric',
            'fee'             => 'required|numeric',
            'fee_percent'     => 'required|numeric',
            'card_amount'     => 'required|numeric',
            'new_balance'     => 'required|numeric',
            'transaction_ref' => 'required|string|max:50',
        ]);

        return view('thankyou3', $data);
    }

    /**
     * Helper to initialize and fetch Gift Card service fees based on user role.
     */
    private function getServiceFees($user): array
    {
        $service = Service::firstOrCreate(
            ['name' => 'Gift Card'],
            ['description' => 'Custom Gift Card generation and redemption', 'is_active' => true]
        );

        // Field 1: Creation Charge (servicesField 1 - implicit)
        $creationFeeField = \App\Models\ServiceField::firstOrCreate(
            ['service_id' => $service->id, 'field_name' => 'Gift Card Creation'],
            ['field_code' => 'GFC_001', 'description' => 'Charge for generating a gift card', 'base_price' => 0, 'is_active' => true]
        );

        // Field 2: Redemption Charge (servicesField 2 - as requested)
        $redemptionFeeField = \App\Models\ServiceField::firstOrCreate(
            ['service_id' => $service->id, 'field_name' => 'Gift Card Redemption'],
            ['field_code' => 'GFC_002', 'description' => 'Charge for redeeming a gift card', 'base_price' => 0, 'is_active' => true]
        );

        return [
            'creation_fee' => $creationFeeField->getPriceForUserType($user->role),
            'redemption_fee' => $redemptionFeeField->getPriceForUserType($user->role),
        ];
    }
}
