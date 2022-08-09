<div class="main-menu menu-fixed menu-light menu-accordion menu-shadow" data-scroll-to-active="true">



    <div class="navbar-header">

        <ul class="nav navbar-nav flex-row">

            <li class="nav-item mr-auto">

                <a class="navbar-brand" href="{{ route('admin.dashboard') }}">

                    {{-- <div class="brand-logo"></div> --}}
                    <h2 class="brand-logo mb-0 p-0"><img src="{{ asset('assets/admin/app-assets/images/pages/4.svg') }}" height="34px" width="40px"></h2>

                    <h2 class="brand-text mb-0">
                        {{-- <img src="{{ asset('assets/admin/app-assets/images/pages/3.svg') }}" height="34px" width="40px"> --}}
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

                <a href="{{ route('admin.dashboard') }}" data-toggle="tooltip" data-placement="right"
                    data-original-title="Dashboard">

                    <i class="feather icon-home"></i>

                    <span class="menu-title" data-i18n="Dashboard">Dashboard</span>

                </a>

            </li>



            @if(Auth()->user()->permissions->contains('name','admin.users.index'))



            <li class="nav-item {{ request()->is('admin/users') ? 'active' : '' }}">

                <a href="{{ route('admin.users.index') }}" data-toggle="tooltip" data-placement="right"
                    data-original-title="Customer Management">

                    <i class="feather icon-users"></i>

                    <span class="menu-title" data-i18n="Customer Management">Customer Management</span>

                </a>

            </li>



            @endif



            @if(Auth()->user()->permissions->contains('name','admin.delivery_associates.index'))



            <li class="nav-item {{ request()->is('admin/delivery-associates') ? 'active' : '' }}">

                <a href="{{ route('admin.delivery_associates.index') }}" data-toggle="tooltip" data-placement="right"
                    data-original-title="Delivery Associates Management">

                    <i class="feather icon-users"></i>

                    <span class="menu-title" data-i18n="Delivery Associates Management">Delivery Associates

                        Management</span>

                </a>

            </li>



            @endif



            @if(Auth()->user()->permissions->contains('name','admin.sub_admins.index'))



            <li class="nav-item {{ request()->is('admin/sub-admins') ? 'active' : '' }}">

                <a href="{{ route('admin.sub_admins.index') }}" data-toggle="tooltip" data-placement="right"
                    data-original-title="Admin Management">

                    <i class="feather icon-users"></i>

                    <span class="menu-title" data-i18n="Admin Management">Admin Management</span>

                </a>

            </li>



            @endif


            @if(
            Auth()->user()->permissions->contains('name','admin.booking.ongoing') ||
            Auth()->user()->permissions->contains('name','admin.booking.past') ||
            Auth()->user()->permissions->contains('name','admin.booking.upcoming') ||
            Auth()->user()->permissions->contains('name','admin.booking.list') ||
            Auth()->user()->permissions->contains('name','admin.booking.returnOrders')
            )

            <li class="nav-item has-sub">

                <a href="#">
                    <i class="feather icon-calendar"></i>
                    <span class="menu-title" data-i18n="My Booking">My Booking</span>
                </a>

                <ul class="menu-content" style="">
                    @if( Auth()->user()->permissions->contains('name','admin.booking.list') )

                    <li class="is-shown {{ request()->is('admin/booking/allBooking') ? 'active' : '' }}">
                        <a href="{{ route('admin.booking.list') }}">
                            <i class="feather icon-circle"></i>
                            <span class="menu-item" data-i18n="Ongoing Booking">All Booking</span>
                        </a>
                    </li>     
                    
                    @endif
                    @if( Auth()->user()->permissions->contains('name','admin.booking.ongoing') )

                    <li class="is-shown {{ request()->is('admin/booking/ongoing') ? 'active' : '' }}">
                        <a href="{{ route('admin.booking.ongoing') }}">
                            <i class="feather icon-circle"></i>
                            <span class="menu-item" data-i18n="Ongoing Booking">Ongoing Booking</span>
                        </a>
                    </li>     
                    
                    @endif

                    @if( Auth()->user()->permissions->contains('name','admin.booking.past') )

                    <li class="is-shown {{ request()->is('admin/booking/past') ? 'active' : '' }}">
                        <a href="{{ route('admin.booking.past') }}">
                            <i class="feather icon-circle"></i>
                            <span class="menu-item" data-i18n="Past Booking">Past Booking</span>
                        </a>
                    </li>     
                    
                    @endif

                    @if( Auth()->user()->permissions->contains('name','admin.booking.upcoming') )

                    <li class="is-shown {{ request()->is('admin/booking/upcoming') ? 'active' : '' }}">
                        <a href="{{ route('admin.booking.upcoming') }}">
                            <i class="feather icon-circle"></i>
                            <span class="menu-item" data-i18n="Upcoming Booking">Upcoming Booking</span>
                        </a>
                    </li>     
                    
                    @endif

                    @if( Auth()->user()->permissions->contains('name','admin.booking.returnOrders') )

                    <li class="is-shown {{ request()->is('admin/booking/returnOrders') ? 'active' : '' }}">
                        <a href="{{ route('admin.booking.returnOrders') }}">
                            <i class="feather icon-circle"></i>
                            <span class="menu-item" data-i18n="Return Orders">Return Orders</span>
                        </a>
                    </li>   
                    
                    @endif

                </ul>
                
            </li>

            @endif

            @if(
            Auth()->user()->permissions->contains('name','admin.account.sales') ||
            Auth()->user()->permissions->contains('name','admin.account.refund')
            )
            <li class="nav-item has-sub">
                <a href="#">
                    <i class="feather icon-file-minus"></i>
                    <span class="menu-title" data-i18n="My Booking">Account Reports</span>
                </a>
                <ul class="menu-content" style="">
                    @if( Auth()->user()->permissions->contains('name','admin.account.sales') )
                        <li class="is-shown {{ request()->is('admin/account/sales') ? 'active' : '' }}">
                            <a href="{{ route('admin.account.sales') }}">
                                <i class="feather icon-circle"></i>
                                <span class="menu-item" data-i18n="Total Sales">Total Sales</span>
                            </a>
                        </li>     
                    @endif
                    @if( Auth()->user()->permissions->contains('name','admin.account.refund') )
                        <li class="is-shown {{ request()->is('admin/account/refund') ? 'active' : '' }}">
                            <a href="{{ route('admin.account.refund') }}">
                                <i class="feather icon-circle"></i>
                                <span class="menu-item" data-i18n="Total Sales">Refund Payments</span>
                            </a>
                        </li>     
                    @endif
                </ul>
            </li>
            @endif
            @if(Auth()->user()->permissions->contains('name','admin.super-category.index'))
            <li class="nav-item {{ request()->is('admin/super-category') ? 'active' : '' }}">

                <a href="{{ route('admin.super-category.index') }}" data-toggle="tooltip" data-placement="right"
                    data-original-title="Super Category">

                    <i class="feather icon-grid"></i>

                    <span class="menu-title" data-i18n="Super Category">Super Category</span>

                </a>

            </li>



            @endif



            @if(Auth()->user()->permissions->contains('name','admin.super-sub-category.index'))



            <li class="nav-item {{ request()->is('admin/super-sub-category') ? 'active' : '' }}">

                <a href="{{ route('admin.super-sub-category.index') }}" data-toggle="tooltip" data-placement="right"
                    data-original-title="Super Sub Category">

                    <i class="feather icon-grid"></i>

                    <span class="menu-title" data-i18n="Super Sub Category">Super Sub Category</span>

                </a>

            </li>



            @endif



            @if(Auth()->user()->permissions->contains('name','admin.category.index'))



            <li class="nav-item {{ request()->is('admin/category') ? 'active' : '' }}">

                <a href="{{ route('admin.category.index') }}" data-toggle="tooltip" data-placement="right"
                    data-original-title="Category">

                    <i class="feather icon-command"></i>

                    <span class="menu-title" data-i18n="Category">Category</span>

                </a>

            </li>



            @endif



            @if(Auth()->user()->permissions->contains('name','admin.sub-category.index'))



            <li class="nav-item {{ request()->is('admin/sub-category') ? 'active' : '' }}">

                <a href="{{ route('admin.sub-category.index') }}" data-toggle="tooltip" data-placement="right"
                    data-original-title="Sub Category">

                    <i class="feather icon-command"></i>

                    <span class="menu-title" data-i18n="Sub Category">Sub Category</span>

                </a>

            </li>



            @endif



            @if(Auth()->user()->permissions->contains('name','admin.brand.index'))



            <li class="nav-item {{ request()->is('admin/brand') ? 'active' : '' }}">

                <a href="{{ route('admin.brand.index') }}" data-toggle="tooltip" data-placement="right"
                    data-original-title="Brands">

                    <i class="feather icon-grid"></i>

                    <span class="menu-title" data-i18n="Brands">Brands</span>

                </a>

            </li>



            @endif



            {{-- @if(Auth()->user()->permissions->contains('name','admin.tax.index'))



            <li class="nav-item {{ request()->is('admin/tax') ? 'active' : '' }}">

                <a href="{{ route('admin.tax.index') }}" data-toggle="tooltip" data-placement="right"
                    data-original-title="Tax">

                    <i class="feather icon-dollar-sign"></i>

                    <span class="menu-title" data-i18n="Tax">Tax</span>

                </a>

            </li>



            @endif --}}



            @if(Auth()->user()->permissions->contains('name','admin.ticket-reasons.index'))



            <li class="nav-item {{ request()->is('admin/ticket-reasons') ? 'active' : '' }}">

                <a href="{{ route('admin.ticket-reasons.index') }}" data-toggle="tooltip" data-placement="right"
                    data-original-title="Ticket Reason">

                    <i class="fa fa-ticket"></i>

                    <span class="menu-title" data-i18n="Ticket Reason">Ticket Reason</span>

                </a>

            </li>



            @endif



            @if(Auth()->user()->permissions->contains('name','admin.offers.index'))
            <li class="nav-item {{ request()->is('admin/offers') ? 'active' : '' }}">
                <a href="{{ route('admin.offers.index') }}" data-toggle="tooltip" data-placement="right"
                    data-original-title="Offers Management">
                    <i class="fa fa-gift"></i>
                    <span class="menu-title" data-i18n="Offers Management">Offers Management</span>
                </a>
            </li>
            @endif
            @if(Auth()->user()->permissions->contains('name','admin.notification.create'))
            <li class="nav-item {{ request()->is('admin/notification') ? 'active' : '' }}">
                <a href="{{ route('admin.notification.create') }}" data-toggle="tooltip" data-placement="right"
                    data-original-title="Send General Notification">
                    <i class="fa fa-gift"></i>
                    <span class="menu-title" data-i18n="General Notification">General Notification</span>
                </a>
            </li>
            @endif
            @if(Auth()->user()->permissions->contains('name','admin.banners.index'))
            <li class="nav-item {{ request()->is('admin/banners') ? 'active' : '' }}">

                <a href="{{ route('admin.banners.index') }}" data-toggle="tooltip" data-placement="right"
                    data-original-title="Manage Advertisement">

                    <i class="fa fa-buysellads"></i>

                    <span class="menu-title" data-i18n="Manage Advertisement">Manage Advertisement</span>

                </a>

            </li>
            @endif

            @if(
            Auth()->user()->permissions->contains('name','admin.bestdeals.index') 
            )
            <li class="nav-item has-sub">
                <a href="#">
                    <i class="feather icon-file-minus"></i>
                    <span class="menu-title" data-i18n="My Booking">Banner Management</span>
                </a>
                <ul class="menu-content" style="">
                    @if( Auth()->user()->permissions->contains('name','admin.bestdeals.index') )
                        <li class="is-shown {{ request()->is('admin/bestdeals/') ? 'active' : '' }}">
                            <a href="{{ route('admin.bestdeals.index') }}">
                                <i class="feather icon-circle"></i>
                                <span class="menu-item" data-i18n="Best Deals">Best Deals</span>
                            </a>
                        </li>     
                    @endif
                    @if( Auth()->user()->permissions->contains('name','admin.newArrivals.index') )
                        <li class="is-shown {{ request()->is('admin/newArrival/') ? 'active' : '' }}">
                            <a href="{{ route('admin.newArrivals.index') }}">
                                <i class="feather icon-circle"></i>
                                <span class="menu-item" data-i18n="New Arrivals">New Arrivals</span>
                            </a>
                        </li>     
                    @endif
                    @if( Auth()->user()->permissions->contains('name','admin.exclusive.index') )
                        <li class="is-shown {{ request()->is('admin/BBExclusive/') ? 'active' : '' }}">
                            <a href="{{ route('admin.exclusive.index') }}">
                                <i class="feather icon-circle"></i>
                                <span class="menu-item" data-i18n="Bheshbhusha Exclusive">Bheshbhusha Exclusive</span>
                            </a>
                        </li>     
                    @endif
                    @if( Auth()->user()->permissions->contains('name','admin.trendingInMen.index') )
                        <li class="is-shown {{ request()->is('admin/TrendingInMen/') ? 'active' : '' }}">
                            <a href="{{ route('admin.trendingInMen.index') }}">
                                <i class="feather icon-circle"></i>
                                <span class="menu-item" data-i18n="Trending In Men">Trending In Men</span>
                            </a>
                        </li>     
                    @endif
                    @if( Auth()->user()->permissions->contains('name','admin.trendingInWomen.index') )
                        <li class="is-shown {{ request()->is('admin/TrendingInWomen/') ? 'active' : '' }}">
                            <a href="{{ route('admin.trendingInWomen.index') }}">
                                <i class="feather icon-circle"></i>
                                <span class="menu-item" data-i18n="Trending In Women">Trending In Women</span>
                            </a>
                        </li>     
                    @endif
                </ul>
            </li>
            @endif


            @if(Auth()->user()->permissions->contains('name','admin.product.index'))



            <li class="nav-item {{ request()->is('admin/product') ? 'active' : '' }}">

                <a href="{{ route('admin.product.index') }}" data-toggle="tooltip" data-placement="right"
                    data-original-title="Product Management">

                    <i class="fa fa-shopping-cart"></i>

                    <span class="menu-title" data-i18n="Product Management">Product Management</span>

                </a>

            </li>



            @endif



            @if(Auth()->user()->permissions->contains('name','admin.cms-master.index'))
            <li class="nav-item {{ request()->is('admin/cms-master') ? 'active' : '' }}">

                <a href="{{ route('admin.cms-master.index') }}" data-toggle="tooltip" data-placement="right"
                    data-original-title="CMS Management">

                    <i class="fa fa-file"></i>

                    <span class="menu-title" data-i18n="CMS Management">CMS Management</span>

                </a>

            </li>
            @endif

            @if(Auth()->user()->permissions->contains('name','admin.web-cms-master.index'))
            <li class="nav-item {{ request()->is('admin/web-cms-master') ? 'active' : '' }}">

                <a href="{{ route('admin.web-cms-master.index') }}" data-toggle="tooltip" data-placement="right"
                    data-original-title="CMS Management">

                    <i class="fa fa-file"></i>

                    <span class="menu-title" data-i18n="CMS Management">Web CMS Management</span>

                </a>

            </li>
            @endif


            @if(Auth()->user()->permissions->contains('name','admin.color.index'))

            <li class="nav-item {{ request()->is('admin/color') ? 'active' : '' }}">

                <a href="{{ route('admin.color.index') }}" data-toggle="tooltip" data-placement="right"
                    data-original-title="Colors">

                    <i class="feather icon-droplet"></i>

                    <span class="menu-title" data-i18n="Colors">Colors</span>

                </a>

            </li>

            @endif

            @if(Auth()->user()->permissions->contains('name','admin.sizes.index'))

            <li class="nav-item {{ request()->is('admin/size') ? 'active' : '' }}">

                <a href="{{ route('admin.sizes.index') }}" data-toggle="tooltip" data-placement="right"
                    data-original-title="Size">

                    <i class="feather icon-layout"></i>

                    <span class="menu-title" data-i18n="Size">Size</span>

                </a>

            </li>

            @endif


            <li class="navigation-header">

                <span>Master</span>

            </li>

            @if(Auth()->user()->permissions->contains('name','admin.role.index'))
            <li class="nav-item {{ request()->is('admin/role') ? 'active' : '' }}">
                <a href="{{ route('admin.role.index') }}" data-toggle="tooltip" data-placement="right"
                    data-original-title="Role Master">
                    <i class="feather icon-flag"></i>
                    <span class="menu-title" data-i18n="State">Role Master</span>
                </a>
            </li>
            @endif

            @if(Auth()->user()->permissions->contains('name','admin.state.index'))



            <li class="nav-item {{ request()->is('admin/state') ? 'active' : '' }}">

                <a href="{{ route('admin.state.index') }}" data-toggle="tooltip" data-placement="right"
                    data-original-title="State Master">

                    <i class="feather icon-flag"></i>

                    <span class="menu-title" data-i18n="State">State Master</span>

                </a>

            </li>



            @endif



            @if(Auth()->user()->permissions->contains('name','admin.city.index'))



            <li class="nav-item {{ request()->is('admin/city') ? 'active' : '' }}">

                <a href="{{ route('admin.city.index') }}" data-toggle="tooltip" data-placement="right"
                    data-original-title="City Master">

                    <i class="feather icon-flag"></i>

                    <span class="menu-title" data-i18n="City">City Master</span>

                </a>

            </li>



            @endif



            @if(Auth()->user()->permissions->contains('name','admin.pincode.index'))



            <li class="nav-item {{ request()->is('admin/pincode') ? 'active' : '' }}">

                <a href="{{ route('admin.pincode.index') }}" data-toggle="tooltip" data-placement="right"
                    data-original-title="Pincode Master">

                    <i class="feather icon-flag"></i>

                    <span class="menu-title" data-i18n="Pincode">Pincode Master</span>

                </a>

            </li>



            @endif



            @if(Auth()->user()->permissions->contains('name','admin.area.index'))
            <li class="nav-item {{ request()->is('admin/area') ? 'active' : '' }}">
                <a href="{{ route('admin.area.index') }}" data-toggle="tooltip" data-placement="right"
                    data-original-title="Area Master">
                    <i class="feather icon-flag"></i>
                    <span class="menu-title" data-i18n="Area">Area Master</span>
                </a>
            </li>
            @endif
            {{-- @if(Auth()->user()->permissions->contains('name','admin.review.review')) --}}
                <li class="nav-item {{ request()->is('admin/review') ? 'active' : '' }}">
                    <a href="{{ route('admin.review.review') }}" data-toggle="tooltip" data-placement="right"
                        data-original-title="Area Master">
                        <i class="feather icon-star"></i>
                        <span class="menu-title" data-i18n="Area">Review And Reating</span>
                    </a>
                </li>
            {{-- @endif --}}
            @if(Auth()->user()->permissions->contains('name','admin.setting.index'))
                <li class="nav-item {{ request()->is('admin/setting') ? 'active' : '' }}">
                    <a href="{{ route('admin.setting.index') }}" data-toggle="tooltip" data-placement="right"
                        data-original-title="Area Master">
                        <i class="feather icon-settings"></i>
                        <span class="menu-title" data-i18n="Area">Setting</span>
                    </a>
                </li>
            @endif
        </ul>

    </div>

</div>