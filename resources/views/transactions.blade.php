<x-app-layout>
    <title>Arewa Smart - Transactions</title>

    <div class="page-body">
        <div class="container-fluid">
            <!-- Page Header -->
            <div class="page-title mb-3">
                <div class="row">
                    <div class="col-12 col-sm-6">
                        <h3 class="fw-bold text-primary">Transaction History</h3>
                        <p class="text-muted small mb-0">
                            View and filter your wallet transactions and service history.
                        </p>
                    </div>
                </div>
            </div>

            <!-- Floating AI Assistant Button (Bottom Right) -->
            <button id="globalAiSummarize"
                class="btn rounded-circle shadow-lg position-fixed d-flex align-items-center justify-content-center transition-all hover-translate-y"
                style="bottom: 30px; right: 30px; width: 60px; height: 60px; z-index: 1050; border: 2px solid white; background: linear-gradient(135deg, #4285F4 0%, #9B72CB 50%, #D96570 100%); color: white;"
                title="AI Transaction Assistant">
                <i class="bi bi-stars fs-2"></i>
            </button>

            <!-- Loading Indicator Overlay (Removed in favor of internal modal loading) -->
            <div id="globalAiInsightsArea" class="d-none"></div>

            <!-- Global AI Modal (Dedicated for whole list summary) -->
            <div class="modal fade" id="globalAiModal" tabindex="-1" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered modal-lg">
                    <div class="modal-content border-0 shadow-lg rounded-4 overflow-hidden">
                        <div class="modal-header py-3 text-white bg-primary">
                            <div class="d-flex align-items-center gap-2">
                                <div class="bg-white bg-opacity-25 rounded-circle p-2 d-flex align-items-center justify-content-center"
                                    style="width: 40px; height: 40px;">
                                    <i class="bi bi-stars fs-15"></i>
                                </div>
                                <h5 class="modal-title fw-bold mb-0">AI Assistant</h5>
                            </div>
                            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                        </div>
                        <div class="modal-body p-4 bg-light" id="globalAiModalBody"
                            style="max-height: 450px; overflow-y: auto;">
                            <!-- AI Content Injected Here -->
                            <div id="globalChatContent"></div>
                        </div>
                        <div class="modal-footer bg-white border-top flex-column align-items-stretch p-3">
                            <div class="input-group shadow-sm rounded-pill overflow-hidden border">
                                <input type="text" id="globalAiQuestion" class="form-control border-0 px-3 py-2"
                                    placeholder="Ask about these transactions..."
                                    onkeydown="if(event.key === 'Enter') submitGlobalQuery()">
                                <button onclick="submitGlobalQuery()" class="btn btn-primary px-3 border-0">
                                    <i class="bi bi-send-fill text-white"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <!-- Transaction History -->
                <div class="col-12 col-xl-12 mb-4">
                    <div class="card shadow-sm border-0 rounded-3 h-100">
                        <div
                            class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                            <h5 class="mb-0 fw-bold"><i class="bi bi-receipt me-2"></i>Transactions</h5>
                            <span class="badge bg-light text-primary fw-semibold">Arewa Smart</span>
                        </div>
                        <div class="card-body">

                            <!-- Filter Form -->
                            <form class="row g-3 mb-4" method="GET" action="{{ route('transactions') }}">
                                <div class="col-12 col-md-3">
                                    <label class="form-label small fw-bold text-muted">Transaction Type</label>
                                    <select class="form-select" name="type">
                                        <option value="">All Types</option>
                                        <option value="credit" {{ request('type') == 'credit' ? 'selected' : '' }}>Credit
                                        </option>
                                        <option value="debit" {{ request('type') == 'debit' ? 'selected' : '' }}>Debit
                                        </option>
                                        <option value="refund" {{ request('type') == 'refund' ? 'selected' : '' }}>Refund
                                        </option>
                                    </select>
                                </div>
                                <div class="col-12 col-md-3">
                                    <label class="form-label small fw-bold text-muted">Service Type</label>
                                    <select class="form-select" name="service_type">
                                        <option value="">All Services</option>
                                        <option value="Airtime" {{ request('service_type') == 'Airtime' ? 'selected' : '' }}>Airtime</option>
                                        <option value="Data" {{ request('service_type') == 'Data' ? 'selected' : '' }}>
                                            Data</option>
                                        <option value="Electricity" {{ request('service_type') == 'Electricity' ? 'selected' : '' }}>Electricity</option>
                                        <option value="Cable" {{ request('service_type') == 'Cable' ? 'selected' : '' }}>
                                            Cable TV</option>
                                        <option value="Education" {{ request('service_type') == 'Education' ? 'selected' : '' }}>Education (WAEC/NECO/JAMB)</option>
                                        <option value="Funding" {{ request('service_type') == 'Funding' ? 'selected' : '' }}>Wallet Funding</option>
                                        <option value="Funding" {{ request('service_type') == 'Funding' ? 'selected' : '' }}>Wallet Debit</option>
                                        <option value="VNIN_TO_NIBSS" {{ request('service_type') == 'VNIN_TO_NIBSS' ? 'selected' : '' }}>VNIN TO NIBSS</option>
                                        <option value="BVN_SEARCH" {{ request('service_type') == 'BVN_SEARCH' ? 'selected' : '' }}>BVN Search</option>
                                        <option value="BVN_MODIFICATION" {{ request('service_type') == 'BVN_MODIFICATION' ? 'selected' : '' }}>BVN Modification</option>
                                        <option value="CRM" {{ request('service_type') == 'CRM' ? 'selected' : '' }}>CRM
                                        </option>
                                        <option value="BVN_USER" {{ request('service_type') == 'BVN_USER' ? 'selected' : '' }}>BVN User</option>
                                        <option value="APPROVAL_REQUEST" {{ request('service_type') == 'APPROVAL_REQUEST' ? 'selected' : '' }}>Approval Request</option>
                                        <option value="AFFIDAVIT" {{ request('service_type') == 'AFFIDAVIT' ? 'selected' : '' }}>Affidavit</option>
                                        <option value="NIN_SELFSERVICE" {{ request('service_type') == 'NIN_SELFSERVICE' ? 'selected' : '' }}>NIN Self Service</option>
                                        <option value="NIN_VALIDATION" {{ request('service_type') == 'NIN_VALIDATION' ? 'selected' : '' }}>NIN Validation</option>
                                        <option value="IPE" {{ request('service_type') == 'IPE' ? 'selected' : '' }}>IPE
                                        </option>
                                        <option value="NIN_MODIFICATION" {{ request('service_type') == 'NIN_MODIFICATION' ? 'selected' : '' }}>NIN Modification</option>
                                        <option value="TIN_INDIVIDUAL" {{ request('service_type') == 'TIN_INDIVIDUAL' ? 'selected' : '' }}>TIN Individual</option>
                                        <option value="TIN_CORPORATE" {{ request('service_type') == 'TIN_CORPORATE' ? 'selected' : '' }}>TIN Corporate</option>
                                        <option value="CAC" {{ request('service_type') == 'CAC' ? 'selected' : '' }}>CAC
                                        </option>
                                        <option value="not_selected" {{ request('service_type') == 'not_selected' ? 'selected' : '' }}>Not Selected</option>
                                    </select>
                                </div>
                                <div class="col-12 col-md-2">
                                    <label class="form-label small fw-bold text-muted">From Date</label>
                                    <input type="date" class="form-control" name="date_from"
                                        value="{{ request('date_from') }}">
                                </div>
                                <div class="col-12 col-md-2">
                                    <label class="form-label small fw-bold text-muted">To Date</label>
                                    <input type="date" class="form-control" name="date_to"
                                        value="{{ request('date_to') }}">
                                </div>
                                <div class="col-12 col-md-2 d-flex align-items-end">
                                    <button class="btn btn-primary w-100 fw-semibold" type="submit">
                                        <i class="bi bi-filter me-1"></i> Filter
                                    </button>
                                </div>
                            </form>

                            <!-- Transactions Table -->
                            <div class="table-responsive">
                                <table class="table table-hover align-middle mb-0">
                                    <thead class="table-light">
                                        <tr>
                                            <th>Date</th>
                                            <th>Reference</th>
                                            <th>Description</th>
                                            <th class="text-center">Type</th>
                                            <th class="text-end">Amount</th>
                                            <th class="text-center">Status</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse ($transactions as $index => $transaction)
                                            <tr style="cursor: pointer;" data-bs-toggle="modal"
                                                data-bs-target="#txModal{{ $transaction->id }}" class="transaction-row">
                                                <td>
                                                    <div class="d-flex flex-column">
                                                        <span
                                                            class="fw-semibold">{{ $transaction->created_at->format('d M Y') }}</span>
                                                    </div>
                                                </td>
                                                <td><span
                                                        class="font-monospace small text-muted">{{ Str::limit($transaction->transaction_ref, 15) }}</span>
                                                </td>
                                                <td>
                                                    <span title="{{ $transaction->description }}" class="fw-medium">
                                                        {{ Str::limit($transaction->description, 30) }}
                                                    </span>
                                                </td>
                                                <td class="text-center">
                                                    @if(in_array($transaction->type, ['credit', 'refund', 'bonus', 'manual_credit']))
                                                        <span class="badge bg-success-subtle text-success fw-semibold">
                                                            {{ $transaction->type == 'manual_credit' ? 'Credit' : ucfirst($transaction->type) }}
                                                        </span>
                                                    @elseif(in_array($transaction->type, ['debit', 'manual_debit']))
                                                        <span class="badge bg-danger-subtle text-danger fw-semibold">
                                                            {{ $transaction->type == 'manual_debit' ? 'Debit' : 'Debit' }}
                                                        </span>
                                                    @else
                                                        <span
                                                            class="badge bg-info-subtle text-info fw-semibold">{{ ucfirst($transaction->type) }}</span>
                                                    @endif
                                                </td>
                                                <td
                                                    class="text-end fw-bold {{ in_array($transaction->type, ['credit', 'refund', 'bonus', 'manual_credit']) ? 'text-success' : 'text-danger' }}">
                                                    {{ in_array($transaction->type, ['credit', 'refund', 'bonus', 'manual_credit']) ? '+' : '-' }}₦{{ number_format($transaction->amount, 2) }}
                                                </td>
                                                <td class="text-center">
                                                    <span
                                                        class="badge bg-{{ $transaction->status == 'completed' || $transaction->status == 'successful' ? 'success' : ($transaction->status == 'failed' ? 'danger' : 'warning') }}-subtle text-{{ $transaction->status == 'completed' || $transaction->status == 'successful' ? 'success' : ($transaction->status == 'failed' ? 'danger' : 'warning') }} fw-semibold">
                                                        {{ ucfirst($transaction->status) }}
                                                    </span>
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="8" class="text-center py-5">
                                                    <div class="d-flex flex-column align-items-center">
                                                        <i class="bi bi-inbox text-muted fs-15 mb-3"></i>
                                                        <h6 class="fw-bold text-muted">No transactions found</h6>
                                                        <p class="text-muted small">Try adjusting your filters.</p>
                                                    </div>
                                                </td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>

                            <div class="mt-4 d-flex justify-content-center">
                                {{ $transactions->withQueryString()->links('vendor.pagination.custom') }}
                            </div>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Transaction Detail Modals (Placed outside the table to prevent shaking) -->
    @foreach ($transactions as $transaction)
        <div class="modal fade" id="txModal{{ $transaction->id }}" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered modal-lg">
                <div class="modal-content border-0 shadow-lg rounded-4 overflow-hidden">
                    <div class="modal-header bg-primary text-white py-3">
                        <div class="d-flex align-items-center gap-2">
                            <div class="bg-white bg-opacity-25 rounded-circle p-2 d-flex align-items-center justify-content-center"
                                style="width: 40px; height: 40px;">
                                <i class="bi bi-receipt fs-15"></i>
                            </div>
                            <div>
                                <h5 class="modal-title fw-bold mb-0">Transaction Detail</h5>
                                <small class="text-white-50">Ref: {{ $transaction->transaction_ref }}</small>
                            </div>
                        </div>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body p-0 bg-white">
                        <div class="p-4">
                            <!-- Detail List -->
                            <div class="row g-3 mb-4">
                                <div class="col-6 col-md-3">
                                    <label class="small text-muted d-block mb-1">Amount</label>
                                    <div
                                        class="fw-bold fs-15 {{ in_array($transaction->type, ['credit', 'refund', 'bonus', 'manual_credit']) ? 'text-success' : 'text-danger' }}">
                                        {{ in_array($transaction->type, ['credit', 'refund', 'bonus', 'manual_credit']) ? '+' : '-' }}₦{{ number_format($transaction->amount, 2) }}
                                    </div>
                                </div>
                                <div class="col-6 col-md-3">
                                    <label class="small text-muted d-block mb-1">Status</label>
                                    <span
                                        class="badge bg-{{ $transaction->status == 'completed' || $transaction->status == 'successful' ? 'success' : ($transaction->status == 'failed' ? 'danger' : 'warning') }}">
                                        {{ ucfirst($transaction->status) }}
                                    </span>
                                </div>
                                <div class="col-12 col-md-6">
                                    <label class="small text-muted d-block mb-1">Date & Time</label>
                                    <div class="fw-semibold text-dark">
                                        {{ $transaction->created_at->format('d M Y, h:i A') }}
                                    </div>
                                </div>
                            </div>

                            <div
                                class="admin-comment-card p-3 rounded-3 bg-light border-start border-4 border-primary mb-4">
                                <label class="small text-uppercase text-muted fw-bold mb-2 d-block">Description</label>
                                <p class="text-dark mb-0 fw-medium">{{ $transaction->description }}</p>
                            </div>

                            <!-- AI Unified Section (Using standard card styling) -->
                            <div id="aiUnifiedSection{{ $transaction->id }}" class="mt-3">
                                <!-- AI Content injected here -->
                            </div>

                            <!-- AI Input (Using project standard look) -->
                            <div id="aiInputWrapper{{ $transaction->id }}" class="d-none mt-3">
                                <div class="input-group shadow-sm rounded-pill overflow-hidden border">
                                    <input type="text" id="aiQuestion{{ $transaction->id }}"
                                        class="form-control border-0 px-3 py-2" placeholder="Ask follow-up details..."
                                        onkeydown="if(event.key === 'Enter') submitUserQuery('{{ $transaction->id }}', '{{ $transaction->description }}', '{{ $transaction->transaction_ref }}')">
                                    <button
                                        onclick="submitUserQuery('{{ $transaction->id }}', '{{ $transaction->description }}', '{{ $transaction->transaction_ref }}')"
                                        class="btn btn-primary px-3 border-0">
                                        <i class="bi bi-send-fill text-white"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div
                        class="modal-footer bg-light border-top d-flex justify-content-between align-items-center py-3 px-4">
                        <div class="d-flex gap-2 w-100 justify-content-between align-items-center">
                            <button type="button" id="summarizeBtn{{ $transaction->id }}"
                                onclick="performSummarization('{{ $transaction->id }}', '{{ $transaction->description }}', '{{ $transaction->transaction_ref }}')"
                                class="btn btn-primary rounded-pill px-4 shadow-sm border-0 transition-all hover-translate-y"
                                style="background: linear-gradient(135deg, #F26522 0%, #ff8c52 100%);">
                                <i class="bi bi-stars me-2 text-white"></i> Summarize with AI
                            </button>
                            <button type="button" class="btn btn-outline-secondary rounded-pill px-4"
                                data-bs-dismiss="modal">Close</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endforeach

    <script>
        let chatHistories = {};
        let globalSummaryText = "";

        function performSummarization(id, description, reference) {
            const btn = document.getElementById(`summarizeBtn${id}`);
            const aiSection = document.getElementById(`aiUnifiedSection${id}`);
            const inputWrapper = document.getElementById(`aiInputWrapper${id}`);

            btn.classList.add('disabled');
            btn.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>AI';

            addBubble(id, 'AI', '✨ *Workking*');

            fetch("{{ route('ai.summarize') }}", {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({ comment: description, reference: reference })
            })
                .then(res => res.json())
                .then(data => {
                    aiSection.lastElementChild.remove();
                    if (data.success) {
                        addBubble(id, 'ai', data.answer);
                        chatHistories[id] = [{ role: 'assistant', content: data.answer }];
                        btn.classList.add('d-none');
                        inputWrapper.classList.remove('d-none');
                    } else {
                        addBubble(id, 'ai', '⚠️ ' + (data.message || 'AI service error.'));
                    }
                })
                .catch(err => {
                    if (aiSection.lastElementChild) aiSection.lastElementChild.remove();
                    addBubble(id, 'ai', '❌ Connection error.');
                })
                .finally(() => {
                    btn.classList.remove('disabled');
                    btn.innerHTML = '<i class="bi bi-stars me-2 text-white"></i> Summarize with AI';
                });
        }

        function submitUserQuery(id, description, reference) {
            const input = document.getElementById(`aiQuestion${id}`);
            const q = input.value.trim();
            if (!q) return;

            input.value = '';
            addBubble(id, 'user', q);
            addBubble(id, 'ai', '<div class="spinner-border spinner-border-sm"></div>');

            fetch("{{ route('ai.ask') }}", {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({
                    comment: description,
                    question: q,
                    history: chatHistories[id] || [],
                    reference: reference
                })
            })
                .then(res => res.json())
                .then(data => {
                    const aiSection = document.getElementById(`aiUnifiedSection${id}`);
                    aiSection.lastElementChild.remove();
                    if (data.success) {
                        addBubble(id, 'ai', data.answer);
                        if (!chatHistories[id]) chatHistories[id] = [];
                        chatHistories[id].push({ role: 'user', content: q }, { role: 'assistant', content: data.answer });
                    } else {
                        addBubble(id, 'ai', 'Please try again.');
                    }
                })
                .catch(err => {
                    const aiSection = document.getElementById(`aiUnifiedSection${id}`);
                    if (aiSection.lastElementChild) aiSection.lastElementChild.remove();
                    addBubble(id, 'ai', 'Connection lost.');
                });
        }

        // --- GLOBAL AI ANALYZER ---
        document.getElementById('globalAiSummarize')?.addEventListener('click', function () {
            const btn = this;
            const globalModal = new bootstrap.Modal(document.getElementById('globalAiModal'));
            const globalContent = document.getElementById('globalChatContent');

            // Open modal instantly
            globalModal.show();
            globalContent.innerHTML = ''; // Start fresh
            addGlobalBubble('ai', '<div class="d-flex align-items-center gap-2 text-primary fw-bold"><div class="spinner-border spinner-border-sm"></div> Analysing Activity...</div>');

            // Extract visible context
            let txSummary = "Summarize my activity from these transactions:\n";
            document.querySelectorAll('.transaction-row').forEach(row => {
                const desc = row.querySelector('.fw-medium').innerText;
                const amt = row.querySelector('.fw-bold').innerText;
                txSummary += `- ${desc}: ${amt}\n`;
            });
            globalSummaryText = txSummary;

            fetch("{{ route('ai.summarize') }}", {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({ comment: txSummary })
            })
                .then(res => res.json())
                .then(data => {
                    globalContent.innerHTML = ''; // Clear loading
                    if (data.success) {
                        addGlobalBubble('ai', data.answer);
                        chatHistories['global'] = [{ role: 'assistant', content: data.answer }];
                    } else {
                        addGlobalBubble('ai', '⚠️ Could not generate summary. Please try again.');
                    }
                })
                .catch(() => {
                    globalContent.innerHTML = '';
                    addGlobalBubble('ai', '❌ Network error.');
                });
        });

        function submitGlobalQuery() {
            const input = document.getElementById('globalAiQuestion');
            const q = input.value.trim();
            if (!q) return;

            input.value = '';
            addGlobalBubble('user', q);
            addGlobalBubble('ai', '<div class="spinner-border spinner-border-sm"></div>');

            fetch("{{ route('ai.ask') }}", {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({
                    comment: globalSummaryText,
                    question: q,
                    history: chatHistories['global'] || []
                })
            })
                .then(res => res.json())
                .then(data => {
                    const contentArea = document.getElementById('globalChatContent');
                    contentArea.lastElementChild.remove();
                    if (data.success) {
                        addGlobalBubble('ai', data.answer);
                        if (!chatHistories['global']) chatHistories['global'] = [];
                        chatHistories['global'].push({ role: 'user', content: q }, { role: 'assistant', content: data.answer });
                    }
                });
        }

        function addBubble(id, type, text) {
            const aiSection = document.getElementById(`aiUnifiedSection${id}`);
            const b = document.createElement('div');
            // Use standard Bootstrap alert classes for professional look without custom CSS
            b.className = `alert ${type === 'ai' ? 'alert-primary' : 'bg-secondary-subtle'} mb-3 border-0 shadow-sm transition-all`;
            b.style.fontSize = '0.9rem';
            b.innerHTML = text.replace(/\n/g, '<br>');
            aiSection.appendChild(b);

            const modalBody = aiSection.closest('.modal-body');
            modalBody.scrollTo({ top: modalBody.scrollHeight, behavior: 'smooth' });
        }

        function addGlobalBubble(type, text) {
            const content = document.getElementById('globalChatContent');
            const b = document.createElement('div');
            b.className = `card mb-3 border-0 shadow-sm ${type === 'ai' ? 'bg-white border-start border-4 border-primary' : 'bg-light ms-auto'}`;
            b.style.maxWidth = type === 'ai' ? '100%' : '85%';
            b.innerHTML = `<div class="card-body p-3">${text.replace(/\n/g, '<br>')}</div>`;
            content.appendChild(b);

            const body = document.getElementById('globalAiModalBody');
            body.scrollTo({ top: body.scrollHeight, behavior: 'smooth' });
        }
    </script>
</x-app-layout>