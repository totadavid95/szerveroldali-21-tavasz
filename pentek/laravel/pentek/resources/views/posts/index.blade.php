@extends('layouts.base')
@section('title', 'Bejegyzések')

@section('main-content')
<div class="container">
    <div class="row justify-content-between">
        <div class="col-12 col-md-8">
            <h1>Üdvözlünk a blogon!</h1>
            <h3 class="mb-1">Minden bejegyzés</h3>
        </div>
        <div class="col-12 col-md-4">
            <div class="py-md-3 text-md-right">
                <p class="my-1">Elérhető műveletek:</p>
                <a href="{{ route('posts.create') }}" role="button" class="btn btn-sm btn-success mb-1"><i class="fas fa-plus-circle"></i> Új bejegyzés</a>
                <a href="{{ route('categories.create') }}" role="button" class="btn btn-sm btn-success mb-1"><i class="fas fa-plus-circle"></i> Új kategória</a>
            </div>
        </div>
    </div>

    <div class="row mt-3">
        <div class="col-12 col-lg-9">
            <div class="row">
                @forelse ($posts as $post)
                    <div class="col-12 col-md-6 col-lg-4 mb-3 d-flex align-items-strech">
                        <div class="card w-100">
                            <div class="card-body">
                                <div class="mb-2">
                                    <h5 class="card-title mb-0">{{ $post->title }}</h5>
                                    <small class="text-secondary">
                                        <span class="mr-2">
                                            <i class="fas fa-user"></i>
                                            <span>{{
                                                $post->author_id
                                                    ? $post->author->name
                                                    : 'Nincs szerző'
                                                }}
                                            </span>
                                        </span>
                                        <span class="mr-2">
                                            <i class="far fa-calendar-alt"></i>
                                            <span>{{ $post->created_at->format('Y. m. d.') }}</span>
                                        </span>
                                    </small>
                                </div>
                                <p class="card-text">{{ Str::of($post->text)->limit(32) }}</p>
                            </div>
                            <div class="card-footer">
                                <a href="{{ route('posts.show', $post) }}" class="btn btn-primary">Megtekint <i class="fas fa-angle-right"></i></a>
                            </div>
                        </div>
                    </div>
                @empty
                    <p>Nincsenek bejegyzések</p>
                @endforelse
            </div>

            <nav aria-label="Page navigation example">
                <ul class="pagination justify-content-center">
                    <li class="page-item disabled">
                        <a class="page-link" href="#" tabindex="-1">Előző</a>
                    </li>
                    <li class="page-item"><a class="page-link" href="#">1</a></li>
                    <li class="page-item"><a class="page-link" href="#">2</a></li>
                    <li class="page-item"><a class="page-link" href="#">3</a></li>
                    <li class="page-item">
                        <a class="page-link" href="#">Következő</a>
                    </li>
                </ul>
            </nav>

        </div>
        <div class="col-12 col-lg-3">
            <div class="row">
                <div class="col-12 mb-3">
                    <div class="card bg-light">
                        <div class="card-body">
                            <h5 class="card-title mb-2">Keresés</h5>
                            <p class="small">Bejegyzés keresése cím alapján.</p>
                            <form>
                                <div class="form-group">
                                    <input type="text" class="form-control" placeholder="Keresett cím">
                                </div>
                                <button type="submit" class="btn btn-primary"><i class="fas fa-search"></i> Keresés</button>
                            </form>
                        </div>
                    </div>
                </div>

                <div class="col-12 mb-3">
                    <div class="card bg-light">
                        <div class="card-body">
                            <h5 class="card-title mb-2">Kategóriák</h5>
                            <p class="small">Bejegyzések megtekintése egy adott kategóriához.</p>
                            <a href="#" class="badge badge-primary">Primary</a>
                            <a href="#" class="badge badge-secondary">Secondary</a>
                            <a href="#" class="badge badge-success">Success</a>
                            <a href="#" class="badge badge-danger">Danger</a>
                            <a href="#" class="badge badge-warning">Warning</a>
                            <a href="#" class="badge badge-info">Info</a>
                            <a href="#" class="badge badge-light">Light</a>
                            <a href="#" class="badge badge-dark">Dark</a>
                        </div>
                    </div>
                </div>

                <div class="col-12 mb-3">
                    <div class="card bg-light">
                        <div class="card-body">
                            <h5 class="card-title mb-2">Statisztika</h5>
                            <div class="small">
                                <p class="mb-1">Adatbázis statisztika:</p>
                                <ul class="fa-ul">
                                    <li><span class="fa-li"><i class="fas fa-user"></i></span>Felhasználók: 1</li>
                                    <li><span class="fa-li"><i class="fas fa-file-alt"></i></span>Bejegyzések: 1
                                    </li>
                                    <li><span class="fa-li"><i class="fas fa-comments"></i></span>Hozzászólások: 3
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>
@endsection
