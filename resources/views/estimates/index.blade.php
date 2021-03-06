@extends('layouts.app')

@section('inhead')
{{-- Скрипты таблиц в шапке --}}
@include('includes.scripts.tablesorter-inhead')
@endsection

@section('title', $page_info->name)

@section('breadcrumbs', Breadcrumbs::render('index', $page_info))

@section('content-count')
{{-- Количество элементов --}}
{{ $estimates->isNotEmpty() ? num_format($estimates->total(), 0) : 0 }}
@endsection

@section('title-content')
{{-- Таблица --}}
@include('includes.title-content', ['page_info' => $page_info, 'class' => App\Estimate::class, 'type' => 'table'])
@endsection

@section('content')

{{-- Таблица --}}
<div class="grid-x">
    <div class="small-12 cell">

        <table class="content-table tablesorter estimates" id="content" data-sticky-container data-entity-alias="estimates">

            <thead class="thead-width sticky sticky-topbar" id="thead-sticky" data-sticky data-margin-top="6.2" data-sticky-on="medium" data-top-anchor="head-content:bottom">
                <tr id="thead-content">
                    <th class="td-drop"></th>
                    <th class="td-checkbox checkbox-th"><input type="checkbox" class="table-check-all" name="" id="check-all"><label class="label-check" for="check-all"></label></th>
                    <th class="td-name">Клиент</th>
                    <th class="td-phone">Телефон</th>
                    <th class="td-number">Номер заказа</th>
                    <th class="td-date">Дата заказа</th>
                    <th class="td-amount">Сумма</th>
                    <th class="td-payment">Оплачено</th>
                    <th class="td-stage">Этап</th>
                    <th class="td-loyalty">Лояльность</th>
                    <th class="td-delete"></th>
                </tr>
            </thead>

            <tbody data-tbodyId="1" class="tbody-width">

              @if($estimates->isNotEmpty())
              @foreach($estimates as $estimate)
              <tr class="item @if($estimate->moderation == 1)no-moderation @endif" id="estimates-{{ $estimate->id }}" data-name="{{ $estimate->name }}">
                  <td class="td-drop"><div class="sprite icon-drop"></div></td>
                  <td class="td-checkbox checkbox">

                    <input type="checkbox" class="table-check" name="estimate_id" id="check-{{ $estimate->id }}"
                    @if(!empty($filter['booklist']['booklists']['default']))
                    @if (in_array($estimate->id, $filter['booklist']['booklists']['default'])) checked
                    @endif
                    @endif
                    >
                    <label class="label-check" for="check-{{ $estimate->id }}"></label>

                </td>
                <td class="td-name">

                  <a href="/admin/estimates?client_id%5B%5D={{ $estimate->client->id }}" class="filter_link" title="Фильтровать">
                    {{ $estimate->client->client->name }}
                </a>
                <br>
                <span class="tiny-text">
                    {{ $estimate->client->client->location->city->name }}, {{ $estimate->client->client->location->address }}
                </span>
                <td class="td-phone">
                    {{ isset($estimate->client->client->main_phone->phone) ? decorPhone($estimate->client->client->main_phone->phone) : 'Номер не указан' }}
                    @if($estimate->client->client->email)<br><span class="tiny-text">{{ $estimate->client->client->email or '' }}</span>@endif
                </td>
                <td class="td-number">{{ $estimate->number or '' }}
                    <br><span class="tiny-text">{{ $estimate->lead->choice->name or '' }}</span>
                </td>


                <td class="td-date">
                    <span>{{ $estimate->created_at->format('d.m.Y') }}</span><br>
                    <span class="tiny-text">{{ $estimate->created_at->format('H:i') }}</span>
                </td>
                <td class="td-amount">{{ num_format($estimate->amount, 0) }}</td>
                <td class="td-payment">{{ num_format($estimate->payment, 0) }}
                  <br><span class="tiny-text">{{ num_format($estimate->amount - $estimate->payment, 0) }}</span>
              </td>
              <td class="td-stage">{{ $estimate->lead->stage->name }}</td>
              <td class="td-loyalty">{{ $estimate->client->loyalty->name }}</td>
              <td class="td-delete">
                  @if ($estimate->system_item !== 1)
                  @can('delete', $estimate)
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
    <span class="pagination-title">Кол-во записей: {{ $estimates->count() }}</span>
    {{ $estimates->appends(isset($filter['inputs']) ? $filter['inputs'] : null)->links() }}
</div>
</div>


{{-- Скрипт сортировки и перетаскивания для таблицы --}}
@include('includes.scripts.tablesorter-script')
@include('includes.scripts.sortable-table-script')
@include('includes.scripts.pickmeup-script')

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

