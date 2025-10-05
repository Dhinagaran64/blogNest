@extends('layouts.app')

@section('content')
<div class="container m-5">
    <h1>{{ isset($post) ? 'Edit Post' : 'Create New Post' }}</h1>

    <form action="{{ isset($post) ? route('post.update', $post->id) : route('post.store') }}" method="POST" enctype="multipart/form-data">
        @csrf
        @if(isset($post))
        @method('PUT')
        @endif

        @include('common.alert')
        <div class="row mb-3">
            <div class="col-md-6">
                <label for="title" class="form-label">Title</label>
                <input type="text" class="form-control" id="title" name="title" value="{{ isset($post) ? $post->title : old('title') }}" required>
            </div>

            <div class="col-md-6">
                <label for="tags" class="form-label">Tags</label>
                @php
                    if(isset($post)) {
                        $tags = [];
                        foreach ($post->tags as $key => $tag) {
                            $tags[] = $tag->name;
                        }
                    }
                @endphp
                <input type="text" class="form-control" id="tags" name="tags" value="{{ isset($post) ? implode(',', $tags) : old('tags') }}" placeholder="Comma-separated tags">
            </div>
        </div>

        <div class="mb-3">
            <label for="content" class="form-label">Content</label>
            <textarea class="form-control" id="content" name="content" rows="5" required>{{ isset($post) ? $post->content : old('content') }}</textarea>
        </div>

        <div class="row mb-3">
            <div class="col-md-6">
                <label for="image" class="form-label">Post Image</label>
                <input type="file" class="form-control" id="image" name="image" onchange="previewImage();">
            </div>

            @if(isset($post) && $post->image)
            <div class="col-md-6">
                <img id="existingImage" src="{{ asset($post->image) }}" alt="Post Image" class="img-fluid mt-2" style="max-height: 200px;">
            </div>
            @endif

            <div class="col-md-6 mt-3" id="imagePreviewDiv" style="display:none;padding:auto;">
                <img id="imagePreview" class="img-fluid" style="max-height: 100px;border:2px solid;">
            </div>
        </div>


        <button type="submit" class="btn btn-success">{{ isset($post) ? 'Update' : 'Create' }}</button>
    </form>
</div>

<script>
    function previewImage() {
        const imageInput = document.getElementById('image');
        const previewDiv = document.getElementById('imagePreviewDiv');
        const previewImage = document.getElementById('imagePreview');

        if (imageInput.files && imageInput.files[0]) {
            const reader = new FileReader();

            reader.onload = function(e) {
                previewImage.src = e.target.result;
                previewDiv.style.display = 'block';
            };

            reader.readAsDataURL(imageInput.files[0]);
        } else {
            previewDiv.style.display = 'none';
        }
    }
</script>


@endsection
