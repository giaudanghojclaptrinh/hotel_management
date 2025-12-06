@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card-common">
                <div class="px-6 py-4 border-b font-semibold">{{ __('Dashboard') }}</div>

                <div class="p-6">
                    @if (session('status'))
                        <div class="bg-green-50 border-l-4 border-green-500 p-4 text-green-800">{{ session('status') }}</div>
                    @endif

                    {{ __('You are logged in!') }}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
