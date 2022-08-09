<div class="button-container text-right mt-1 mb-1"></div>

<div class="table-responsive">
    <table class="table table-striped dataex-html5-selectors_1">
        <thead>
            <tr>
                <th>#</th>
                <th>Name</th>
                <!--<th>Email</th>-->
                <th>Mobile No</th>

                <th>Mode of Reg.</th>
                <th>Status</th>
                @if(
                Auth()->user()->permissions->contains('name','admin.users.edit')
                )
                <th class="not-export-col">Action</th>
                @endif

            </tr>
        </thead>
        <tbody id="usersBody">
            @if(count($users) > 0)
            @foreach ($users as $user)
            <tr>
                <td>{{ $loop->iteration}}</td>
                <td>{{ $user->first_name.' '.$user->last_name }}</td>
                <!--<td>{{ $user->email }}</td>-->
                <td>{{ $user->mobile_number }}</td>
                <td>{{ $user->mode_of_registration }}</td>

                <td>{!! $user->status ? '<p class="badge badge-pill badge-light-primary">
                        Active</p>' : '<p class="badge badge-pill badge-light-danger">
                        Inactive
                    </p>' !!}</td>

                @if(
                Auth()->user()->permissions->contains('name','admin.users.edit')
                )
                <td>
                    @if(
                    Auth()->user()->permissions->contains('name','admin.users.edit')
                    )
                    <a href="{{ route('admin.users.edit',$user->id) }}"
                        class="btn btn-sm btn-primary"><i class="fa fa-pencil fa-lg"></i>
                    </a>
                    @endif

                </td>
                @endif
            </tr>

            @endforeach
            @endif
        </tbody>
    </table>
</div>

<script type="text/javascript">
    var table = $('.dataex-html5-selectors_1').DataTable( {
    dom: 'Blfrtip',
    buttons: [
        // {
        //     extend: 'copyHtml5',
        //     exportOptions: {
        //         columns: [ 0, ':visible' ]
        //     }
        // },
        {
            extend: 'csv',
            text: '<i class="fa fa-file-excel-o"></i> &nbsp; EXCEL',
            className: 'btn-success',
            exportOptions: {
                columns: ':visible:not(.not-export-col)'
            }
        },
        {
            extend: 'pdfHtml5',
            text: '<i class="fa fa-file-pdf-o"></i> &nbsp; PDF',
            className: 'btn-danger',
            exportOptions: {
                columns: ':visible:not(.not-export-col)'
            }
        },
        // {
        //     text: 'JSON',
        //     action: function ( e, dt, button, config ) {
        //         var data = dt.buttons.exportData();

        //         $.fn.dataTable.fileSave(
        //             new Blob( [ JSON.stringify( data ) ] ),
        //             'Export.json'
        //         );
        //     }
        // },
        {
            extend: 'print',
            text: '<i class="fa fa-print"></i> &nbsp; PRINT',
            className: 'btn-print',
            exportOptions: {
                columns: ':visible:not(.not-export-col)'
            }
        }
    ]
    });

    table.buttons().container()
    .appendTo('.button-container');
</script>
