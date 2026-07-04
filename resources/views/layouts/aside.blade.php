<aside
    id="sidebar"
    class="fixed lg:static top-0 left-[-300px] lg:left-0 z-50
    w-72 h-screen bg-white rounded-r-xl lg:rounded-xl
    shadow-lg border-r border-gray-100
    p-4 transition-all duration-300 overflow-y-auto">

    <!-- LOGO -->
    <div class="relative pb-5 border-b border-gray-100">

        <div class="flex items-center justify-center gap-3">

            <!-- Logo -->
            <img
                src="{{ asset('images/logo.jpg') }}"
                alt="Glowver Logo"
                class="w-14 h-14 object-contain">

            <!-- Text -->
            <div>

                <h2 class="font-bold text-2xl text-slate-800 leading-none">
                    Glowver
                </h2>

                <p class="text-xs text-gray-400 mt-1">
                    Point Of Sale System
                </p>

            </div>

        </div>

        <button
            id="closeBtn"
            class="lg:hidden absolute right-0 top-0">

            <i class="fa-solid fa-xmark text-xl"></i>

        </button>

    </div>

    <!-- MENU -->
    <div class="mt-6">

        <h6 class="submenu-hdr text-sm font-semibold mb-3 text-gray-700 ">
            Main
        </h6>

        <ul class="space-y-2">

            <!-- Dashboard -->
            <li>

                <a href="{{ route('dashboard') }}"
                   class="flex items-center gap-3 p-2 m-3 rounded-md transition-all duration-200

                   {{ request()->routeIs('dashboard')
                        ? 'bg-blue-50 text-blue-600'
                        : 'text-slate-600 hover:bg-blue-50 hover:text-blue-600' }}">

                    <i class="fa-solid fa-house w-5 text-center text-sm" ></i>

                    <span class="font-small">
                        Dashboard
                    </span>

                </a>

            </li>

            <!-- Inventory -->
            <li>

                <a href="{{ route('inventory') }}"
                   class="flex items-center gap-3 p-2 m-3 rounded-md transition-all duration-200

                   {{ request()->routeIs('inventory*')
                                                ? 'bg-blue-50 text-blue-600'
                        : 'text-slate-600 hover:bg-blue-50 hover:text-blue-600' }}">

                    <i class="fa-solid fa-boxes-stacked w-5 text-center"></i>

                    <span class="font-small">
                        Inventory
                    </span>

                </a>

            </li>

        </ul>

    </div>

    <!-- TRANSACTION -->
    <div class="mt-8">

        <h6 class="submenu-hdr text-sm font-semibold mb-3 text-gray-700 ">
            Transaction
        </h6>

        <ul class="space-y-2">

            <li>

                <a href="{{ route('transactions.sales') }}"
                   class="flex items-center gap-3 p-2 m-3 rounded-md transition-all duration-200

                   {{ request()->routeIs('transactions.sales*')
                        ? 'bg-blue-50 text-blue-600'
                        : 'text-slate-600 hover:bg-blue-50 hover:text-blue-600' }}">

                    <i class="fa-solid fa-cash-register w-5 text-center"></i>

                    <span class="font-small">
                        Sales
                    </span>

                </a>

            </li>

            <li>

                <a href="{{ route('purchase') }}"
                   class="flex items-center gap-3 p-2 m-3 rounded-md transition-all duration-200

                   {{ request()->routeIs('purchase*')
                                                ? 'bg-blue-50 text-blue-600'
                        : 'text-slate-600 hover:bg-blue-50 hover:text-blue-600' }}">

                    <i class="fa-solid fa-cart-shopping w-5 text-center"></i>

                    <span class="font-small">
                        Purchase
                    </span>

                </a>

            </li>

            <li>

                <a href="{{ route('sales-return') }}"
                   class="flex items-center gap-3 p-2 m-3 rounded-md transition-all duration-200

                   {{ request()->routeIs('sales-return*')
                                                ? 'bg-blue-50 text-blue-600'
                        : 'text-slate-600 hover:bg-blue-50 hover:text-blue-600' }}">

                    <i class="fa-solid fa-arrow-rotate-left w-5 text-center"></i>

                    <span class="font-small">
                        Sales Return
                    </span>

                </a>

            </li>

            <li>

                <a href="{{ route('purchase-return') }}"
                   class="flex items-center gap-3 p-2 m-3 rounded-md transition-all duration-200

                   {{ request()->routeIs('purchase-return*')
                                                ? 'bg-blue-50 text-blue-600'
                        : 'text-slate-600 hover:bg-blue-50 hover:text-blue-600' }}">

                    <i class="fa-solid fa-arrow-rotate-right w-5 text-center"></i>

                    <span class="font-small">
                        Purchase Return
                    </span>

                </a>

            </li>

        </ul>

    </div>

    <!-- FOOTER USER -->
    <div class="mt-10 border-t border-gray-100 pt-5">

        <div class="flex items-center gap-3">

            <div
                class="w-12 h-12 rounded-full bg-blue-600 text-white
                flex items-center justify-center font-bold">

                A

            </div>

            <div>

                <h4 class="font-semibold text-slate-700">
                    Admin
                </h4>

                <p class="text-sm text-gray-400">
                    Administrator
                </p>

            </div>

        </div>

    </div>

</aside>