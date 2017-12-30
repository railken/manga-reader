@extends('Emails::layout')

@section('content')    		
	<div class="title">
		<h1>Welcome to MangaReader!</h1>
	</div>
	<div class="content">
		<p><b>Hey {{ $user->username }},</b></p>

		<p>Allons-y! Thanks for registering an account with MangaRader.</p>
		<p>Just one step more, we'll need to verify your email</p>

		<a class='btn btn-primary' href="{{ env('EMAILS_URL') }}/confirm-email/{{ $user->pendingEmail->token }}">Verify Email</a>
		
	</div>
@endsection