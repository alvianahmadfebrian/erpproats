<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8"/>
    <meta content="width=device-width, initial-scale=1.0" name="viewport"/>
    <title>@yield('title', 'Proats Music Center')</title>
    <script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&amp;display=swap" rel="stylesheet"/>
    <link href="https://fonts.googleapis.com" rel="preconnect"/>
    <link crossorigin="" href="https://fonts.gstatic.com" rel="preconnect"/>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&amp;family=Outfit:wght@300;400;500;600;700&amp;family=Playfair+Display:ital,wght@0,400..900;1,400..900&amp;family=JetBrains+Mono:wght@500&amp;display=swap" rel="stylesheet"/>
    <script id="tailwind-config">
        tailwind.config = {
            darkMode: "class",
            theme: {
                extend: {
                    "colors": {
                        "secondary-fixed": "#d8e2ff",
                        "surface-container-high": "#e6e8ea",
                        "on-background": "#191c1e",
                        "primary-fixed-dim": "#bec6e0",
                        "tertiary-container": "#0b1c30",
                        "on-primary-fixed": "#131b2e",
                        "on-tertiary": "#ffffff",
                        "on-surface-variant": "#45464d",
                        "on-tertiary-fixed-variant": "#38485d",
                        "on-tertiary-container": "#75859d",
                        "on-error": "#ffffff",
                        "on-primary-fixed-variant": "#3f465c",
                        "on-secondary-fixed": "#001a42",
                        "error-container": "#ffdad6",
                        "on-tertiary-fixed": "#0b1c30",
                        "outline": "#76777d",
                        "secondary": "#0058be",
                        "surface-container-lowest": "#ffffff",
                        "on-primary": "#ffffff",
                        "on-secondary-container": "#fefcff",
                        "tertiary-fixed": "#d3e4fe",
                        "surface-variant": "#e0e3e5",
                        "on-secondary": "#ffffff",
                        "inverse-primary": "#bec6e0",
                        "surface-container-highest": "#e0e3e5",
                        "on-primary-container": "#7c839b",
                        "inverse-surface": "#2d3133",
                        "surface-dim": "#d8dadc",
                        "primary-fixed": "#dae2fd",
                        "error": "#ba1a1a",
                        "surface-container-low": "#f2f4f6",
                        "primary-container": "#131b2e",
                        "primary": "#000000",
                        "inverse-on-surface": "#eff1f3",
                        "on-error-container": "#93000a",
                        "secondary-container": "#2170e4",
                        "tertiary-fixed-dim": "#b7c8e1",
                        "on-surface": "#191c1e",
                        "secondary-fixed-dim": "#adc6ff",
                        "surface-bright": "#f7f9fb",
                        "surface-tint": "#565e74",
                        "surface-container": "#eceef0",
                        "on-secondary-fixed-variant": "#004395",
                        "background": "#f7f9fb",
                        "tertiary": "#000000",
                        "outline-variant": "#c6c6cd",
                        "surface": "#f7f9fb"
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
                        "unit": "4px",
                        "gutter": "16px",
                        "stack-sm": "8px",
                        "container-padding": "24px",
                        "stack-md": "16px"
                    },
                    "fontFamily": {
                        "sans": ["Outfit", "Inter", "sans-serif"],
                        "serif": ["Outfit", "sans-serif"],
                        "label-caps": ["Outfit", "sans-serif"],
                        "body-sm": ["Outfit", "sans-serif"],
                        "body-lg": ["Outfit", "sans-serif"],
                        "headline-md": ["Outfit", "sans-serif"],
                        "headline-sm": ["Outfit", "sans-serif"],
                        "data-mono": ["JetBrains Mono"],
                        "display-lg": ["Outfit", "sans-serif"],
                        "body-md": ["Outfit", "sans-serif"]
                    },
                    "fontSize": {
                        "label-caps": ["11px", { "lineHeight": "16px", "letterSpacing": "0.05em", "fontWeight": "700" }],
                        "body-sm": ["12px", { "lineHeight": "16px", "fontWeight": "400" }],
                        "body-lg": ["16px", { "lineHeight": "24px", "fontWeight": "400" }],
                        "headline-md": ["24px", { "lineHeight": "32px", "letterSpacing": "-0.01em", "fontWeight": "600" }],
                        "headline-sm": ["18px", { "lineHeight": "24px", "fontWeight": "600" }],
                        "data-mono": ["13px", { "lineHeight": "18px", "fontWeight": "500" }],
                        "display-lg": ["32px", { "lineHeight": "40px", "letterSpacing": "-0.02em", "fontWeight": "700" }],
                        "body-md": ["14px", { "lineHeight": "20px", "fontWeight": "400" }]
                    }
                }
            }
        }
    </script>
    <style>
        body { background-color: #F8FAFC; }
        .material-symbols-outlined { font-variation-settings: 'FILL' 0, 'wght' 400, 'GRAD' 0, 'opsz' 24; }

        /* Shared Dashboard Helper Styles */
        .card-level-1 { background-color: #ffffff; border: 1px solid #E2E8F0; }
        .table-header { background-color: #F1F5F9; }
        .table-row { border-bottom: 1px solid #E2E8F0; }
        .table-row:hover { background-color: #F8FAFC; }
        .table-row:last-child { border-bottom: none; }
        .btn-primary { background-color: #0b1c30; color: #ffffff; border-radius: 0.25rem; }
        .btn-secondary { background-color: transparent; border: 1px solid #CBD5E1; color: #0b1c30; border-radius: 0.25rem; }
        .badge-active { background-color: rgba(33, 112, 228, 0.1); color: #2170e4; }
        .badge-suspend { background-color: rgba(186, 26, 26, 0.1); color: #ba1a1a; }
        .input-field { border: 1px solid #CBD5E1; border-radius: 0.25rem; }
        .input-field:focus { border-color: #2170e4; box-shadow: 0 0 0 3px rgba(33, 112, 228, 0.1); outline: none; }
        .material-symbols-outlined.fill-icon { font-variation-settings: 'FILL' 1; }

        /* Collapsible Sidebar Styles */
        #sidebar.collapsed {
            width: 70px !important;
        }
        #sidebar.collapsed .sidebar-header-container {
            padding-left: 15px !important;
            padding-right: 15px !important;
            justify-content: center !important;
        }
        #sidebar.collapsed #logo-text {
            opacity: 0 !important;
            width: 0 !important;
            height: 0 !important;
            overflow: hidden !important;
            margin: 0 !important;
        }
        #sidebar.collapsed #sidebar-toggle {
            margin-left: 0 !important;
        }
        #sidebar.collapsed .nav-label-header {
            opacity: 0 !important;
            height: 0 !important;
            margin: 0 !important;
            padding: 0 !important;
            overflow: hidden !important;
        }
        #sidebar.collapsed .nav-item-text {
            opacity: 0 !important;
            width: 0 !important;
            height: 0 !important;
            overflow: hidden !important;
            margin: 0 !important;
        }
        #sidebar.collapsed nav {
            padding-left: 8px !important;
            padding-right: 8px !important;
        }
        #sidebar.collapsed nav a {
            justify-content: center !important;
            padding-left: 0 !important;
            padding-right: 0 !important;
            gap: 0 !important;
        }
        #main-content.sidebar-collapsed {
            margin-left: 70px !important;
        }
    </style>
    @stack('styles')
</head>
<body class="text-on-background font-sans bg-background antialiased h-screen overflow-hidden flex">

<!-- SideNavBar -->
<aside id="sidebar" class="hidden md:flex flex-col h-full py-8 fixed left-0 top-0 w-[240px] bg-white border-r border-outline-variant z-20 text-on-surface font-sans overflow-hidden shrink-0 transition-all duration-300">
    <!-- Content Container -->
    <div class="flex flex-col h-full">
        <!-- Logo & Header -->
        <div class="px-6 mb-8 flex items-center justify-between gap-3 sidebar-header-container transition-all duration-300">
            <div id="sidebar-logo" class="flex items-center gap-3 overflow-hidden">
                <div class="w-12 h-12 rounded-xl border border-outline-variant shadow-sm bg-surface-container-low p-1.5 shrink-0 flex items-center justify-center">
                    <img src="/logo.png" alt="Pro Ats Logo" class="w-full h-full object-contain rounded-md"/>
                </div>
            </div>
            <button id="sidebar-toggle" class="p-1.5 rounded-lg hover:bg-slate-100 text-slate-500 hover:text-slate-900 transition-colors cursor-pointer shrink-0">
                <span class="material-symbols-outlined text-[20px] block" id="sidebar-toggle-icon">menu</span>
            </button>
        </div>
        
        <!-- Nav Items -->
        <nav class="flex-1 flex flex-col gap-1.5 px-4 transition-all duration-300">
            <!-- Section: Main -->
            <div class="px-3 mb-1 text-[10px] font-bold text-slate-400 uppercase tracking-widest nav-label-header transition-all duration-300">Main</div>

            <!-- Dashboard -->
            <a class="flex items-center gap-3 px-3 py-2.5 rounded-xl transition-all duration-200 group {{ Route::is('dashboard') ? 'bg-indigo-600 text-white font-semibold shadow-md shadow-indigo-600/20' : 'text-slate-600 hover:text-indigo-600 hover:bg-slate-50' }}" href="{{ route('dashboard') }}">
                <span class="material-symbols-outlined text-[20px] transition-colors shrink-0 {{ Route::is('dashboard') ? 'text-white' : 'text-slate-500 group-hover:text-indigo-600' }}">dashboard</span>
                <span class="text-sm nav-item-text transition-all duration-300">Dashboard</span>
            </a>

            <!-- Section: Operations -->
            <div class="px-3 mt-4 mb-1 text-[10px] font-bold text-slate-400 uppercase tracking-widest nav-label-header transition-all duration-300">Operations</div>

            <!-- Inventory -->
            <a class="flex items-center gap-3 px-3 py-2.5 rounded-xl transition-all duration-200 group {{ Route::is('inventory') ? 'bg-indigo-600 text-white font-semibold shadow-md shadow-indigo-600/20' : 'text-slate-600 hover:text-indigo-600 hover:bg-slate-50' }}" href="{{ route('inventory') }}">
                <span class="material-symbols-outlined text-[20px] transition-colors shrink-0 {{ Route::is('inventory') ? 'text-white' : 'text-slate-500 group-hover:text-indigo-600' }}">inventory_2</span>
                <span class="text-sm nav-item-text transition-all duration-300">Inventory</span>
            </a>
            <!-- Finance -->
            <a class="flex items-center gap-3 px-3 py-2.5 rounded-xl transition-all duration-200 group {{ Route::is('finance') ? 'bg-indigo-600 text-white font-semibold shadow-md shadow-indigo-600/20' : 'text-slate-600 hover:text-indigo-600 hover:bg-slate-50' }}" href="{{ route('finance') }}">
                <span class="material-symbols-outlined text-[20px] transition-colors shrink-0 {{ Route::is('finance') ? 'text-white' : 'text-slate-500 group-hover:text-indigo-600' }}">payments</span>
                <span class="text-sm nav-item-text transition-all duration-300">Finance</span>
            </a>
            <!-- Vendors -->
            <a class="flex items-center gap-3 px-3 py-2.5 rounded-xl transition-all duration-200 group {{ Route::is('vendors') ? 'bg-indigo-600 text-white font-semibold shadow-md shadow-indigo-600/20' : 'text-slate-600 hover:text-indigo-600 hover:bg-slate-50' }}" href="{{ route('vendors') }}">
                <span class="material-symbols-outlined text-[20px] transition-colors shrink-0 {{ Route::is('vendors') ? 'text-white' : 'text-slate-500 group-hover:text-indigo-600' }}">storefront</span>
                <span class="text-sm nav-item-text transition-all duration-300">Vendors</span>
            </a>
            <!-- HR Management -->
            <a class="flex items-center gap-3 px-3 py-2.5 rounded-xl transition-all duration-200 group {{ Route::is('hr') ? 'bg-indigo-600 text-white font-semibold shadow-md shadow-indigo-600/20' : 'text-slate-600 hover:text-indigo-600 hover:bg-slate-50' }}" href="{{ route('hr') }}">
                <span class="material-symbols-outlined text-[20px] transition-colors shrink-0 {{ Route::is('hr') ? 'text-white' : 'text-slate-500 group-hover:text-indigo-600' }}">groups</span>
                <span class="text-sm nav-item-text transition-all duration-300">HR Management</span>
            </a>
            
            <!-- Settings -->
            <a class="flex items-center gap-3 px-3 py-2.5 rounded-xl transition-all duration-200 group text-slate-600 hover:text-indigo-600 hover:bg-slate-50 mt-auto" href="#">
                <span class="material-symbols-outlined text-[20px] transition-colors shrink-0 text-slate-500 group-hover:text-indigo-600">settings</span>
                <span class="text-sm nav-item-text transition-all duration-300">Settings</span>
            </a>
            <!-- Sign Out -->
            <form id="logout-form" action="{{ route('logout') }}" method="POST" class="hidden">
                @csrf
            </form>
            <a class="flex items-center gap-3 px-3 py-2.5 rounded-xl transition-all duration-200 text-rose-600 hover:text-rose-700 hover:bg-rose-50 cursor-pointer" href="#" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                <span class="material-symbols-outlined text-[20px] shrink-0 text-rose-600">logout</span>
                <span class="text-sm nav-item-text transition-all duration-300 font-semibold">Sign Out</span>
            </a>
        </nav>
    </div>
</aside>

<!-- Main Content Area -->
<main id="main-content" class="flex-1 flex flex-col md:ml-[240px] w-full h-full overflow-hidden transition-all duration-300">
    <script>
        // Instant Sidebar Collapse State
        if (localStorage.getItem('sidebar-collapsed') === 'true') {
            document.getElementById('sidebar').classList.add('collapsed');
            const mc = document.getElementById('main-content');
            if (mc) mc.classList.add('sidebar-collapsed');
        }
        // Toggle Logic
        document.getElementById('sidebar-toggle').addEventListener('click', () => {
            const sidebar = document.getElementById('sidebar');
            const mc = document.getElementById('main-content');
            sidebar.classList.toggle('collapsed');
            mc.classList.toggle('sidebar-collapsed');
            const isCollapsed = sidebar.classList.contains('collapsed');
            localStorage.setItem('sidebar-collapsed', isCollapsed);
        });
    </script>
    
    <!-- TopNavBar -->
    <header class="flex justify-between items-center h-16 px-container-padding bg-surface dark:bg-background border-b border-outline-variant dark:border-outline z-10 shrink-0 sticky top-0 w-full">
        <div class="flex items-center gap-gutter">
            <div class="relative focus-within:ring-2 focus-within:ring-secondary-container rounded-lg overflow-hidden flex items-center bg-surface-container-low px-stack-sm py-unit w-64 border border-outline-variant">
                <span class="material-symbols-outlined text-on-surface-variant ml-unit">search</span>
                <input class="w-full bg-transparent border-none focus:ring-0 font-body-sm text-body-sm text-on-surface ml-unit py-1" placeholder="Cari..." type="text"/>
            </div>
        </div>
        <div class="flex items-center gap-stack-md">
            <div class="flex items-center gap-unit text-on-surface-variant">
                <button class="p-unit rounded-full hover:bg-surface-container-high transition-colors"><span class="material-symbols-outlined">notifications</span></button>
                <button class="p-unit rounded-full hover:bg-surface-container-high transition-colors"><span class="material-symbols-outlined">history</span></button>
                <button class="p-unit rounded-full hover:bg-surface-container-high transition-colors"><span class="material-symbols-outlined">chat</span></button>
            </div>
            
            <div class="h-8 w-px bg-outline-variant"></div>

            <span class="hidden sm:inline font-body-sm font-bold text-on-surface">{{ auth()->user()->name }}</span>

            <div class="h-8 w-8 rounded-full overflow-hidden bg-surface-container-high shrink-0 border border-outline-variant">
                <img class="object-cover w-full h-full" alt="Administrator Profile" src="https://lh3.googleusercontent.com/aida-public/AB6AXuAQkMq1AMBNLib2h-mipTIddvHoARh9ZgA5Te7DlXq4xQ41YIflmo2TS997PiYSvfZ9cTrBDIL3klDulCID7nUZPJB-xuduWkFXXi2QVzkOFs95qSUDV8OXq5oJD7KtqhCrPvsZjgreMTEu3aEmrzPBQuDCK6QVV57b-Zj8WCOnGP-0uI2v7KyWg78V6xNTfJSinCw3XTM-jCfLG7Xq-RyWfFo6rieIJq1-Fsop88Z3W2HKeqw1zjHXlg"/>
            </div>
        </div>
    </header>
    
    <!-- Page Content -->
    <div class="flex-1 overflow-y-auto p-container-padding w-full">
        @yield('content')
    </div>
</main>

@stack('scripts')
</body>
</html>
