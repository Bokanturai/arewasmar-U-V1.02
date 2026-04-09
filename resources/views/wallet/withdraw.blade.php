<x-app-layout>
    <title>Arewa Smart - {{ $title ?? 'Withdraw Funds' }}</title>

    <style>
        /* Premium UI Form & Selection Matching */
        .premium-form-control {
            background-color: var(--bs-body-bg) !important;
            color: var(--bs-body-color) !important;
            border: 1px solid rgba(128, 128, 128, 0.2) !important;
            transition: all 0.3s ease;
        }
        .premium-form-control:focus {
            border-color: var(--bs-primary) !important;
            box-shadow: 0 0 0 0.25rem rgba(var(--bs-primary-rgb), 0.1) !important;
            background-color: var(--bs-body-bg) !important;
            color: var(--bs-body-color) !important;
        }
        .premium-select option {
            background-color: var(--bs-body-bg);
            color: var(--bs-body-color);
        }
        
        /* Dark Mode overrides */
        [data-bs-theme="dark"] .premium-form-control,
        .dark-mode .premium-form-control,
        body.dark .premium-form-control {
            background-color: #1a1d24 !important; 
            color: #e2e8f0 !important;
            border-color: rgba(255, 255, 255, 0.1) !important;
        }
        [data-bs-theme="dark"] .premium-form-control:focus,
        .dark-mode .premium-form-control:focus,
        body.dark .premium-form-control:focus {
            border-color: var(--bs-primary) !important;
            box-shadow: 0 0 0 0.25rem rgba(var(--bs-primary-rgb), 0.25) !important;
            background-color: #15171c !important;
        }

        .transfer-review-section, .premium-card {
            border-radius: 16px;
            background-color: var(--bs-body-bg) !important;
            border: 1px solid rgba(128, 128, 128, 0.1) !important;
        }
        [data-bs-theme="dark"] .transfer-review-section, 
        [data-bs-theme="dark"] .premium-card,
        .dark-mode .transfer-review-section, 
        .dark-mode .premium-card {
            background-color: #111318 !important;
            border-color: rgba(255, 255, 255, 0.05) !important;
        }

        .receipt-card {
            background: linear-gradient(145deg, var(--bs-body-bg), rgba(var(--bs-primary-rgb), 0.03));
            border: 1px dashed rgba(var(--bs-primary-rgb), 0.3);
            border-radius: 12px;
            position: relative;
        }
        
        [data-bs-theme="dark"] .receipt-card,
        .dark-mode .receipt-card,
        body.dark .receipt-card {
            background: linear-gradient(145deg, #1a1d24, rgba(var(--bs-primary-rgb), 0.1));
            border-color: rgba(var(--bs-primary-rgb), 0.5);
        }

        .recent-item {
            transition: all 0.2s ease;
            border: 1px solid rgba(128,128,128,0.1) !important;
        }
        .recent-item:hover {
            transform: translateY(-2px);
            border-color: var(--bs-primary) !important;
            box-shadow: 0 4px 12px rgba(var(--bs-primary-rgb), 0.1);
        }
    </style>

    <div class="container-fluid px-0 px-lg-4">
        <div class="row justify-content-center py-3 py-lg-4">
            <div class="col-12">
                <div class="row g-3 g-lg-4 mt-0 justify-content-center">

                    {{-- Withdrawal Form Column --}}
                    <div class="col-12 col-lg-6 col-xl-6">
                        <div class="card premium-card shadow-sm border-0 h-100">
                            <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center" style="border-radius: 16px 16px 0 0;">
                                <h5 class="mb-0 fw-bold"><i class="bi bi-bank me-2"></i>Secure Payout</h5>
                                <span class="badge bg-light text-primary fw-semibold">
                                    <i class="bi bi-shield-lock-fill me-1"></i> Instant
                                </span>
                            </div>

                            <div class="card-body">
                                {{-- Eligibility Banner --}}
                                @if($totalVolume < $eligibilityAmount)
                                    <div class="alert alert-warning border-0 shadow-sm rounded-4 mb-4 d-flex align-items-center p-3"
                                        style="background: #fffbeb;">
                                        <div class="bg-warning bg-opacity-20 rounded-circle p-2 me-3">
                                            <i class="bi bi-exclamation-triangle-fill text-warning"></i>
                                        </div>
                                        <div>
                                            <h6 class="fw-bold mb-0 text-warning-emphasis" style="font-size: 0.9rem;">
                                                Verification Pending</h6>
                                            <p class="mb-0 small text-muted">Unlock payouts by doing
                                                <strong>₦{{ number_format($eligibilityAmount - $totalVolume, 2) }}</strong>
                                                more.
                                            </p>
                                        </div>
                                    </div>
                                @else
                                    <div class="alert alert-success border-0 shadow-sm rounded-4 mb-4 d-flex align-items-center p-3"
                                        style="background: #f0fdf4;">
                                        <div class="bg-success bg-opacity-20 rounded-circle p-2 me-3">
                                            <i class="bi bi-shield-check-fill text-success"></i>
                                        </div>
                                        <div>
                                            <h6 class="fw-bold mb-0 text-success-emphasis" style="font-size: 0.9rem;">
                                                Account Verified</h6>
                                            <p class="mb-0 small text-muted">Your account qualifies for instant bank
                                                settlement.</p>
                                        </div>
                                    </div>
                                @endif

                                {{-- Flash Messages --}}
                                @if (session('success'))
                                    <div class="alert alert-success alert-dismissible fade show rounded-3 border-0 shadow-sm small py-2"
                                        role="alert">
                                        <i class="bi bi-check-circle-fill me-2"></i> {!! session('success') !!}
                                        <button type="button" class="btn-close btn-sm" data-bs-dismiss="alert"></button>
                                    </div>
                                @endif

                                @if (session('error'))
                                    <div class="alert alert-danger alert-dismissible fade show rounded-3 border-0 shadow-sm small py-2"
                                        role="alert">
                                        <i class="bi bi-exclamation-triangle-fill me-2"></i> {{ session('error') }}
                                        <button type="button" class="btn-close btn-sm" data-bs-dismiss="alert"></button>
                                    </div>
                                @endif

                                {{-- Withdrawal Form --}}
                                <form id="withdrawForm" method="POST" action="{{ route('withdraw.process') }}">
                                    @csrf

                                    {{-- Bank Preview Section --}}
                                    <div id="bankPreview" class="alert alert-info border-0 shadow-sm rounded-3 mb-4 d-flex align-items-center p-3" style="display: none !important; background-size: cover; background-position: center;">
                                        <div class="bg-primary bg-opacity-10 rounded-circle p-2 me-3" style="width: 45px; height: 45px; display: flex; align-items: center; justify-content: center;">
                                            <i class="bi bi-bank fs-15 text-primary" id="defaultBankIcon"></i>
                                            <img src="" id="selectedBankLogo" class="d-none" style="width: 30px; height: 30px; object-fit: contain;">
                                        </div>
                                        <div class="flex-grow-1">
                                            <h6 id="previewBankName" class="fw-bold mb-0 text-primary" style="font-size: 0.9rem;">Select a Bank</h6>
                                            <small id="previewAccountNo" class="text-muted">Enter details below</small>
                                        </div>
                                    </div>

                                   <!-- Bank Selection -->
                                <div class="col-mb-4">
                                    <label class="form-label fw-semibold">Select Bank</label>
                                    <div class="input-group input-group-lg shadow-sm rounded-3">
                                        <span class="input-group-text bg-white border-end-0"><i class="fas fa-university text-primary"></i></span>
                                        <select name="bank_code" id="bank_code" class="form-select border-start-0 ps-0" required>
                                            <option value="">Choose a bank...</option>
                                            @foreach($banks as $bank)
                                                <option value="{{ $bank->bank_code }}">{{ $bank->bank_name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>

                                    {{-- Account Number --}}
                                    <div class="col-mb-4">
                                        <label for="account_no" class="form-label fw-semibold text-body">Account Number <span class="text-danger">*</span></label>
                                        <input type="text" id="account_no" name="account_no" 
                                            class="form-control form-control-lg premium-form-control shadow-sm"
                                            placeholder="10 Digits" maxlength="10" inputmode="numeric" required>
                                        <div class="mt-2" style="min-height: 20px;">
                                            <small id="accountNameDisplay" class="text-success fw-bold"></small>
                                            <small id="accountErrorDisplay" class="text-danger fw-bold small"></small>
                                            <input type="hidden" name="account_name" id="account_name_hidden">
                                        </div>
                                    </div>

                                    {{-- Amount --}}
                                    <div class="mb-4">
                                        <div class="d-flex justify-content-between align-items-center mb-2">
                                            <label for="amount" class="form-label fw-semibold text-body mb-0">Withdrawal Amount <span class="text-danger">*</span></label>
                                            <small class="text-muted">Balance: <strong class="text-success">₦{{ number_format(auth()->user()->wallet->balance ?? 0, 2) }}</strong></small>
                                        </div>
                                        <input type="number" id="amount" name="amount" 
                                            class="form-control form-control-lg premium-form-control shadow-sm"
                                            placeholder="0.00" min="100" step="any" required>
                                        <div class="d-flex justify-content-between mt-1">
                                            <small class="text-muted small">Min: ₦100.00</small>
                                            <small class="text-muted small">Limit:
                                                ₦{{ number_format($user->limit, 2) }}</small>
                                        </div>
                                    </div>

                                    {{-- Submit --}}
                                    <div class="d-grid mt-4">
                                        <button type="button"
                                            class="btn btn-primary btn-lg fw-semibold shadow-sm py-3"
                                            id="proceedBtn" @if($totalVolume < $eligibilityAmount) disabled @endif>
                                            <i class="bi bi-lightning-charge-fill me-2"></i> Authorize Payout
                                        </button>
                                    </div>

                                    @if(auth()->user()->role === 'super_admin')
                                        <div class="text-center mt-3">
                                            <a href="{{ route('withdraw.syncBanks') }}" class="btn btn-sm text-muted">
                                                <i class="bi bi-arrow-repeat me-1"></i> Sync Bank Infrastructure
                                            </a>
                                        </div>
                                    @endif
                                </form>
                            </div>
                        </div>
                    </div>

                    {{-- Recent Payouts Column --}}
                    <div class="col-12 col-lg-6 col-xl-6">
                        <div class="card premium-card border-0 shadow-sm h-100">
                            <div class="card-header bg-transparent p-3 border-0 d-flex align-items-center justify-content-between pt-4">
                                <div class="d-flex align-items-center">
                                    <div class="bg-primary bg-opacity-10 rounded-3 p-2 me-3" style="width: 42px; height: 42px; display: flex; align-items: center; justify-content: center;">
                                        <i class="bi bi-clock-history text-primary fs-15"></i>
                                    </div>
                                    <div>
                                        <h6 class="mb-0 fw-bold text-body">Recent Recipients</h6>
                                        <p class="text-muted small mb-0">Select for rapid payout</p>
                                    </div>
                                </div>
                            </div>

                            <div class="card-body p-3" id="recentRecipientsBody">
                                @if(isset($recentRecipients) && count($recentRecipients) > 0)
                                    <div class="d-flex flex-column gap-3">
                                        @foreach($recentRecipients as $recipient)
                                            <div class="d-flex justify-content-between align-items-center p-3 rounded-3 mb-2 cursor-pointer recent-item bg-body-secondary"
                                                onclick="selectRecentBank('{{ $recipient['bank_code'] }}', '{{ $recipient['account_no'] }}', '{{ $recipient['account_name'] }}')">
                                                <div class="d-flex align-items-center">
                                                    <div class="rounded-circle shadow-sm me-3 d-flex align-items-center justify-content-center bg-body"
                                                        style="width: 40px; height: 40px; border: 1px solid rgba(128,128,128,0.2);">
                                                        @if(!empty($recipient['bank_url']))
                                                            <img src="{{ $recipient['bank_url'] }}" alt="logo"
                                                                style="width: 25px; height: 25px; object-fit: contain;">
                                                        @else
                                                            <i class="bi bi-bank text-primary"></i>
                                                        @endif
                                                    </div>
                                                    <div>
                                                        <h6 class="mb-0 fw-bold small text-body">
                                                            {{ $recipient['account_name'] }}
                                                        </h6>
                                                        <small class="text-muted small d-block">{{ $recipient['bank_name'] }} • {{ $recipient['account_no'] }}</small>
                                                    </div>
                                                </div>
                                                <i class="bi bi-chevron-right text-muted small"></i>
                                            </div>
                                        @endforeach
                                    </div>
                                @else
                                    <div class="text-center py-5">
                                        <div class="bg-primary bg-opacity-10 rounded-circle p-4 d-inline-block mb-3">
                                            <i class="bi bi-wallet2 display-5 text-primary opacity-50"></i>
                                        </div>
                                        <h6 class="fw-bold text-body">No Recent Payouts</h6>
                                        <p class="text-muted px-4 small">History of your trusted recipients will appear
                                            here.</p>
                                    </div>
                                @endif
                            </div>

                            <div class="card-footer bg-transparent p-3 border-top text-center mt-auto" style="border-color: rgba(128,128,128,0.1) !important;">
                                <small class="text-muted opacity-75 letter-spacing text-uppercase fs-15">Protected by
                                    Arewa Smart Multi-Factor Authentication</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
       {{-- PIN Confirmation Modal --}}
    <div class="modal fade" id="pinModal" data-bs-backdrop="static" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content transfer-review-section shadow-lg border-0">
                <div class="modal-header border-0 pb-0 justify-content-between align-items-center p-4">
                    <h5 class="modal-title fw-bold text-body" id="modalTitle">Review Transfer</h5>
                    <button type="button" class="btn-close shadow-none" data-bs-dismiss="modal"></button>
                </div>

                <div class="modal-body p-4 pt-2">
                    <p class="text-muted small mb-4" id="modalSubtitle">Please review details carefully before authorization.</p>

                    {{-- Step 1: Confirmation Summary --}}
                    <div id="confirmationStep" class="modal-step">
                        <div class="receipt-card p-4 mb-4">
                            <div class="text-center mb-4">
                                <div class="bg-primary bg-opacity-10 d-inline-block rounded-circle p-3 mb-2" style="width: 65px; height: 65px; display: flex; align-items: center; justify-content: center; margin: 0 auto;">
                                    <i class="bi bi-send-check-fill text-primary fs-15"></i>
                                </div>
                                <div class="fs-1 fw-bold text-primary" id="confirmAmount">₦0.00</div>
                                <h6 class="text-muted small mb-0">Total Amount</h6>
                            </div>

                            <div class="d-flex justify-content-between mb-3 align-items-center">
                                <span class="text-muted small">Recipient</span>
                                <span id="confirmAccountName" class="fw-bold text-end text-body">---</span>
                            </div>
                            <div class="d-flex justify-content-between mb-3 align-items-center">
                                <span class="text-muted small">Bank Name</span>
                                <span id="confirmBankName" class="fw-bold text-end text-body">---</span>
                            </div>
                            <div class="d-flex justify-content-between mb-3 align-items-center">
                                <span class="text-muted small">Account No</span>
                                <span id="confirmAccountNo" class="fw-bold text-end text-body">---</span>
                            </div>
                            <div class="d-flex justify-content-between align-items-center mt-3 pt-3" style="border-top: 1px dashed rgba(var(--bs-primary-rgb), 0.3);">
                                <span class="text-muted small">Transaction Fee</span>
                                <span class="fw-bold text-success text-end">Free</span>
                            </div>
                        </div>

                        <div class="d-grid mt-4">
                            <button type="button" id="btnGoToPin" class="btn btn-primary btn-lg fw-bold shadow-sm py-3 rounded-pill">
                                Confirm & Proceed <i class="bi bi-shield-lock ms-2"></i>
                            </button>
                        </div>
                    </div>

                    {{-- Step 2: PIN Entry --}}
                    <div id="pinStep" class="modal-step d-none">
                        <div class="text-center mb-4 pt-2">
                            <div class="bg-primary bg-opacity-10 rounded-circle p-3 mb-3" style="width: 65px; height: 65px; display: inline-flex; align-items: center; justify-content: center;">
                                <i class="bi bi-shield-lock-fill text-primary fs-15"></i>
                            </div>
                            <h5 class="fw-bold text-body">Transaction PIN</h5>
                            <p class="text-muted small">Enter your 5-digit security PIN to authorize</p>
                        </div>

                        <div class="d-flex justify-content-center mb-4">
                            <input type="password" name="pin" id="pinInput"
                                class="form-control premium-form-control text-center fw-bold fs-15 py-3 border-2 rounded-pill shadow-sm"
                                maxlength="5" inputmode="numeric" placeholder="•••••" required
                                style="letter-spacing: 12px; max-width: 250px;">
                        </div>

                        <small id="pinError" class="text-danger d-none text-center d-block fw-bold mb-3"></small>

                        <div class="row g-3">
                            <div class="col-4">
                                <button type="button" id="btnBackToConfirm" class="btn btn-light w-100 fw-bold py-3 rounded-pill bg-body-secondary border-0 text-body">Back</button>
                            </div>
                            <div class="col-8">
                                <button type="button" id="confirmPinBtn" class="btn btn-primary w-100 fw-bold shadow-sm py-3 rounded-pill">
                                    <span class="spinner-border spinner-border-sm me-2 d-none" id="pinLoader"></span>
                                    <span id="confirmPinText">Authorize Payout</span>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const accountNoInput = document.getElementById('account_no');
            const bankCodeSelect = document.getElementById('bank_code');
            const accountNameDisplay = document.getElementById('accountNameDisplay');
            const accountErrorDisplay = document.getElementById('accountErrorDisplay');
            const accountNameHidden = document.getElementById('account_name_hidden');
            const proceedBtn = document.getElementById('proceedBtn');
            const amountInput = document.getElementById('amount');

            // Preview Elements
            const bankPreview = document.getElementById('bankPreview');
            const previewBankName = document.getElementById('previewBankName');
            const previewAccountNo = document.getElementById('previewAccountNo');
            const selectedBankLogo = document.getElementById('selectedBankLogo');
            const defaultBankIcon = document.getElementById('defaultBankIcon');

            // Modal Elements
            const confirmationStep = document.getElementById('confirmationStep');
            const pinStep = document.getElementById('pinStep');
            const btnGoToPin = document.getElementById('btnGoToPin');
            const btnBackToConfirm = document.getElementById('btnBackToConfirm');
            const modalTitle = document.getElementById('modalTitle');
            const modalSubtitle = document.getElementById('modalSubtitle');

            let pinModal;
            try {
                pinModal = new bootstrap.Modal(document.getElementById('pinModal'));
            } catch (e) {
                console.error("Bootstrap Modal initialization failed:", e);
                // Fallback or alert if necessary
            }

            let verificationTimeout;

            function updateBankPreview() {
                const selectedOption = bankCodeSelect.options[bankCodeSelect.selectedIndex];
                const bankName = selectedOption ? selectedOption.text : 'Select a Bank';
                const bankUrl = selectedOption ? selectedOption.getAttribute('data-url') : null;
                const bgUrl = selectedOption ? selectedOption.getAttribute('data-bg') : null;
                const accountNo = accountNoInput.value;

                if (bankCodeSelect.value) {
                    bankPreview.style.setProperty('display', 'flex', 'important');
                    previewBankName.textContent = bankName;
                    previewAccountNo.textContent = accountNo || 'Enter account number';

                    if (bankUrl) {
                        selectedBankLogo.src = bankUrl;
                        selectedBankLogo.classList.remove('d-none');
                        defaultBankIcon.classList.add('d-none');
                    } else {
                        selectedBankLogo.classList.add('d-none');
                        defaultBankIcon.classList.remove('d-none');
                    }

                    if (bgUrl) {
                        bankPreview.style.backgroundImage = `url(${bgUrl})`;
                        bankPreview.classList.remove('alert-info');
                        bankPreview.style.backgroundColor = 'primary';
                        // Add overlay effect for readability
                        bankPreview.style.boxShadow = 'inset 0 0 0 2000px rgba(255, 255, 255, 0.85)';
                    } else {
                        bankPreview.style.backgroundImage = 'none';
                        bankPreview.classList.add('alert-info');
                        bankPreview.style.boxShadow = 'none';
                    }
                } else {
                    bankPreview.style.setProperty('display', 'none', 'important');
                }
            }

            function performVerification() {
                const bankCode = bankCodeSelect.value;
                const accountNo = accountNoInput.value;
                updateBankPreview();

                if (bankCode && accountNo.length === 10) {
                    accountNameDisplay.innerHTML = '<span class="spinner-border spinner-border-sm me-1"></span> Verifying...';
                    accountErrorDisplay.innerHTML = '';

                    fetch("{{ route('withdraw.verifyAccount') }}", {
                        method: "POST",
                        headers: {
                            "Content-Type": "application/json",
                            "X-CSRF-TOKEN": "{{ csrf_token() }}"
                        },
                        body: JSON.stringify({ bank_code: bankCode, account_no: accountNo })
                    })
                        .then(response => response.json())
                        .then(data => {
                            if (data.success) {
                                accountNameDisplay.innerHTML = '<i class="bi bi-check-circle-fill me-1"></i> ' + data.account_name;
                                accountNameHidden.value = data.account_name;
                                accountErrorDisplay.innerHTML = '';
                            } else {
                                accountNameDisplay.innerHTML = '';
                                accountErrorDisplay.innerHTML = '<i class="bi bi-x-circle-fill me-1"></i> ' + data.message;
                                accountNameHidden.value = '';
                            }
                        })
                        .catch(err => {
                            console.error("Verification failed:", err);
                            accountNameDisplay.innerHTML = '';
                            accountErrorDisplay.innerHTML = '<i class="bi bi-exclamation-triangle-fill me-1"></i> Connection failed';
                        });
                }
            }

            accountNoInput.addEventListener('input', () => {
                clearTimeout(verificationTimeout);
                updateBankPreview();
                if (accountNoInput.value.length === 10) {
                    verificationTimeout = setTimeout(performVerification, 500);
                } else {
                    accountNameDisplay.innerHTML = '';
                    accountErrorDisplay.innerHTML = '';
                    accountNameHidden.value = '';
                }
            });

            bankCodeSelect.addEventListener('change', performVerification);

            // Quick Select Recent Bank
            window.selectRecentBank = function (bankCode, accountNo, accountName) {
                bankCodeSelect.value = bankCode;
                accountNoInput.value = accountNo;

                // UI Feedback
                accountNoInput.classList.add('is-valid');
                setTimeout(() => accountNoInput.classList.remove('is-valid'), 2000);

                // Populate verification displays immediately
                accountNameDisplay.innerHTML = '<i class="bi bi-check-circle-fill me-1"></i> ' + accountName;
                accountNameHidden.value = accountName;
                accountErrorDisplay.innerHTML = '';

                updateBankPreview();

                // Focus on amount for user
                document.getElementById('amount').focus();

                // Scroll to form (mobile friendly)
                document.getElementById('withdrawForm').scrollIntoView({ behavior: 'smooth', block: 'center' });
            };

            // Modal Step Logic
            proceedBtn.addEventListener('click', function () {
                const amount = amountInput.value;
                const bankName = bankCodeSelect.options[bankCodeSelect.selectedIndex].text;
                const accountNo = accountNoInput.value;
                const accountName = accountNameHidden.value;

                if (!amount || amount < 100) {
                    alert('Please enter a valid amount (Min ₦100)');
                    return;
                }

                if (!accountName) {
                    alert('Please wait for account name verification.');
                    return;
                }

                // Populate summary
                document.getElementById('confirmAccountName').textContent = accountName;
                document.getElementById('confirmBankName').textContent = bankName;
                document.getElementById('confirmAccountNo').textContent = accountNo;
                document.getElementById('confirmAmount').textContent = '₦' + parseFloat(amount).toLocaleString(undefined, { minimumFractionDigits: 2 });

                // Reset Modal to Step 1
                confirmationStep.classList.remove('d-none');
                pinStep.classList.add('d-none');
                modalTitle.textContent = 'Confirm Transaction';
                modalSubtitle.textContent = 'Please review details carefully';

                if (pinModal) {
                    pinModal.show();
                } else {
                    // Fallback to data attributes if bootstrap object is missing
                    const modalEl = document.getElementById('pinModal');
                    const bsModal = new bootstrap.Modal(modalEl);
                    bsModal.show();
                }
            });

            btnGoToPin.addEventListener('click', function () {
                confirmationStep.classList.add('d-none');
                pinStep.classList.remove('d-none');
                modalTitle.textContent = 'Authorize Payout';
                modalSubtitle.textContent = 'Step 2 of 2: Security PIN';
                document.getElementById('pinInput').focus();
            });

            btnBackToConfirm.addEventListener('click', function () {
                pinStep.classList.add('d-none');
                confirmationStep.classList.remove('d-none');
                modalTitle.textContent = 'Confirm Transaction';
                modalSubtitle.textContent = 'Please review details carefully';
            });

            document.getElementById('confirmPinBtn').addEventListener('click', function () {
                const confirmBtn = this;
                const loader = document.getElementById('pinLoader');
                const confirmText = document.getElementById('confirmPinText');
                const pinError = document.getElementById('pinError');
                const pin = document.getElementById('pinInput').value.trim();

                if (!pin || pin.length !== 5) {
                    pinError.innerHTML = 'Please enter 5-digit PIN.';
                    pinError.classList.remove('d-none');
                    return;
                }

                confirmBtn.disabled = true;
                loader.classList.remove('d-none');
                confirmText.textContent = "Verifying...";
                pinError.classList.add('d-none');

                // Verify PIN via AJAX
                fetch("{{ route('verify.pin') }}", {
                    method: "POST",
                    headers: {
                        "Content-Type": "application/json",
                        "X-CSRF-TOKEN": "{{ csrf_token() }}"
                    },
                    body: JSON.stringify({ pin })
                })
                    .then(response => response.json())
                    .then(data => {
                        if (data.valid) {
                            const form = document.getElementById('withdrawForm');
                            const pinInputHidden = document.createElement('input');
                            pinInputHidden.type = 'hidden';
                            pinInputHidden.name = 'pin';
                            pinInputHidden.value = pin;
                            form.appendChild(pinInputHidden);
                            form.submit();
                        } else {
                            pinError.innerHTML = 'Incorrect PIN. Try again.';
                            pinError.classList.remove('d-none');
                            confirmBtn.disabled = false;
                            loader.classList.add('d-none');
                            confirmText.textContent = "Authorize Now";
                            document.getElementById('pinInput').value = '';
                        }
                    })
                    .catch(err => {
                        console.error("PIN check failed:", err);
                        pinError.innerHTML = 'System error.';
                        pinError.classList.remove('d-none');
                        confirmBtn.disabled = false;
                        loader.classList.add('d-none');
                        confirmText.textContent = "Authorize Now";
                    });
            });
        });
    </script>
</x-app-layout>