@extends('layouts.dashboard')

@push('style')
    @include('style.datatable')
    @include('style.select2')
@endpush
@section('content')
    <div class="content-header">
        <div class="d-flex justify-content-between">
            <h4>Laporan</h4>
            {{-- Filter button --}}
            <button type="button" class="btn d-sm-block d-md-block d-lg-block d-xl-block d-none btn-primary mb-2"
                data-toggle="modal" data-target="#filterModal">
                Filter
            </button>

            {{-- Modal Filter --}}
            <div class="modal fade" id="filterModal">
                <div class="modal-dialog modal-lg">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h4 class="modal-title">Filter Data</h4>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <form method="GET" action="{{ route('dashboard.report.index') }}">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="startDate">Dari Tanggal</label>
                                            <input type="date" class="form-control" id="startDate" name="start_date"
                                                value="{{ request('start_date') }}">
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="endDate">Sampai Tanggal</label>
                                            <input type="date" class="form-control" id="endDate" name="end_date"
                                                value="{{ request('end_date') }}">
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="type">Tipe</label>
                                            <select class="select2" style="width: 100%;" id="type" name="type[]"
                                                multiple>
                                                <option value="izin"
                                                    {{ in_array('izin', request('type', [])) ? 'selected' : '' }}>Izin
                                                </option>
                                                <option value="sakit"
                                                    {{ in_array('sakit', request('type', [])) ? 'selected' : '' }}>Sakit
                                                </option>
                                                <option value="cuti"
                                                    {{ in_array('cuti', request('type', [])) ? 'selected' : '' }}>Cuti
                                                </option>
                                                <option value="lembur"
                                                    {{ in_array('lembur', request('type', [])) ? 'selected' : '' }}>Lembur
                                                </option>
                                            </select>
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="status">Status</label>
                                            <select class="select2" style="width: 100%;" id="status" name="status[]"
                                                multiple>
                                                <option value="pending"
                                                    {{ in_array('pending', request('status', [])) ? 'selected' : '' }}>
                                                    Pending</option>
                                                <option value="approved"
                                                    {{ in_array('approved', request('status', [])) ? 'selected' : '' }}>
                                                    Approved</option>
                                                <option value="rejected"
                                                    {{ in_array('rejected', request('status', [])) ? 'selected' : '' }}>
                                                    Rejected</option>
                                            </select>
                                        </div>
                                    </div>

                                    <div class="col-12">
                                        <button type="submit" class="btn btn-primary">Terapkan Filter</button>
                                    </div>
                                </div>
                            </form>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="card">
        <div class="card-body">
            {{-- Data table --}}
            <table class="table table-bordered" id="data-table">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Nama</th>
                        <th>Leader</th>
                        <th>Tanggal</th>
                        <th>Mulai</th>
                        <th>Selesai</th>
                        <th>Deskripsi</th>
                        <th>Tipe</th>
                        <th>Status</th>
                        @if (auth()->user()->role === 'superadmin' || auth()->user()->position_id == 1)
                            <th>Aksi</th>
                        @endif
                    </tr>
                </thead>
                <tbody>
                    @forelse ($leaves as $data)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $data->user->name }}</td>
                            <td>{{ $data->leader->name }}</td>
                            <td>{{ $data->date }}</td>
                            {{-- Modifikasi di sini --}}
                            <td>{{ \Carbon\Carbon::parse($data->start)->format('Y-m-d') }}</td>
                            <td>{{ \Carbon\Carbon::parse($data->end)->format('Y-m-d') }}</td>
                            {{-- Akhir modifikasi --}}
                            <td>{{ $data->description }}</td>
                            <td>{{ ucfirst($data->type) }}</td>
                            <td>{{ ucfirst($data->status) }}</td>
                            @if (auth()->user()->role === 'superadmin' || auth()->user()->position_id == 1)
                                <td>
                                    <form action="{{ route('dashboard.leaves.destroy', $data->id) }}" method="POST">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger">Delete</button>
                                    </form>

                                </td>
                            @endif
                        </tr>
                    @empty
                        <tr>
                            <td colspan="9" class="text-center">Tidak ada data yang ditemukan.</td>
                        </tr>
                    @endforelse
                </tbody>
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
        });

        //Initialize Select2 Elements
        $('.select2').select2()

        //Initialize Select2 Elements
        $('.select2').select2({
            theme: 'bootstrap4'
        })
    </script>
@endpush
