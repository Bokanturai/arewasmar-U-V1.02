<x-guest-layout>
    <title>Arewa Smart - {{ $title ?? 'Reset Password' }}</title>
    
    <div class="auth-card">
        <div class="auth-logo">
            <a href="/">
                <img src="{{ asset('assets/img/logo/new-logo.png') }}" alt="Arewa Smart Logo">
            </a>
        </div>

        <div class="text-center mb-4">
            <h2 class="fw-bold mb-1">Set New Password</h2>
            <p class="text-muted small">Regain access to your account</p>
        </div>

        <form method="POST" action="{{ route('password.store') }}">
            @csrf

            {{-- Password Reset Token --}}
            <input type="hidden" name="token" value="{{ $request->route('token') }}">

            {{-- Email Address --}}
            <div class="mb-3">
                <label class="form-label fw-semibold" for="email">Email Address</label>
                <div class="input-group">
                    <input 
                        type="email" 
                        id="email" 
                        name="email" 
                        value="{{ old('email', $request->email) }}" 
                        required 
                        readonly 
                        class="form-control border-end-0 @error('email') is-invalid @enderror">
                    <span class="input-group-text border-start-0">
                        <i class="ti ti-mail"></i>
                    </span>
                    @error('email')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            {{-- Password Field --}}
            <div class="mb-3">
                <label class="form-label fw-semibold" for="password">New Password</label>
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
                
                {{-- Password Strength --}}
                <div class="mt-2">
                    <div class="progress" style="height: 5px; border-radius: 10px;">
                        <div id="passwordStrengthBar" class="progress-bar" role="progressbar"></div>
                    </div>
                    <small id="passwordStrengthText" class="text-muted mt-1 d-block" style="font-size: 0.75rem;"></small>
                </div>
            </div>

            {{-- Confirm Password Field --}}
            <div class="mb-4">
                <label class="form-label fw-semibold" for="password_confirmation">Confirm Password</label>
                <div class="pass-group position-relative">
                    <input 
                        type="password" 
                        id="password_confirmation" 
                        name="password_confirmation" 
                        required 
                        placeholder="••••••••"
                        class="form-control @error('password_confirmation') is-invalid @enderror">
                    <span class="ti toggle-password ti-eye-off position-absolute end-0 top-50 translate-middle-y me-3 cursor-pointer text-muted fs-18"></span>
                    @error('password_confirmation')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <small id="passwordMatchError" class="text-danger d-none mt-1">Passwords do not match.</small>
            </div>

            {{-- Submit Button --}}
            <button type="submit" class="btn btn-primary w-100 mb-4 py-2">Update Password</button>

            {{-- Back to Login --}}
            <div class="text-center">
                <p class="text-muted small mb-0">
                    Suddenly remembered? 
                    <a href="{{ route('login') }}" class="text-primary fw-bold">Return to login</a>
                </p>
            </div>
        </form>
    </div>

    {{-- Footer Text --}}
    <p class="auth-footer-text">&copy; {{ date('Y') }} Arewa Smart. All rights reserved.</p>
</x-guest-layout>
