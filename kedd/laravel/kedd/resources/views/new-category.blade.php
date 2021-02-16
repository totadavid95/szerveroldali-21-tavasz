@extends('layouts.base')
@section('title', 'Új kategória')

@section('main-content')
<div class="container">
    <h1>Új kategória</h1>
    <p class="mb-1">Ezen az oldalon tudsz új kategóriát létrehozni. A bejegyzéseket úgy tudod hozzárendelni, ha a
        kategória létrehozása után módosítod a bejegyzést, és ott bejelölöd ezt a kategóriát is.</p>
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
                <div class="form-check">
                    <input class="form-check-input" type="radio" name="style" id="style-primary" value="primary"
                        checked>
                    <label class="form-check-label" for="style-primary">
                        <span class="badge badge-primary">Primary</span>
                    </label>
                </div>
                <div class="form-check">
                    <input class="form-check-input" type="radio" name="style" id="style-secondary"
                        value="secondary">
                    <label class="form-check-label" for="style-secondary">
                        <span class="badge badge-secondary">Secondary</span>
                    </label>
                </div>
                <div class="form-check">
                    <input class="form-check-input" type="radio" name="style" id="style-success" value="success">
                    <label class="form-check-label" for="style-success">
                        <span class="badge badge-success">Success</span>
                    </label>
                </div>
                <div class="form-check">
                    <input class="form-check-input" type="radio" name="style" id="style-danger" value="danger">
                    <label class="form-check-label" for="style-danger">
                        <span class="badge badge-danger">Danger</span>
                    </label>
                </div>
                <div class="form-check">
                    <input class="form-check-input" type="radio" name="style" id="style-warning" value="warning">
                    <label class="form-check-label" for="style-warning">
                        <span class="badge badge-warning">Warning</span>
                    </label>
                </div>
                <div class="form-check">
                    <input class="form-check-input" type="radio" name="style" id="style-info" value="info">
                    <label class="form-check-label" for="style-info">
                        <span class="badge badge-info">Info</span>
                    </label>
                </div>
                <div class="form-check">
                    <input class="form-check-input" type="radio" name="style" id="style-light" value="light">
                    <label class="form-check-label" for="style-light">
                        <span class="badge badge-light">Light</span>
                    </label>
                </div>
                <div class="form-check">
                    <input class="form-check-input" type="radio" name="style" id="style-dark" value="dark">
                    <label class="form-check-label" for="style-dark">
                        <span class="badge badge-dark">Dark</span>
                    </label>
                </div>
            </div>
        </div>
        <div class="text-center">
            <button type="submit" class="btn btn-primary">Létrehoz</button>
        </div>
    </form>
</div>
@endsection
