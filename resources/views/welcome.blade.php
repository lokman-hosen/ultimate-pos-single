@extends('layouts.auth2')
@section('title', config('app.name', 'ultimatePOS'))
@inject('request', 'Illuminate\Http\Request')
@section('content')
    <div class="tw-relative tw-w-full tw-h-screen tw-overflow-hidden tw-flex tw-items-center tw-justify-center tw-bg-gradient-to-br tw-from-slate-900 tw-via-indigo-900 tw-to-purple-800">
        <!-- Background decorative elements (optional) -->
        <div class="tw-absolute tw-inset-0 tw-bg-[url('data:image/svg+xml;base64,...')] tw-opacity-10"></div> <!-- skip base64 for simplicity, use a simple blob or nothing -->

        <div class="tw-relative tw-z-10 tw-text-center tw-px-4 tw-max-w-4xl">
            <!-- Logo -->
            <div class="tw-flex tw-justify-center tw-mb-4">
                <img src="{{ asset('img/logo-small.png') }}" alt="{{ config('app.name') }}" class="tw-h-16 tw-w-auto tw-drop-shadow-lg tw-bg-white tw-rounded-full tw-p-1">
            </div>

            <!-- Title (1) -->
            <h1 class="tw-text-6xl md:tw-text-7xl tw-font-extrabold tw-text-white tw-tracking-tight tw-drop-shadow-2xl">
                {{ config('app.name', 'UltimatePOS') }}
            </h1>

            <!-- Caption (2) -->
            <br> <br>
            <p class="tw-text-xl md:tw-text-2xl tw-font-light tw-text-indigo-200 tw-mt-2 tw-tracking-wide tw-space-y-3">
                {{ env('APP_TITLE', 'Complete ERP, Inventory & Point of Sale Solution') }}
            </p>
            <br> <br>

            <!-- Feature Highlights (3) -->
            <div class="tw-flex tw-flex-wrap tw-justify-center tw-gap-4 md:tw-gap-8 tw-mt-8 md:tw-top-8">
                <div class="tw-flex tw-items-center tw-gap-2 tw-bg-white/10 tw-backdrop-blur-sm tw-px-5 tw-py-2 tw-rounded-full tw-border tw-border-white/20 tw-shadow-lg">
                    <span class="tw-text-2xl">📦</span>
                    <span class="tw-text-sm md:tw-text-base tw-font-medium tw-text-white">Inventory</span>
                </div>
                <div class="tw-flex tw-items-center tw-gap-2 tw-bg-white/10 tw-backdrop-blur-sm tw-px-5 tw-py-2 tw-rounded-full tw-border tw-border-white/20 tw-shadow-lg">
                    <span class="tw-text-2xl">🛒</span>
                    <span class="tw-text-sm md:tw-text-base tw-font-medium tw-text-white">POS</span>
                </div>
                <div class="tw-flex tw-items-center tw-gap-2 tw-bg-white/10 tw-backdrop-blur-sm tw-px-5 tw-py-2 tw-rounded-full tw-border tw-border-white/20 tw-shadow-lg">
                    <span class="tw-text-2xl">📊</span>
                    <span class="tw-text-sm md:tw-text-base tw-font-medium tw-text-white">Reports</span>
                </div>
                <div class="tw-flex tw-items-center tw-gap-2 tw-bg-white/10 tw-backdrop-blur-sm tw-px-5 tw-py-2 tw-rounded-full tw-border tw-border-white/20 tw-shadow-lg">
                    <span class="tw-text-2xl">👥</span>
                    <span class="tw-text-sm md:tw-text-base tw-font-medium tw-text-white">CRM</span>
                </div>
                <div class="tw-flex tw-items-center tw-gap-2 tw-bg-white/10 tw-backdrop-blur-sm tw-px-5 tw-py-2 tw-rounded-full tw-border tw-border-white/20 tw-shadow-lg">
                    <span class="tw-text-2xl">⚡</span>
                    <span class="tw-text-sm md:tw-text-base tw-font-medium tw-text-white">Real-time</span>
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="tw-mt-8 tw-flex tw-flex-wrap tw-justify-center tw-gap-4">
                @if (config('constants.allow_registration'))
                    <a href="{{ route('business.getRegister') }}@if(!empty(request()->lang))?lang={{ request()->lang }}@endif"
                       class="tw-px-8 tw-py-3 tw-w-48 tw-mt-3 tw-bg-white tw-text-indigo-900 tw-font-bold tw-rounded-full tw-shadow-xl hover:tw-shadow-2xl hover:tw-scale-105 tw-transition-all tw-duration-200">
                        Get Started Free
                    </a>
                @endif
                <a href="{{ action([\App\Http\Controllers\Auth\LoginController::class, 'login']) }}@if(!empty(request()->lang))?lang={{ request()->lang }}@endif"
                   class="tw-px-8 tw-py-3 tw-w-48 tw-mt-3 tw-bg-transparent tw-border-2 tw-border-white tw-text-white tw-font-bold tw-rounded-full hover:tw-bg-white hover:tw-text-indigo-900 tw-transition-all tw-duration-200">
                    Sign In
                </a>
                @if (Route::has('pricing') && config('app.env') != 'demo')
                    <a href="{{ action([\Modules\Superadmin\Http\Controllers\PricingController::class, 'index']) }}"
                       class="tw-px-8 tw-w-48 tw-mt-3 tw-py-3 tw-bg-purple-500/30 tw-backdrop-blur-sm tw-border tw-border-purple-300 tw-text-white tw-font-bold tw-rounded-full hover:tw-bg-purple-500/50 tw-transition-all tw-duration-200">
                        View Pricing
                    </a>
                @endif
            </div>
        </div>

        <!-- Footer (Fit in single page) -->
        <div class="tw-absolute tw-bottom-4 tw-left-0 tw-right-0 tw-text-center tw-text-white/30 tw-text-sm">
            © {{ date('Y') }} {{ config('app.name') }}. All rights reserved.
        </div>
    </div>
@endsection