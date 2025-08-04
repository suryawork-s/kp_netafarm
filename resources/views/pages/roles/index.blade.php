@extends('layouts.dashboard')

@push('style')
    @include('style.datatable')
    @include('style.select2')
@endpush
@section('content')
    <div class="content-header">
        <div class="d-flex justify-content-between">
            <h4>Data Roles</h4>
            {{-- button add with modal --}}
            <button type="button" class="btn d-sm-block d-md-block d-lg-block d-xl-block d-none btn-primary mb-2"
                data-toggle="modal" data-target="#modal-default">
                Tambah Role
            </button>
        </div>
        <button type="button" class="btn d-md-none d-lg-none d-xl-none d-block btn-primary mb-2" data-toggle="modal"
            data-target="#modal-default">
            Tambah Role
        </button>


        <div class="modal fade" id="modal-default">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title">Tambah Role</h4>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <form id="addForm" method="POST">
                            @csrf
                            <div class="form-group">
                                <label for="name">Role</label>
                                <input type="text" name="name" class="form-control" id="name"
                                    placeholder="Masukkan role">
                                <div class="text-danger" id="nameError"></div>
                            </div>
                            <div class="form-group">
                                <label for="permissions">Permissions</label>
                                <select class="select2" style="width: 100%;" id="permissions" name="permissions[]" multiple>
                                    @foreach ($permissions as $permission)
                                        <option value="{{ $permission->name }}">{{ $permission->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="justify-content-between">
                                <button type="button" id="dismissModal" class="btn btn-default"
                                    data-dismiss="modal">Close</button>
                                <button type="submit" id="submitBtn" class="btn btn-primary">Save changes</button>
                            </div>
                        </form>
                    </div>
                </div>
                <!-- /.modal-content -->
            </div>
            <!-- /.modal-dialog -->
        </div>

        {{-- Modal Edit --}}
        <div class="modal fade" id="modal-edit">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title">Edit Role</h4>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <form id="editForm" method="POST">
                            @csrf
                            @method('PUT') <!-- Tambahkan method PUT -->
                            <input type="hidden" name="id" id="idEdit"> <!-- Hidden input untuk ID -->
                            <div class="form-group">
                                <label for="name">Role</label>
                                <input type="text" name="name" class="form-control" id="nameEdit"
                                    placeholder="Masukkan role">
                                <div class="text-danger" id="nameErrorEdit"></div>
                            </div>
                            <div class="form-group">
                                <label for="permissions">Permissions</label>
                                <select class="select2" style="width: 100%;" id="permissionsEdit" name="permissions[]"
                                    multiple>
                                    @foreach ($permissions as $permission)
                                        <option value="{{ $permission->name }}">{{ $permission->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="justify-content-between">
                                <button type="button" id="dismissModalEdit" class="btn btn-default"
                                    data-dismiss="modal">Close</button>
                                <button type="submit" id="submitBtnEdit" class="btn btn-primary">Update
                                    changes</button>
                            </div>
                        </form>

                    </div>
                </div>
                <!-- /.modal-content -->
            </div>
            <!-- /.modal-dialog -->
        </div>
    </div>


    <table class="table table-bordered" id="data-table">
        <thead>
            <tr>
                <th>No</th>
                <th>Nama</th>
                <th>Permissions</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($roles as $role)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ $role->name }}</td>
                    <th>
                        {{-- Permissions --}}
                        @foreach ($role->permissions as $permission)
                            <span class="badge badge-primary">{{ $permission->name }}</span>
                        @endforeach
                    </th>
                    <td>
                        {{-- btn edit --}}
                        <button type="button" class="btn btn-primary" id="editBtn"
                            onclick="editData({{ $role->id }})">Edit</button>
                        {{-- btn delete --}}
                        <button type="button" class="btn btn-danger" id="deleteBtn"
                            onclick="deleteData({{ $role->id }})">Hapus</button>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
@endsection

@push('script')
    @include('scripts.datatable')
    @include('scripts.select2')
    <script>
        $(function() {
            let table = $("#data-table").DataTable({
                "responsive": true,
                "lengthChange": false,
                "autoWidth": true,
                "buttons": ["copy", "csv", "excel", "pdf", "print", "colvis"],
            }).buttons().container().appendTo('#data-table_wrapper .col-md-6:eq(0)');

            $('#addForm').on('submit', function(e) {
                e.preventDefault();
                handleFormSubmit($(this), table);
            });

            $('#editForm').on('submit', function(e) {
                e.preventDefault();
                handleFormSubmitEdit($(this), table);
            });


            //Initialize Select2 Elements
            $('.select2').select2()

            //Initialize Select2 Elements
            $('.select2').select2({
                theme: 'bootstrap4'
            })
        });

        function handleFormSubmit(form, table) {
            let formData = form.serialize();
            let submitBtn = $('#submitBtn');
            let dismissBtn = $('#dismissModal');

            submitBtn.html('Loading...').attr('disabled', true);

            $.ajax({
                url: "{{ route('dashboard.roles.store') }}",
                type: "POST",
                dataType: "json",
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                data: formData,
                success: function(response) {
                    resetFormState(submitBtn, dismissBtn);
                    displayToast('success', response.message);
                    setInterval(() => {
                        location.reload();
                    }, 1000);
                },
            });
        }

        function resetFormState(submitBtn, dismissBtn, reset = true, formSelector = '#addForm', errorSelector =
            '#nameError') {
            if (reset) {
                $(formSelector)[0].reset();
                $(errorSelector).text('');
            }
            submitBtn.html('Save changes').attr('disabled', false);
            dismissBtn.trigger('click');
        }

        function displayErrors(response, errorSelector = '#nameError') {
            let errors = response.responseJSON.errors;
            if (errors.name) {
                $(errorSelector).text(errors.name[0]);
            } else {
                $(errorSelector).text('');
            }
        }


        function displayToast(type, message) {
            var Toast = Swal.mixin({
                toast: true,
                position: 'top-end',
                showConfirmButton: false,
                timer: 3000
            });

            Toast.fire({
                icon: type,
                title: message
            });
        }

        function editData(id) {
            $.ajax({
                url: "{{ route('dashboard.roles.edit', ':id') }}".replace(':id', id),
                type: 'GET',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function(response) {
                    $('#modal-edit').modal('show');
                    $('#idEdit').val(response.role.id); // Set ID ke hidden input
                    $('#nameEdit').val(response.role.name); // Set nama jabatan

                    // Reset select2 dan set selected permissions
                    $('#permissionsEdit').val(response.role.permissions.map(permission => permission.name))
                        .trigger('change');
                },
                error: function(response) {
                    displayToast('error', 'Gagal mengambil data jabatan.');
                }
            });
        }


        function handleFormSubmitEdit(form, table) {
            let formData = form.serialize(); // ini bentuknya string, cocok untuk data langsung
            let submitBtn = $('#submitBtnEdit');
            let dismissBtn = $('#dismissModalEdit');
            let id = $('#idEdit').val(); // Ambil ID dari hidden input

            submitBtn.html('Loading...').attr('disabled', true);

            $.ajax({
                url: "{{ route('dashboard.roles.update', ':id') }}".replace(':id', id),
                type: "POST", // HARUS pakai POST + _method PUT
                dataType: "json",
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                data: form.serialize(), // kirim string data form langsung
                success: function(response) {
                    if (response.success) {
                        resetFormState(submitBtn, dismissBtn, true, '#editForm', '#nameErrorEdit');
                        displayToast('success', response.message);
                        setTimeout(() => {
                            location.reload();
                        }, 1000);
                    }
                },
                error: function(response) {
                    displayErrors(response, '#nameErrorEdit');
                    resetFormState(submitBtn, dismissBtn, false, '#editForm', '#nameErrorEdit');
                }
            });
        }



        function deleteData(id) {
            Swal.fire({
                title: 'Are you sure?',
                text: "You won't be able to revert this!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, delete it!'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: "{{ route('dashboard.roles.destroy', ':id') }}".replace(':id', id),
                        type: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                        data: {
                            _method: 'DELETE' // Tambahkan parameter _method untuk menunjukkan metode DELETE
                        },
                        success: function(response) {
                            displayToast('success', 'Jabatan berhasil dihapus.');
                            setInterval(() => {
                                location.reload();
                            }, 1000);
                        },
                        error: function(response) {
                            displayToast('error', 'Gagal menghapus departemen.');
                        }
                    });
                }
            });
        }
    </script>
@endpush
