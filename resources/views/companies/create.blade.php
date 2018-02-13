@extends('layouts.app')

@section('inhead')
  @include('includes.inhead-pickmeup')
@endsection

@section('title', 'Новая компания')

@section('title-content')
	<div class="top-bar head-content">
    <div class="top-bar-left">
       <h2 class="header-content">ДОБАВЛЕНИЕ НОВОЙ КОМПАНИИ</h2>
    </div>
    <div class="top-bar-right">
    </div>
  </div>
@endsection

@section('content')

  {{ Form::open(['route' => 'companies.store', 'data-abide', 'novalidate']) }}
    @include('companies.form', ['submitButtonText' => 'Добавить компанию', 'param' => ''])
  {{ Form::close() }}

@endsection

@section('scripts')
  @include('includes.scripts.city-list')
  @include('includes.inputs-mask')
  <script type="text/javascript">
    // При добавлении филиала ищем город в нашей базе
  $('#city-name-field-add').keyup(function() {
    checkCity();
  });

  // Проверка существования компании
  $(document).on('keyup', '.company_inn-field', function() {

    var company_inn = document.getElementById('company_inn-field').value;
    // alert(company_inn);

    if(company_inn.length > 9){

      $.ajax({
        headers: {
          'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        url: "/companies/check_company",
        type: "POST",
        data: {company_inn: company_inn},
        success: function (data) {

          if(data == 0){

          } else {
            document.getElementById('company_inn-field').value = '';
            alert(data);          
          };

        }
      });

    };

  });
</script>
@endsection



