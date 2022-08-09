<div class="sidenav-overlay"></div>
<div class="drag-target"></div>

<footer class="footer footer-static footer-light">
    <p class="clearfix blue-grey lighten-2 mb-0">
        <span class="float-md-left d-block d-md-inline-block mt-25">
            COPYRIGHT &copy; 2021
            <a class="text-bold-800 grey darken-2" href="#" target="_blank">Pixinvent,</a>
            All rights Reserved
        </span>
        <span class="float-md-right d-none d-md-block">
            Hand-crafted & Made with
            <i class="feather icon-heart pink"></i>
        </span>
        <button class="btn btn-primary btn-icon scroll-top" type="button">
            <i class="feather icon-arrow-up"></i>
        </button>
    </p>
</footer>

<script src="{{ asset('assets/admin/app-assets/vendors/js/vendors.min.js') }}"></script>
<script src="{{ asset('assets/admin/app-assets/vendors/js/charts/apexcharts.min.js') }}"></script>
<script src="{{ asset('assets/admin/app-assets/js/core/app-menu.js') }}"></script>
<script src="{{ asset('assets/admin/app-assets/js/core/app.js') }}"></script>
<script src="{{ asset('assets/admin/app-assets/js/scripts/components.js') }}"></script>
<script src="{{ asset('assets/admin/assets/js/jquery.validate.min.js') }}"></script>
<script src="{{ asset('assets/admin/assets/js/additional-methods.min.js') }}"></script>
<script src="{{ asset('assets/admin/assets/izitoast/js/iziToast.min.js') }}"></script>
<script src="{{ asset('assets/admin/app-assets/vendors/js/extensions/sweetalert2.all.min.js') }}"></script>
<script src="{{ asset('assets/admin/app-assets/js/scripts/extensions/sweet-alerts.js') }}"></script>
<script src="{{ asset('assets/admin/app-assets/vendors/js/forms/select/select2.full.min.js') }}"></script>
<script src="{{ asset('assets/admin/app-assets/js/scripts/forms/select/form-select2.js') }}"></script>

<script>
    var site_url = "{{ route('admin.dashboard') }}";
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
                let _token = $('meta[name="csrf-token"]').attr('content');
                $.ajax({
                    url: action,
                    method: 'POST',
                    data: {
                        _token: _token
                    },
                    success: function(response) {
                        //console.log(response);
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

        //Delete All
        $(".delete-selected-record").off('click').on('click', function(event) {
            event.stopPropagation();
            var selected_length = $(".chk_del:checked").length;
            var id_label_arr = [];
            var id_arr = [];
            var table_arr = [];
            var image_path_arr = [];

            if (selected_length == 0) {
                iziToast.error({
                    title: "Error!",
                    message: "Select atleast one record!",
                    position: "topRight",
                });
            } else {

                swal({
                    title: "Are you sure?",
                    text: "Once deleted, you will not be able to recover this data!",
                    buttons: true,
                }).then((willDelete) => {
                    if (willDelete) {

                        $(".chk_del:checked").each(function() {
                            var id_label = $(this).data('id-label');
                            var id = $(this).data('id');
                            var table = $(this).data('table');
                            var image_path = $(this).data('image-path');

                            id_label_arr.push(id_label);
                            id_arr.push(id);
                            table_arr.push(table);
                            image_path_arr.push(image_path);
                        });

                        $.ajax({
                            url: site_url + 'deleteSelectedRecord',
                            method: 'POST',
                            data: {
                                id_arr: id_arr,
                                id_label_arr: id_label_arr,
                                table_arr: table_arr,
                                image_path_arr: image_path_arr
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
                    } else {}
                });
            }
        });
</script>

@include('flash')

@yield('footer')