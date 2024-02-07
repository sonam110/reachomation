@if ($errors->any())
<div class="alert alert-danger alert-dismissible fade show rounded-1 text-start" role="alert">
	<ul class="list-group list-group-flush border-0 bg-transparent">
		@foreach ($errors->all() as $error)
		<li class="list-group-item border-0 bg-transparent p-0">
			{{ $error }}
		</li>
		@endforeach
	</ul>
	<button type="button" class="btn-close shadow-none" data-bs-dismiss="alert" aria-label="Close"></button>
</div>
{{-- <div class="alert alert-danger login-danger"> <a href="#" class="close" data-dismiss="alert"
		aria-label="close">&times;</a>
	<ul>
		@foreach ($errors->all() as $error)
		<li>{{ $error }}</li>
		@endforeach
	</ul>
</div> --}}
@endif


@if ($message = Session::get('success'))
<div class="alert alert-success alert-dismissible fade show rounded-1 text-start" role="alert">
	<strong>Success!</strong> {{ $message }}
	<button type="button" class="btn-close shadow-none" data-bs-dismiss="alert" aria-label="Close"></button>
</div>
{{-- <div class="alert alert-success alert-dismissible">
	<a href="#" class="close" data-dismiss="alert" aria-label="close">×</a>
	<strong>Success!</strong> {{ $message }}
</div> --}}
@endif

@if ($message = Session::get('subscribe'))
<div class="alert alert-success alert-dismissible fade show rounded-1 text-start" role="alert">
	<strong>Success!</strong> {{ $message }}
	<button type="button" class="btn-close shadow-none" data-bs-dismiss="alert" aria-label="Close"></button>
</div>
{{-- <div class="alert alert-success alert-dismissible">
	<a href="#" class="close" data-dismiss="alert" aria-label="close">×</a>
	<strong>Success!</strong> {{ $message }}
</div> --}}
@endif


@if ($message = Session::get('error'))
<div class="alert alert-danger alert-dismissible fade show rounded-1 text-start" role="alert">
	{{ $message }}
	<button type="button" class="btn-close shadow-none" data-bs-dismiss="alert" aria-label="Close"></button>
</div>
{{-- <div class="alert alert-danger alert-dismissible">
	<a href="#" class="close" data-dismiss="alert" aria-label="close">×</a>
	{{ $message }}
</div> --}}
@endif