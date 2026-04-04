<x-guest-layout>
    <title>Arewa Smart - {{ $title ?? 'Forgot Password' }}</title>
    
    <div class="auth-card">
        <div class="auth-logo">
            <a href="/">
                <img src="{{ asset('assets/img/logo/new-logo.png') }}" alt="Arewa Smart Logo">
            </a>
        </div>

        <div class="text-center mb-4">
            <h2 class="fw-bold mb-1">Reset Password</h2>
            <p class="text-muted small">Enter your email and we'll send a reset link</p>
        </div>

        <x-auth-session-status class="mb-4 text-center" :status="session('status')" />

        <form method="POST" action="{{ route('password.email') }}">
            @csrf

            {{-- Email Field --}}
            <div class="mb-4">
                <label class="form-label fw-semibold" for="email">Email Address</label>
                <div class="input-group">
                    <input 
                        type="email" 
                        id="email" 
                        name="email" 
                        value="{{ old('email') }}" 
                        required 
                        autofocus 
                        placeholder="Enter your email"
                        class="form-control border-end-0 @error('email') is-invalid @enderror">
                    <span class="input-group-text border-start-0">
                        <i class="ti ti-mail"></i>
                    </span>
                    @error('email')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            {{-- Submit Button --}}
            <button type="submit" class="btn btn-primary w-100 mb-4 py-2">Get Reset Link</button>

            {{-- Back to Login --}}
            <div class="text-center">
                <p class="text-muted small mb-0">
                    Remembered? 
                    <a href="{{ route('login') }}" class="text-primary fw-bold">Sign In</a>
                </p>
            </div>
        </form>
    </div>

    {{-- Footer Text --}}
    <p class="auth-footer-text">&copy; {{ date('Y') }} Arewa Smart. All rights reserved.</p>
</x-guest-layout>
