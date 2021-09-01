@extends('layouts.master')

@section('content')

@include('layouts.navbar')

@include('layouts.sidebar')

<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Role</h1>
                </div>
            </div>
        </div><!-- /.container-fluid -->
    </section>

    <!-- Main content -->
    <section class="content">
        <!-- Default box -->
        <small>
            <div class="card card-solid">
                <div class="card-body">
                    <div class="row d-flex align-items-stretch">
                        <div class="col-lg-12 mx-auto" style="overflow-x: scroll;">
                            @can('role-create')
                            <div class="mb-3">
                                <a class="btn btn-success text-sm float-left" href="{{route('roles.create')}}" role="button"><i class="fas fa-plus"></i> Add New Role</a>
                            </div>
                            @endcan
                            <table id="example" class="display" style="width:100%">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Name</th>
                                        <th>Created At</th>
                                        <th>Updated At</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </small>
    </section>
</div>

<!-- Modal -->
<div class="modal fade" id="deleteModal" tabindex="-1" role="dialog" aria-labelledby="deleteModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel"><i class="fa fa-trash"></i> &nbsp; Role User</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <p class="text-center">Are you sure you want to delete this role?</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <form action="{{ route('roles.destroy', 'id') }}" method="post">
                    @csrf
                    @method('DELETE')
                    <input id="id" name="id" hidden />
                    <button type="submit" class="btn btn-outline-danger">Delete</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@section('register-stylecode')
<link rel="stylesheet" href="{{ asset('Datatables/datatables.min.css') }}">
@endsection

@section('register-scriptcode')
<script type="text/javascript" src="{{ asset('Datatables/datatables.min.js') }}"></script>
<script type="text/javascript" src="{{ asset('Datatables/Buttons-1.7.1/js/dataTables.buttons.min.js') }}"></script>
<script type="text/javascript" src="{{ asset('Datatables/Buttons-1.7.1/js/buttons.html5.min.js') }}"></script>
<script>
$(document).ready(function () {
    var canEdit = <?php echo auth()->user()->can('role-edit') ? 1 : 0 ?>;
    var canDelete = <?php echo auth()->user()->can('role-delete') ? 1 : 0; ?>;

    var tabelData = $('#example').DataTable({
        "processing": true,
        "dom": 'Bfrtip',
        "buttons": {
            "buttons": [],
            "dom": {
                "button": {
                    "className": 'btn'
                }
            }
        },
        "oLanguage": {
            "sProcessing": "<img src='{{asset('images/ajax-loader.gif')}}'> <b>Loading<b/>"
        },
        "ajax": {
            "url": "/getRole", //{{route('role.getRole')}}
            "type": "GET",
        },
        "columns": [{
                "data": "no"
            },
            {
                "data": "name"
            },
            {
                "data": "created_at"
            },
            {
                "data": "updated_at"
            },
            {
                mRender: function (data, type, row) {
                    var button = (canEdit ? '<a class="btn btn-warning btn-sm mr-1" data-toggle="tooltip" data-placement="top" title="Edit" href="{{ url("roles")}}/' + row.id + '/edit"><i class="far fa-edit"></i></a>' : '') +
                            (canDelete ? '<button type="button" onClick="handleDelete(' + row.no + ')" class="btn btn-danger btn-sm" data-toggle="modal" data-target="#deleteModal">' +
                                    '<i class="fa fa-trash"></i><p hidden id="encId' + row.no + '">' + row.id + '</p>' +
                                    '</button>' : '');

                    return button;
                }
            }
        ]
    });
});

function handleDelete(id) {
    var encId = document.getElementById("encId" + id).innerHTML;
    document.getElementById("id").value = encId;
}
</script>
@endsection