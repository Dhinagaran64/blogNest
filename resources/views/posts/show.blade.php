@extends('layouts.app')

@section('content')
<div class="container m-5">
    @include('common.alert')
    <div class="row">
        <div class="col-md-12 text-center">
            <img src="{{ asset($post->image) }}" class="img-fluid mb-4 mx-auto d-block" alt="{{ $post->title }}" style="height: 400px; object-fit: cover;">

            <h1>{{ $post->title }}</h1>

            <p class="text-muted">By
                @if (Auth::check() && auth()->user()->id == $post->user->id )
                <strong>You</strong>
                @else
                <strong>{{ $post->user->name }}</strong>
                @endif
            on {{ $post->created_at->format('F j, Y') }}</p>

            @if($post->tags->isNotEmpty())
                <div class="tags m-4">
                    <div>
                        @foreach($post->tags as $tag)
                            <span class="badge bg-success">{{ $tag->name }}</span>
                        @endforeach
                    </div>
                </div>
            @else
                <p>No tags available for this post.</p>
            @endif

            <p class="text-start">{{ $post->content }}</p>

            <hr>
        </div>
    </div>


    <!-- Related Posts Section -->
    <div class="row">
        <div class="col-md-12">
            <h4>Related Posts</h4>
            <div class="row">
                @if($relatedPosts->isNotEmpty())
                    @foreach($relatedPosts as $blog)
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
                                    @if (Auth::check() && auth()->user()->name == $blog->user->name )
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
                @else
                    <div class="col-md-12">
                        <p class="text-muted">No related posts available.</p>
                    </div>
                @endif
            </div>
            <hr>
        </div>
    </div>


    <!-- Comments Section -->
    <div class="row">
        <div class="col-md-12">
            <h4>Comments ({{ $post->comments->count() }})</h4>

            <!-- Existing Comments -->
            @if($post->comments->count() > 0)
                @foreach($post->comments as $comment)
                    <div class="media mb-4" id="comment-{{ $comment->id }}">
                        <div class="media-body d-flex align-items-center gap-3">
                            <img src="{{ asset($comment->user->profile_image) }}" class="rounded-circle" alt="{{ $comment->user->name }}" width="50" height="50">

                            @if (Auth::check() && auth()->user()->id == $comment->user_id )
                            <h6 class="mt-0 mb-0">You</h6>
                            @else
                            <h6 class="mt-0 mb-0">{{ $comment->user->name }}</h6>
                            @endif

                            <small class="text-muted">{{ $comment->created_at->diffForHumans() }}</small>
                            @if(Auth::check() && auth()->user()->id == $comment->user_id)
                                <a href="javascript:void(0);" class="btn btn-sm btn-outline-warning edit-comment-btn" data-comment-id="{{ $comment->id }}">
                                    Edit
                                </a>
                                <form action="{{ route('comments.delete', $comment->id) }}" method="POST" class="d-inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-danger">
                                        <i class="fas fa-trash-alt"></i> Delete
                                    </button>
                                </form>
                            @endif
                        </div>
                        <div class="comment-content mx-5">
                            <p id="comment-text-{{ $comment->id }}">{{ $comment->comment }}</p>
                            <form id="edit-comment-form-{{ $comment->id }}" class="d-none" action="{{ route('comments.update', $comment->id) }}" method="POST">
                                @csrf
                                @method('PUT')
                                <textarea class="form-control" name="comment">{{ $comment->comment }}</textarea>
                                <button type="submit" class="btn btn-success btn-sm mt-2">Update</button>
                                <a href="javascript:void(0);" class="btn btn-secondary btn-sm mt-2 cancel-edit-btn" data-comment-id="{{ $comment->id }}">Cancel</a>
                            </form>
                        </div>
                    </div>
                @endforeach
            @else
                <p>No comments yet. Be the first to comment!</p>
            @endif

            <hr>

            <div class="container">
                <h5>Leave a Comment</h5>
                <form action="{{ route('comments.store', $post->id) }}" method="POST">
                    @csrf
                    <div class="form-group">
                        <textarea class="form-control" name="comment" rows="4" placeholder="Write your comment here..." required></textarea>
                    </div>
                    <button type="submit" class="btn btn-primary my-4">Post Comment</button>
                </form>
            </div>
        </div>
    </div>
</div>


<script>
    document.addEventListener('DOMContentLoaded', function () {
        document.querySelectorAll('.edit-comment-btn').forEach(function (editBtn) {
            editBtn.addEventListener('click', function () {
                var commentId = this.getAttribute('data-comment-id');
                document.getElementById('comment-text-' + commentId).classList.add('d-none');
                document.getElementById('edit-comment-form-' + commentId).classList.remove('d-none');
            });
        });

        document.querySelectorAll('.cancel-edit-btn').forEach(function (cancelBtn) {
            cancelBtn.addEventListener('click', function () {
                var commentId = this.getAttribute('data-comment-id');
                document.getElementById('comment-text-' + commentId).classList.remove('d-none');
                document.getElementById('edit-comment-form-' + commentId).classList.add('d-none');
            });
        });
    });
</script>
@endsection
