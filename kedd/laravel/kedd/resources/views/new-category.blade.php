@extends('layouts.base')
@section('title', 'Új kategória')

@section('main-content')
<div class="container">
    <h1>Új kategória</h1>
    <p class="mb-1">Ezen az oldalon tudsz új kategóriát létrehozni. A bejegyzéseket úgy tudod hozzárendelni, ha a kategória létrehozása után módosítod a bejegyzést, és ott bejelölöd ezt a kategóriát is.</p>
    <div class="mb-4">
        <a href="{{ route('home') }}"><i class="fas fa-long-arrow-alt-left"></i> Vissza a bejegyzésekhez</a>
    </div>

    <form action="{{ route('store-category') }}" method="POST">
        @csrf
        <div class="form-group row">
            <label for="name" class="col-sm-2 col-form-label">Név*</label>
            <div class="col-sm-10">
                <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" placeholder="Kategória neve" value="{{ old('name') }}">
                @error('name')
                    <div class="invalid-feedback">
                        {{ $message }}
                    </div>
                @enderror
            </div>
        </div>
        <div class="form-group row">
            <label for="inputPassword" class="col-sm-2 col-form-label">Stílus*</label>
            <div class="col-sm-10">
                @foreach ($possible_styles as $style)
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="style" id="style-{{ $style }}" value="{{ $style }}" {{ old('style') === $style ? 'checked' : '' }}>
                        <label class="form-check-label" for="style-{{ $style }}">
                            <span class="badge badge-{{ $style }}">{{ $style }}</span>
                        </label>
                    </div>
                @endforeach
                @error('style')
                    <p class="text-danger small">{{ $message }}</p>
                @enderror
            </div>
        </div>
        <div class="text-center">
            <button type="submit" class="btn btn-primary">Létrehoz</button>
        </div>
    </form>
</div>
@endsection
