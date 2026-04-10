<x-app-layout>
    <title>Arewa Smart - {{ $title ?? 'Buy Data' }}</title>
    @push('styles')
    <style>
        .network-selection .network-card {
            cursor: pointer;
            transition: all 0.3s ease;
            border: 2px solid #eee;
            background: #fff;
            position: relative;
            overflow: hidden;
        }
        .network-selection .network-card:hover {
            transform: translateY(-3px);
            border-color: #0d6efd;
            box-shadow: 0 4px 15px rgba(0,0,0,0.1);
        }
        .network-selection .network-card.active {
            border-color: #0d6efd;
            background-color: #f0f7ff;
        }
        .network-selection .network-card.active::after {
            content: '✓';
            position: absolute;
            top: 5px;
            right: 5px;
            background: #0d6efd;
            color: white;
            width: 18px;
            height: 18px;
            border-radius: 50%;
            font-size: 11px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: bold;
        }
        .network-selection .network-card img {
            width: 40px;
            height: 40px;
            object-fit: contain;
            border-radius: 8px;
        }
    </style>
    @endpush

    <div class="container-fluid px-0 px-md-3 mt-3">
        <div class="row g-0 g-md-4 justify-content-center">
                <div class="col-12 col-xl-5 mb-4">
                    <div class="card custom-card shadow-lg border-0 rounded-0 rounded-md-4 d-flex flex-column" style="height: 650px;">
                        <div class="card-header justify-content-between bg-primary text-white rounded-0 rounded-top-md-4 flex-shrink-0">
                            <div class="card-title fw-semibold">
                                <i class="bi bi-credit-card me-2"></i> Buy Data
                            </div>
                        </div>
                        <div class="card-body overflow-auto flex-grow-1">
                            <p class="text-center text-muted mb-4">
                                Select your mobile network, enter your phone number, and choose a data plan to proceed.
                            </p>

                            {{-- Flash Messages --}}
                            @if (session('success'))
                                <div class="alert alert-success alert-dismissible fade show text-center" role="alert">
                                    {{ session('success') }}
                                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                                </div>
                            @endif

                            @if (session('error'))
                                <div class="alert alert-danger alert-dismissible fade show text-center" role="alert">
                                    {{ session('error') }}
                                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                                </div>
                            @endif

                            @if ($errors->any())
                                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                    <ul class="mb-0 text-start small">
                                        @foreach ($errors->all() as $error)
                                            <li>{{ $error }}</li>
                                        @endforeach
                                    </ul>
                                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                                </div>
                            @endif

                            {{-- Buy Data Form --}}
                            <form id="buyDataForm" method="POST" action="{{ route('buydata') }}">
                                @csrf


                                  {{-- Phone Number --}}
                                <div class="mb-3">
                                    <label class="form-label fw-semibold">Phone Number</label>
                                    <input type="text" id="mobileno" name="mobileno"
                                           oninput="validateNumber()" 
                                           class="form-control text-center"
                                           placeholder="08012345678"
                                           maxlength="11" required>
                                    <small id="networkResult" class="text-muted"></small>
                                </div>

                                {{-- Network Selection --}}
                                <div class="network-selection mb-4">
                                    <label class="form-label fw-semibold text-dark small mb-3 text-center d-block w-100">Select network operator</label>
                                    <div class="row text-center g-2 g-sm-3 justify-content-center">
                                        @php
                                            $networks = [
                                                'mtn'      => ['name' => 'MTN',    'img' => 'mtn.jpg', 'prefix' => 'mtn'],
                                                'airtel'   => ['name' => 'Airtel', 'img' => 'Airtel.png', 'prefix' => 'airtel'],
                                                'glo'      => ['name' => 'Glo',    'img' => 'glo.jpg', 'prefix' => 'glo'],
                                                'etisalat' => ['name' => '9Mobile','img' => '9Mobile.jpg', 'prefix' => 'etisalat'],
                                            ];
                                        @endphp
                                        @foreach($networks as $id => $network)
                                            <div class="col-3">
                                                <div class="network-card p-2 border rounded-3 text-center" 
                                                     id="net-{{ $id }}"
                                                     onclick="selectNetwork('{{ $network['prefix'] }}', 'net-{{ $id }}')">
                                                    <img src="{{ asset('assets/img/apps/' . $network['img']) }}" alt="{{ $network['name'] }}" class="mb-1">
                                                    <span class="d-block small fw-bold" style="font-size: 10px;">{{ $network['name'] }}</span>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                    <input type="hidden" name="network" id="service_id" required>
                                </div>

                                {{-- Bundle --}}
                                <div class="mb-3">
                                    <label class="form-label fw-semibold">Select Bundle</label>
                                    <select name="bundle" id="bundle" class="form-select text-center" required>
                                        <option value="">Choose Bundle</option>
                                    </select>
                                </div>

                                {{-- Amount --}}
                                <div class="mb-3 text-start">
                                    <label for="amount" class="form-label fw-semibold d-flex justify-content-between">
                                        <span>Amount</span>
                                        <small class="text-muted d-flex align-items-center gap-1">Balance: 
                                            <strong class="text-success" id="walletBalance">
                                                ₦{{ number_format($wallet->balance ?? 0, 2) }}
                                            </strong>
                                            <i class="bi bi-eye-slash-fill ms-1" id="toggleBalance" style="cursor: pointer;" onclick="toggleBalanceVisibility()"></i>
                                        </small>
                                    </label>
                                    <input type="text" id="amountToPay" name="amount" readonly class="form-control text-center" />
                                </div>

                                {{-- Submit --}}
                                <div class="d-grid mt-4">
                                    <button type="button" id="openPinModalBtn" class="btn btn-primary btn-lg fw-semibold">
                                        Proceed to Buy
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>

              {{-- Right Column: AI Smart Chatbot & History --}}
            <div class="col-12 col-xl-7 mt-2 mt-md-0">
                <div class="card shadow-lg border-0 rounded-0 rounded-md-4 overflow-hidden h-100 d-flex flex-column shadow-hover transition-all" style="height: 650px;">
                    <div class="card-header bg-white text-white p-3 p-md-4 d-flex align-items-center justify-content-between rounded-0 rounded-top-md-4 flex-shrink-0">
                        <div class="d-flex align-items-center gap-3">
                            <div class="bg-success rounded-circle p-2 shadow-sm" style="width: 42px; height: 42px; display: flex; align-items: center; justify-content: center;">
                                <i class="bi bi-robot fs-15"></i>
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
                    @php
                        $networksData = $recentPurchases->groupBy(function($item) {
                            $meta = json_decode($item->metadata, true);
                            return strtolower($meta['network'] ?? 'other');
                        })->map(fn($group) => $group->sum('amount'));

                        $chartLabels = $networksData->keys()->map(fn($k) => strtoupper($k))->toArray();
                        $chartValues = $networksData->values()->toArray();
                        
                        $baseColors = [
                            'MTN' => '#FFCC00',
                            'AIRTEL' => '#ED1C24',
                            'GLO' => '#008D41',
                            'ETISALAT' => '#006633',
                            '9MOBILE' => '#006633'
                        ];
                        $chartColors = [];
                        foreach($chartLabels as $label) {
                            $chartColors[] = $baseColors[$label] ?? '#0d6efd';
                        }
                    @endphp
                    <div class="collapse show" id="smeHistoryCollapse">
                        <div class="bg-white border-bottom shadow-sm">
                            <div class="p-3 border-bottom d-flex justify-content-between align-items-center bg-light-subtle">
                                <div>
                                    <small class="fw-bold text-muted text-uppercase mb-0 fs-11 px-2">
                                        <i class="bi bi-bar-chart-fill me-2"></i>Usage Distribution
                                    </small>
                                </div>
                                <div class="text-end px-2">
                                    <h6 class="mb-0 fw-bold text-primary fs-13">₦{{ number_format($recentPurchases->sum('amount'), 0) }}</h6>
                                    <small class="text-muted fs-9">Total Last 10</small>
                                </div>
                            </div>

                            <div class="p-3">
                                <div id="usageChart" style="min-height: 180px;"></div>
                            </div>

                            <div class="px-3 pb-3">
                                <small class="text-muted fw-bold text-uppercase fs-9 d-block mb-2">Recent Activity</small>
                                <div class="transaction-list" style="max-height: 500px; overflow-y: auto; scrollbar-width: thin; scrollbar-color: #df6808ff #f8f9fa;">
                                    <style>
                                        .transaction-list::-webkit-scrollbar { width: 6px; }
                                        .transaction-list::-webkit-scrollbar-track { background: #f8f9fa; border-radius: 10px; }
                                        .transaction-list::-webkit-scrollbar-thumb { background-color: #df6808ff; border-radius: 10px; }
                                        .transaction-list::-webkit-scrollbar-thumb:hover { background-color: #c55a06ff; }
                                    </style>
                                    @forelse($recentPurchases->take(15) as $history)
                                        @php
                                            $meta = json_decode($history->metadata, true);
                                            $phone = $meta['phone'] ?? substr($history->description, -11);
                                            $networkLabel = strtolower($meta['network'] ?? 'data');
                                            $networkColors = [
                                                'mtn' => 'warning',
                                                'airtel' => 'danger',
                                                'glo' => 'success',
                                                'etisalat' => 'dark',
                                                '9mobile' => 'dark'
                                            ];
                                            $nColor = $networkColors[$networkLabel] ?? 'primary';
                                        @endphp
                                        <div class="d-flex align-items-center justify-content-between p-2 mb-2 rounded-3 bg-light-subtle shadow-sm border-start border-3 border-{{ $nColor }}" 
                                             onclick="repeatSme('{{ $networkLabel }}', '{{ $phone }}')" 
                                             style="cursor: pointer; transition: all 0.2s ease;">
                                            <div class="d-flex align-items-center gap-2">
                                                <div class="bg-{{ $nColor }} bg-opacity-10 text-{{ $nColor }} rounded-circle d-flex align-items-center justify-content-center" style="width: 32px; height: 32px;">
                                                    <i class="bi bi-phone fs-12"></i>
                                                </div>
                                                <div>
                                                    <h6 class="mb-0 fs-12 fw-bold text-dark">{{ $phone }}</h6>
                                                    <small class="text-muted fs-10 text-uppercase">{{ $networkLabel }} • {{ $history->created_at->diffForHumans() }}</small>
                                                </div>
                                            </div>
                                            <div class="text-end">
                                                <span class="d-block fs-12 fw-bold text-dark">₦{{ number_format($history->amount, 0) }}</span>
                                                <span class="badge {{ $history->status == 'successful' ? 'bg-success' : 'bg-danger' }} p-1" style="font-size: 8px;">{{ strtoupper($history->status) }}</span>
                                            </div>
                                        </div>
                                    @empty
                                        <div class="text-center py-4 text-muted small">No recent activity.</div>
                                    @endforelse
                                </div>
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

                    <div class="card-footer bg-white border-top p-3 p-md-4 mt-auto rounded-0 rounded-bottom-md-4">
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
   
   @include('pages.pin')

    @push('scripts')
    <script>
function selectNetwork(prefix, elementId) {
    // Update active UI
    document.querySelectorAll('.network-card').forEach(card => card.classList.remove('active'));
    document.getElementById(elementId).classList.add('active');

    // Update hidden input
    const serviceInput = document.getElementById('service_id');
    serviceInput.value = prefix;

    // Fetch bundles
    fetchBundles(prefix);
}

function fetchBundles(prefix) {
    const bundleSelect = document.getElementById('bundle');
    bundleSelect.innerHTML = '<option value="">Loading plans...</option>';

    fetch(`{{ route('fetch.bundles') }}?id=${prefix}`)
        .then(response => response.json())
        .then(data => {
            bundleSelect.innerHTML = '<option value="">Choose Bundle</option>';
            data.forEach(bundle => {
                const option = document.createElement('option');
                option.value = bundle.variation_code;
                option.textContent = bundle.name;
                bundleSelect.appendChild(option);
            });
        })
        .catch(err => {
            console.error('Error fetching bundles:', err);
            bundleSelect.innerHTML = '<option value="">Error loading plans</option>';
        });
}

// Add event listener for bundle change to fetch price
document.getElementById('bundle').addEventListener('change', function() {
    const bundleCode = this.value;
    const amountInput = document.getElementById('amountToPay');
    
    if (!bundleCode) {
        amountInput.value = '';
        return;
    }

    amountInput.value = 'Loading price...';

    fetch(`{{ route('fetch.bundle.price') }}?id=${bundleCode}`)
        .then(response => response.json())
        .then(price => {
            amountInput.value = '₦' + price;
        })
        .catch(err => {
            console.error('Error fetching price:', err);
            amountInput.value = 'Error';
        });
});

function validateNumber() {
    const phone = document.getElementById('mobileno').value;
    const result = document.getElementById('networkResult');
    const selectedNetwork = document.getElementById('service_id').value;

    if (phone.length >= 4) {
        const patterns = {
            'mtn': ['0803','0806','0703','0706','0813','0816','0810','0814','0815','0903','0906','0913','0916','0702','0704'],
            'airtel': ['0802','0808','0701','0708','0812','0902','0907','0901','0904','0912','0917'],
            'glo': ['0805','0807','0705','0815','0811','0905','0915'],
            'etisalat': ['0809','0817','0818','0909','0908']
        };

        let detected = null;
        for (const [net, prefixes] of Object.entries(patterns)) {
            if (prefixes.some(p => phone.startsWith(p))) {
                detected = net;
                break;
            }
        }

        if (detected) {
            const netNames = { mtn: 'MTN', airtel: 'Airtel', glo: 'Glo', etisalat: '9Mobile' };
            result.textContent = 'Detected: ' + netNames[detected];
            result.className = 'text-success';

            // Automatic selection logic
            if (selectedNetwork !== detected) {
                selectNetwork(detected, 'net-' + detected);
            }
        } else {
            result.textContent = 'Unknown sequence';
            result.className = 'text-muted';
        }
    } else {
        result.textContent = '';
    }
}

// Populate PIN Modal and Open
document.getElementById('openPinModalBtn').addEventListener('click', function() {
    const network = document.getElementById('service_id').value;
    const bundle = document.getElementById('bundle');
    const bundleText = bundle.options[bundle.selectedIndex]?.text;
    const amount = document.getElementById('amountToPay').value;
    const phone = document.getElementById('mobileno').value;

    if (!network || !bundle.value || !phone || phone.length < 11) {
        Swal.fire({
            icon: 'warning',
            title: 'Action Required',
            text: !phone || phone.length < 11 ? 'Please enter a valid 11-digit phone number.' : 'Please select a network and plan.'
        });
        return;
    }

    // Populate the PIN selection summary fields
    const networks = {
        'mtn': 'MTN Nigeria',
        'airtel': 'Airtel Nigeria',
        'glo': 'Globacom (Glo)',
        'etisalat': '9Mobile'
    };

    document.getElementById('confirmAccountName').textContent = networks[network] || 'Mobile Data';
    document.getElementById('confirmBankName').textContent = bundleText || 'Selected Plan';
    document.getElementById('confirmAccountNo').textContent = phone;
    document.getElementById('confirmAmount').textContent = amount;

    // Show modal
    const pinModal = new bootstrap.Modal(document.getElementById('pinModal'));
    pinModal.show();
});

document.getElementById('confirmPinBtn').addEventListener('click', function() {
    const confirmBtn = this;
    const loader = document.getElementById('pinLoader');
    const confirmText = document.getElementById('confirmPinText');
    const pinError = document.getElementById('pinError');
    const pinErrorText = document.getElementById('pinErrorText');
    const pin = document.getElementById('pinInput').value.trim();

    if (!pin) {
        if (pinErrorText) pinErrorText.textContent = "Please enter your PIN.";
        pinError?.classList.remove('d-none');
        return;
    }

    confirmBtn.disabled = true;
    loader?.classList.remove('d-none');
    if (confirmText) confirmText.textContent = "Verifying...";

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
            document.getElementById('buyDataForm').submit();
        } else {
            if (pinErrorText) pinErrorText.textContent = "Incorrect PIN. Please try again.";
            pinError?.classList.remove('d-none');
            confirmBtn.disabled = false;
            loader?.classList.add('d-none');
            if (confirmText) confirmText.textContent = "Authorize Now";
        }
    })
    .catch(err => {
        console.error("PIN check failed:", err);
        if (pinErrorText) pinErrorText.textContent = "Network error. Please try again.";
        pinError?.classList.remove('d-none');
        confirmBtn.disabled = false;
        loader?.classList.add('d-none');
        if (confirmText) confirmText.textContent = "Authorize Now";
    });
});

// History Chart Initialization
document.addEventListener('DOMContentLoaded', function() {
    const options = {
        series: @json($chartValues),
        chart: {
            type: 'donut',
            height: 220,
            sparkline: { enabled: true }
        },
        labels: @json($chartLabels),
        colors: @json($chartColors),
        plotOptions: {
            pie: {
                donut: {
                    size: '75%',
                    labels: {
                        show: true,
                        name: {
                            show: true,
                            fontSize: '12px',
                            fontWeight: 600,
                            offsetY: -10
                        },
                        value: {
                            show: true,
                            fontSize: '16px',
                            fontWeight: 700,
                            offsetY: 5,
                            formatter: function(val) { return '₦' + parseInt(val).toLocaleString(); }
                        },
                        total: {
                            show: true,
                            label: 'Total',
                            fontSize: '12px',
                            fontWeight: 600,
                            formatter: function(w) {
                                return '₦' + w.globals.seriesTotals.reduce((a, b) => a + b, 0).toLocaleString();
                            }
                        }
                    }
                }
            }
        },
        dataLabels: { enabled: false },
        legend: {
            show: true,
            position: 'bottom',
            fontSize: '10px',
            markers: { radius: 12 },
            itemMargin: { horizontal: 5, vertical: 5 }
        },
        tooltip: {
            y: { formatter: function(val) { return '₦' + val.toLocaleString(); } }
        }
    };

    if (document.querySelector("#usageChart")) {
        const chart = new ApexCharts(document.querySelector("#usageChart"), options);
        chart.render();
    }
});



function repeatSme(network, phone) {
    // Select network card
    const cardId = 'net-' + (network === '9mobile' ? 'etisalat' : network);
    const card = document.getElementById(cardId);
    if (card) {
        card.click();
        
        // Populate phone number
        const phoneInput = document.getElementById('mobileno');
        phoneInput.value = phone;
        
        // Focus bundle select
        document.getElementById('bundle').focus();
        
        // Scroll to form
        document.querySelector('.card-body').scrollIntoView({ behavior: 'smooth' });
    }
}

function toggleBalanceVisibility() {
    const balanceSpan = document.getElementById('walletBalance');
    const toggleIcon = document.getElementById('toggleBalance');
    const actualBalance = "₦{{ number_format($wallet->balance ?? 0, 2) }}";
    
    if (balanceSpan.textContent.includes('***')) {
        balanceSpan.textContent = actualBalance;
        toggleIcon.className = 'bi bi-eye-slash-fill ms-1';
        localStorage.setItem('balanceVisible', 'true');
    } else {
        balanceSpan.textContent = '₦****.**';
        toggleIcon.className = 'bi bi-eye-fill ms-1';
        localStorage.setItem('balanceVisible', 'false');
    }
}

// Persist balance visibility preference
document.addEventListener('DOMContentLoaded', function() {
    if (localStorage.getItem('balanceVisible') === 'false') {
        const balanceSpan = document.getElementById('walletBalance');
        const toggleIcon = document.getElementById('toggleBalance');
        if (balanceSpan && toggleIcon) {
            balanceSpan.textContent = '₦****.**';
            toggleIcon.className = 'bi bi-eye-fill ms-1';
        }
    }
});
</script>
@endpush

</x-app-layout>
