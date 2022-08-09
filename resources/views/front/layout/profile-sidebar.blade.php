<div class="setting-sidebar">

    <div class="profile-img">

        <img src="{{ auth()->user()->profile_pic != '' ? asset('uploads/user/' . auth()->user()->profile_pic) : asset('assets/front/images/profile-pic.png') }}"
            alt="">

        <div class="file-input">

            <input type="file" name="profile_pic" id="file-input" class="file-input-input" />

            <label class="file-input-label" for="file-input"> <span><i class="fas fa-camera"></i></span></label>

        </div>

    </div>

    <div class="menu-list">

        <ul>

            <li class="{{ request()->is('my-orders') ? 'active' : '' }}">
                <a href="{{ route('my_orders') }}" title="">
                    <div class="icon">
                        <img src="{{ asset('assets/front/images/bag.svg') }}" alt="">
                    </div>
                    Orders
                </a>
            </li>

            <li class="{{ request()->is('wishlist') ? 'active' : '' }}">
                <a href="{{ route('wishlist') }}" title="">
                    <div class="icon">
                        <img src="{{ asset('assets/front/images/fav.svg') }}" alt="">
                    </div>
                    Wishlist
                </a>
            </li>

            <li class="{{ request()->is('help-center') ? 'active' : '' }}">
                <a href="{{ route('helpCenter') }}" title="">
                    <div class="icon">
                        <img src="{{ asset('assets/front/images/setting.svg') }}" alt="">
                    </div>
                    Help Center
                </a>
            </li>

            <li class="{{ request()->is('my-address') ? 'active' : '' }}">
                <a href="{{ route('my_address') }}" title="">
                    <div class="icon">
                        <img src="{{ asset('assets/front/images/location.svg') }}" alt="">
                    </div>
                    Address
                </a>
            </li>

            <li class="{{ request()->is('profile') ? 'active' : '' }}">
                <a href="{{ route('profile') }}" title="">
                    <div class="icon">
                        <img src="{{ asset('assets/front/images/profile-icon.svg') }}" alt="">
                    </div>
                    Profile Details
                </a>
            </li>

            <li class="{{ request()->is('settings') ? 'active' : '' }}">
                <a href="{{ route('settings') }}" title="">
                    <div class="icon">
                        <img src="{{ asset('assets/front/images/setting.svg') }}" alt="">
                    </div>
                    Settings
                </a>
            </li>

            <li class="{{ request()->is('notifications') ? 'active' : '' }}">
                <a href="{{ route('notifications') }}" title="">
                    <div class="icon">
                        <img src="{{ asset('assets/front/images/notification.svg') }}" alt="">
                    </div>
                    Notification
                </a>
            </li>

        </ul>

    </div>

</div>
