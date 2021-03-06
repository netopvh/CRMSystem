@extends('layouts.app')

@section('inhead')
    @include('includes.scripts.class.city_search')
@endsection

@section('title', 'Редактировать дилера')

@section('breadcrumbs', Breadcrumbs::render('edit', $page_info, $dealer->client->client->name))

@section('title-content')
<div class="top-bar head-content">
    <div class="top-bar-left">
        <h2 class="header-content">РЕДАКТИРОВАТЬ ДИЛЕРА</h2>
    </div>
    <div class="top-bar-right">
    </div>
</div>
@endsection

@section('content')
    {{ Form::model($dealer->client->client, ['url' => '/admin/dealers/'.$dealer->id, 'data-abide', 'novalidate', 'class' => 'form-check-city']) }}
    {{ method_field('PATCH') }}
        @include('companies.form', ['submitButtonText' => 'Редактировать дилера', 'param'=>'', 'company'=>$dealer->client->client])
    {{ Form::close() }}
@endsection

@section('modals')
    <section id="modal"></section>
    {{-- Модалка удаления с ajax --}}
    @include('includes.modals.modal-delete-ajax')
@endsection

@section('scripts')
    @include('includes.scripts.inputs-mask')
    @include('includes.scripts.modal-delete-script')
    @include('includes.scripts.extra-phone')
    @include('includes.bank_accounts.bank-account-script', ['id' => $dealer->client->client->id])
@endsection


