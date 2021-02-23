@extends('layouts.base')
@section('title', 'Új kategória')

@section('main-content')
<div class="container">
    <h1>Új bejegyzés</h1>
    <p class="mb-1">Ezen az oldalon tudsz új bejegyzést létrehozni.</p>
    <div class="mb-4">
        <a href="{{ route('home') }}"><i class="fas fa-long-arrow-alt-left"></i> Vissza a bejegyzésekhez</a>
    </div>

    <form action="{{ route('store-post') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <div class="form-group row">
            <label for="title" class="col-sm-2 col-form-label">Cím*</label>
            <div class="col-sm-10">
                <input type="text" class="form-control @error('title') is-invalid @enderror" id="title" name="title" placeholder="Bejegyzés címe" value="{{ old('title') }}">
                @error('title')
                    <div class="invalid-feedback">
                        {{ $message }}
                    </div>
                @enderror
            </div>
        </div>
        <div class="form-group row">
            <label for="text" class="col-sm-2 col-form-label">Szöveg*</label>
            <div class="col-sm-10">
                <textarea id="text" rows="5" class="form-control @error('text') is-invalid @enderror" name="text" placeholder="Bejegyzés szövege">{{ old('text') }}</textarea>
                @error('text')
                    <div class="invalid-feedback">
                        {{ $message }}
                    </div>
                @enderror
            </div>
        </div>
        <div class="form-group row">
            <label for="inputPassword" class="col-sm-2 col-form-label">Kategória</label>
            <div class="col-sm-10">
                <div class="form-check">
                    <input type="checkbox" class="form-check-input" value="1" id="category1" name="categories[]">
                    <label for="category1" class="form-check-label">
                        <span class="badge badge-primary">Primary</span>
                    </label>
                </div>
                <div class="form-check">
                    <input type="checkbox" class="form-check-input" value="2" id="category2" name="categories[]">
                    <label for="category2" class="form-check-label">
                        <span class="badge badge-secondary">Secondary</span>
                    </label>
                </div>
                <div class="form-check">
                    <input type="checkbox" class="form-check-input" value="3" id="category3" name="categories[]">
                    <label for="category3" class="form-check-label">
                        <span class="badge badge-success">Success</span>
                    </label>
                </div>
                <div class="form-check">
                    <input type="checkbox" class="form-check-input" value="4" id="category4" name="categories[]">
                    <label for="category4" class="form-check-label">
                        <span class="badge badge-danger">Danger</span>
                    </label>
                </div>
            </div>
        </div>
        <div class="form-group row">
            <label class="col-sm-2 col-form-label">Beállítások</label>
            <div class="col-sm-10">
                <div class="form-group">
                    <div class="form-check">
                        <input type="checkbox" class="form-check-input" value="1" id="disable-comments" name="disable-comments" {{ old('disable-comments') ? 'checked' : '' }}>
                        <label for="disable-comments" class="form-check-label">Hozzászólások tiltása</label>
                    </div>
                    <div class="form-check">
                        <input type="checkbox" class="form-check-input" value="1" id="hide-post" name="hide-post" {{ old('hide-post') ? 'checked' : '' }}>
                        <label for="hide-post" class="form-check-label">Bejegyzés elrejtése</label>
                    </div>
                </div>
            </div>
        </div>
        <div class="form-group row">
            <label for="attachment" class="col-sm-2 col-form-label">Csatolmány</label>
            <div class="col-sm-10">
                <div class="form-group">
                    <input type="file" class="form-control-file @error('attachment') is-invalid @enderror" id="attachment" name="attachment">
                    @error('attachment')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                    @enderror
                </div>
            </div>
        </div>
        <div class="text-center">
            <button type="submit" class="btn btn-primary">Létrehoz</button>
        </div>
    </form>
</div>
@endsection
