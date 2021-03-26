@extends('layouts.app')

@section('title', 'Új bejegyzés')

@section('content')
<div class="container">
    <h1>Új bejegyzés</h1>
    <p class="mb-1">Ezen az oldalon tudsz új bejegyzést létrehozni.</p>
    <div class="mb-4">
        <a href="{{ route('posts.index') }}"><i class="fas fa-long-arrow-alt-left"></i> Vissza a bejegyzésekhez</a>
    </div>

    @if (Session::has('post-created'))
        <div class="alert alert-success" role="alert">
            A(z) {{ Session::get('post-created') }} nevű bejegyzés sikeresen létrejött!
        </div>
    @endif

    <form action="{{ route('posts.store') }}" method="POST" enctype="multipart/form-data">
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
                <textarea rows="5" class="form-control @error('text') is-invalid @enderror" id="text" name="text" placeholder="Bejegyzés szövege">{{ old('text') }}</textarea>
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
                <div class="row">
                    @forelse ($categories->chunk(5) as $chunk)
                        <div class="col-6 col-md-4 col-lg-2">
                            @foreach ($chunk as $category)
                                <div class="form-check">
                                    <input
                                        type="checkbox"
                                        class="form-check-input"
                                        value="{{ $category->id }}"
                                        id="category{{ $category->id }}"
                                        name="categories[]"
                                        @if (is_array(old('categories')) && in_array($category->id, old('categories')))
                                            checked
                                        @endif
                                    >
                                    <label
                                        for="category{{ $category->id }}"
                                        class="form-check-label"
                                    >
                                        <span class="badge badge-{{ $category->style }}">
                                            {{ $category->name }}
                                        </span>
                                    </label>
                                </div>
                            @endforeach
                        </div>
                    @empty
                        <p>Nincsenek kategóriák</p>
                    @endforelse
                </div>
            </div>
        </div>
        <div class="form-group row">
            <label class="col-sm-2 col-form-label">Beállítások</label>
            <div class="col-sm-10">
                <div class="form-group">
                    <div class="form-check">
                        <input type="checkbox" class="form-check-input" value="1" id="disable_comments" name="disable_comments">
                        <label for="disable_comments" class="form-check-label">Hozzászólások tiltása</label>
                    </div>
                    <div class="form-check">
                        <input type="checkbox" class="form-check-input" value="1" id="hide_post" name="hide_post">
                        <label for="hide_post" class="form-check-label">Bejegyzés elrejtése</label>
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
