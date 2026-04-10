<x-app-layout>
    <title>Arewa Smart - {{ $title ?? 'SME Data' }}</title>

    <div class="container-fluid px-0 px-md-3 py-3">
        <div class="row g-3 g-md-4 justify-content-center">
            
            {{-- Left Column: SME Data Form --}}
            <div class="col-12 col-xl-6">
                <div class="card shadow-lg border-0 rounded-4 overflow-hidden mb-4 bounce-in">
                    <div class="card-header bg-success text-white p-3 p-md-4">
                        <div class="d-flex justify-content-between align-items-center">
                            <h5 class="mb-0 fw-bold"><i class="bi bi-globe2 me-2"></i>SME Data Service</h5>
                            <span class="badge bg-white text-success rounded-pill px-3 py-2">
                                Balance: ₦{{ number_format($wallet->balance ?? 0, 2) }}
                            </span>
                        </div>
                    </div>

                    <div class="card-body p-3 p-md-4">
                        {{-- Flash Messages --}}
                        @if (session('success'))
                            <div class="alert alert-success alert-dismissible fade show text-center" role="alert">
                                {!! session('success') !!}
                                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                            </div>
                        @endif

                        @if (session('error'))
                            <div class="alert alert-danger alert-dismissible fade show text-center" role="alert">
                                {{ session('error') }}
                                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                            </div>
                        @endif

                        <form id="buySmeDataForm" method="POST" action="{{ route('buy-sme-data.submit') }}">
                            @csrf

                            <div class="row mb-3">
                                <div class="col-md-6 mb-3 mb-md-0">
                                    <label class="form-label fw-semibold small">Network</label>
                                    <select name="network" id="sme_network" class="form-select border-light shadow-sm" required>
                                        <option value="">Choose Network</option>
                                        @foreach ($networks as $network)
                                            <option value="{{ $network->network }}">{{ $network->network }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label fw-semibold small">Data Type</label>
                                    <select name="type" id="sme_type" class="form-select border-light shadow-sm" required>
                                        <option value="">Select Type</option>
                                    </select>
                                </div>
                            </div>

                            <div class="mb-3">
                                <label class="form-label fw-semibold small">Select Plan</label>
                                <select name="plan" id="sme_plan" class="form-select border-light shadow-sm" required>
                                    <option value="">Choose a plan</option>
                                </select>
                            </div>

                            <div class="row mb-4">
                                <div class="col-md-6 mb-3 mb-md-0">
                                    <label class="form-label fw-semibold small">Phone Number</label>
                                    <input type="text" id="sme_mobile" name="mobileno"
                                           class="form-control border-light shadow-sm"
                                           placeholder="08012345678"
                                           maxlength="11" required>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label fw-semibold small">Payable Amount</label>
                                    <div class="input-group">
                                        <span class="input-group-text bg-white border-light shadow-sm">₦</span>
                                        <input type="text" id="sme_amount" name="amount" readonly class="form-control bg-light border-light shadow-sm fw-bold text-success" placeholder="0.00" />
                                    </div>
                                </div>
                            </div>

                            <div class="d-grid">
                                <button type="button" class="btn btn-success btn-lg fw-bold rounded-pill shadow-sm py-3"
                                        data-bs-toggle="modal" data-bs-target="#pinModal">
                                    Purchase SME Data <i class="bi bi-chevron-right ms-1"></i>
                                </button>
                            </div>
                        </form>
                    </div>

                    {{-- Reliable SME Plans Footer --}}
                    <div class="card-footer bg-light border-0 p-3 p-md-4">
                        <small class="fw-bold text-muted d-block mb-3"><i class="bi bi-check-all text-success me-1"></i>VERIFIED SME BUNDLES</small>
                        <div class="d-flex flex-wrap gap-2">
                            @forelse($reliablePlans as $plan)
                                <button type="button" class="btn btn-white border shadow-sm btn-sm rounded-pill py-2 px-3 fw-semibold small" 
                                        onclick="applySmePlan('{{ $plan->network }}', '{{ $plan->plan_type }}', '{{ $plan->data_id }}')">
                                    {{ $plan->network }} {{ $plan->size }} <span class="text-success fs-7">₦{{ number_format($plan->variation_amount, 0) }}</span>
                                </button>
                            @empty
                                <small class="text-muted">No featured bundles available.</small>
                            @endforelse
                        </div>
                    </div>
                </div>
            </div>

            {{-- Right Column: AI Smart Chatbot & History --}}
            <div class="col-12 col-xl-6">
                <div class="card shadow-lg border-0 rounded-4 overflow-hidden h-100 d-flex flex-column shadow-hover transition-all" style="min-height: 600px;">
                    <div class="card-header bg-dark text-white p-3 p-md-4 d-flex align-items-center justify-content-between">
                        <div class="d-flex align-items-center gap-3">
                            <div class="bg-success rounded-circle p-2 shadow-sm" style="width: 42px; height: 42px; display: flex; align-items: center; justify-content: center;">
                                <i class="bi bi-robot fs-5"></i>
                            </div>
                            <div>
                                <h6 class="mb-0 fw-bold">Arewa Smart AI Guide</h6>
                                <small class="text-success small fw-bold"><i class="bi bi-circle-fill fs-8 me-1"></i> Online Assistant</small>
                            </div>
                        </div>
                        <div class="d-flex gap-2">
                            <button class="btn btn-sm btn-outline-light border-0 rounded-circle" data-bs-toggle="collapse" data-bs-target="#smeHistoryCollapse" title="Toggle History">
                                <i class="bi bi-clock-history"></i>
                            </button>
                            <button class="btn btn-sm btn-outline-light border-0 rounded-circle" onclick="clearChat()" title="Clear Chat"><i class="bi bi-trash"></i></button>
                        </div>
                    </div>

                    {{-- Collapsible History Section --}}
                    <div class="collapse show" id="smeHistoryCollapse">
                        <div class="bg-white border-bottom shadow-sm">
                            <div class="p-3 border-bottom d-flex justify-content-between align-items-center bg-light-subtle">
                                <small class="fw-bold text-muted text-uppercase mb-0 fs-7 px-2"><i class="bi bi-clock-history me-2"></i>Recent SME Top-ups</small>
                                <span class="badge bg-success rounded-pill small">{{ count($recentPurchases) }}</span>
                            </div>
                            <div class="table-responsive" style="max-height: 180px; overflow-y: auto;">
                                <table class="table table-hover align-middle mb-0">
                                    <thead class="bg-light fs-8 sticky-top">
                                        <tr>
                                            <th class="border-0 px-3 py-2">Info</th>
                                            <th class="border-0 py-2">Number</th>
                                            <th class="border-0 py-2">Amount</th>
                                            <th class="border-0 text-end px-3 py-2">Status</th>
                                        </tr>
                                    </thead>
                                    <tbody class="fs-7">
                                        @forelse($recentPurchases as $history)
                                            @php
                                                $meta = json_decode($history->metadata, true);
                                                $phone = $meta['phone'] ?? substr($history->description, -11);
                                                $network = $meta['network'] ?? 'Data';
                                            @endphp
                                            <tr onclick="repeatSme('{{ $network }}', '{{ $phone }}')" style="cursor: pointer;">
                                                <td class="px-3 fw-bold text-uppercase py-2 text-success small">{{ $network }}</td>
                                                <td class="py-2 small">{{ $phone }}</td>
                                                <td class="py-2 text-dark fw-semibold small">₦{{ number_format($history->amount, 0) }}</td>
                                                <td class="text-end px-3 py-2">
                                                    <span class="badge rounded-pill {{ $history->status == 'completed' ? 'bg-success-subtle text-success' : 'bg-danger-subtle text-danger' }}" style="font-size: 10px;">
                                                        {{ ucfirst($history->status) }}
                                                    </span>
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="4" class="text-center py-4 text-muted small">No recent SME purchases.</td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>

                    <div class="card-body bg-light-subtle flex-grow-1 overflow-auto p-3 p-md-4" id="aiChatWindow">
                        <div class="d-flex gap-2 mb-4 animate-fade-in">
                            <div class="bg-success text-white rounded-circle p-1 align-self-start shadow-sm" style="width:28px;height:28px;font-size:12px;display:flex;align-items:center;justify-content:center; flex-shrink:0;">AS</div>
                            <div class="bg-white p-3 rounded-4 rounded-start-0 shadow-sm border small" style="max-width: 85%;">
                                <p class="mb-0 text-dark">Hello! I'm your SME Data specialist. SME bundles are affordable and last up to 30 days. Need recommendations for MTN or Airtel?</p>
                            </div>
                        </div>
                    </div>

                    <div id="aiTypingIndicator" class="px-4 py-2 d-none">
                        <small class="text-muted"><i class="bi bi-stars spin me-1"></i> Analyzing SME options...</small>
                    </div>

                    <div class="card-footer bg-white border-top p-3 p-md-4 mt-auto">
                        <div class="d-flex gap-2 mb-3 overflow-auto pb-2 no-scrollbar">
                            <button class="btn btn-xs btn-outline-success rounded-pill text-nowrap px-3 shadow-sm" onclick="askAi('Which MTN SME plan is best?')">MTN SME</button>
                            <button class="btn btn-xs btn-outline-success rounded-pill text-nowrap px-3 shadow-sm" onclick="askAi('Show me Airtel Gifting plans')">Airtel Gift</button>
                            <button class="btn btn-xs btn-outline-success rounded-pill text-nowrap px-3 shadow-sm" onclick="askAi('Cheapest Corporate Data?')">Corporate</button>
                        </div>
                        <div class="input-group bg-light rounded-pill p-1 border shadow-sm focus-within-shadow">
                            <input type="text" id="aiInput" class="form-control border-0 bg-transparent ps-3" placeholder="Ask about SME data...">
                            <button class="btn btn-success rounded-circle p-2 mx-1 shadow-sm d-flex align-items-center justify-content-center" id="sendAiBtn" style="width:38px;height:38px;">
                                <i class="bi bi-send-fill fs-14"></i>
                            </button>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>

    {{-- PIN Modal --}}
    @include('pages.pin')

    <style>
        .fs-8 { font-size: 0.7rem; }
        .fs-7 { font-size: 0.8rem; }
        .fs-14 { font-size: 14px; }
        .spin { animation: fa-spin 2s infinite linear; }
        @keyframes fa-spin { 0% { transform: rotate(0deg); } 100% { transform: rotate(359deg); } }
        .no-scrollbar::-webkit-scrollbar { display: none; }
        .shadow-hover:hover { box-shadow: 0 0.5rem 1.5rem rgba(0, 0, 0, 0.1) !important; }
        .transition-all { transition: all 0.3s ease; }
        .btn-xs { padding: 0.35rem 0.75rem; font-size: 0.75rem; font-weight: 600; }
        .focus-within-shadow:focus-within { box-shadow: 0 0 0 0.25rem rgba(25, 135, 84, 0.1) !important; border-color: #198754 !important; }
        .animate-fade-in { animation: fadeIn 0.5s ease; }
        @keyframes fadeIn { from { opacity: 0; transform: translateY(10px); } to { opacity: 1; transform: translateY(0); } }
        .bounce-in { animation: bounceIn 0.6s cubic-bezier(0.68, -0.55, 0.265, 1.55); }
        @keyframes bounceIn { 0% { transform: scale(0.9); opacity: 0; } 100% { transform: scale(1); opacity: 1; } }
    </style>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        let convHistory = [];

        $(document).ready(function () {
            // SME AJAX Selection Logic
            $("#sme_network").change(function () {
                let service_id = $(this).val();
                if(!service_id) return;
                $("#sme_type").empty().append("<option value=''>Loading...</option>");
                
                $.ajax({
                    type: "get",
                    url: "{{ url('sme-data/fetch-data-type') }}",
                    data: { id: service_id },
                    success: function (response) {
                        $("#sme_type").empty().append("<option value=''>Data Type</option>");
                        response.forEach(item => {
                            $("#sme_type").append(`<option value="${item.plan_type}">${item.plan_type}</option>`);
                        });
                        $("#sme_plan").empty().append("<option value=''>Select Plan</option>");
                        $("#sme_amount").val("");
                    }
                });
            });

            $("#sme_type").change(function () {
                let service_id = $("#sme_network").val();
                let type = $(this).val();
                if(!service_id || !type) return;
                $("#sme_plan").empty().append("<option value=''>Fetching plans...</option>");

                $.ajax({
                    type: "get",
                    url: "{{ url('sme-data/fetch-data-plan') }}",
                    data: { id: service_id, type: type },
                    success: function (response) {
                        $("#sme_plan").empty().append("<option value=''>Data Plan</option>");
                        response.forEach(item => {
                            let text = item.size + " - " + item.validity;
                            $("#sme_plan").append(`<option value="${item.data_id}">${text}</option>`);
                        });
                        $("#sme_amount").val("");
                    }
                });
            });

            $("#sme_plan").change(function () {
                let plan_id = $(this).val();
                if(!plan_id) { $("#sme_amount").val(""); return; }

                $.ajax({
                    type: "get",
                    url: "{{ url('sme-data/fetch-sme-data-bundles-price') }}",
                    data: { id: plan_id },
                    success: function (response) {
                        $("#sme_amount").val(response);
                    }
                });
            });

            // AI Chatbot Logic
            const aiWin = document.getElementById('aiChatWindow');
            const aiIn = document.getElementById('aiInput');
            const typeInd = document.getElementById('aiTypingIndicator');

            const addBubble = (txt, role = 'user') => {
                const wrap = document.createElement('div');
                wrap.className = `d-flex mb-4 animate-fade-in ${role === 'user' ? 'justify-content-end' : ''}`;
                
                const html = role === 'user'
                    ? `<div class="bg-success text-white p-3 rounded-4 rounded-top-end-0 shadow-sm border-0 small shadow-hover" style="max-width: 85%;">${txt}</div>`
                    : `<div class="d-flex gap-2">
                        <div class="bg-success text-white rounded-circle p-1 align-self-start shadow-sm" style="width:28px;height:28px;font-size:12px;display:flex;align-items:center;justify-content:center; flex-shrink:0;">AS</div>
                        <div class="bg-white p-3 rounded-4 rounded-start-0 shadow-sm border small shadow-hover text-dark" style="max-width: 85%;">${txt}</div>
                       </div>`;
                
                wrap.innerHTML = html;
                aiWin.appendChild(wrap);
                aiWin.scrollTop = aiWin.scrollHeight;
                convHistory.push({ role, content: txt });
            };

            window.askAi = (txt) => {
                if(!txt.trim()) return;
                addBubble(txt, 'user');
                aiIn.value = '';
                typeInd.classList.remove('d-none');

                fetch("{{ route('ai.ask') }}", {
                    method: "POST",
                    headers: { "Content-Type": "application/json", "X-CSRF-TOKEN": "{{ csrf_token() }}" },
                    body: JSON.stringify({
                        comment: "User is on the SME Data page inquiring about bundles.",
                        question: txt,
                        history: convHistory
                    })
                })
                .then(r => r.json())
                .then(data => {
                    typeInd.classList.add('d-none');
                    if(data.success) addBubble(data.answer, 'assistant');
                    else addBubble("I'm sorry, I'm having trouble connecting right now.", 'assistant');
                })
                .catch(() => {
                    typeInd.classList.add('d-none');
                    addBubble("Network error. Please try again.", 'assistant');
                });
            };

            $('#sendAiBtn').on('click', () => askAi(aiIn.value));
            $('#aiInput').on('keypress', (e) => { if(e.key === 'Enter') askAi(aiIn.value); });
        });

        // Global Helpers
        function repeatSme(net, phone) {
            $("#sme_network").val(net).trigger('change');
            $("#sme_mobile").val(phone).addClass('border-success');
            setTimeout(() => $("#sme_mobile").removeClass('border-success'), 1000);
            window.scrollTo({ top: 0, behavior: 'smooth' });
        }

        function applySmePlan(net, type, pid) {
            $("#sme_network").val(net).trigger('change');
            setTimeout(() => {
                $("#sme_type").val(type).trigger('change');
                setTimeout(() => {
                    $("#sme_plan").val(pid).trigger('change');
                    window.scrollTo({ top: 0, behavior: 'smooth' });
                }, 800);
            }, 800);
        }

        function clearChat() {
            document.getElementById('aiChatWindow').innerHTML = '';
            convHistory = [];
        }

        // PIN Verification
        $('#confirmPinBtn').on('click', function() {
            const pin = $('#pinInput').val().trim();
            if(!pin) return;
            $(this).prop('disabled', true);
            $('#pinLoader').removeClass('d-none');
            
            $.ajax({
                type: "POST",
                url: "{{ route('verify.pin') }}",
                data: { pin: pin, _token: "{{ csrf_token() }}" },
                success: function(data) {
                    if (data.valid) $('#buySmeDataForm').submit();
                    else {
                        alert("Incorrect PIN.");
                        $('#confirmPinBtn').prop('disabled', false);
                        $('#pinLoader').addClass('d-none');
                    }
                }
            });
        });
    </script>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        let convHistory = [];

        $(document).ready(function () {
            // SME AJAX Selection Logic
            $("#sme_network").change(function () {
                let service_id = $(this).val();
                if(!service_id) return;
                $("#sme_type").empty().append("<option value=''>Loading...</option>");
                
                $.ajax({
                    type: "get",
                    url: "{{ url('sme-data/fetch-data-type') }}",
                    data: { id: service_id },
                    success: function (response) {
                        $("#sme_type").empty().append("<option value=''>Data Type</option>");
                        response.forEach(item => {
                            $("#sme_type").append(`<option value="${item.plan_type}">${item.plan_type}</option>`);
                        });
                        $("#sme_plan").empty().append("<option value=''>Select Plan</option>");
                        $("#sme_amount").val("");
                    }
                });
            });

            $("#sme_type").change(function () {
                let service_id = $("#sme_network").val();
                let type = $(this).val();
                if(!service_id || !type) return;
                $("#sme_plan").empty().append("<option value=''>Fetching plans...</option>");

                $.ajax({
                    type: "get",
                    url: "{{ url('sme-data/fetch-data-plan') }}",
                    data: { id: service_id, type: type },
                    success: function (response) {
                        $("#sme_plan").empty().append("<option value=''>Data Plan</option>");
                        response.forEach(item => {
                            let text = item.size + " - " + item.validity;
                            $("#sme_plan").append(`<option value="${item.data_id}">${text}</option>`);
                        });
                        $("#sme_amount").val("");
                    }
                });
            });

            $("#sme_plan").change(function () {
                let plan_id = $(this).val();
                if(!plan_id) { $("#sme_amount").val(""); return; }

                $.ajax({
                    type: "get",
                    url: "{{ url('sme-data/fetch-sme-data-bundles-price') }}",
                    data: { id: plan_id },
                    success: function (response) {
                        $("#sme_amount").val(response);
                    }
                });
            });

            // AI Chatbot Logic
            const aiWin = document.getElementById('aiChatWindow');
            const aiIn = document.getElementById('aiInput');
            const typeInd = document.getElementById('aiTypingIndicator');

            const addBubble = (txt, role = 'user') => {
                const wrap = document.createElement('div');
                wrap.className = `d-flex mb-4 animate-fade-in ${role === 'user' ? 'justify-content-end' : ''}`;
                
                const html = role === 'user'
                    ? `<div class="bg-success text-white p-3 rounded-4 rounded-top-end-0 shadow-sm border-0 small shadow-hover" style="max-width: 85%;">${txt}</div>`
                    : `<div class="d-flex gap-2">
                        <div class="bg-success text-white rounded-circle p-1 align-self-start shadow-sm" style="width:28px;height:28px;font-size:12px;display:flex;align-items:center;justify-content:center; flex-shrink:0;">AS</div>
                        <div class="bg-white p-3 rounded-4 rounded-start-0 shadow-sm border small shadow-hover text-dark" style="max-width: 85%;">${txt}</div>
                       </div>`;
                
                wrap.innerHTML = html;
                aiWin.appendChild(wrap);
                aiWin.scrollTop = aiWin.scrollHeight;
                convHistory.push({ role, content: txt });
            };

            window.askAi = (txt) => {
                if(!txt.trim()) return;
                addBubble(txt, 'user');
                aiIn.value = '';
                typeInd.classList.remove('d-none');

                fetch("{{ route('ai.ask') }}", {
                    method: "POST",
                    headers: { "Content-Type": "application/json", "X-CSRF-TOKEN": "{{ csrf_token() }}" },
                    body: JSON.stringify({
                        comment: "User is on the SME Data page inquiring about bundles.",
                        question: txt,
                        history: convHistory
                    })
                })
                .then(r => r.json())
                .then(data => {
                    typeInd.classList.add('d-none');
                    if(data.success) addBubble(data.answer, 'assistant');
                    else addBubble("I'm sorry, I'm having trouble connecting right now.", 'assistant');
                })
                .catch(() => {
                    typeInd.classList.add('d-none');
                    addBubble("Network error. Please try again.", 'assistant');
                });
            };

            $('#sendAiBtn').on('click', () => askAi(aiIn.value));
            $('#aiInput').on('keypress', (e) => { if(e.key === 'Enter') askAi(aiIn.value); });
        });

        // Global Helpers
        function repeatSme(net, phone) {
            $("#sme_network").val(net).trigger('change');
            $("#sme_mobile").val(phone).addClass('border-success');
            setTimeout(() => $("#sme_mobile").removeClass('border-success'), 1000);
            window.scrollTo({ top: 0, behavior: 'smooth' });
        }

        function applySmePlan(net, type, pid) {
            $("#sme_network").val(net).trigger('change');
            setTimeout(() => {
                $("#sme_type").val(type).trigger('change');
                setTimeout(() => {
                    $("#sme_plan").val(pid).trigger('change');
                    window.scrollTo({ top: 0, behavior: 'smooth' });
                }, 800);
            }, 800);
        }

        function clearChat() {
            document.getElementById('aiChatWindow').innerHTML = '';
            convHistory = [];
        }

        // PIN Verification
        $('#confirmPinBtn').on('click', function() {
            const pin = $('#pinInput').val().trim();
            if(!pin) return;
            $(this).prop('disabled', true);
            $('#pinLoader').removeClass('d-none');
            
            $.ajax({
                type: "POST",
                url: "{{ route('verify.pin') }}",
                data: { pin: pin, _token: "{{ csrf_token() }}" },
                success: function(data) {
                    if (data.valid) $('#buySmeDataForm').submit();
                    else {
                        alert("Incorrect PIN.");
                        $('#confirmPinBtn').prop('disabled', false);
                        $('#pinLoader').addClass('d-none');
                    }
                }
            });
        });
    </script>
</x-app-layout>
