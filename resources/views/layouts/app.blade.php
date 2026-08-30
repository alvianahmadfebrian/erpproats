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
            <div class="flex items-center gap-2 text-on-surface-variant">
                <!-- Notifications Button & Dropdown -->
                <div class="relative">
                    <button onclick="toggleDropdown(event, 'notifications-dropdown')" class="relative p-2 rounded-full hover:bg-surface-container-high transition-colors focus:outline-none" title="Notifikasi">
                        <span class="material-symbols-outlined block">notifications</span>
                        <span id="notif-badge" class="absolute top-1.5 right-1.5 w-2 h-2 bg-rose-500 rounded-full ring-2 ring-white"></span>
                    </button>
                    <!-- Dropdown -->
                    <div id="notifications-dropdown" class="hidden absolute right-0 mt-2 w-80 bg-surface-container-lowest/95 backdrop-blur-sm border border-outline-variant rounded-2xl shadow-xl z-50 overflow-hidden animate-scale-up">
                        <div class="p-4 border-b border-outline-variant/60 flex justify-between items-center bg-surface-container-low/40">
                            <span class="font-body-sm font-bold text-primary">Notifikasi</span>
                            <button onclick="clearNotifications(event)" class="text-xs text-indigo-600 hover:underline">Tandai semua dibaca</button>
                        </div>
                        <div class="divide-y divide-outline-variant/40 max-h-80 overflow-y-auto">
                            <!-- Item 1 -->
                            <a href="{{ route('inventory') }}" class="flex gap-3 p-3.5 hover:bg-surface-container-low/50 transition-colors">
                                <div class="w-9 h-9 rounded-xl bg-amber-500/10 text-amber-600 flex items-center justify-center shrink-0">
                                    <span class="material-symbols-outlined text-[20px]">warning</span>
                                </div>
                                <div class="flex-1 min-w-0 text-left">
                                    <p class="font-body-sm text-[13px] font-semibold text-primary leading-tight">Stok Menipis: Gibson Les Paul</p>
                                    <p class="font-body-sm text-[11px] text-on-surface-variant mt-0.5">Tersisa 4 unit di gudang utama</p>
                                    <span class="text-[10px] text-on-surface-variant/70 mt-1 block">5 menit yang lalu</span>
                                </div>
                            </a>
                            <!-- Item 2 -->
                            <a href="{{ route('hr') }}" class="flex gap-3 p-3.5 hover:bg-surface-container-low/50 transition-colors">
                                <div class="w-9 h-9 rounded-xl bg-indigo-500/10 text-indigo-600 flex items-center justify-center shrink-0">
                                    <span class="material-symbols-outlined text-[20px]">event_note</span>
                                </div>
                                <div class="flex-1 min-w-0 text-left">
                                    <p class="font-body-sm text-[13px] font-semibold text-primary leading-tight">Pengajuan Cuti Baru</p>
                                    <p class="font-body-sm text-[11px] text-on-surface-variant mt-0.5">Sarah Johnson mengajukan cuti tahunan</p>
                                    <span class="text-[10px] text-on-surface-variant/70 mt-1 block">1 jam yang lalu</span>
                                </div>
                            </a>
                            <!-- Item 3 -->
                            <a href="{{ route('finance') }}" class="flex gap-3 p-3.5 hover:bg-surface-container-low/50 transition-colors">
                                <div class="w-9 h-9 rounded-xl bg-emerald-500/10 text-emerald-600 flex items-center justify-center shrink-0">
                                    <span class="material-symbols-outlined text-[20px]">account_balance_wallet</span>
                                </div>
                                <div class="flex-1 min-w-0 text-left">
                                    <p class="font-body-sm text-[13px] font-semibold text-primary leading-tight">Payroll Selesai Diproses</p>
                                    <p class="font-body-sm text-[11px] text-on-surface-variant mt-0.5">Gaji karyawan bulan April sukses ditransfer</p>
                                    <span class="text-[10px] text-on-surface-variant/70 mt-1 block">3 jam yang lalu</span>
                                </div>
                            </a>
                        </div>
                    </div>
                </div>

                <!-- History Button & Dropdown -->
                <div class="relative">
                    <button onclick="toggleDropdown(event, 'history-dropdown')" class="p-2 rounded-full hover:bg-surface-container-high transition-colors focus:outline-none" title="Aktivitas Terbaru">
                        <span class="material-symbols-outlined block">history</span>
                    </button>
                    <!-- Dropdown -->
                    <div id="history-dropdown" class="hidden absolute right-0 mt-2 w-80 bg-surface-container-lowest/95 backdrop-blur-sm border border-outline-variant rounded-2xl shadow-xl z-50 overflow-hidden animate-scale-up">
                        <div class="p-4 border-b border-outline-variant/60 flex justify-between items-center bg-surface-container-low/40">
                            <span class="font-body-sm font-bold text-primary">Aktivitas Terbaru</span>
                        </div>
                        <div class="divide-y divide-outline-variant/40 max-h-80 overflow-y-auto">
                            <!-- Item 1 -->
                            <div class="flex gap-3 p-3.5 hover:bg-surface-container-low/50 transition-colors text-left">
                                <div class="w-8 h-8 rounded-full bg-slate-100 flex items-center justify-center text-slate-600 shrink-0">
                                    <span class="material-symbols-outlined text-[18px]">edit</span>
                                </div>
                                <div class="flex-1 min-w-0">
                                    <p class="font-body-sm text-[12px] text-primary"><span class="font-semibold">Anda</span> mengubah detail vendor <span class="font-semibold">Yamaha Music</span></p>
                                    <span class="text-[10px] text-on-surface-variant/70 mt-0.5 block">10 menit yang lalu</span>
                                </div>
                            </div>
                            <!-- Item 2 -->
                            <div class="flex gap-3 p-3.5 hover:bg-surface-container-low/50 transition-colors text-left">
                                <div class="w-8 h-8 rounded-full bg-slate-100 flex items-center justify-center text-slate-600 shrink-0">
                                    <span class="material-symbols-outlined text-[18px]">add_circle</span>
                                </div>
                                <div class="flex-1 min-w-0">
                                    <p class="font-body-sm text-[12px] text-primary"><span class="font-semibold">Anda</span> menambahkan item inventaris <span class="font-semibold">Fender Stratocaster</span></p>
                                    <span class="text-[10px] text-on-surface-variant/70 mt-0.5 block">1 jam yang lalu</span>
                                </div>
                            </div>
                            <!-- Item 3 -->
                            <div class="flex gap-3 p-3.5 hover:bg-surface-container-low/50 transition-colors text-left">
                                <div class="w-8 h-8 rounded-full bg-slate-100 flex items-center justify-center text-slate-600 shrink-0">
                                    <span class="material-symbols-outlined text-[18px]">payments</span>
                                </div>
                                <div class="flex-1 min-w-0">
                                    <p class="font-body-sm text-[12px] text-primary"><span class="font-semibold">Anda</span> mencatat pemasukan baru <span class="font-semibold text-emerald-600">Rp 4.500.000</span></p>
                                    <span class="text-[10px] text-on-surface-variant/70 mt-0.5 block">3 jam yang lalu</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Chat/Feedback Button & Dropdown -->
                <div class="relative">
                    <button onclick="toggleDropdown(event, 'chat-dropdown')" class="p-2 rounded-full hover:bg-surface-container-high transition-colors focus:outline-none" title="Live Support & Feedback">
                        <span class="material-symbols-outlined block">chat</span>
                    </button>
                    <!-- Dropdown -->
                    <div id="chat-dropdown" class="hidden absolute right-0 mt-2 w-80 bg-surface-container-lowest/95 backdrop-blur-sm border border-outline-variant rounded-2xl shadow-xl z-50 overflow-hidden animate-scale-up">
                        <div class="p-4 border-b border-outline-variant/60 flex justify-between items-center bg-surface-container-low/40">
                            <div class="flex items-center gap-2">
                                <div class="w-2 h-2 bg-green-500 rounded-full animate-pulse"></div>
                                <span class="font-body-sm font-bold text-primary">Live Support & Feedback</span>
                            </div>
                        </div>
                        <!-- Chat message thread -->
                        <div class="p-4 space-y-4 max-h-64 overflow-y-auto" id="chat-messages">
                            <div class="flex items-start gap-2.5">
                                <div class="w-7 h-7 rounded-full bg-indigo-100 flex items-center justify-center text-indigo-600 shrink-0 text-[10px] font-bold">CS</div>
                                <div class="bg-surface-container-low p-3 rounded-2xl rounded-tl-none font-body-sm text-[12px] text-on-surface leading-normal text-left max-w-[80%]">
                                    Halo! Ada yang bisa kami bantu? Anda juga bisa memberikan masukan/feedback di sini.
                                </div>
                            </div>
                        </div>
                        <!-- Chat input -->
                        <form id="chat-form" onsubmit="sendChatMessage(event)" class="p-3 border-t border-outline-variant/60 bg-surface-container-low/20 flex gap-2">
                            <label class="sr-only" for="chat-message-input">Pesan</label>
                            <input id="chat-message-input" type="text" placeholder="Ketik pesan..." required class="flex-1 bg-surface-container-lowest border border-outline-variant rounded-full px-3.5 py-1.5 font-body-sm text-xs focus:outline-none focus:ring-2 focus:ring-indigo-500/50"/>
                            <button type="submit" class="w-8 h-8 rounded-full bg-indigo-600 text-white flex items-center justify-center hover:bg-indigo-700 transition-colors shrink-0">
                                <span class="material-symbols-outlined text-[16px] block">send</span>
                            </button>
                        </form>
                    </div>
                </div>
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

<script>
    function toggleDropdown(event, dropdownId) {
        event.preventDefault();
        event.stopPropagation();
        const dropdowns = ['notifications-dropdown', 'history-dropdown', 'chat-dropdown'];
        dropdowns.forEach(id => {
            const el = document.getElementById(id);
            if (!el) return;
            if (id === dropdownId) {
                el.classList.toggle('hidden');
            } else {
                el.classList.add('hidden');
            }
        });
    }

    function clearNotifications(event) {
        event.preventDefault();
        event.stopPropagation();
        const badge = document.getElementById('notif-badge');
        if (badge) {
            badge.classList.add('hidden');
        }
        alert('Semua notifikasi telah ditandai sebagai dibaca!');
    }

    function sendChatMessage(event) {
        event.preventDefault();
        const input = document.getElementById('chat-message-input');
        const text = input.value.trim();
        if (!text) return;

        input.value = '';

        const container = document.getElementById('chat-messages');
        
        // Append user message
        const userMsg = document.createElement('div');
        userMsg.className = 'flex justify-end';
        userMsg.innerHTML = `
            <div class="bg-indigo-600 text-white p-3 rounded-2xl rounded-tr-none font-body-sm text-[12px] leading-normal text-left max-w-[80%] shadow-sm">
                ${escapeHtml(text)}
            </div>
        `;
        container.appendChild(userMsg);
        container.scrollTop = container.scrollHeight;

        // Auto reply
        setTimeout(() => {
            const botMsg = document.createElement('div');
            botMsg.className = 'flex items-start gap-2.5 animate-fade-in';
            botMsg.innerHTML = `
                <div class="w-7 h-7 rounded-full bg-indigo-100 flex items-center justify-center text-indigo-600 shrink-0 text-[10px] font-bold">CS</div>
                <div class="bg-surface-container-low p-3 rounded-2xl rounded-tl-none font-body-sm text-[12px] text-on-surface leading-normal text-left max-w-[80%] shadow-sm">
                    Terima kasih atas pesan Anda! Masukan Anda telah kami catat untuk pengembangan sistem ERP ini.
                </div>
            `;
            container.appendChild(botMsg);
            container.scrollTop = container.scrollHeight;
        }, 1000);
    }

    function escapeHtml(str) {
        return str.replace(/&/g, "&amp;").replace(/</g, "&lt;").replace(/>/g, "&gt;").replace(/"/g, "&quot;").replace(/'/g, "&#039;");
    }

    // Close on click outside
    window.addEventListener('click', () => {
        const dropdowns = ['notifications-dropdown', 'history-dropdown', 'chat-dropdown'];
        dropdowns.forEach(id => {
            const el = document.getElementById(id);
            if (el) {
                el.classList.add('hidden');
            }
        });
    });

    // Stop propagation inside dropdowns
    document.querySelectorAll('#notifications-dropdown, #history-dropdown, #chat-dropdown').forEach(el => {
        el.addEventListener('click', (e) => {
            e.stopPropagation();
        });
    });
</script>
</body>
</html>
