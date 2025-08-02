@extends('layouts.app')

@section('title', 'Yeni Xəbər Əlavə Et')

@section('header')
    <h1>Yeni Xəbər Əlavə Et</h1>
@endsection

@section('content')
    <div class="container mx-auto">
        <form action="{{ route('news.store') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
            @csrf

            <div>
                <label for="title" class="block text-sm font-medium text-gray-700">Başlıq</label>
                <input type="text" name="title" id="title" class="form-control w-full" required>
            </div>

            <div>
                <label for="description" class="block text-sm font-medium text-gray-700">Təsvir</label>
                <textarea name="description" id="editor" class="form-control w-full"></textarea>
            </div>

            <div>
                <label for="image" class="block text-sm font-medium text-gray-700">Şəkil (İstəyə bağlı)</label>
                <input type="file" name="image" id="image" class="form-control w-full">
            </div>

            <button type="submit" class="btn btn-success">Əlavə Et</button>
        </form>
    </div>
@endsection

@section('scripts')
    <script src="https://cdn.ckeditor.com/4.20.1/standard/ckeditor.js"></script>
    <script>
        CKEDITOR.replace('editor');
    </script>
@endsection
