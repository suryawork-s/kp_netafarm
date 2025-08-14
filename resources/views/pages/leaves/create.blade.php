@extends('layouts.dashboard')


@push('style')
    @include('style.select2')
@endpush

@section('content')
    <div class="content-header">
        <div class="d-flex justify-content-between">
            <h4>Pengajuan {{ $type }}</h4>
        </div>
    </div>

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            {{ session('error') }}
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    @endif

    <div class="card">
        <div class="card-body">
            <form id="leaveForm" action="{{ route('dashboard.leaves.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Karyawan</label>
                            <input type="text" name="name" class="form-control" id="name"
                                value="{{ Auth::user()->name }}" readonly>
                            <input type="hidden" name="user_id" class="form-control" id="user_id"
                                value="{{ Auth::user()->id }}">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Pilih Leader</label>
                            <select class="select2" style="width: 100%;" name="leader_id" id="leader_id">
                                @foreach ($leaders as $leader)
                                    <option value="{{ $leader->id }}">{{ $leader->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    @if ($type == 'izin')
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>Tipe Izin</label>
                                <select class="select2" style="width: 100%;" name="type" id="type">
                                    <option value="izin" id="izin">Izin</option>
                                    <option value="sakit" id="sakit">Sakit</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-4">
                            {{-- Date --}}
                            <div class="form-group">
                                <label>Mulai</label>
                                <input type="date" name="start" class="form-control" id="start" placeholder="Mulai"
                                    value="{{ old('start') }}">
                                @error('start')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-4">
                            {{-- Date --}}
                            <div class="form-group">
                                <label>Selesai</label>
                                <input type="date" name="end" class="form-control" id="end"
                                    placeholder="Selesai" value="{{ old('end') }}">
                                @error('end')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                    @else
                        <input type="hidden" name="type" id="type" value="{{ $type }}">
                        @if ($type == 'lembur')
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label>Tanggal</label>
                                    <input type="date" name="date" class="form-control" id="date"
                                        placeholder="Tanggal Lembur" value="{{ old('date') }}">
                                </div>
                            </div>
                        @endif
                        <div class="col-md-6">
                            {{-- Date --}}
                            <div class="form-group">
                                <label>Mulai</label>
                                @if ($type == 'lembur')
                                    {{-- time picker --}}
                                    <input type="time" name="start" class="form-control" id="start"
                                        placeholder="Mulai" value="{{ old('start') }}">
                                @else
                                    <input type="date" name="start" class="form-control" id="start"
                                        placeholder="Mulai" value="{{ old('start') }}">
                                @endif
                                @error('start')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            {{-- Date --}}
                            <div class="form-group">
                                <label>Selesai</label>
                                @if ($type == 'lembur')
                                    {{-- time picker --}}
                                    <input type="time" name="end" class="form-control" id="end"
                                        placeholder="Mulai" value="{{ old('end') }}">
                                @else
                                    <input type="date" name="end" class="form-control" id="end"
                                        placeholder="Mulai" value="{{ old('end') }}">
                                @endif
                                @error('end')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                    @endif
                    <div class="col-md-12">
                        <div class="form-group">
                            <label>Keterangan</label>
                            <textarea name="description" class="form-control" id="description" placeholder="Masukkan Keterangan">{{ old('description') }}</textarea>
                            @error('description')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-12" id="attachment-form" hidden>
                        <div class="form-group">
                            <label>Lampiran</label>
                            <input type="file" name="attachment" class="form-control" id="attachment">
                            @error('attachment')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-12">
                        <div class="form-group">
                            <a href="#" id="backBtn" class="btn btn-default">Kembali</a>
                            <button type="submit" id="submitBtn" class="btn btn-primary">Submit</button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection


@push('script')
    @include('scripts.select2')

    <script>
        //Initialize Select2 Elements
        $('.select2').select2()

        //Initialize Select2 Elements
        $('.select2').select2({
            theme: 'bootstrap4'
        })

        // Jika #type option value nya 2, maka munculkan attachment
        $('#type').on('change', function() {
            if ($(this).val() == 'sakit') {
                $('#attachment-form').prop('hidden', false);
            } else {
                $('#attachment-form').prop('hidden', true);
            }
        });

        $('#leaveForm').on('submit', function(e) {
            // submit button to loading state
            let submitBtn = $('#submitBtn');
            let backBtn = $('#backBtn');
            submitBtn.html(
                    '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>')
                .attr('disabled', true);
            // btn back disabled
            backBtn.attr('disabled', true);
        });
    </script>
@endpush
