<x-guest-layout>
    <title>Arewa Smart - {{ $title ?? 'Verify Email' }}</title>
    
    <div class="auth-card">
        <div class="auth-logo">
            <a href="/">
                <img src="{{ asset('assets/img/logo/new-logo.png') }}" alt="Arewa Smart Logo">
            </a>
        </div>

        <div class="text-center mb-4">
            <h2 class="fw-bold mb-1">Verify Email</h2>
            <p class="text-muted small">Please verify your email to continue</p>
        </div>

        {{-- Status Message --}}
        @if (session('status') == 'verification-link-sent')
            <div class="alert alert-success border-0 shadow-sm mb-4 small">
                <i class="ti ti-circle-check fs-5 me-2"></i>
                A new verification link has been sent to your email.
            </div>
        @endif

        <div class="auth-body">
            <p class="text-muted small mb-4">
                Thanks for signing up! Before getting started, could you verify your email address by clicking on the link we just emailed to you?
            </p>

            {{-- Actions --}}
            <div class="d-grid gap-2">
                {{-- Resend Verification Link --}}
                <form method="POST" action="{{ route('verification.send') }}">
                    @csrf
                    <button type="submit" class="btn btn-primary w-100 py-2">
                        Resend Verification Email
                    </button>
                </form>

                {{-- Log Out --}}
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="btn btn-outline-light text-muted border w-100 py-2">
                        Log Out
                    </button>
                </form>
            </div>
        </div>
    </div>

    {{-- Footer Text --}}
    <p class="auth-footer-text">&copy; {{ date('Y') }} Arewa Smart. All rights reserved.</p>
</x-guest-layout>
