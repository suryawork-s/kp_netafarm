@extends('layouts.dashboard')
@push('style')
    @include('style.datatable')
    @include('style.select2')
@endpush
@section('content')
    <div class="content-header">
        <div class="d-flex justify-content-between">
            <h4>Data Departemen</h4>
            {{-- button add with modal --}}
            <button type="button" class="btn d-sm-block d-md-block d-lg-block d-xl-block d-none btn-primary mb-2"
                data-toggle="modal" data-target="#modal-default">
                Tambah Departemen
            </button>
        </div>
        <button type="button" class="btn d-md-none d-lg-none d-xl-none d-block btn-primary mb-2" data-toggle="modal"
            data-target="#modal-default">
            Tambah Departemen
        </button>
    </div>

    <div class="card">
        <div class="card-body">
            {{-- Modal Create --}}
            <div class="modal fade" id="modal-default">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h4 class="modal-title">Tambah Departemen</h4>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <form id="departmentForm" method="POST">
                                @csrf
                                <div class="form-group">
                                    <label for="name">Departemen</label>
                                    <input type="text" name="name" class="form-control" id="name"
                                        placeholder="Masukkan departemen">
                                    <div class="text-danger" id="nameError"></div>
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
                            <h4 class="modal-title">Edit Departemen</h4>
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
                                    <label for="name">Departemen</label>
                                    <input type="text" name="name" class="form-control" id="nameEdit"
                                        placeholder="Masukkan departemen">
                                    <div class="text-danger" id="nameErrorEdit"></div>
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

            {{-- Modal Detail --}}
            {{-- <div class="modal fade" id="modal-detail">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h4 class="modal-title">Detail Departemen</h4>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <div class="form-group">
                                <label>Multiple</label>
                                <select id="select2-position" name="positions[]" class="select2" multiple="multiple"
                                    data-placeholder="Jabatan yang tersedia" style="width: 100%;">
                                </select>

                            </div>
                        </div>
                    </div>
                    <!-- /.modal-content -->
                </div>
                <!-- /.modal-dialog -->
            </div> --}}
            <table class="table table-bordered" id="data-table">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Departemen</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($departments as $department)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $department->name }}</td>
                            <td>
                                {{-- btn detail
                                <button type="button" class="btn btn-success" id="detailBtn"
                                    onclick="detailData({{ $department->id }})">Detail</button> --}}
                                {{-- btn edit --}}
                                <button type="button" class="btn btn-primary" id="editBtn"
                                    onclick="editData({{ $department->id }})">Edit</button>
                                {{-- btn delete --}}
                                <button type="button" class="btn btn-danger" id="deleteBtn"
                                    onclick="deleteData({{ $department->id }})">Hapus</button>
                            </td>
                        </tr>
                    @endforeach
            </table>
        </div>
    </div>
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

            // //Initialize Select2 Elements
            // $('.select2').select2()

            // //Initialize Select2 Elements
            // $('.select2bs4').select2({
            //     theme: 'bootstrap4'
            // })

            $('#departmentForm').on('submit', function(e) {
                e.preventDefault();
                handleFormSubmit($(this), table);
            });

            $('#editForm').on('submit', function(e) {
                e.preventDefault();
                handleFormSubmitEdit($(this), table);
            });
        });

        function handleFormSubmit(form, table) {
            let formData = form.serialize();
            let submitBtn = $('#submitBtn');
            let dismissBtn = $('#dismissModal');

            submitBtn.html('Loading...').attr('disabled', true);

            $.ajax({
                url: "{{ route('dashboard.departments.store') }}",
                type: "POST",
                dataType: "json",
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                data: formData,
                success: function(response) {
                    if (response.success) {
                        resetFormState(submitBtn, dismissBtn);
                        displayToast('success', response.message);
                        setInterval(() => {
                            location.reload();
                        }, 1000);
                    }
                },
                error: function(response) {
                    displayErrors(response);
                    resetFormState(submitBtn, dismissBtn, false);
                }
            });
        }

        function resetFormState(submitBtn, dismissBtn, reset = true, formSelector = '#departmentForm', errorSelector =
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


        function detailData(id) {
            $.ajax({
                url: "{{ route('dashboard.departments.show', ':id') }}".replace(':id', id),
                type: 'GET',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function(response) {
                    $('#modal-detail').modal('show');

                    let select = $('#select2-position');
                    select.empty(); // Kosongkan select sebelum mengisi dengan data baru

                    if (response.active.length === 0) {
                        // Jika tidak ada posisi yang aktif, masukkan semua posisi dari `positions`
                        response.positions.forEach(function(position) {
                            let option = new Option(position.name, position.id, false, false);
                            select.append(option);
                        });
                    } else {
                        // Jika ada posisi yang aktif, masukkan hanya posisi yang aktif
                        response.active.forEach(function(position) {
                            let option = new Option(position.name, position.id, true, true);
                            select.append(option);
                        });
                    }

                    // Initialize select2 dengan data yang baru dimasukkan
                    select.trigger('change');
                },
                error: function(response) {
                    displayToast('error', 'Gagal mengambil data departemen.');
                }
            });
        }



        function editData(id) {
            $.ajax({
                url: "{{ route('dashboard.departments.edit', ':id') }}".replace(':id', id),
                type: 'GET',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function(response) {
                    $('#modal-edit').modal('show');
                    $('#idEdit').val(response.id); // Set ID ke hidden input
                    $('#nameEdit').val(response.name); // Set nama departemen
                },
                error: function(response) {
                    displayToast('error', 'Gagal mengambil data departemen.');
                }
            });
        }


        function handleFormSubmitEdit(form, table) {
            let formData = form.serialize();
            let submitBtn = $('#submitBtnEdit');
            let dismissBtn = $('#dismissModalEdit');
            let id = $('#idEdit').val(); // Ambil ID dari hidden input

            submitBtn.html('Loading...').attr('disabled', true);

            $.ajax({
                url: "{{ route('dashboard.departments.update', ':id') }}".replace(':id', id),
                type: "PUT",
                dataType: "json",
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                data: formData,
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
                        url: "{{ route('dashboard.departments.destroy', ':id') }}".replace(':id', id),
                        type: 'DELETE',
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                        success: function(response) {
                            displayToast('success', 'Departemen berhasil dihapus.');
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
