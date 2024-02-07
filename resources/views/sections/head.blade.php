<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta http-equiv="X-UA-Compatible" content="ie=edge">
<meta name="csrf-token" content="{{ csrf_token() }}">
<meta name="theme-color" content="#0f172a">
<title> Reachomation</title>
<!-- Bootstrap CSS -->
<link  rel="stylesheet" href="/css/bootstrap.min.css" rel="stylesheet" >
<!-- Bootstrap icons -->
<link rel="stylesheet" href="/css/bootstrap-icons.css">
<!-- Manual CSS -->
<link rel="stylesheet" href="{{asset('css/custom.css')}}">
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.4/jquery.min.js" integrity="sha512-pumBsjNRGGqkPzKHndZMaAG+bir374sORyzM3uulLV14lN5LyykqNk8eEeUlUkB3U0M4FApyaHraT65ihJhDpQ==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/emojionearea/3.4.2/emojionearea.css" integrity="sha512-0Nyh7Nf4sn+T48aTb6VFkhJe0FzzcOlqqZMahy/rhZ8Ii5Q9ZXG/1CbunUuEbfgxqsQfWXjnErKZosDSHVKQhQ==" crossorigin="anonymous" referrerpolicy="no-referrer" />
<!-- favicon -->
<link rel="icon" href="{{ asset('images/apple-touch-icon.png') }}" />
<link rel="apple-touch-icon" href="{{ asset('images/apple-touch-icon.png') }}" />
<!-- JQuery -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.4/jquery.min.js" integrity="sha512-pumBsjNRGGqkPzKHndZMaAG+bir374sORyzM3uulLV14lN5LyykqNk8eEeUlUkB3U0M4FApyaHraT65ihJhDpQ==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/emojionearea/3.4.2/emojionearea.min.js" integrity="sha512-hkvXFLlESjeYENO4CNi69z3A1puvONQV5Uh+G4TUDayZxSLyic5Kba9hhuiNLbHqdnKNMk2PxXKm0v7KDnWkYA==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
<!-- Summernote css/js -->
<!-- include summernote css/js -->
<link href="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote.min.css" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.nicescroll/3.7.6/jquery.nicescroll.min.js" integrity="sha512-zMfrMAZYAlNClPKjN+JMuslK/B6sPM09BGvrWlW+cymmPmsUT1xJF3P4kxI3lOh9zypakSgWaTpY6vDJY/3Dig==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
<link href="/css/material-css.css?family=Material+Icons|Material+Icons+Outlined" rel="stylesheet">

<script src="https://js.stripe.com/v3/"></script>
<script src="/js/views/sections/head.js"></script>
{{-- {!! Html::style('css/toastr.min.css') !!} --}}
<script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js" integrity="sha512-VEd+nq25CkR676O+pLBnDW09R7VQX9Mdiij052gVCp5yVH3jGtH70Ho/UUv4mJDsEdTvqRCFZg0NKGiojGnUCw==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
{!! Html::style('js/plugins/datatable/dataTables.bootstrap4.min.css') !!}
