<nav class="header-navbar navbar-expand-lg navbar navbar-with-menu floating-nav navbar-light navbar-shadow">

    <div class="navbar-wrapper">
        <div class="navbar-container content">
            <div class="navbar-collapse" id="navbar-mobile">

                <div class="mr-auto float-left bookmark-wrapper d-flex align-items-center">
                    <ul class="nav navbar-nav">
                        <li class="nav-item mobile-menu d-xl-none mr-auto">
                            <a class="nav-link nav-menu-main menu-toggle hidden-xs" href="#">
                                <i class="ficon feather icon-menu"></i>
                            </a>
                        </li>
                    </ul>
                </div>

                <ul class="nav navbar-nav float-right">

                    <li class="dropdown dropdown-language nav-item">
                        <a class="dropdown-toggle nav-link" id="dropdown-flag" href="#" data-toggle="dropdown"
                            aria-haspopup="true" aria-expanded="false">
                            <i class="flag-icon flag-icon-us"></i>
                            <span class="selected-language">English</span>
                        </a>
                        <div class="dropdown-menu" aria-labelledby="dropdown-flag">
                            <a class="dropdown-item" href="#" data-language="en">
                                <i class="flag-icon flag-icon-us"></i> English
                            </a>
                            <a class="dropdown-item" href="#" data-language="fr">
                                <i class="flag-icon flag-icon-fr"></i> French
                            </a>
                            <a class="dropdown-item" href="#" data-language="de">
                                <i class="flag-icon flag-icon-de"></i> German
                            </a>
                            <a class="dropdown-item" href="#" data-language="pt">
                                <i class="flag-icon flag-icon-pt"></i> Portuguese
                            </a>
                        </div>
                    </li>

                    <li class="nav-item d-none d-lg-block">
                        <a class="nav-link nav-link-expand">
                            <i class="ficon feather icon-maximize"></i>
                        </a>
                    </li>

                    <li class="nav-item nav-search">
                        <a class="nav-link nav-link-search">
                            <i class="ficon feather icon-search"></i>
                        </a>
                        <div class="search-input">
                            <div class="search-input-icon">
                                <i class="feather icon-search primary"></i>
                            </div>
                            <input class="input" type="text" placeholder="Explore Vuexy..." tabindex="-1"
                                data-search="template-list">
                            <div class="search-input-close">
                                <i class="feather icon-x"></i>
                            </div>
                            <ul class="search-list search-list-main"></ul>
                        </div>
                    </li>


                    <li class="dropdown dropdown-user nav-item">
                        <a class="dropdown-toggle nav-link dropdown-user-link" href="#" data-toggle="dropdown">
                            <div class="user-nav d-sm-flex d-none">
                                <span class="user-name text-bold-600">
                                    {{ Auth::user()->name }}
                                </span>
                                <span class="user-status">Available</span>
                            </div>
                            <span>
                                <img class="round"
                                    src="{{ asset('assets/admin/app-assets/images/portrait/small/avatar-s-11.jpg') }}"
                                    alt="avatar" height="40" width="40">
                            </span>
                        </a>
                        <div class="dropdown-menu dropdown-menu-right">
                            <a class="dropdown-item" href="{{ route('admin.profile') }}">
                                <i class="feather icon-user"></i> Edit Profile
                            </a>
                            <a class="dropdown-item" href="{{ route('admin.change-password') }}">
                                <i class="fa fa-key"></i> Change Password
                            </a>
                            <div class="dropdown-divider"></div>
                            <a class="dropdown-item" href="#" onclick="event.preventDefault();
                            document.getElementById('logout-form').submit();">
                                <i class="feather icon-power"></i> Logout
                                <form id="logout-form" action="{{ route('admin.logout') }}" method="POST" class="d-none">
                                    @csrf
                                </form>
                            </a>
                        </div>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</nav>