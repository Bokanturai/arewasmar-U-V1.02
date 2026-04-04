<x-guest-layout>
    <title>Arewa Smart - {{ $title ?? 'Confirm Password' }}</title>
    
    <div class="auth-card">
        <div class="auth-logo">
            <a href="/">
                <img src="{{ asset('assets/img/logo/new-logo.png') }}" alt="Arewa Smart Logo">
            </a>
        </div>

        <div class="text-center mb-4">
            <h2 class="fw-bold mb-1">Confirm Access</h2>
            <p class="text-muted small">This is a secure area. Please confirm your password.</p>
        </div>

        <form method="POST" action="{{ route('password.confirm') }}">
            @csrf

            {{-- Password Field --}}
            <div class="mb-4">
                <label class="form-label fw-semibold" for="password">Password</label>
                <div class="pass-group position-relative">
                    <input 
                        type="password" 
                        id="password" 
                        name="password" 
                        required 
                        placeholder="••••••••"
                        class="form-control @error('password') is-invalid @enderror">
                    <span class="ti toggle-password ti-eye-off position-absolute end-0 top-50 translate-middle-y me-3 cursor-pointer text-muted fs-18"></span>
                    @error('password')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            {{-- Confirm Button --}}
            <button type="submit" class="btn btn-primary w-100 mb-4 py-2">Confirm Identity</button>

            {{-- Register Link --}}
            <div class="text-center">
                <p class="text-muted small mb-0">
                    Need help? 
                    <a href="{{ route('login') }}" class="text-primary fw-bold">Sign In Again</a>
                </p>
            </div>
        </form>
    </div>

    {{-- Footer Text --}}
    <p class="auth-footer-text">&copy; {{ date('Y') }} Arewa Smart. All rights reserved.</p>
</x-guest-layout>
