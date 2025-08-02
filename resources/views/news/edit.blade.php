@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Xəbəri Redaktə Et</h1>

    <form action="{{ route('news.update', $news->id) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <div class="form-group">
            <label>Başlıq</label>
            <input type="text" name="title" class="form-control" value="{{ $news->title }}">
        </div>

        <div class="form-group">
            <label>Təsvir</label>
            <textarea name="description" class="form-control" rows="5">{{ $news->description }}</textarea>
        </div>

        <div class="form-group">
            <label>Şəkil</label>
            <input type="file" name="image" class="form-control">
            @if($news->image)
                <img src="{{ asset('storage/' . $news->image) }}" alt="" style="max-width: 150px;">
            @endif
        </div>

        <button type="submit" class="btn btn-success">Yadda saxla</button>
    </form>
</div>
@endsection
