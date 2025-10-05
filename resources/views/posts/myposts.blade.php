@extends('layouts.app')

@section('content')
<div class="container m-5">
    <div class="row">
        <div class="col-12 d-flex justify-content-between align-items-center mb-4">
            <h1 class="h3">Posts</h1>
            <a href="{{ route('post.create') }}" class="btn btn-primary">Create New Post</a>
        </div>
    </div>

    <div class="row">
        @if($posts->count() > 0)
            @foreach($posts as $post)
            <div class="col-md-4 mb-4">
                <div class="card h-100">
                    <img src="{{ $post->image ?? 'https://via.placeholder.com/600x400' }}" class="card-img-top" alt="{{ $post->title }}" style="object-fit: cover; height: 200px;">
                    <div class="card-body">
                        <div class="tags">
                            @foreach($post->tags as $tag)
                            <span class="badge bg-success text-white">{{ $tag->name }}</span>
                            @endforeach
                        </div>
                    </div>

                    <div class="card-body">
                        <h5 class="card-title">{{ $post->title }}</h5>
                        <p class="card-text">{{ Str::limit($post->content, 100) }}</p>
                        <a href="{{ route('post.show', $post->id) }}" class="btn btn-primary">Read More</a>
                    </div>

                    <div class="card-footer d-flex justify-content-between">
                        <a href="{{ route('post.edit', $post->id) }}" class="btn btn-primary">Edit</a>
                        <form action="{{ route('post.delete', $post->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this post?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger">Delete</button>
                        </form>
                    </div>
                    <div class="card-footer">
                        <small class="text-muted">
                            By
                            @if (Auth::check() && auth()->user()->name == $post->user->name )
                            <strong>You</strong>
                            @else
                            <strong>{{ $post->user->name }}</strong>
                            @endif
                             - Published on {{ $post->created_at->format('F j, Y') }}
                        </small>
                    </div>
                </div>
            </div>
            @endforeach
        @else
            <div class="col-12 text-center">
                <p>No posts created yet. </p>
            </div>
        @endif
    </div>

    <!-- Pagination Links -->
    @if ($posts->count() > 0)
    <div class="d-flex justify-content-between ">
        <div class="mt-2">
            Showing {{ $posts->firstItem() }} to {{ $posts->lastItem() }} of {{ $posts->total() }} Entries
        </div>
        <div class="" aria-label="Page navigation example">
            <ul class="pagination justify-content-center">
                @if ($posts->onFirstPage())
                    <li class="page-item disabled"><span class="page-link"><</span></li>
                @else
                    <li class="page-item"><a class="page-link bg-light  text-dark " href="{{ $posts->previousPageUrl() }}"><</a></li>
                @endif

                @for ($i = 1; $i <= $posts->lastPage(); $i++)
                    <li class="page-item {{ ($posts->currentPage() == $i) ? 'active' : '' }}">
                        <a class="page-link" href="{{ $posts->url($i) }}">{{ $i }}</a>
                    </li>
                @endfor

                @if ($posts->hasMorePages())
                    <li class="page-item"><a class="page-link bg-light  text-dark " href="{{ $posts->nextPageUrl() }}">></a></li>
                @else
                    <li class="page-item disabled"><span class="page-link">></span></li>
                @endif
            </ul>
        </div>
    </div>
    @endif
</div>

@endsection
