@extends('layouts.app')
@push('css')
<link href="https://cdn.jsdelivr.net/npm/datatables.net-bs5@1.13.6/css/dataTables.bootstrap5.min.css" rel="stylesheet">
@endpush
@section('header')
{{ __('User') }}
@endsection
@section('content')
<div class="container mt-5">
    <h2 class="mb-4">User Management (Laravel 13 Server-Side AJAX)</h2>
    <table class="table table-bordered yajra-datatable">
        <thead>
            <tr>
                <th>No</th>
                <th>Name</th>
                <th>Email</th>
                <th>Created At</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
        </tbody>
    </table>
</div>

<div class="modal fade" id="editUserModal" tabindex="-1" aria-labelledby="editUserModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editUserModalLabel">Edit User</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="editUserForm">
                <div class="modal-body">
                    <input type="hidden" id="user_id" name="id">

                    <div class="mb-3">
                        <label for="name" class="form-label">Name</label>
                        <input type="text" class="form-control" id="name" name="name" required>
                    </div>
                    <div class="mb-3">
                        <label for="email" class="form-label">Email address</label>
                        <input type="email" class="form-control" id="email" name="email" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-success" id="saveBtn">Save Changes</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
@push('js')

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

<script src="https://cdn.jsdelivr.net/npm/datatables.net@1.13.6/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/datatables.net-bs5@1.13.6/js/dataTables.bootstrap5.min.js"></script>
<script type="text/javascript">
    $(function() {

        // Setup CSRF Token untuk semua request AJAX jQuery
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        // Inisialisasi DataTable
        var table = $('.yajra-datatable').DataTable({
            processing: true,
            serverSide: true,
            ajax: "{{ route('users.data') }}",
            columns: [{
                    data: 'DT_RowIndex',
                    name: 'DT_RowIndex',
                    orderable: false,
                    searchable: false
                },
                {
                    data: 'name',
                    name: 'name'
                },
                {
                    data: 'email',
                    name: 'email'
                },
                {
                    data: 'created_at',
                    name: 'created_at'
                },
                {
                    data: 'action',
                    name: 'action',
                    orderable: false,
                    searchable: false
                },
            ]
        });

        // 1. Tampilkan Modal Saat Tombol Edit di-Klik
        $('body').on('click', '.editUserBtn', function() {
            var user_id = $(this).data('id');

            // Ambil data user via AJAX GET
            $.get("/users/" + user_id + "/edit", function(data) {
                $('#editUserModalLabel').html("Edit User");
                $('#saveBtn').val("edit-user");
                $('#editUserModal').modal('show');

                // Isi form dengan data dari server
                $('#user_id').val(data.id);
                $('#name').val(data.name);
                $('#email').val(data.email);
            })
        });

        // 2. Proses Submit Form Edit via AJAX PUT
        $('#editUserForm').on('submit', function(e) {
            e.preventDefault();
            $('#saveBtn').html('Saving...');

            var user_id = $('#user_id').val();

            $.ajax({
                data: $(this).serialize(),
                url: "/users/" + user_id,
                type: "PUT",
                dataType: 'json',
                success: function(data) {
                    $('#editUserForm').trigger("reset");
                    $('#editUserModal').modal('hide');
                    $('#saveBtn').html('Save Changes');

                    // Reload DataTable secara otomatis tanpa reload halaman penuh
                    table.draw();

                    alert(data.success);
                },
                error: function(data) {
                    console.log('Error:', data);
                    $('#saveBtn').html('Save Changes');
                    alert('Terjadi kesalahan, silakan cek inputan Anda.');
                }
            });
        });

        $('#editUserModal').on('hide.bs.modal', function() {
            $(document.activeElement).blur();
        });
    });
</script>

@endpush