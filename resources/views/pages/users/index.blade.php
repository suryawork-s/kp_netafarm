@extends('layouts.dashboard')

@push('style')
    @include('style.datatable')
@endpush
@section('content')
    <div class="content-header">
        <div class="d-flex justify-content-between">
            <h4>Data Karyawan</h4>
            {{-- button add with modal --}}
            @if (auth()->user()->role === 'superadmin' || auth()->user()->position_id == 1)
                <a href="{{ route('dashboard.users.create') }}"
                    class="btn d-sm-block d-md-block d-lg-block d-xl-block d-none btn-primary mb-2">
                    Tambah Karyawan
                </a>
            @endif
        </div>
        <a href="#" class="btn d-md-none d-lg-none d-xl-none d-block btn-primary mb-2">
            Tambah Karyawan
        </a>
    </div>


    <div class="card">
        <div class="card-body">
            <table class="table table-bordered" id="data-table">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Nama</th>
                        <th>Departemen</th>
                        <th>Posisi</th>
                        <th>Username</th>
                        <th>Email</th>
                        <th>No. HP</th>
                        <th>Tahun Bergabung</th>
                        <th>Status</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($users as $user)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $user->name }}</td>
                            <td>{{ $user->department->name }}</td>
                            <td>{{ $user->position->name }}</td>
                            <td>{{ $user->username }}</td>
                            <td>{{ $user->email }}</td>
                            <td>{{ $user->phone }}</td>
                            <td>{{ $user->join_year }}</td>
                            <td>
                                @if ($user->status == 'active')
                                    <span class="badge badge-success">Aktif</span>
                                @else
                                    <span class="badge badge-danger">Tidak aktif</span>
                                @endif
                            </td>
                            <td>
                                {{-- btn detail modal --}}
                                @if (auth()->user()->role === 'superadmin' || auth()->user()->position_id == 1)
                                    <button type="button" class="btn btn-success" id="detailBtn"
                                        onclick="detailData({{ $user->id }})">Detail</button>
                                    <a class="btn btn-primary"
                                        href="{{ route('dashboard.users.edit', $user->id) }}">Edit</a>
                                    <button type="button" class="btn btn-danger" id="deleteBtn"
                                        onclick="deleteData({{ $user->id }})">Hapus</button>
                                @endif
                            </td>
                        </tr>
                    @endforeach
            </table>
        </div>
    </div>

    <div class="modal fade" id="modal-detail">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Detail Karyawan</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6 py-2">
                            <label for="name">Nama</label>
                            <input type="text" name="name" class="form-control" id="nameDetail" disabled>
                        </div>

                        <div class="col-md-6 py-2">
                            <label for="username">Username</label>
                            <input type="text" name="username" class="form-control" id="usernameDetail" disabled>
                        </div>
                        <div class="col-md-6 py-2">
                            <label for="email">Email</label>
                            <input type="text" name="email" class="form-control" id="emailDetail" disabled>
                        </div>

                        <div class="col-md-6 py-2">
                            <label for="phone">No. HP</label>
                            <input type="text" name="phone" class="form-control" id="phoneDetail" disabled>
                        </div>

                        <div class="col-md-6 py-2">
                            <label for="join_year">Tahun Bergabung</label>
                            <input type="text" name="join_year" class="form-control" id="join_yearDetail" disabled>
                        </div>

                        <div class="col-md-6 py-2">
                            <label for="department_id">Departemen</label>
                            <input type="text" name="department_id" class="form-control" id="department_idDetail"
                                disabled>
                        </div>

                        <div class="col-md-6 py-2">
                            <label for="position_id">Posisi</label>
                            <input type="text" name="position_id" class="form-control" id="position_idDetail" disabled>
                        </div>

                        <div class="col-md-6 py-2">
                            <label for="status">Status</label>
                            <input type="text" name="status" class="form-control" id="statusDetail" disabled>
                        </div>
                    </div>

                    {{-- <h4 class="py-2">Data Cuti</h4>

                    <div class="row">
                        <div class="col-md-4">
                            <label for="cuti">Total</label>
                            <input type="text" name="total" class="form-control" id="cutiTotal" disabled>
                        </div>

                        <div class="col-md-4">
                            <label for="cuti">Sisa</label>
                            <input type="text" name="total" class="form-control" id="cutiSisa" disabled>
                        </div>

                        <div class="col-md-4">
                            <label for="cuti">Terpakai</label>
                            <input type="text" name="total" class="form-control" id="cutiTerpakai" disabled>
                        </div>
                    </div> --}}
                </div>
            </div>
            <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
    </div>
@endsection


@push('script')
    @include('scripts.datatable')

    <script>
        $(function() {
            let table = $("#data-table").DataTable({
                "responsive": true,
                "lengthChange": false,
                "autoWidth": true,
                "buttons": ["copy", "csv", "excel", "pdf", "print", "colvis"],
            }).buttons().container().appendTo('#data-table_wrapper .col-md-6:eq(0)');
        });

        function detailData(id) {
            $.ajax({
                url: "{{ route('dashboard.users.show', ':id') }}".replace(':id', id),
                type: 'GET',
                success: function(response) {
                    console.log(response);
                    $('#nameDetail').val(response.user.name);
                    $('#usernameDetail').val(response.user.username);
                    $('#emailDetail').val(response.user.email);
                    $('#phoneDetail').val(response.user.phone);
                    $('#join_yearDetail').val(response.user.join_year);
                    $('#department_idDetail').val(response.user.department.name);
                    $('#position_idDetail').val(response.user.position.name);
                    $('#statusDetail').val(response.user.status);
                    if (response.leaves) {
                        $('#cutiTotal').val(response.leaves.total);
                        $('#cutiSisa').val(response.leaves.remaining);
                        $('#cutiTerpakai').val(response.leaves.used);
                    }
                    $('#modal-detail').modal('show');
                },
                error: function(xhr) {
                    Swal.fire(
                        'Gagal!',
                        xhr.responseJSON.message ||
                        'Terjadi kesalahan saat menampilkan detail pengguna.',
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
                        url: "{{ route('dashboard.users.destroy', ':id') }}".replace(':id', id),
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
