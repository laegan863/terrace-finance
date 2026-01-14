@php
    use Illuminate\Support\Facades\Route;

    $isActive = fn ($name) => request()->routeIs($name);
    $dashboardActive = $isActive('dashboard');
    $leadsActive = $isActive('tfc.leads.index');
    $pricingActive = $isActive('tfc.pricing-factor.index');
    $applicationsActive = $isActive('tfc.applications.index');
    $invoicesOpen = $isActive('tfc.invoices.index') || $isActive('tfc.invoices.history');
    $offersOpen = $isActive('tfc.offers.index') || $isActive('tfc.offers.history');
    $applicationStatusActive = $isActive('tfc.application-status.index');
    $statusNotificationsOpen = $isActive('tfc.status-notifications.index') || $isActive('tfc.status-notifications.history');

@endphp

<div class="sidebar" data-background-color="dark">
    <div class="sidebar-logo">
        <div class="logo-header" data-background-color="dark">
            <a href="{{ url('/') }}" class="logo">
                <img
                    src="{{ asset('images/logo/logo.png') }}"
                    alt="navbar brand"
                    class="navbar-brand bg-white"
                    height="50"
                />
            </a>
            <div class="nav-toggle">
                <button class="btn btn-toggle toggle-sidebar">
                    <i class="gg-menu-right"></i>
                </button>
                <button class="btn btn-toggle sidenav-toggler">
                    <i class="gg-menu-left"></i>
                </button>
            </div>
            <button class="topbar-toggler more">
                <i class="gg-more-vertical-alt"></i>
            </button>
        </div>
    </div>

    <div class="sidebar-wrapper scrollbar scrollbar-inner">
        <div class="sidebar-content">
            <ul class="nav nav-secondary">

                <li class="nav-item {{ $dashboardActive ? 'active' : '' }}">
                    <a href="{{ Route::has('dashboard') ? route('dashboard') : '#' }}">
                        <i class="fas fa-home"></i>
                        <p>Dashboard</p>
                    </a>
                </li>

                <li class="nav-item {{ $leadsActive ? 'active' : '' }}">
                    <a href="{{ Route::has('tfc.leads.index') ? route('tfc.leads.index') : '#' }}">
                        <i class="fas fa-user-plus"></i>
                        <p>Leads</p>
                    </a>
                </li>

                <li class="nav-item {{ $pricingActive ? 'active' : '' }}">
                    <a href="{{ Route::has('tfc.pricing-factor.index') ? route('tfc.pricing-factor.index') : '#' }}">
                        <i class="fas fa-calculator"></i>
                        <p>Pricing Factor</p>
                    </a>
                </li>

                <li class="nav-item {{ $applicationsActive ? 'active' : '' }}">
                    <a href="{{ Route::has('tfc.applications.index') ? route('tfc.applications.index') : '#' }}">
                        <i class="fas fa-file-signature"></i>
                        <p>Applications</p>
                    </a>
                </li>

                {{-- Invoices (with submenu) --}}
                <li class="nav-item {{ $invoicesOpen ? 'active' : '' }}">
                    <a data-bs-toggle="collapse"
                    href="#invoicesMenu"
                    class="{{ $invoicesOpen ? '' : 'collapsed' }}"
                    aria-expanded="{{ $invoicesOpen ? 'true' : 'false' }}">
                        <i class="fas fa-file-invoice-dollar"></i>
                        <p>Invoices</p>
                        <span class="caret"></span>
                    </a>

                    <div class="collapse {{ $invoicesOpen ? 'show' : '' }}" id="invoicesMenu">
                        <ul class="nav nav-collapse">
                            <li class="{{ $isActive('tfc.invoices.index') ? 'active' : '' }}">
                                <a href="{{ Route::has('tfc.invoices.index') ? route('tfc.invoices.index') : '#' }}">
                                    <span class="sub-item">Post Invoice</span>
                                </a>
                            </li>

                            <li class="{{ $isActive('tfc.invoices.history') ? 'active' : '' }}">
                                <a href="{{ Route::has('tfc.invoices.history') ? route('tfc.invoices.history') : '#' }}">
                                    <span class="sub-item">Invoice History</span>
                                </a>
                            </li>
                        </ul>
                    </div>
                </li>

                {{-- Offers (with submenu) --}}
                <li class="nav-item {{ $offersOpen ? 'active' : '' }}">
                    <a data-bs-toggle="collapse"
                    href="#offersMenu"
                    class="{{ $offersOpen ? '' : 'collapsed' }}"
                    aria-expanded="{{ $offersOpen ? 'true' : 'false' }}">
                        <i class="fas fa-handshake"></i>
                        <p>Offers</p>
                        <span class="caret"></span>
                    </a>

                    <div class="collapse {{ $offersOpen ? 'show' : '' }}" id="offersMenu">
                        <ul class="nav nav-collapse">
                            <li class="{{ $isActive('tfc.offers.index') ? 'active' : '' }}">
                                <a href="{{ Route::has('tfc.offers.index') ? route('tfc.offers.index') : '#' }}">
                                    <span class="sub-item">Post Offer</span>
                                </a>
                            </li>

                            <li class="{{ $isActive('tfc.offers.history') ? 'active' : '' }}">
                                <a href="{{ Route::has('tfc.offers.history') ? route('tfc.offers.history') : '#' }}">
                                    <span class="sub-item">Offer History</span>
                                </a>
                            </li>
                        </ul>
                    </div>
                </li>

                <li class="nav-item {{ $applicationStatusActive ? 'active' : '' }}">
                    <a href="{{ Route::has('tfc.application-status.index') ? route('tfc.application-status.index') : '#' }}">
                        <i class="fas fa-clipboard-check"></i>
                        <p>Application Status</p>
                    </a>
                </li>

                <li class="nav-item {{ $statusNotificationsOpen ? 'active' : '' }}">
                    <a data-bs-toggle="collapse"
                    href="#statusNotificationsMenu"
                    class="{{ $statusNotificationsOpen ? '' : 'collapsed' }}"
                    aria-expanded="{{ $statusNotificationsOpen ? 'true' : 'false' }}">
                        <i class="fas fa-bell"></i>
                        <p>Status Notifications</p>
                        <span class="caret"></span>
                    </a>

                    <div class="collapse {{ $statusNotificationsOpen ? 'show' : '' }}" id="statusNotificationsMenu">
                        <ul class="nav nav-collapse">
                            <li class="{{ $isActive('tfc.status-notifications.index') ? 'active' : '' }}">
                                <a href="{{ Route::has('tfc.status-notifications.index') ? route('tfc.status-notifications.index') : '#' }}">
                                    <span class="sub-item">Receive Status Notification</span>
                                </a>
                            </li>

                            <li class="{{ $isActive('tfc.status-notifications.history') ? 'active' : '' }}">
                                <a href="{{ Route::has('tfc.status-notifications.history') ? route('tfc.status-notifications.history') : '#' }}">
                                    <span class="sub-item">Status Notification History</span>
                                </a>
                            </li>
                        </ul>
                    </div>
                </li>


            </ul>

        </div>
    </div>
</div>
