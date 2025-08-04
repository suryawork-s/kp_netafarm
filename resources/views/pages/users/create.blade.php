@extends('layouts.dashboard')


@push('style')
    @include('style.select2')
@endpush

@section('content')
    <div class="content-header">
        <div class="d-flex justify-content-between">
            <h4>Tambah Karyawan</h4>
        </div>
    </div>

    <div class="card">
        <div class="card-body">
            <form id="userForm" action="{{ route('dashboard.users.store') }}" method="POST">
                @csrf
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            {{-- Select Department ID --}}
                            <label>Departemen</label>
                            <select class="select2" style="width: 100%;" name="department_id">
                                @foreach ($departments as $department)
                                    <option
                                        value="{{ $department->id }}"{{ old('department_id') == $department->id ? 'selected' : '' }}>
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
                                        {{ old('position_id') == $position->id ? 'selected' : '' }}>{{ $position->name }}
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
                                    <option value="{{ $role->name }}">{{ $role->name }}</option>
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
                                placeholder="Masukkan Nama" value="{{ old('name') }}">
                            @error('name')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="username">Username</label>
                            <input type="text" name="username" class="form-control" id="username"
                                placeholder="Masukkan Username" value="{{ old('username') }}">
                            @error('username')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="email">Email</label>
                            <input type="email" name="email" class="form-control" id="email"
                                placeholder="Masukkan Email" value="{{ old('email') }}">
                            @error('email')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="phone">Phone</label>
                            <input type="text" name="phone" class="form-control" id="phone"
                                placeholder="Masukkan Phone" value="{{ old('phone') }}">
                            @error('phone')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>

                    <div class="col-md-6">
                        {{-- Join year --}}
                        <div class="form-group">
                            <label for="join_year">Tahun Bergabung</label>
                            {{-- input year --}}
                            {{-- input date year --}}
                            <input type="text" name="join_year" class="form-control" id="join_year"
                                placeholder="Masukkan Tahun Bergabung" value="{{ old('join_year') }}">
                            @error('join_year')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="password">Password</label>
                            <input type="password" name="password" class="form-control" id="password"
                                placeholder="Masukkan Password" value="{{ old('password') }}">
                            @error('password')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="repeat_password">Repeat Password</label>
                            <input type="password" name="repeat_password" class="form-control" id="repeat_password"
                                placeholder="Masukkan Password" value="{{ old('repeat_password') }}">
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
    </script>
@endpush
