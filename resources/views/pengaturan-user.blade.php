@extends('index')

@section('content')

<section>
    <form action="{{ route('pengaturan_user_update') }}" method="post" autocomplete="off">
        @csrf
        <div class="card">

            {{-- show success message after save data --}}
            @if (session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
            @endif

            {{-- show error message --}}
            @if ($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $errors)
                    <li>{{ $errors }}</li>
                    @endforeach
                </ul>
            </div>
            @endif
            

            <div class="card-body">
                <div class="row">                    
                    <div class="col-lg-6">
                        <div class="mb-2 mt-2">
                            <label for="" class="form-label">Nama*</label>
                            <input type="text" class="form-control" id="name" name="name" value="{{$user->name}}" required>
                        </div>
                    </div>

                    <div class="col-lg-6">
                        <div class="mb-2">
                            <label for="" class="form-label">Email*</label>
                            <input type="text" class="form-control" id="email" name="email" value="{{$user->email}}" required>
                        </div>
                    </div>

                    <div class="col-lg-6">
                        <div class="mb-2">
                            <label for="" class="form-label">Telepon*</label>
                            <input type="text" class="form-control" id="phone" name="phone" value="{{$user->phone}}" required>
                        </div>
                    </div>

                    <div class="col-lg-6">
                        <div class="mb-2">
                            <label for="" class="form-label">Password (isi jika ganti password)</label>
                            <input type="password" class="form-control" id="password" name="password">
                        </div>
                    </div>

                </div>

                <div class="row">
                    <div class="col-lg-12">
                        <div class="mb-2 mt-4">
                            <button type="submit" class="btn btn-info">Simpan</button>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </form>
</section>

@endsection