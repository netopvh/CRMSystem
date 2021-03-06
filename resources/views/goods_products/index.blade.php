@extends('layouts.app')

@section('inhead')
<meta name="description" content="{{ $page_info->description }}" />
{{-- Скрипты таблиц в шапке --}}
@include('includes.scripts.tablesorter-inhead')
@endsection

@section('title', $page_info->name)

@section('breadcrumbs', Breadcrumbs::render('index', $page_info))

@section('content-count')
{{-- Количество элементов --}}
{{ $goods_products->isNotEmpty() ? num_format($goods_products->total(), 0) : 0 }}
@endsection

@section('title-content')
{{-- Таблица --}}
@include('includes.title-content', ['page_info' => $page_info, 'class' => App\GoodsProduct::class, 'type' => 'table'])
@endsection

@section('content')
{{-- Таблица --}}
<div class="grid-x">
    <div class="small-12 cell">

        <table class="content-table tablesorter" id="content" data-sticky-container data-entity-alias="goods_products">

            <thead class="thead-width sticky sticky-topbar" id="thead-sticky" data-sticky data-margin-top="6.2" data-sticky-on="medium" data-top-anchor="head-content:bottom">
                <tr id="thead-content">
                    <th class="td-drop"></th>
                    <th class="td-checkbox checkbox-th"><input type="checkbox" class="table-check-all" name="" id="check-all"><label class="label-check" for="check-all"></label></th>
                    <th class="td-name" data-serversort="name">Название группы товаров</th>
                    <th class="td-goods_category">Категория</th>
                    <th class="td-description">Описание</th>
                    <th class="td-control"></th>
                    <th class="td-delete"></th>
                </tr>
            </thead>

            <tbody data-tbodyId="1" class="tbody-width">

                @if(isset($goods_products) && $goods_products->isNotEmpty())
                @foreach($goods_products as $goods_product)

                <tr class="item @if($goods_product->moderation == 1)no-moderation @endif" id="goods_products-{{ $goods_product->id }}" data-name="{{ $goods_product->name }}">
                    <td class="td-drop">
                        <div class="sprite icon-drop"></div>
                    </td>
                    <td class="td-checkbox checkbox">
                        <input type="checkbox" class="table-check" name="goods_product_id" id="check-{{ $goods_product->id }}"

                        {{-- Если в Booklist существует массив Default (отмеченные пользователем позиции на странице) --}}
                        @if(!empty($filter['booklist']['booklists']['default']))
                        {{-- Если в Booklist в массиве Default есть id-шник сущности, то отмечаем его как checked --}}
                        @if (in_array($goods_product->id, $filter['booklist']['booklists']['default'])) checked
                        @endif
                        @endif
                        ><label class="label-check" for="check-{{ $goods_product->id }}"></label>
                    </td>
                    <td class="td-name">

                        @can('update', $goods_product)
                        {{ link_to_route('goods_products.edit', $goods_product->name, $parameters = ['id' => $goods_product->id], $attributes = []) }}
                        @endcan

                        @cannot('update', $goods_product)
                        {{ $goods_product->name }}
                        @endcannot

                        {{-- %5B%5D --}}
                        ({{ link_to_route('goods.index', $goods_product->articles_count, $parameters = ['goods_product_id' => $goods_product->id], $attributes = ['class' => 'filter_link light-text', 'title' => 'Перейти на список товаров']) }}) {{ ($goods_product->set_status == 'set') ? '(Набор)' : '' }}

                    </td>
                    <td class="td-goods_category">{{ $goods_product->category->name }}</td>
                    <td class="td-description">{{ $goods_product->description }}</td>

                    {{-- Элементы управления --}}
                    @include('includes.control.table_td', ['item' => $goods_product])

                    <td class="td-delete">

                        @include('includes.control.item_delete_table', ['item' => $goods_product])

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
        <span class="pagination-title">Кол-во записей: {{ $goods_products->count() }}</span>
        {{ $goods_products->appends(isset($filter['inputs']) ? $filter['inputs'] : null)->links() }}
    </div>
</div>
@endsection

@section('modals')
{{-- Модалка удаления с refresh --}}
@include('includes.modals.modal-delete')
@endsection

@section('scripts')
{{-- Скрипт чекбоксов, сортировки и перетаскивания для таблицы --}}
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
@endsection