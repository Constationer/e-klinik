@extends('index')

@section('content')

<section>
    <form action="{{ route('pengaturan_update') }}" method="post" autocomplete="off">
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
                    <div class="col-lg-12">
                        <div class="mb-2 mt-2">
                            <label for="" class="form-label">Nama Website*</label>
                            <input type="text" class="form-control" id="web_name" name="web_name" value="{{$web_name}}" required>
                        </div>
                    </div>

                    <div class="col-lg-12">
                        <div class="mb-2">
                            <label for="" class="form-label">Deskripsi Website*</label>
                            <textarea class="form-control" id="web_desc" name="web_desc" rows="3" required>{{$web_desc}}</textarea>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-lg-12">
                        <div class="mb-2 mt-4">
                            @if (in_array(1, $user_roles))
                                <button type="submit" class="btn btn-info">Simpan</button>
                            @endif
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </form>
</section>

@endsection