@extends('layouts.app')

@section('content')
<div>
    <!-- Bootstrap Carousel -->
    <div id="homePageCarousel" class="carousel slide" data-bs-ride="carousel" style="height: 600px;margin: 50px">
        <ol class="carousel-indicators">
            <li data-bs-target="#homePageCarousel" data-bs-slide-to="0" class="active"></li>
            <li data-bs-target="#homePageCarousel" data-bs-slide-to="1"></li>
            <li data-bs-target="#homePageCarousel" data-bs-slide-to="2"></li>
        </ol>

        <div class="carousel-inner">
            <div class="carousel-item active">
                <img src="{{ asset('assets/img/slider3.jpg') }}" class="d-block w-100" alt="First Slide" style="height: 600px;">
                <div class="carousel-caption">
                    {{-- <h5 style="color: black;">First Slide Title</h5>
                    <p style="color: black;">Description for the first slide.</p> --}}
                </div>
            </div>
            <div class="carousel-item">
                <img src="{{ asset('assets/img/slider2.jpg') }}" class="d-block w-100" alt="Second Slide" style="height: 600px;">
                <div class="carousel-caption">
                    {{-- <h5 style="color: black;">Second Slide Title</h5>
                    <p style="color: black;">Description for the second slide.</p> --}}
                </div>
            </div>
            <div class="carousel-item">
                <img src="{{ asset('assets/img/slider1.jpg') }}" class="d-block w-100" alt="Third Slide" style="height: 600px;">
                <div class="carousel-caption d-none d-md-block">
                    {{-- <h5 style="color: black;">Third Slide Title</h5>
                    <p style="color: black;">Description for the third slide.</p> --}}
                </div>
            </div>
        </div>

        <!-- Carousel Controls -->
        <a class="carousel-control-prev" href="#homePageCarousel" role="button" data-bs-slide="prev">
            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
            <span class="visually-hidden">Previous</span>
        </a>
        <a class="carousel-control-next" href="#homePageCarousel" role="button" data-bs-slide="next">
            <span class="carousel-control-next-icon" aria-hidden="true"></span>
            <span class="visually-hidden">Next</span>
        </a>
    </div>

    <div class="container mt-5">

        <div class="row mb-4">
            <div class="col text-center">
                <h2>Recent Blogs</h2>
            </div>
        </div>

        <div class="row">
            @if($recentBlogs->isEmpty())
                <div class="col-12">
                    <p class="text-center">No recent posts available.</p>
                </div>
            @else
                @foreach($recentBlogs as $blog)
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

            @endif
        </div>

        <div class="row m-5">
            <div class="col-12">
                <div class="d-flex align-items-center justify-content-center">
                    <hr class="flex-grow-1 me-3">
                    <a href="{{ route('post.all') }}" class="btn btn-primary">View All</a>
                    <hr class="flex-grow-1 ms-3">
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
