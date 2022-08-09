@extends('front.layout.main')

@section('section')

    <section class="inner-page-banner">

        <div class="container-fluid">

            <ol class="breadcrumb">

                <li class="breadcrumb-item"><a href="#">Home</a></li>

                <li class="breadcrumb-item active">Address</li>

            </ol>

        </div>

    </section>

    <section class="profile-page">

        <div class="container">

            <div class="row">

                <div class="col-xl-3 col-lg-3 col-md-4 col-sm-12">

                    @include('front.layout.profile-sidebar')

                </div>

                <div class="col-xl-9 col-lg-9 col-md-8 col-sm-12">

                    <div class="dashboard-title">

                        <h3>Address</h3>

                        <a href="#" data-toggle="modal" data-target="#add-new-address" data-backdrop="static"
                            class="btn border-btn">Add New Address</a>

                    </div>

                    <div class="white-bg my-address">

                        @if (count($userAddressList) > 0)

                            @php
                                
                                $defaultAddress = $userAddressList->firstWhere('is_default', 1);
                                $otherAddress = $userAddressList->where('is_default', 0);
                                
                            @endphp

                            @if ($defaultAddress)

                                <div class="address-title">Default Address</div>

                                <div class="default-address">

                                    <div class="form-group box-shadow">

                                        <label class="radio-box">

                                            <b>{{ $defaultAddress->contact_name }}</b>
                                            <h5>{{ $defaultAddress->save_as == 0 ? 'Home' : 'Work' }}</h5>

                                            <p>
                                                {{ $defaultAddress->address . ',' . $defaultAddress->locality . ',' . $defaultAddress->city . ',' . $defaultAddress->state . '-' . $defaultAddress->pincode }}
                                            </p>

                                            <p><span class="text-muted">Mobile:
                                                </span>{{ $defaultAddress->mobile_no }}</p>
                                            <p><span class="text-muted">Email: </span>{{ $defaultAddress->email }}</p>

                                            <input type="radio" name="sendoption" checked="checked">
                                            <span class="checkmark"></span>

                                        </label>

                                        <div class="edit-delete">

                                            <a href="javascript:;" class="btn border-btn btn_remove_address" data-address_id="{{ $defaultAddress->id }}">Remove</a>

                                            <a href="#" class="btn border-btn" data-toggle="modal"
                                                data-target="#edit-address" data-backdrop="static"
                                                data-contact_name="{{ $defaultAddress->contact_name }}" ,
                                                data-mobile_no="{{ $defaultAddress->mobile_no }}" ,
                                                data-email="{{ $defaultAddress->email }}" ,
                                                data-save_as="{{ $defaultAddress->save_as }}" ,
                                                data-address="{{ $defaultAddress->address }}" ,
                                                data-locality="{{ $defaultAddress->locality }}" ,
                                                data-city="{{ $defaultAddress->city }}" ,
                                                data-state="{{ $defaultAddress->state }}" ,
                                                data-pincode="{{ $defaultAddress->pincode }}" ,
                                                data-address_id="{{ $defaultAddress->id }}" ,
                                                data-is_default="{{ $defaultAddress->is_default }}">Edit</a>

                                        </div>

                                    </div>

                                </div>

                            @endif

                            @if (count($otherAddress) > 0)

                                <div class="address-title">Other Address</div>

                                @foreach ($otherAddress as $otherAddress)

                                    <div class="default-address">

                                        <div class="form-group">

                                            <label class="radio-box">

                                                <b>{{ $otherAddress->contact_name }}</b>
                                                <h5>{{ $otherAddress->save_as == 0 ? 'Home' : 'Work' }}</h5>

                                                <p>
                                                    {{ $otherAddress->address . ',' . $otherAddress->locality . ',' . $otherAddress->city . ',' . $otherAddress->state . '-' . $otherAddress->pincode }}
                                                </p>

                                                <p><span class="text-muted">Mobile:
                                                    </span>{{ $otherAddress->mobile_no }}</p>
                                                <p><span class="text-muted">Email: </span>{{ $otherAddress->email }}
                                                </p>

                                                <input type="radio" name="sendoption" checked="checked">
                                                <span class="checkmark"></span>

                                            </label>

                                            <div class="edit-delete">

                                                <a href="javascript:;" class="btn border-btn btn_remove_address" data-address_id="{{ $otherAddress->id }}">Remove</a>

                                                <a href="#" class="btn border-btn" data-toggle="modal"
                                                    data-target="#edit-address" data-backdrop="static"
                                                    data-contact_name="{{ $otherAddress->contact_name }}" ,
                                                    data-mobile_no="{{ $otherAddress->mobile_no }}" ,
                                                    data-email="{{ $otherAddress->email }}" ,
                                                    data-save_as="{{ $otherAddress->save_as }}" ,
                                                    data-address="{{ $otherAddress->address }}" ,
                                                    data-locality="{{ $otherAddress->locality }}" ,
                                                    data-city="{{ $otherAddress->city }}" ,
                                                    data-state="{{ $otherAddress->state }}" ,
                                                    data-pincode="{{ $otherAddress->pincode }}" ,
                                                    data-address_id="{{ $otherAddress->id }}" ,
                                                    data-is_default="{{ $otherAddress->is_default }}" ,>Edit</a>

                                            </div>

                                        </div>

                                    </div>

                                @endforeach

                            @endif

                        @else

                            <div class="text-center text-muted">
                                You have no address available.
                            </div>

                        @endif

                    </div>

                </div>

            </div>

        </div>

        <form action="{{ route('address.remove') }}" method="post" id="removeAddressForm">
            @csrf
            <input type="hidden" name="address_id" id="address_id">
        </form>

    </section>

@endsection

@section('footer')

    <script type="text/javascript">
        $(document).ready(function() {

            $(".btn_remove_address").click(function() {
                var address_id = $(this).data('address_id');
                var form = $("#removeAddressForm");
                $("#address_id").val(address_id);
                // Delete if confirm
                (async () => {
                    var isConfirmed = await confirmDelete();
                    if (isConfirmed) {
                        form.submit();
                    }
                })();
            });

        });
    </script>

@endsection
