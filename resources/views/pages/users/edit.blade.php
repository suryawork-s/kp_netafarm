@extends('layouts.dashboard')


@push('style')
    @include('style.datatable')
    @include('style.select2')
@endpush

@section('content')
    <div class="content-header">
        <div class="d-flex justify-content-between">
            <h4>Edit Karyawan</h4>
        </div>
    </div>

    <div class="card">
        <div class="card-body">
            <form id="userForm" action="{{ route('dashboard.users.update', $user->id) }}" method="POST">
                @csrf
                @method('PUT') <!-- Menambahkan metode PUT untuk update -->
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            {{-- Select Department ID --}}
                            <label>Departemen</label>
                            <select class="select2" style="width: 100%;" name="department_id">
                                @foreach ($departments as $department)
                                    <option value="{{ $department->id }}"
                                        {{ old('department_id', $user->department_id) == $department->id ? 'selected' : '' }}>
                                        {{ $department->name }}
                                    </option>
                                @endforeach
                            </select>
                            {{-- errors --}}
                            @error('department_id')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="form-group">
                            {{-- Select Position ID --}}
                            <label>Jabatan</label>
                            <select class="select2" style="width: 100%;" name="position_id">
                                @foreach ($positions as $position)
                                    <option value="{{ $position->id }}"
                                        {{ old('position_id', $user->position_id) == $position->id ? 'selected' : '' }}>
                                        {{ $position->name }}
                                    </option>
                                @endforeach
                            </select>
                            {{-- errors --}}
                            @error('position_id')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="role">Role</label>
                            <select class="select2" style="width: 100%;" id="role" name="role">
                                @foreach ($roles as $role)
                                    {{-- selected --}}
                                    <option value="{{ $role->name }}"
                                        {{ $role->name == $user->getRoleNames()->first() ? 'selected' : '' }}>
                                        {{ $role->name }}</option>
                                @endforeach
                            </select>
                            @error('role')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="name">Nama</label>
                            <input type="text" name="name" class="form-control" id="name"
                                placeholder="Masukkan Nama" value="{{ old('name', $user->name) }}">
                            @error('name')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="username">Username</label>
                            <input type="text" name="username" class="form-control" id="username"
                                placeholder="Masukkan Username" value="{{ old('username', $user->username) }}">
                            @error('username')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="email">Email</label>
                            <input type="email" name="email" class="form-control" id="email"
                                placeholder="Masukkan Email" value="{{ old('email', $user->email) }}">
                            @error('email')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="phone">Phone</label>
                            <input type="text" name="phone" class="form-control" id="phone"
                                placeholder="Masukkan Phone" value="{{ old('phone', $user->phone) }}">
                            @error('phone')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>

                    <div class="col-md-6">
                        {{-- Join year --}}
                        <div class="form-group">
                            <label for="join_year">Tahun Bergabung</label>
                            <input type="text" name="join_year" class="form-control" id="join_year"
                                placeholder="Masukkan Tahun Bergabung" value="{{ old('join_year', $user->join_year) }}">
                            @error('join_year')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="status">Status</label>
                            <select class="form-control" name="status" id="status">
                                <option value="active" {{ old('status', $user->status) == 'active' ? 'selected' : '' }}>
                                    Active
                                </option>
                                <option value="inactive"
                                    {{ old('status', $user->status) == 'inactive' ? 'selected' : '' }}>
                                    Inactive
                                </option>
                            </select>
                            @error('status')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="password">Password</label>
                            <input type="password" name="password" class="form-control" id="password"
                                placeholder="Masukkan Password">
                            @error('password')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="repeat_password">Repeat Password</label>
                            <input type="password" name="password_confirmation" class="form-control"
                                id="password_confirmation" placeholder="Ulangi Password">
                            @error('repeat_password')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>

                    {{-- Button back and submit --}}
                    <div class="col-md-12">
                        <div class="form-group">
                            <a href="{{ route('dashboard.users.index') }}" id="backBtn"
                                class="btn btn-default">Kembali</a>
                            <button type="submit" id="submitBtn" class="btn btn-primary">Submit</button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <div class="card">
        <div class="card-header">
            <div class="d-flex justify-content-between">
                <h4>Data Cuti {{ $user->name }}</h4>
                
                <button type="button" data-toggle="modal" data-target="#modal-add"
                    class="btn d-sm-block d-md-block d-lg-block d-xl-block d-none btn-primary mb-2" id="addBtn">
                    Tambah Data Cuti
                </button>
            </div>
        </div>
        <div class="card-body">
            
            <table class="table table-bordered" id="data-table">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Tahun</th>
                        <th>Total</th>
                        <th>Digunakan</th>
                        <th>Sisa</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($user->leaves as $leave)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $leave->year }}</td>
                            <td>{{ $leave->total }}</td>
                            <td>{{ $leave->used }}</td>
                            <td>{{ $leave->remaining }}</td>
                            <td>
                                @if ($leave)
                                    
                                    <div class="d-flex">
                                        <button type="button" class="btn btn-success" id="editBtn"
                                            onclick="editData({{ $leave?->id }})">Edit</button>
                                        <form action="{{ route('dashboard.users.leaves.destroy', $leave?->id) }}"
                                            method="POST" id="deleteForm{{ $leave?->id }}">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-danger mx-2" id="deleteBtn"
                                                onclick="return confirm('Apakah anda yakin ingin menghapus data ini?')">Hapus</button>
                                        </form>
                                    </div>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    {{-- Modal Create Data --}}
    <div class="modal fade" id="modal-add">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Tambah Data Cuti</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form method="POST" action="{{ route('dashboard.users.leaves.store', $user->id) }}">
                        @csrf
                        <div class="form-group">
                            <label for="year">Tahun</label>
                            <input type="number" name="year" class="form-control" id="year"
                                placeholder="Masukkan tahun">
                            <div class="text-danger" id="nameError"></div>
                        </div>
                        <div class="form-group">
                            <label for="total">Total</label>
                            <input type="number" name="total" class="form-control" id="total"
                                placeholder="Masukkan total cuti">
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
            
        </div>
        
    </div>

    <!-- Modal Edit Data -->
    <div class="modal fade" id="modal-edit">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Edit Data Cuti</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form id="form-edit" method="POST">
                        @csrf
                        @method('PUT') 
                        <div class="form-group">
                            <label for="year">Tahun</label>
                            <input type="number" name="year" class="form-control" id="year-edit"
                                placeholder="Masukkan tahun">
                        </div>
                        <div class="form-group">
                            <label for="total">Total</label>
                            <input type="number" name="total" class="form-control" id="total-edit"
                                placeholder="Masukkan total cuti">
                        </div>
                        <div class="form-group">
                            <label for="used">Digunakan</label>
                            <input type="number" name="used" class="form-control" id="used-edit"
                                placeholder="Masukkan cuti yang digunakan">
                        </div>
                        <div class="form-group">
                            <label for="remaining">Sisa</label>
                            <input type="number" name="remaining" class="form-control" id="remaining-edit"
                                placeholder="Masukkan sisa cuti">
                        </div>
                        <div class="justify-content-between">
                            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                            <button type="submit" class="btn btn-primary">Save changes</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection


@push('script')
    @include('scripts.datatable')
    @include('scripts.select2')

    <script>
        let table = $("#data-table").DataTable({
            "responsive": true,
            "lengthChange": false,
            "autoWidth": true,
            "buttons": ["copy", "csv", "excel", "pdf", "print", "colvis"],
        }).buttons().container().appendTo('#data-table_wrapper .col-md-6:eq(0)');

        //Initialize Select2 Elements
        $('.select2').select2()

        //Initialize Select2 Elements
        $('.select2').select2({
            theme: 'bootstrap4'
        })

        $('#userForm').on('submit', function(e) {
            // submit button to loading state
            let submitBtn = $('#submitBtn');
            let backBtn = $('#backBtn');
            submitBtn.html(
                    '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>')
                .attr('disabled', true);
            // btn back disabled
            backBtn.attr('disabled', true);
        });

        function editData(id) {
            $.ajax({
                url: "{{ route('dashboard.users.leaves.show', ':id') }}".replace(':id', id),
                type: 'GET',
                success: function(response) {
                    $('#year-edit').val(response.year);
                    $('#total-edit').val(response.total);
                    $('#used-edit').val(response.used);
                    $('#remaining-edit').val(response.remaining);

                    // Set action URL untuk form edit
                    $('#form-edit').attr('action', "{{ route('dashboard.users.leaves.update', ':id') }}"
                        .replace(':id', id));

                    // Tampilkan modal
                    $('#modal-edit').modal('show');
                },
                error: function(xhr) {
                    Swal.fire(
                        'Gagal!',
                        xhr.responseJSON.message || 'Terjadi kesalahan saat menampilkan data.',
                        'error'
                    );
                }
            });
        }

        function deleteData(id) {
            Swal.fire({
                title: 'Apakah anda yakin?',
                text: "Anda tidak akan dapat mengembalikannya!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Ya, Hapus!'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: "{{ route('dashboard.users.leaves.destroy', ':id') }}".replace(':id', id),
                        type: 'DELETE',
                        data: {
                            _token: "{{ csrf_token() }}"
                        },
                        success: function(response) {
                            Swal.fire(
                                'Terhapus!',
                                response.message,
                                'success'
                            ).then(() => {
                                location.reload(); // Reload the page after closing the alert
                            });
                        },
                        error: function(xhr) {
                            Swal.fire(
                                'Gagal!',
                                xhr.responseJSON.message ||
                                'Terjadi kesalahan saat menghapus pengguna.',
                                'error'
                            );
                        }
                    });
                }
            });
        }
    </script>
@endpush
