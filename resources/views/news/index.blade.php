@extends('layouts.app')

@section('content')
    <div class="container">
        <h1>Xəbərlər</h1>
        <a href="{{ route('news.create') }}" class="btn btn-primary mb-3">Yeni Xəbər</a>

        @foreach ($news as $item)
    <div class="card mb-3">
        <div class="card-body">
            <h3>{{ $item->title }}</h3>
            @if ($item->image)
                <img src="{{ asset('storage/' . $item->image) }}" alt="" style="max-width: 200px;">
            @endif
            <div>{!! $item->description !!}</div>

            <a href="{{ route('news.edit', $item->id) }}" class="btn btn-warning btn-sm">Redaktə et</a>

            <form action="{{ route('news.destroy', $item->id) }}" method="POST" style="display:inline-block;" onsubmit="return confirm('Silinsin?')">
                @csrf
                @method('DELETE')
                <button class="btn btn-danger btn-sm">Sil</button>
            </form>
        </div>
    </div>
@endforeach

        

    </div>
@endsection
