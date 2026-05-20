<aside class="sidebar-wrapper" data-simplebar="true">
    <div class="sidebar-header">
        <div>
            <img src="{{ asset('assets/backend') }}/assets/images/logo-icon.png" class="logo-icon" alt="logo icon">
        </div>
        <div>
            <h4 class="logo-text">Jewelry Shop</h4>
        </div>
        <div class="toggle-icon ms-auto"><i class="bi bi-chevron-double-left"></i></div>
    </div>

    <ul class="metismenu" id="menu">
        <li>
            <a href="{{ route('admin.dashboard') }}" wire:navigate>
                <div class="parent-icon"><i class="bi bi-house-door"></i></div>
                <div class="menu-title">{{ __('messages.dashboard') }}</div>
            </a>
        </li>

        <li class="menu-label">{{ __('messages.master_data') }}</li>
        <li>
            <a href="javascript:;" class="has-arrow">
                <div class="parent-icon"><i class="bi bi-building"></i></div>
                <div class="menu-title">{{ __('messages.master_data') }}</div>
            </a>
            <ul>
                <li><a href="#"><i class="bi bi-arrow-right-short"></i>{{ __('messages.companies') }}</a></li>
                <li><a href="#"><i class="bi bi-arrow-right-short"></i>{{ __('messages.branches') }}</a></li>
                <li><a href="#"><i class="bi bi-arrow-right-short"></i>{{ __('messages.warehouses') }}</a></li>
            </ul>
        </li>
        <li>
            <a href="javascript:;" class="has-arrow">
                <div class="parent-icon"><i class="bi bi-people"></i></div>
                <div class="menu-title">{{ __('messages.user_management') }}</div>
            </a>
            <ul>
                <li><a href="#"><i class="bi bi-arrow-right-short"></i>{{ __('messages.users') }}</a></li>
                <li><a href="#"><i class="bi bi-arrow-right-short"></i>{{ __('messages.roles') }}</a></li>
                <li><a href="#"><i class="bi bi-arrow-right-short"></i>{{ __('messages.permissions') }}</a></li>
            </ul>
        </li>

        <li class="menu-label">{{ __('messages.catalog') }}</li>
        <li>
            <a href="javascript:;" class="has-arrow">
                <div class="parent-icon"><i class="bi bi-gem"></i></div>
                <div class="menu-title">{{ __('messages.catalog') }}</div>
            </a>
            <ul>
                <li><a href="#"><i class="bi bi-arrow-right-short"></i>{{ __('messages.categories') }}</a></li>
                <li><a href="#"><i class="bi bi-arrow-right-short"></i>{{ __('messages.metal_types') }}</a></li>
                <li><a href="#"><i class="bi bi-arrow-right-short"></i>{{ __('messages.purities') }}</a></li>
                <li><a href="#"><i class="bi bi-arrow-right-short"></i>{{ __('messages.gemstones') }}</a></li>
                <li><a href="#"><i class="bi bi-arrow-right-short"></i>{{ __('messages.products') }}</a></li>
            </ul>
        </li>
        <li>
            <a href="javascript:;" class="has-arrow">
                <div class="parent-icon"><i class="bi bi-person-vcard"></i></div>
                <div class="menu-title">{{ __('messages.parties') }}</div>
            </a>
            <ul>
                <li><a href="#"><i class="bi bi-arrow-right-short"></i>{{ __('messages.customers') }}</a></li>
                <li><a href="#"><i class="bi bi-arrow-right-short"></i>{{ __('messages.suppliers') }}</a></li>
                <li><a href="#"><i class="bi bi-arrow-right-short"></i>{{ __('messages.goldsmiths') }}</a></li>
            </ul>
        </li>

        <li class="menu-label">{{ __('messages.inventory') }}</li>
        <li>
            <a href="javascript:;" class="has-arrow">
                <div class="parent-icon"><i class="bi bi-box-seam"></i></div>
                <div class="menu-title">{{ __('messages.inventory') }}</div>
            </a>
            <ul>
                <li><a href="#"><i class="bi bi-arrow-right-short"></i>{{ __('messages.stock_items') }}</a></li>
                <li><a href="#"><i class="bi bi-arrow-right-short"></i>{{ __('messages.stock_movements') }}</a></li>
                <li><a href="#"><i class="bi bi-arrow-right-short"></i>{{ __('messages.stock_adjustments') }}</a></li>
                <li><a href="#"><i class="bi bi-arrow-right-short"></i>{{ __('messages.stock_transfers') }}</a></li>
                <li><a href="#"><i class="bi bi-arrow-right-short"></i>{{ __('messages.gold_rates') }}</a></li>
            </ul>
        </li>

        <li class="menu-label">{{ __('messages.purchases') }} / {{ __('messages.sales') }}</li>
        <li>
            <a href="#">
                <div class="parent-icon"><i class="bi bi-cart-plus"></i></div>
                <div class="menu-title">{{ __('messages.purchases') }}</div>
            </a>
        </li>
        <li>
            <a href="javascript:;" class="has-arrow">
                <div class="parent-icon"><i class="bi bi-cash-coin"></i></div>
                <div class="menu-title">{{ __('messages.sales') }}</div>
            </a>
            <ul>
                <li><a href="#"><i class="bi bi-arrow-right-short"></i>{{ __('messages.sales') }}</a></li>
                <li><a href="#"><i class="bi bi-arrow-right-short"></i>{{ __('messages.sale_returns') }}</a></li>
                <li><a href="#"><i class="bi bi-arrow-right-short"></i>{{ __('messages.installments') }}</a></li>
            </ul>
        </li>

        <li class="menu-label">{{ __('messages.repairs') }} / {{ __('messages.custom_orders') }}</li>
        <li>
            <a href="#">
                <div class="parent-icon"><i class="bi bi-tools"></i></div>
                <div class="menu-title">{{ __('messages.repairs') }}</div>
            </a>
        </li>
        <li>
            <a href="#">
                <div class="parent-icon"><i class="bi bi-pencil-square"></i></div>
                <div class="menu-title">{{ __('messages.custom_orders') }}</div>
            </a>
        </li>

        <li class="menu-label">{{ __('messages.finance') }}</li>
        <li>
            <a href="javascript:;" class="has-arrow">
                <div class="parent-icon"><i class="bi bi-wallet2"></i></div>
                <div class="menu-title">{{ __('messages.finance') }}</div>
            </a>
            <ul>
                <li><a href="#"><i class="bi bi-arrow-right-short"></i>{{ __('messages.expenses') }}</a></li>
                <li><a href="#"><i class="bi bi-arrow-right-short"></i>{{ __('messages.expense_categories') }}</a></li>
                <li><a href="#"><i class="bi bi-arrow-right-short"></i>{{ __('messages.cash_accounts') }}</a></li>
                <li><a href="#"><i class="bi bi-arrow-right-short"></i>{{ __('messages.cash_transactions') }}</a></li>
            </ul>
        </li>
    </ul>
</aside>
