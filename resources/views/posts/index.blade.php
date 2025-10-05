@extends('layouts.app')

@section('content')
<div>
    <div class="container mt-5">
        <div class="row mb-4">
            <div class="col text-center">
                <h2>Blogs</h2>
            </div>
        </div>

        <div class="row mb-4">
            <div class="col-md-12">
                <form action="" method="GET" id="searchForm">
                    <div class="input-group gap-2">
                        <input type="text" class="form-control" name="search" id="search" placeholder="Search by Post Title ..." value="{{ request('search') }}">
                        <input type="text" class="form-control" name="tags" id="tags" placeholder="Search by Tags (comma-separated)" value="{{ request('tags') }}">

                        <div class="input-group-append">
                            <button type="submit" class="btn btn-primary">Search</button>
                            <a href="{{ route('post.all') }}" class="btn btn-danger" onclick="resetSearch()">Reset</a>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <div class="row">
            @if($posts->isEmpty())
                <div class="col-12">
                    <p class="text-center">No posts available.</p>
                </div>
            @else
                @foreach($posts as $blog)
                    <div class="col-md-4 mb-4">
                        <div class="card h-100">
                            <img src="{{ asset($blog->image) }}" class="card-img-top" alt="{{ $blog->title }}" style="object-fit: cover; height: 200px;">

                            <div class="card-body">
                                <div class="tags">
                                    @foreach($blog->tags as $tag)
                                        <span class="badge bg-success text-white">{{ $tag->name }}</span>
                                    @endforeach
                                </div>
                            </div>

                            <div class="card-body">
                                <h5 class="card-title">{{ $blog->title }}</h5>

                                <p class="card-text">{{ Str::limit($blog->content, 100) }}</p>

                                <a href="{{ route('post.show', $blog->id) }}" class="btn btn-primary">Read More</a>
                            </div>

                            <div class="card-footer d-flex align-items-center">
                                @if($blog->user->profile_image)
                                <img src="{{ asset($blog->user->profile_image) }}" alt="{{ $blog->user->name }}" class="rounded-circle me-2" style="width: 40px; height: 40px; object-fit: cover;">
                                @endif
                                <small class="text-muted">
                                    By
                                    @if (Auth::check() && auth()->user()->name == $blog->user->name)
                                    <strong>You</strong>
                                    @else
                                    <strong>{{ $blog->user->name }}</strong>
                                    @endif
                                    - Published on {{ $blog->created_at->format('F j, Y') }}
                                </small>
                            </div>
                        </div>
                    </div>
                @endforeach
            @endif
        </div>

        <!-- Pagination Links -->
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


    </div>
</div>

<script>
    function resetSearch(){
        document.getElementById('search').value = '';
        document.getElementById('tags').value = '';
    }
</script>
@endsection
