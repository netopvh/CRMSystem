@extends('layouts.app')

@section('breadcrumbs', Breadcrumbs::render('index', $page_info))

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <div class="panel panel-default">






                <div class="panel-body">
                    @if (session('status'))
                        <div class="alert alert-success">
                            {{ session('status') }}
                        </div>
                    @endif

                    {{-- <img src="/img/work.jpg"> --}}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
