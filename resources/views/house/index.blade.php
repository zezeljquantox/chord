@extends('layouts.app')

@section('content')

    <div class="container">
        @foreach ($houses as $house)
            <div class="card mb-3">
                <div class="card-body">
                    {{ $house->user->name }}
                </div>
            </div>
        @endforeach

        {{ $houses->links() }}
    </div>
@endsection