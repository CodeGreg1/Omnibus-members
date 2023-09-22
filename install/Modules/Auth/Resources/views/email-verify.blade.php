@extends('auth::layouts.app-master')
    
@section('content')
    <div class="card">
        <div class="card-body">

            @if (session('resent'))
                <div class="alert alert-success" role="alert">
                    @lang('A fresh verification link has been sent to your email address.')
                </div>
            @endif

            @lang('Before proceeding, please check your email for a verification link.')
            @lang('If you did not receive the email'),
            <form action="{{ route('auth.verification.resend') }}" method="POST" class="d-inline">
                @csrf
                <button type="submit" class="d-inline btn btn-link p-0">
                    @lang('Click here to request another')
                </button>.
            </form>
        </div>
    </div>
@endsection