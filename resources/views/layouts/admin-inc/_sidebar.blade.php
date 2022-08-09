<div class="main-menu menu-fixed menu-light menu-accordion menu-shadow" data-scroll-to-active="true">



    <div class="navbar-header">

        <ul class="nav navbar-nav flex-row">

            <li class="nav-item mr-auto">

                <a class="navbar-brand" href="{{ route('admin.dashboard') }}">

                    <div class="brand-logo"></div>

                    <h2 class="brand-text mb-0">

                        {{config('app.name', 'Laravel')}}

                    </h2>

                </a>

            </li>

            <li class="nav-item nav-toggle">

                <a class="nav-link modern-nav-toggle pr-0" data-toggle="collapse">

                    <i class="feather icon-x d-block d-xl-none font-medium-4 primary toggle-icon"></i>

                    <i class="toggle-icon feather icon-disc font-medium-4 d-none d-xl-block collapse-toggle-icon primary"

                        data-ticon="icon-disc"></i>

                </a>

            </li>

        </ul>

    </div>



    <div class="shadow-bottom"></div>



    <div class="main-menu-content">



        <ul class="navigation navigation-main" id="main-menu-navigation" data-menu="menu-navigation">



            <li class="nav-item {{ request()->is('admin') ? 'active' : '' }}">

                <a href="{{ route('admin.dashboard') }}">

                    <i class="feather icon-home"></i>

                    <span class="menu-title" data-i18n="Dashboard">Dashboard</span>

                </a>

            </li>

            <li class="nav-item {{ request()->is('admin/superCategoryList') ? 'active' : '' }}">

                <a href="{{ route('superCategoryList') }}">

                    <i class="feather icon-grid"></i>

                    <span class="menu-title" data-i18n="Category">Super Category</span>

                </a>

            </li>

            <li class="nav-item {{ request()->is('admin/superSubCategoryList') ? 'active' : '' }}">

                <a href="{{ route('superSubCategoryList') }}">

                    <i class="feather icon-command"></i>

                    <span class="menu-title" data-i18n="Sub Category">Super Sub Category</span>

                </a>

            </li>
            
            
            <li class="nav-item {{ request()->is('admin/category') ? 'active' : '' }}">
                <a href="{{ route('admin.category.index') }}" data-toggle="tooltip" data-placement="right"
                    data-original-title="Category">
                    <i class="feather icon-grid"></i>
                    <span class="menu-title" data-i18n="Category">Category</span>
                </a>
            </li>

            <li class="nav-item {{ request()->is('admin/sub-category') ? 'active' : '' }}">
                <a href="{{ route('admin.sub-category.index') }}" data-toggle="tooltip" data-placement="right"
                    data-original-title="Sub Category">
                    <i class="feather icon-command"></i>
                    <span class="menu-title" data-i18n="Sub Category">Sub Category</span>
                </a>
            </li>



                <li class="nav-item {{ request()->is('admin/tax') ? 'active' : '' }}">
                    <a href="{{ route('admin.tax.index') }}" data-toggle="tooltip" data-placement="right"
                        data-original-title="Tax">
                        <i class="feather icon-dollar-sign"></i>
                        <span class="menu-title" data-i18n="Tax">Tax</span>
                    </a>
                </li>

                <li class="nav-item {{ request()->is('admin/ticket-reasons') ? 'active' : '' }}">
                    <a href="{{ route('admin.ticket-reasons.index') }}" data-toggle="tooltip" data-placement="right"
                        data-original-title="Ticket Reason">
                        <i class="fa fa-ticket"></i>
                        <span class="menu-title" data-i18n="Ticket Reason">Ticket Reason</span>
                    </a>
                </li>

                <li class="nav-item {{ request()->is('admin/offers') ? 'active' : '' }}">
                    <a href="{{ route('admin.offers.index') }}" data-toggle="tooltip" data-placement="right"
                        data-original-title="Offers Management">
                        <i class="fa fa-gift"></i>
                        <span class="menu-title" data-i18n="Offers Management">Offers Management</span>
                    </a>
                </li>
                <li class="nav-item {{ request()->is('admin/notification') ? 'active' : '' }}">
                    <a href="{{ route('admin.offers.index') }}" data-toggle="tooltip" data-placement="right"
                        data-original-title="Send General Notification">
                        <i class="fa fa-gift"></i>
                        <span class="menu-title" data-i18n="Offers Management">General Notification</span>
                    </a>
                </li>
                <li class="nav-item {{ request()->is('admin/banners') ? 'active' : '' }}">
                    <a href="{{ route('admin.banners.index') }}" data-toggle="tooltip" data-placement="right"
                        data-original-title="Manage Advertisement">
                        <i class="fa fa-buysellads"></i>
                        <span class="menu-title" data-i18n="Manage Advertisement">Manage Advertisement</span>
                    </a>
                </li>
                
            <li class="nav-item {{ request()->is('admin/product') ? 'active' : '' }}">

                <a href="{{ route('productList') }}">

                    <i class="feather icon-dollar-sign"></i>

                    <span class="menu-title" data-i18n="Tax">Product Management</span>

                </a>

            </li>
           
            <li class="nav-item {{ request()->is('admin/cmsList') ? 'active' : '' }}">
                <a href="{{ route('cmsList') }}">
                    <i class="feather icon-command"></i>
                    <span class="menu-title" data-i18n="Category">Cms Management</span>
                </a>
            </li>
            <li class="nav-item {{ request()->is('admin/customerList') ? 'active' : '' }}">
                <a href="{{ route('customerList') }}">
                    <i class="feather icon-flag"></i>
                    <span class="menu-title" data-i18n="Category">Customer Management</span>
                </a>
            </li>


            <li class="navigation-header">

                <span>Master</span>

            </li>





            <li class="nav-item {{ request()->is('admin/state') ? 'active' : '' }}">

                <a href="{{ route('admin.state.index') }}">

                    <i class="feather icon-flag"></i>

                    <span class="menu-title" data-i18n="State">State Master</span>

                </a>

            </li>



            <li class="nav-item {{ request()->is('admin/city') ? 'active' : '' }}">

                <a href="{{ route('admin.city.index') }}">

                    <i class="feather icon-flag"></i>

                    <span class="menu-title" data-i18n="City">City Master</span>

                </a>

            </li>



            <li class="nav-item {{ request()->is('admin/pincode') ? 'active' : '' }}">

                <a href="{{ route('admin.pincode.index') }}">

                    <i class="feather icon-flag"></i>

                    <span class="menu-title" data-i18n="Pincode">Pincode Master</span>

                </a>

            </li>
            
            <li class="nav-item {{ request()->is('admin/area') ? 'active' : '' }}">
                <a href="{{ route('admin.area.index') }}" data-toggle="tooltip" data-placement="right"
                    data-original-title="Area Master">
                    <i class="feather icon-flag"></i>
                    <span class="menu-title" data-i18n="Area">Area Master</span>
                </a>
            </li>
            <li class="nav-item {{ request()->is('admin/setting') ? 'active' : '' }}">
                <a href="{{ route('admin.setting.index') }}" data-toggle="tooltip" data-placement="right"
                    data-original-title="Area Master">
                    <i class="feather icon-flag"></i>
                    <span class="menu-title" data-i18n="Area">Setting</span>
                </a>
            </li>


        </ul>

    </div>

</div>