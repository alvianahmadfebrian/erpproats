<!DOCTYPE html>
<html class="light" lang="en">
<head>
    <meta charset="utf-8">
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    <title>Login - Proats Music Center Admin ERP</title>
    <script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&amp;display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&amp;family=Outfit:wght@300;400;500;600;700&amp;family=Playfair+Display:ital,wght@0,400..900;1,400..900&amp;family=JetBrains+Mono:wght@500&amp;display=swap" rel="stylesheet">
    <script id="tailwind-config">
        tailwind.config = {
            darkMode: "class",
            theme: {
                extend: {
                    "colors": {
                        "inverse-on-surface": "#eff1f3",
                        "surface": "#f8fafc",
                        "on-secondary-container": "#fefcff",
                        "surface-container-highest": "#e0e3e5",
                        "inverse-surface": "#2d3133",
                        "on-surface": "#0f172a",
                        "surface-bright": "#f8fafc",
                        "on-background": "#0f172a",
                        "primary-fixed": "#dae2fd",
                        "on-primary-fixed-variant": "#3f465c",
                        "secondary": "#4f46e5",
                        "on-secondary-fixed-variant": "#004395",
                        "tertiary-fixed": "#d3e4fe",
                        "surface-container-high": "#e2e8f0",
                        "secondary-container": "#4f46e5",
                        "on-error-container": "#93000a",
                        "outline": "#64748b",
                        "surface-container": "#eceef0",
                        "on-primary-container": "#7c839b",
                        "tertiary-fixed-dim": "#b7c8e1",
                        "on-secondary-fixed": "#001a42",
                        "tertiary-container": "#0b1c30",
                        "on-secondary": "#ffffff",
                        "on-surface-variant": "#475569",
                        "inverse-primary": "#bec6e0",
                        "primary": "#0f172a",
                        "surface-dim": "#d8dadc",
                        "on-primary-fixed": "#131b2e",
                        "surface-variant": "#e2e8f0",
                        "primary-container": "#0f172a",
                        "on-tertiary-container": "#75859d",
                        "error-container": "#ffdad6",
                        "tertiary": "#000000",
                        "background": "#f8fafc",
                        "on-tertiary-fixed": "#0b1c30",
                        "on-error": "#ffffff",
                        "primary-fixed-dim": "#bec6e0",
                        "secondary-fixed-dim": "#adc6ff",
                        "on-primary": "#ffffff",
                        "on-tertiary-fixed-variant": "#38485d",
                        "surface-tint": "#565e74",
                        "surface-container-low": "#f1f5f9",
                        "secondary-fixed": "#d8e2ff",
                        "outline-variant": "#cbd5e1",
                        "surface-container-lowest": "#ffffff",
                        "error": "#ba1a1a",
                        "on-tertiary": "#ffffff"
                    },
                    "borderRadius": {
                        "none": "0px",
                        "sm": "0.125rem",
                        "DEFAULT": "0.375rem",
                        "md": "0.5rem",
                        "lg": "0.75rem",
                        "xl": "1rem",
                        "2xl": "1.5rem",
                        "3xl": "2rem",
                        "full": "9999px"
                    },
                    "spacing": {
                        "stack-lg": "32px",
                        "stack-sm": "8px",
                        "unit": "4px",
                        "stack-md": "16px",
                        "gutter": "16px",
                        "container-padding": "24px"
                    },
                    "fontFamily": {
                        "sans": ["Outfit", "Inter", "sans-serif"],
                        "serif": ["Playfair Display", "serif"],
                        "headline-sm": ["Outfit", "sans-serif"],
                        "label-caps": ["Outfit", "sans-serif"],
                        "body-md": ["Outfit", "sans-serif"],
                        "body-lg": ["Outfit", "sans-serif"],
                        "body-sm": ["Outfit", "sans-serif"],
                        "data-mono": ["JetBrains Mono"],
                        "headline-md": ["Playfair Display", "serif"],
                        "display-lg": ["Playfair Display", "serif"]
                    }
                }
            }
        }
    </script>
</head>
<body class="bg-background text-on-background min-h-screen flex font-sans antialiased">
<!-- Split Screen Layout -->
<div class="flex w-full min-h-screen">
    <!-- Left Side: Image/Branding -->
    <div class="hidden lg:flex lg:w-1/2 bg-slate-950 relative overflow-hidden flex-col justify-center items-center p-12 text-center">
        <!-- Background piano image with no-repeat, cover, and center alignment -->
        <div class="absolute inset-0 z-0 bg-cover bg-no-repeat bg-center opacity-40 mix-blend-luminosity transform scale-105 transition-transform duration-[15s] hover:scale-100" style="background-image: url('https://lh3.googleusercontent.com/aida-public/AB6AXuCZ52DS3Qp53--2boKnAFay0S5PwycglaUGw8RqqsXQJ3guR15Fd6UmOPdN57YMcHYGEXnKtPeTWxrtomJ6CWd6lvadPD4j6p5_2GPiaOZyKKOqdb5mMYfx_Kk3NyiZn6siUGdH-0yWo9ADV4IqC5mVH0n-a35KrAYSPNGIriNEzMfVZlGFnGnhDh1qexkA6T0iSZpmWLkGzNMrX3eXfuLmtiP_UBT8uenl_Z8vMWii8VAXEkflsTtbPw')" data-alt="Classical piano keyboard"></div>
        <!-- Dark overlay gradient -->
        <div class="absolute inset-0 bg-gradient-to-br from-slate-950 via-slate-900/90 to-indigo-950/80 z-10"></div>
        <!-- Ambient radial glow -->
        <div class="absolute inset-0 bg-[radial-gradient(circle_at_top_right,rgba(99,102,241,0.15),transparent_50%)] z-10"></div>

        <div class="relative z-20 flex flex-col items-center justify-center gap-6">
            <div class="w-24 h-24 rounded-3xl border border-white/20 shadow-2xl bg-white/10 backdrop-blur-xl p-3 shrink-0 flex items-center justify-center transform hover:rotate-6 transition-transform duration-300">
                <img src="/logo.png" alt="Pro Ats Logo" class="w-full h-full object-contain rounded-xl"/>
            </div>
            <div>
                <h1 class="font-serif text-4xl xl:text-5xl text-white font-semibold tracking-wide drop-shadow-md">Proats Music</h1>
                <p class="font-sans text-xs tracking-[0.3em] uppercase text-indigo-300 font-bold mt-2.5">Admin ERP</p>
            </div>
        </div>
    </div>
    
    <!-- Right Side: Login Form -->
    <div class="w-full lg:w-1/2 flex items-center justify-center p-6 sm:p-12 bg-slate-50 relative overflow-hidden">
        <!-- Ambient Background Glows -->
        <div class="absolute -top-40 -right-40 w-96 h-96 rounded-full bg-indigo-100/40 blur-3xl pointer-events-none"></div>
        <div class="absolute -bottom-40 -left-40 w-96 h-96 rounded-full bg-blue-100/30 blur-3xl pointer-events-none"></div>

        <div class="w-full max-w-md bg-white/90 backdrop-blur-xl p-8 sm:p-10 rounded-2xl border border-slate-100 shadow-[0_20px_50px_rgba(15,23,42,0.04)] relative z-10 transition-all duration-300 hover:shadow-[0_20px_60px_rgba(15,23,42,0.06)]">
            <!-- Mobile/Tablet Header -->
            <div class="text-center mb-8 lg:hidden flex flex-col items-center justify-center">
                <div class="w-14 h-14 rounded-2xl border border-slate-100 shadow-md bg-white p-2 mb-3">
                    <img src="/logo.png" alt="Pro Ats Logo" class="w-full h-full object-contain"/>
                </div>
                <h1 class="font-serif text-2xl text-slate-900 font-semibold">Proats Music</h1>
                <p class="font-sans text-[10px] tracking-wider uppercase text-slate-500 font-bold mt-1">Admin ERP</p>
            </div>
            
            <div class="mb-8">
                <h2 class="font-sans text-2xl font-bold text-slate-900 tracking-tight">Sign In</h2>
                <p class="font-sans text-sm text-slate-500 mt-2">Enter your credentials to access the secure administrative portal.</p>
            </div>

            <!-- Error Alerts -->
            @if ($errors->any())
                <div class="mb-6 p-4 rounded-xl bg-rose-50 border border-rose-100 flex items-start gap-3 shadow-sm animate-pulse">
                    <span class="material-symbols-outlined text-rose-500 shrink-0 text-xl">error</span>
                    <span class="font-sans text-xs font-semibold text-rose-800 leading-normal">{{ $errors->first() }}</span>
                </div>
            @endif

            <form action="{{ route('login') }}" class="space-y-5" method="POST">
                @csrf
                <!-- Username Field -->
                <div>
                    <label class="block text-xs font-semibold text-slate-700 uppercase tracking-wider mb-2" for="email">Username</label>
                    <div class="relative group">
                        <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none">
                            <span class="material-symbols-outlined text-slate-400 group-focus-within:text-indigo-500 transition-colors duration-200 text-xl">person</span>
                        </div>
                        <input class="block w-full pl-11 pr-4 py-2.5 border border-slate-200 rounded-xl bg-slate-50/50 text-slate-800 placeholder-slate-400 font-sans text-sm focus:outline-none focus:border-indigo-500 focus:ring-4 focus:ring-indigo-500/10 focus:bg-white transition-all duration-200" id="email" name="email" value="{{ old('email') }}" placeholder="Masukkan username" required="" type="text">
                    </div>
                </div>
                <!-- Password Field -->
                <div>
                    <label class="block text-xs font-semibold text-slate-700 uppercase tracking-wider mb-2" for="password">Password</label>
                    <div class="relative group">
                        <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none">
                            <span class="material-symbols-outlined text-slate-400 group-focus-within:text-indigo-500 transition-colors duration-200 text-xl">lock</span>
                        </div>
                        <input class="block w-full pl-11 pr-12 py-2.5 border border-slate-200 rounded-xl bg-slate-50/50 text-slate-800 placeholder-slate-400 font-sans text-sm focus:outline-none focus:border-indigo-500 focus:ring-4 focus:ring-indigo-500/10 focus:bg-white transition-all duration-200" id="password" name="password" placeholder="Masukkan password" required="" type="password">
                        <button type="button" id="toggle-password" class="absolute inset-y-0 right-0 pr-3.5 flex items-center text-slate-400 hover:text-slate-600 transition-colors cursor-pointer" onclick="togglePasswordVisibility()">
                            <span class="material-symbols-outlined text-xl" id="toggle-password-icon">visibility</span>
                        </button>
                    </div>
                </div>
                <!-- Remember Me -->
                <div class="flex items-center pt-1">
                    <input class="h-4 w-4 text-indigo-600 border-slate-300 rounded focus:ring-indigo-500 focus:ring-offset-white cursor-pointer" id="remember-me" name="remember-me" type="checkbox" {{ old('remember-me') ? 'checked' : '' }}>
                    <label class="ml-2 block text-sm text-slate-600 cursor-pointer hover:text-slate-800 transition-colors" for="remember-me">
                        Remember me
                    </label>
                </div>
                <!-- Submit Button -->
                <div class="pt-2">
                    <button class="w-full flex justify-center py-3 px-4 border border-transparent rounded-xl bg-gradient-to-r from-indigo-600 to-indigo-700 text-white font-sans text-sm font-semibold shadow-lg shadow-indigo-600/15 hover:shadow-indigo-600/25 hover:scale-[1.01] active:scale-[0.99] focus:outline-none focus:ring-4 focus:ring-indigo-500/20 transition-all duration-200 cursor-pointer" type="submit">
                        Sign In
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
function togglePasswordVisibility() {
    const passwordInput = document.getElementById('password');
    const icon = document.getElementById('toggle-password-icon');
    if (passwordInput.type === 'password') {
        passwordInput.type = 'text';
        icon.textContent = 'visibility_off';
    } else {
        passwordInput.type = 'password';
        icon.textContent = 'visibility';
    }
}
</script>
</body>
</html>
