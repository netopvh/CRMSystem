@extends('layouts.app')

@section('inhead')
<meta name="description" content="{{ $page_info->page_description }}" />
{{-- Скрипты таблиц в шапке --}}
@include('includes.scripts.tablesorter-inhead')
@endsection

@section('title', $page_info->name)

@section('breadcrumbs', Breadcrumbs::render('index', $page_info))

@section('content-count')
{{-- Количество элементов --}}
  @if(!empty($clients))
    {{ num_format($clients->total(), 0) }}
  @endif
@endsection

@section('title-content')
{{-- Таблица --}}
@include('includes.title-content', ['page_info' => $page_info, 'class' => App\Client::class, 'type' => 'table'])
@endsection

@section('content')
{{-- Таблица --}}
<div class="grid-x">
  <div class="small-12 cell">
    <table class="content-table tablesorter" id="content" data-sticky-container data-entity-alias="clients">
      <thead class="thead-width sticky sticky-topbar" id="thead-sticky" data-sticky data-margin-top="6.2" data-sticky-on="medium" data-top-anchor="head-content:bottom">
        <tr id="thead-content">
          <th class="td-drop"></th>
          <th class="td-checkbox checkbox-th"><input type="checkbox" class="table-check-all" name="" id="check-all"><label class="label-check" for="check-all"></label></th>
          <th class="td-name" data-serversort="name">Клиент</th>
          <th class="td-address">Адрес</th>
          <th class="td-phone">Телефон</th>
          <th class="td-orders-count">Кол-во заказов</th>
          <th class="td-badget">Сумма</th>
          <th class="td-time-frame">Срок</th>
          <th class="td-loyalty">Лояльность</th>
          <th class="td-access">Доступ к ЛК</th>
          <th class="td-control"></th>
          <th class="td-delete"></th>
        </tr>
      </thead>
      <tbody data-tbodyId="1" class="tbody-width">
        @if(!empty($clients))
        @foreach($clients as $client)
        <tr class="item @if($client->moderation == 1)no-moderation @endif" id="clients-{{ $client->id }}" data-name="{{ $client->client->name }}">
          <td class="td-drop"><div class="sprite icon-drop"></div></td>
          <td class="td-checkbox checkbox">
            <input type="checkbox" class="table-check" name="dealer_id" id="check-{{ $client->id }}"

            {{-- Если в Booklist существует массив Default (отмеченные пользователем позиции на странице) --}}
            @if(!empty($filter['booklist']['booklists']['default']))
            {{-- Если в Booklist в массиве Default есть id-шник сущности, то отмечаем его как checked --}}
            @if (in_array($client->id, $filter['booklist']['booklists']['default'])) checked
            @endif
            @endif
            ><label class="label-check" for="check-{{ $client->id }}"></label>
          </td>
          <td class="td-name">

            @if($client->client_type == 'App\User')
              <a href="clients/{{ $client->id }}/edit">
                {{ $client->client->name }}
              </a>
            @else
              <a href="clients/{{ $client->id }}/edit">
                {{ $client->client->name }} ({{ $client->client->legal_form->name or '' }})
              </a>
            @endif

          </td>

          <td class="td-address">@if(!empty($client->client->location->address)){{ $client->client->location->address }}@endif </td>
          <td class="td-phone">{{ isset($client->client->main_phone->phone) ? decorPhone($client->client->main_phone->phone) : 'Номер не указан' }}</td>


          <td class="td-count-orders">@if(!empty($client->orders)){{ $client->orders->count() }} @endif </td>
          <td class="td-badget">{{-- $client->badget_count or ' ... ' --}} </td>
          <td class="td-time-frame">{{-- $client->time_frame or ' ... ' --}} </td>
          <td class="td-loyalty">{{-- $client->loyalty or ' ... ' --}} </td>
          <td class="td-access">{{-- $client->access or ' ... ' --}} </td>


          {{-- Элементы управления --}}
          @include('includes.control.table-td', ['item' => $client])

          <td class="td-delete">
            @if ($client->system_item != 1)
            @can('delete', $client)
            <a class="icon-delete sprite" data-open="item-delete"></a>
            @endcan
            @endif
          </td>
        </tr>
        @endforeach
        @endif
      </tbody>
    </table>
  </div>
</div>

{{-- Pagination --}}
<div class="grid-x" id="pagination">
  <div class="small-6 cell pagination-head">
    <span class="pagination-title">Кол-во записей: {{ $clients->count() }}</span>
    {{ $clients->appends(isset($filter['inputs']) ? $filter['inputs'] : null)->links() }}
  </div>
</div>
@endsection

@section('modals')
{{-- Модалка удаления с refresh --}}
@include('includes.modals.modal-delete')

{{-- Модалка удаления с refresh --}}
@include('includes.modals.modal-delete-ajax')

@endsection

@section('scripts')
{{-- Скрипт сортировки и перетаскивания для таблицы --}}
@include('includes.scripts.tablesorter-script')
@include('includes.scripts.sortable-table-script')

{{-- Скрипт отображения на сайте --}}
@include('includes.scripts.ajax-display')

{{-- Скрипт системной записи --}}
@include('includes.scripts.ajax-system')

{{-- Скрипт чекбоксов --}}
@include('includes.scripts.checkbox-control')

{{-- Скрипт модалки удаления --}}
@include('includes.scripts.modal-delete-script')
@include('includes.scripts.delete-ajax-script')

@endsection