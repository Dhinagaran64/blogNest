@extends('layouts.app')

@section('content')
<div class="container">
    <h2 class="mb-4">Profile</h2>

    @if(session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
    @endif

    @if($errors->any())
    <div class="alert alert-danger">
        <ul>
            @foreach($errors->all() as $error)
            <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
    @endif

    <form action="{{ route('profile.update') }}" method="POST" enctype="multipart/form-data">
        @csrf

        <div class="row mb-3">
            <div class="col-md-6">
                <label for="name" class="form-label">Name</label>
                <input type="text" class="form-control" id="name" name="name"
                       value="{{ old('name', $user->name) }}" required>
            </div>

            <div class="col-md-6">
                <label for="email" class="form-label">Email</label>
                <input type="email" class="form-control" id="email" name="email"
                       value="{{ old('email', $user->email) }}" required>
            </div>
        </div>

        <div class="row mb-3">
            <div class="col-md-6">
                <label for="profile_image" class="form-label">Profile Image</label>
                <input type="file" class="form-control" id="profile_image" name="profile_image" onchange="previewImage();">
            </div>

            @if($user->profile_image)
            <div class="col-md-6">
                <img src="{{ asset($user->profile_image) }}" id="existingImage" alt="Profile Image"
                     class="img-fluid mt-2" style="max-height: 150px;">
            </div>
            @endif

            <div class="col-md-6 mt-2" id="imagePreviewDiv" style="display:none;">
                <img id="imagePreview" class="img-fluid mt-2" style="max-height: 150px;">
            </div>
        </div>

        <div class="row mb-3">
            <div class="col-md-12">
                <button type="submit" class="btn btn-primary">Update Profile</button>
            </div>
        </div>
    </form>
</div>

<script>
    function previewImage() {
        const file = document.getElementById("profile_image").files[0];
        const reader = new FileReader();

        reader.onloadend = function () {
            const imagePreview = document.getElementById("imagePreview");
            const imagePreviewDiv = document.getElementById("imagePreviewDiv");
            const existingImage = document.getElementById("existingImage");

            if (file) {
                imagePreview.src = reader.result;
                imagePreviewDiv.style.display = "block";
                existingImage.style.display = "none";
            } else {
                imagePreviewDiv.style.display = "none";
                existingImage.style.display = "block";
            }
        };

        if (file) {
            reader.readAsDataURL(file);
        }
    }

    if (document.getElementById("existingImage")) {
        document.getElementById("existingImage").style.display = "block";
        document.getElementById("imagePreviewDiv").style.display = "none";
    }
</script>

@endsection
