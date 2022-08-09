<footer class="footer">

    <div class="container">

        <div class="footer-logo"><img src="{{ asset('assets/front/images/logo.svg') }}" alt=""></div>

        <div class="row">

            <div class="col-xs-12 col-sm-4 col-md-3">

                <h3 class="footer-title">Company</h3>

                <div class="footer-link">

                    <ul>

                        <li><a href="{{ route('about-us') }}">About Us</a></li>

                        <li><a href="{{ route('faq') }}">FAQs</a></li>

                    </ul>

                </div>

            </div>

            <div class="col-xs-12 col-sm-4 col-md-3">

                <h3 class="footer-title">For You</h3>

                <div class="footer-link">

                    <ul>

                        <li><a href="{{ route('privacy_policy') }}">Privacy Policy</a></li>

                        <li><a href="{{ route('terms_and_conditions') }}">Terms and conditions</a></li>

                    </ul>

                </div>

            </div>

            <div class="col-xs-12 col-sm-4 col-md-4">

                <h3 class="footer-title">Online Shopping</h3>

                <div class="footer-link">

                    @php
                        $parentCategory = getCategoryData();
                    @endphp

                    <ul>
                        @if (count($parentCategory) > 0)

                            @foreach ($parentCategory as $mainCategory)

                                <li><a
                                        href="{{ route('categoryProductList', Crypt::encrypt($mainCategory->id)) }}">{{ $mainCategory->supercategory_name }}</a>
                                </li>

                            @endforeach

                        @endif

                        {{-- <li><a href="{{ route('shop') }}">Women</a></li>

              <li><a href="{{ route('shop') }}">Kids</a></li>

              <li><a href="{{ route('shop') }}">Essentials</a></li> --}}

                    </ul>

                </div>

            </div>

            <div class="col-xs-12 col-sm-4 col-md-2">

                <div class="social">

                    <h3 class="footer-title">Social Links</h3>

                    <ul>

                        <li><a target="_blank" href="#"><i class="fab fa-facebook-f"></i></a></li>

                        <li><a target="_blank" href="#"><i class="fab fa-twitter"></i></a></li>

                        <li><a target="_blank" href="#"><i class="fab fa-instagram"></i></a></li>

                    </ul>

                    <div class="download-app">

                        <a href="#" title=""><img src="{{ asset('assets/front/images/app-store.png') }}" alt=""></a>

                        <a href="#" title=""><img src="{{ asset('assets/front/images/play-store.png') }}" alt=""></a>

                    </div>

                </div>

            </div>

        </div>

        <div class="easy-return">
            @if (webCmsMaster())
                <div class="row">

                    <div class="col-md-4">

                        <div class="box">

                            <div class="img">
                                @if (webCmsMaster()->image_one)
                                    <img src="{{ URL::asset('uploads/cms/'.webCmsMaster()->image_one) }}" alt="">
                                @endif
                            </div>

                            <div class="text">
                                @if (webCmsMaster()->title_one)
                                    <h4>{{ webCmsMaster()->title_one }}</h4>
                                @endif
                                @if (webCmsMaster()->discription_one)
                                    <h4>{{ webCmsMaster()->discription_one }}</h4>
                                @endif
                            </div>

                        </div>

                    </div>

                    <div class="col-md-4">

                        <div class="box">

                            <div class="img">
                                @if (webCmsMaster()->image_two)
                                    <img src="{{ URL::asset('uploads/cms/'.webCmsMaster()->image_two) }}" alt="">
                                @endif
                            </div>

                            <div class="text">
                                @if (webCmsMaster()->title_two)
                                    <h4>{{ webCmsMaster()->title_two }}</h4>
                                @endif
                                @if (webCmsMaster()->discription_two)
                                    <h4>{{ webCmsMaster()->discription_two }}</h4>
                                @endif
                            </div>

                        </div>

                    </div>

                    <div class="col-md-4">

                        <div class="box">

                            <div class="img">
                                @if (webCmsMaster()->image_three)
                                    <img src="{{ URL::asset('uploads/cms/'.webCmsMaster()->image_three) }}" alt="">
                                @endif
                            </div>

                            <div class="text">
                                @if (webCmsMaster()->title_three)
                                    <h4>{{ webCmsMaster()->title_three }}</h4>
                                @endif
                                @if (webCmsMaster()->discription_three)
                                    <h4>{{ webCmsMaster()->discription_three }}</h4>
                                @endif
                            </div>

                        </div>

                    </div>

                </div>
            @endif

        </div>

    </div>

    <div class="footer-bottom">

        <div class="copyright-footer">© 2021 BheshBhusa. All rights reserved.</div>

    </div>

</footer>



<!-- Scripts -->

<!-- <script src="js/bootstrap.min.js"></script> -->

<script src="{{ asset('assets/front/js/bootstrap.bundle.js') }}"></script>

<!-- Jquery UI -->

<script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.11.2/jquery-ui.min.js"></script>

<script src="https://cdnjs.cloudflare.com/ajax/libs/jqueryui-touch-punch/0.2.3/jquery.ui.touch-punch.min.js"></script>

<!-- Fancy Box -->

<script src="https://cdn.jsdelivr.net/npm/@fancyapps/ui/dist/fancybox.umd.js"></script>

<!-- Custom Script -->

<script type="text/javascript" src="https://cdn.jsdelivr.net/jquery.slick/1.4.1/slick.min.js"></script>

<!-- Jquery Validate -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.3/jquery.validate.min.js"
integrity="sha512-37T7leoNS06R80c8Ulq7cdCDU5MNQBwlYoy1TX/WUsLFC2eYNqtKlV0QjH7r8JpG/S0GUMZwebnVFLPd6SU5yg=="
crossorigin="anonymous" referrerpolicy="no-referrer"></script>

<script src="{{ asset('assets/front/js/page-scroll.js') }}"></script>

<script src="{{ asset('assets/front/js/main.js') }}"></script>

<script src="{{ asset('assets/front/izitoast/js/iziToast.min.js') }}"></script>

<script src="{{ asset('assets/front/sweetalert/sweetalert2.all.min.js') }}"></script>

{{-- <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyC2j3-CHmh_oGxfwr7St8y2fmrqBi-O4JM&callback=initMap&v=weekly"async ></script>

<script type="text/javascript">
    let map;
    let marker;
    let geocoder;
    let responseDiv;
    let response;

    function initMap() {
      map = new google.maps.Map(document.getElementById("map"), {
        zoom: 8,
        center: { lat: -34.397, lng: 150.644 },
        mapTypeControl: false,
      });
      geocoder = new google.maps.Geocoder();

      const inputText = document.createElement("input");

      inputText.type = "text";
      inputText.placeholder = "Enter a location";

      const submitButton = document.createElement("input");

      submitButton.type = "button";
      submitButton.value = "Geocode";
      submitButton.classList.add("button", "button-primary");

      const clearButton = document.createElement("input");

      clearButton.type = "button";
      clearButton.value = "Clear";
      clearButton.classList.add("button", "button-secondary");
      response = document.createElement("pre");
      response.id = "response";
      response.innerText = "";
      responseDiv = document.createElement("div");
      responseDiv.id = "response-container";
      responseDiv.appendChild(response);

      const instructionsElement = document.createElement("p");

      instructionsElement.id = "instructions";
      instructionsElement.innerHTML =
        "<strong>Instructions</strong>: Enter an address in the textbox to geocode or click on the map to reverse geocode.";
      map.controls[google.maps.ControlPosition.TOP_LEFT].push(inputText);
      map.controls[google.maps.ControlPosition.TOP_LEFT].push(submitButton);
      map.controls[google.maps.ControlPosition.TOP_LEFT].push(clearButton);
      map.controls[google.maps.ControlPosition.LEFT_TOP].push(instructionsElement);
      map.controls[google.maps.ControlPosition.LEFT_TOP].push(responseDiv);
      marker = new google.maps.Marker({
        map,
      });
      map.addListener("click", (e) => {
        geocode({ location: e.latLng });
      });
      submitButton.addEventListener("click", () =>
        geocode({ address: inputText.value })
      );
      clearButton.addEventListener("click", () => {
        clear();
      });
      clear();
    }

    function clear() {
      marker.setMap(null);
      responseDiv.style.display = "none";
    }

    function geocode(request) {
      clear();
      geocoder
        .geocode(request)
        .then((result) => {
          const { results } = result;

          map.setCenter(results[0].geometry.location);
          marker.setPosition(results[0].geometry.location);
          marker.setMap(map);
          responseDiv.style.display = "block";
          response.innerText = JSON.stringify(result, null, 2);
          return results;
        })
        .catch((e) => {
          alert("Geocode was not successful for the following reason: " + e);
        });
    }
</script> --}}

@include('flash')

<script>
    $(document).ready(function() {

        $("#addressForm").validate({
            rules: {
                contact_name: {
                    required: true,
                },
                mobile_no: {
                    required: true,
                    number: true,
                },
                email: {
                    required: true,
                    email: true,
                },
                pincode: {
                    required: true,
                    number: true,
                },
                address: {
                    required: true,
                },
                locality: {
                    required: true,
                },
                city: {
                    required: true,
                },
                state: {
                    required: true,
                },
            },
            messages: {
                contact_name: {
                    required: "Name Required",
                },
                mobile_no: {
                    required: "Mobile No Required",
                },
                email: {
                    required: "Email Required",
                },
                pincode: {
                    required: "Pincode Required",
                },
                address: {
                    required: "Address Required",
                },
                locality: {
                    required: "Locality Required",
                },
                city: {
                    required: "City Required",
                },
                state: {
                    required: "State Required",
                },

            },
            submitHandler: function(form) {
                form.submit();
            }
        });

        $("#editAddressForm").validate({
            rules: {
                contact_name: {
                    required: true,
                },
                mobile_no: {
                    required: true,
                    number: true,
                },
                email: {
                    required: true,
                    email: true,
                },
                pincode: {
                    required: true,
                    number: true,
                },
                address: {
                    required: true,
                },
                locality: {
                    required: true,
                },
                city: {
                    required: true,
                },
                state: {
                    required: true,
                },
            },
            messages: {
                contact_name: {
                    required: "Name Required",
                },
                mobile_no: {
                    required: "Mobile No Required",
                },
                email: {
                    required: "Email Required",
                },
                pincode: {
                    required: "Pincode Required",
                },
                address: {
                    required: "Address Required",
                },
                locality: {
                    required: "Locality Required",
                },
                city: {
                    required: "City Required",
                },
                state: {
                    required: "State Required",
                },

            },
            submitHandler: function(form) {
                form.submit();
            }
        });

        $("#edit-address").on('show.bs.modal', function(e) {

            var contact_name = $(e.relatedTarget).data('contact_name');
            var mobile_no = $(e.relatedTarget).data('mobile_no');
            var email = $(e.relatedTarget).data('email');
            var save_as = $(e.relatedTarget).data('save_as');
            var address = $(e.relatedTarget).data('address');
            var locality = $(e.relatedTarget).data('locality');
            var city = $(e.relatedTarget).data('city');
            var state = $(e.relatedTarget).data('state');
            var pincode = $(e.relatedTarget).data('pincode');
            var is_default = $(e.relatedTarget).data('is_default');
            var address_id = $(e.relatedTarget).data('address_id');

            $(e.currentTarget).find($("#e_contact_name")).val(contact_name);
            $(e.currentTarget).find($("#e_mobile_no")).val(mobile_no);
            $(e.currentTarget).find($("#e_email")).val(email);
            $(e.currentTarget).find($("#e_address")).val(address);
            $(e.currentTarget).find($("#e_locality")).val(locality);
            $(e.currentTarget).find($("#e_city")).val(city);
            $(e.currentTarget).find($("#e_state")).val(state);
            $(e.currentTarget).find($("#e_pincode")).val(pincode);
            $(e.currentTarget).find($("#e_address_id")).val(address_id);
            // $(e.currentTarget).find($("#e_is_default")).val(is_default);

            $(".e_save_as[value=" + save_as + "]").prop('checked', true);
            $("#e_is_default").prop('checked', is_default);

        });

        $("#file-input").change(function() {
            var file = this.files[0];
            var fileType = file["type"];
            var validImageTypes = ["image/gif", "image/jpeg", "image/png"];
            if ($.inArray(fileType, validImageTypes) < 0) {
                // invalid file type code goes here.
                alert("Please select valid image file...");
            } else {
                var formData = new FormData();
                formData.append('_token', "{{ csrf_token() }}");
                formData.append('profile_pic', $('#file-input')[0].files[0]);

                $.ajax({
                    url: "{{ route('profile_pic_post') }}",
                    type: 'POST',
                    data: formData,
                    enctype: 'multipart/form-data',
                    processData: false, // tell jQuery not to process the data
                    contentType: false, // tell jQuery not to set contentType
                    beforeSend: function() {
                        $('.fade-out').show();
                        $('.fade-out .loader-inner').show();
                    },
                    complete: function() {
                        $('.fade-out').hide();
                        $('.fade-out .loader-inner').hide();
                    },
                    success: function(data) {
                        // console.log(data.message);
                        // console.log(data.status);
                        if (data.status) {
                            iziToast.success({
                                title: data.message,
                                position: "topRight",
                            });
                            location.reload();
                        } else {
                            iziToast.error({
                                title: data.message,
                                position: "topRight",
                            });
                            location.reload();
                        }
                        //   alert(data);


                    }
                });
            }
        });

        $(".delete_notification").click(function() {
            var notification_id = $(this).data('id');
            $('<input>').attr({
                type: 'hidden',
                name: 'notification_id',
                value: notification_id
            }).appendTo('#deleteNotification');
            $("#deleteNotification").submit();
        });

        //Delete Record
        $(".delete-record").off('click').on('click', function(event) {

            event.stopPropagation();

            Swal.fire({

                title: "Are you sure?",

                text: "Once deleted, you will not be able to recover this data!",

                type: 'warning',

                showCancelButton: true,

                confirmButtonColor: '#3085d6',

                cancelButtonColor: '#d33',

                confirmButtonText: 'Yes, delete it!',

                confirmButtonClass: 'btn btn-primary',

                cancelButtonClass: 'btn btn-danger ml-1',

                buttonsStyling: false,

            }).then((willDelete) => {

                if (willDelete.value) {

                    var action = $(this).data('action');

                    $.ajax({

                        url: action,

                        method: 'POST',

                        data: {

                            _token: "{{ csrf_token() }}"

                        },

                        success: function(response) {

                            // console.log(response);

                            location.reload();

                        },

                        error: function(error) {

                            console.log(error);

                            alert(error);

                        }

                    })

                } else if (willDelete.dismiss === Swal.DismissReason.cancel) {

                    Swal.fire({

                        title: 'Cancelled',

                        text: 'Your imaginary file is safe :)',

                        type: 'error',

                        confirmButtonClass: 'btn btn-success',

                    })

                }

            });

        });

        $(".wishlist_check").click(function() {
            var product_id = $(this).data('id');
            // var is_favorite = $(this).val();
            var is_favorite = ($(this).is(':checked')) ? 1 : 0;
            // console.log(product_id);
            var _token = "{{ csrf_token() }}";
            $form = $("<form method='post' action='{{ route('addRemoveWishlist') }}'></form>");
            $form.append('<input type="hidden" name="_token" value="' + _token + '">');
            $form.append('<input type="hidden" name="product_id" value="' + product_id + '">');
            $form.append('<input type="hidden" name="is_favorite" value="' + is_favorite + '">');
            // console.log($form);
            $('body').append($form);
            $form.submit();
        });

    });
</script>

<script type="text/javascript">
    function addRemoveWishlist(product_id, is_favorite) {
        var _token = "{{ csrf_token() }}";
        $form = $("<form method='post' action='{{ route('addRemoveWishlist') }}'></form>");
        $form.append('<input type="hidden" name="_token" value="' + _token + '">');
        $form.append('<input type="hidden" name="product_id" value="' + product_id + '">');
        $form.append('<input type="hidden" name="is_favorite" value="' + is_favorite + '">');
        // console.log($form);
        $('body').append($form);
        $form.submit();
    }

    async function confirmDelete() {
        return await Swal.fire({
            title: "Are you sure?",
            text: "Once deleted, you will not be able to recover this data!",
            type: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes, delete it!',
            confirmButtonClass: 'btn btn-primary',
            cancelButtonClass: 'btn btn-danger ml-1',
            buttonsStyling: false,

        }).then((willDelete) => {
            if (willDelete.value) {
                // console.log(willDelete.value);
                return true;
            } else if (willDelete.dismiss === Swal.DismissReason.cancel) {
                Swal.fire({
                    title: 'Cancelled',
                    text: 'Your imaginary file is safe :)',
                    type: 'error',
                    confirmButtonClass: 'btn btn-success',
                });
                // console.log(false);
                return false;
            }
        });
    }

    function showError(error) {
        iziToast.error({
            title: error,
            position: "topRight",
        });
    }

    function showSuccess(success) {
        iziToast.success({
            title: success,
            position: "topRight",
        });
    }

    function validate(evt) {
        var theEvent = evt || window.event;

        // Handle paste
        if (theEvent.type === 'paste') {
            key = event.clipboardData.getData('text/plain');
        } else {
            // Handle key press
            var key = theEvent.keyCode || theEvent.which;
            key = String.fromCharCode(key);
        }
        // var regex = /[0-9]|\./;
        var regex = /[0-9]/;
        if (!regex.test(key)) {
            theEvent.returnValue = false;
            if (theEvent.preventDefault) theEvent.preventDefault();
        }
    }
</script>



<!-- Add Address Modal -->

<div class="modal fade add-address-modal" id="add-new-address">

    <div class="modal-dialog modal-dialog-centered" role="document">

        <div class="modal-content">

            <form method="post" action="{{ route('address.save') }}" id="addressForm">

                @csrf

                <div class="modal-header">

                    <h5 class="modal-title">Add Address</h5>

                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">

                        <span aria-hidden="true">&times;</span>

                    </button>

                </div>

                <div class="modal-body">
                    {{-- <div id="map"></div> --}}
                    <h4>Contact Details</h4>

                    <div class="form-group">

                        <input type="text" name="contact_name" class="form-control" placeholder="Name*">

                    </div>

                    <div class="form-group">

                        <input type="text" name="mobile_no" class="form-control" placeholder="Mobile Number*">

                    </div>

                    <div class="form-group">

                        <input type="text" name="email" class="form-control" placeholder="Email*">

                    </div>

                    <h4>Address</h4>

                    <div class="form-group">

                        <input type="text" name="pincode" class="form-control" placeholder="Pincode*">

                    </div>



                    <div class="form-group">

                        <textarea name="address" class="form-control"
                            placeholder="Address (House No, building, street, area)*"></textarea>

                    </div>



                    <div class="form-group">

                        <input type="text" name="locality" class="form-control" placeholder="locality / town*">

                    </div>

                    <div class="row">

                        <div class="col-xl-6 col-lg-6 col-md-12">

                            <div class="form-group">

                                <input type="text" name="city" class="form-control" placeholder="City">

                            </div>

                        </div>

                        <div class="col-xl-6 col-lg-6 col-md-12">

                            <div class="form-group">

                                <input type="text" name="state" class="form-control" placeholder="State">

                            </div>

                        </div>

                    </div>

                    <h4>Save Address As</h4>

                    <div class="radio-list">

                        <label class="radio-box">Home<input type="radio" name="save_as" value="0" checked><span
                                class="checkmark"></span></label>

                        <label class="radio-box">Work<input type="radio" name="save_as" value="1"><span
                                class="checkmark"></span></label>

                    </div>
                    <br>

                    <div class="common-check">

                        <label class="checkbox">
                            <div>Make this my default address</div>
                            <div class="number">
                            </div>
                            <input type="checkbox" name="is_default" value="1">
                            <span class="checkmark"></span>
                        </label>

                    </div>

                </div>

                <div class="modal-footer">

                    <button type="button" class="btn border-btn" data-dismiss="modal">Close</button>

                    <button type="submit" class="btn theme-btn">Save Address</button>

                </div>

            </form>

        </div>

    </div>

</div>

<!-- Edit Address Modal -->

<div class="modal fade add-address-modal" id="edit-address">

    <div class="modal-dialog modal-dialog-centered" role="document">

        <div class="modal-content">

            <form method="post" action="{{ route('address.update') }}" id="editAddressForm">

                @csrf

                <div class="modal-header">

                    <h5 class="modal-title">Edit Address</h5>

                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">

                        <span aria-hidden="true">&times;</span>

                    </button>

                </div>

                <div class="modal-body">

                    <h4>Contact Details</h4>

                    <div class="form-group">

                        <input type="text" name="contact_name" class="form-control" placeholder="Name*"
                            id="e_contact_name">

                    </div>

                    <div class="form-group">

                        <input type="text" name="mobile_no" class="form-control" placeholder="Mobile Number*"
                            id="e_mobile_no">

                    </div>

                    <div class="form-group">

                        <input type="text" name="email" class="form-control" placeholder="Email*" id="e_email">

                    </div>

                    <h4>Address</h4>

                    <div class="form-group">

                        <input type="text" name="pincode" class="form-control" placeholder="Pincode*" id="e_pincode">

                    </div>



                    <div class="form-group">

                        <textarea name="address" class="form-control"
                            placeholder="Address (House No, building, street, area)*" id="e_address"></textarea>

                    </div>



                    <div class="form-group">

                        <input type="text" name="locality" class="form-control" placeholder="locality / town*"
                            id="e_locality">

                    </div>

                    <div class="row">

                        <div class="col-xl-6 col-lg-6 col-md-12">

                            <div class="form-group">

                                <input type="text" name="city" class="form-control" placeholder="City" id="e_city">

                            </div>

                        </div>

                        <div class="col-xl-6 col-lg-6 col-md-12">

                            <div class="form-group">

                                <input type="text" name="state" class="form-control" placeholder="State" id="e_state">

                            </div>

                        </div>

                    </div>

                    <h4>Save Address As</h4>

                    <div class="radio-list">

                        <label class="radio-box">Home
                            <input type="radio" name="save_as" value="0" class="e_save_as"><span
                                class="checkmark"></span></label>

                        <label class="radio-box">Work
                            <input type="radio" name="save_as" value="1" class="e_save_as"><span
                                class="checkmark"></span></label>

                    </div>
                    <br>

                    <div class="common-check">

                        <label class="checkbox">
                            <div>Make this my default address</div>
                            <div class="number">
                            </div>
                            <input type="checkbox" name="is_default" value="1" id="e_is_default">
                            <span class="checkmark">
                            </span>
                        </label>

                    </div>

                    <input type="hidden" name="address_id" id="e_address_id">

                </div>

                <div class="modal-footer">

                    <button type="button" class="btn border-btn" data-dismiss="modal">Close</button>

                    <button type="submit" class="btn theme-btn">Save Address</button>

                </div>

            </form>

        </div>

    </div>

</div>



<!-- Delete Modal -->

<div id="delete-modal" class="modal delete-confirm">

    <div class="modal-dialog ">

        <div class="modal-content">

            <div class="modal-header">

                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>

            </div>

            <div class="modal-body">

                <div class="icon-box"><span>&times;</span></div>

                <h4>Are you sure?</h4>

                <p>Do you really want to delete these records? This process cannot be undone.</p>

                <div class="button-list">

                    <button type="button" class="btn border-btn" data-dismiss="modal">Cancel</button>

                    <button type="button" class="btn delete">Delete</button>

                </div>

            </div>

        </div>

    </div>

</div>
<!-- Order Track Modal -->

<div class="modal fade order-track" id="track-order" tabindex="-1" role="dialog"
    aria-labelledby="exampleModalCenterTitle" aria-hidden="true">

    <div class="modal-dialog modal-dialog-centered" role="document">

        <div class="modal-content">

            <div class="modal-header">

                <h5 class="modal-title">Track Item</h5>

                <button type="button" class="close" data-dismiss="modal" aria-label="Close">

                    <span aria-hidden="true">&times;</span>

                </button>

            </div>

            <div class="modal-body">

                <ul class="track-item">

                    <li><span>Arriving</span> By Thu, 01 May</li>

                    <li><span>Shipped</span> By Thu, 01 May</li>

                    <li class="active"><span>Order Placed</span> By Wed, 30 Apr</li>

                </ul>

            </div>

            <!-- <div class="modal-footer">

        <button type="button" class="btn border-btn" data-dismiss="modal">Close</button>

        <button type="button" class="btn theme-btn"></button>

      </div> -->

        </div>

    </div>

</div>




<!-- Return Order Modal -->

<!-- <div class="modal fade return-order" id="return-order" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">

  <div class="modal-dialog modal-dialog-centered" role="document">

    <div class="modal-content">

      <div class="modal-header">

        <h5 class="modal-title" >Return Order</h5>

        <button type="button" class="close" data-dismiss="modal" aria-label="Close">

          <span aria-hidden="true">&times;</span>

        </button>

      </div>

      <div class="modal-body">        

         <div class="default-address">

            <div class="form-group">

                <label class="radio-box">

                  <h5>Home</h5>

                  <p><b>John Doe</b> <span class="text-muted">(Deafult)</span></p>

                  <p>Rno 319 Shree Krishna Complex, opp mahesh aptm gate no2 new ashok nagar east delhi, delhi 380061</p>

                  <p><span class="text-muted">Mobile: </span>9865654456</p>

                  <input type="radio" name="sendoption" checked="checked"><span class="checkmark"></span>

                </label>

            </div>

            <div class="form-group">

                <label class="radio-box">

                  <h5>Work</h5>

                  <p><b>John Doe</b></p>

                  <p>Rno 319 Shree Krishna Complex, opp mahesh aptm gate no2 new ashok nagar east delhi, delhi 380061</p>

                  <p><span class="text-muted">Mobile: </span>9865654456</p>

                  <input type="radio" name="sendoption"><span class="checkmark"></span>

                </label>

            </div>            

          </div>

          <div class="bank-details">

            <h5>Bank Details</h5>

            <div class="form-group">

              <input type="text" name="" class="form-control" placeholder="IFSC CODE">

            </div>

            <div class="form-group">

              <input type="text" name="" class="form-control" placeholder="Account Number">

            </div>

            <div class="form-group">

              <input type="text" name="" class="form-control" placeholder="Account holder Name">

            </div>

          </div>

      </div>

      <div class="modal-footer">

        <button type="button" class="btn border-btn" data-dismiss="modal">Close</button>

        <button type="button" class="btn theme-btn">Submit</button>

      </div>

    </div>

  </div>

</div> -->

