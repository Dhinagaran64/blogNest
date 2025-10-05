@if(session()->has('message.level'))
    <div class="mt-2 alert alert-{{ session('message.level') }} alert-dismissible text-dark" role="alert">
        {!! session('message.content') !!}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
@endif

@if ($errors->any())
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <div class="alert-text text-black">
            <ul style="list-style: none; padding-left: 0px;">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
@endif

