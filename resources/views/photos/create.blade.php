@extends('layouts.app')

@section('inhead')
@include('includes.scripts.pickmeup-inhead')
<script src="/js/plugins/dropzone/dist/dropzone.js"></script>
<link rel="stylesheet" href="/js/plugins/dropzone/dist/dropzone.css">
<!-- <script src="/js/plugins/clipboard/dist/clipboard.min.js"></script> -->
@endsection

@section('title', 'Новый пользователь')

@section('breadcrumbs', Breadcrumbs::render('section-create', $parent_page_info, $album, $page_info))

@section('title-content')
<div class="top-bar head-content">
	<div class="top-bar-left">
		<h2 class="header-content">добавление новой фотографии</h2>

	</div>
	<div class="top-bar-right">
	</div>
</div>
@endsection

@section('content')
{{ Form::open(['url' => '/albums/'.$alias.'/photos', 'data-abide', 'novalidate', 'files'=>'true', 'class'=> 'dropzone', 'id' => 'my-dropzone']) }}
@include('photos.form', ['submitButtonText' => 'Добавить фотографию', 'param' => ''])
{{ Form::close() }}
@endsection

@section('modals')
{{-- Модалка удаления с ajax --}}
@include('includes.modals.modal-delete-ajax')
@endsection

@section('scripts')
@include('includes.scripts.cities-list')
@include('includes.scripts.inputs-mask')
@include('includes.scripts.pickmeup-script')
@include('includes.scripts.upload-file')
<script>

	Dropzone.options.myDropzone = {
		paramName: 'photo',
        maxFilesize: 5, // MB
        maxFiles: 20,
        acceptedFiles: ".jpeg,.jpg,.png,.gif",
      };
    </script>

@endsection



