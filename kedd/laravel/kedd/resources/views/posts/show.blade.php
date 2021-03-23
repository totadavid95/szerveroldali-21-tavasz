@extends('layouts.app')
@section('title', $post->title)

@section('content')
<div class="container">
    <div class="row justify-content-between">
        <div class="col-12 col-md-8">
            <h1>{{ $post->title }}</h1>

            <div class="d-flex my-1 text-secondary">
                <span class="mr-2">
                    <i class="fas fa-user"></i>
                    <span>{{ $post->author ? $post->author->name : 'Nincs szerző' }}</span>
                </span>
                <span class="mr-2">
                    <i class="far fa-calendar-alt"></i>
                    <span>{{ $post->created_at->format('Y. m. d.') }}</span>
                </span>
            </div>

            <div class="mb-2">
                @foreach($post->categories as $category)
                    <a href="#" class="badge badge-{{ $category->style }}">{{ $category->name }}</a>
                @endforeach
            </div>

            <div class="mb-3">
                <a href="{{ route('posts.index') }}"><i class="fas fa-long-arrow-alt-left"></i> Minden bejegyzés</a>
            </div>
        </div>
        <div class="col-12 col-md-4">
            <div class="py-md-3 text-md-right">
                <p class="my-1">Bejegyzés kezelése:</p>
                @can('update', $post)
                    <a href="{{ route('posts.edit', $post) }}" role="button" class="btn btn-sm btn-primary"><i class="far fa-edit"></i> Módosítás</a>
                @endcan
                <button type="button" class="btn btn-sm btn-danger"><i class="far fa-trash-alt"></i> Törlés</button>
            </div>
        </div>
    </div>

    <div class="mt-3">
        <div class="mb-3">{!! nl2br(e($post->text)) !!}</div>

        @if ($post->attachment_hash_name !== null)
            <div class="attachment mb-3">
                <h5>Csatolmány</h5>
                <a href="{{ route('posts.attachment', ['id' => $post->id]) }}">{{ $post->attachment_original_name }}</a>
            </div>
        @endif

        <h3>Hozzászólások</h3>
        <p>Coming soon...</p>
    </div>
</div>
@endsection
