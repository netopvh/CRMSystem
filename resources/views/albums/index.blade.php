@extends('layouts.app')
 
@section('inhead')
{{-- Скрипты таблиц в шапке --}}
  @include('includes.scripts.tablesorter-inhead')
@endsection

@section('title', $page_info->name)

@section('breadcrumbs', Breadcrumbs::render('index', $page_info))

@section('title-content')
{{-- Таблица --}}
@include('includes.title-content', ['page_info' => $page_info, 'class' => App\Album::class, 'type' => 'table'])
@endsection
 
@section('content')

{{-- Таблица --}}
<div class="grid-x">
  <div class="small-12 cell">
    <table class="table-content tablesorter" id="content" class="content-albums" data-sticky-container data-entity-alias="albums">
      <thead class="thead-width sticky sticky-topbar" id="thead-sticky" data-sticky data-margin-top="6.2" data-sticky-on="medium" data-top-anchor="head-content:bottom">
        <tr id="thead-content">
          <th class="td-drop"></th>
          <th class="td-checkbox checkbox-th"><input type="checkbox" class="table-check-all" name="" id="check-all"><label class="label-check" for="check-all"></label></th>
          <th>Обложка</th>
          <th class="td-name">Название альбомa</th>
          <th class="td-category">Категория</th>
          <th class="td-description">Описание</th>
          <th class="td-date">Сведения</th>
          <th class="td-company-id">Компания</th>
          <th class="td-author">Автор</th>
          @can ('publisher', App\Album::class)
          <th class="td-display"></th>
          @endcan
          <th class="td-delete"></th>
        </tr>
      </thead>
      <tbody data-tbodyId="1" class="tbody-width">
      @if(!empty($albums))

        @foreach($albums as $album)
        <tr class="item @if($album->moderation == 1)no-moderation @endif" id="albums-{{ $album->id }}" data-name="{{ $album->name }}">
          <td class="td-drop"><div class="sprite icon-drop"></div></td>
          <td class="td-checkbox checkbox">

            <input type="checkbox" class="table-check" name="album_id" id="check-{{ $album->id }}"
            @if(!empty($filter['booklist']['booklists']['default']))
              @if (in_array($album->id, $filter['booklist']['booklists']['default'])) checked 
              @endif
            @endif 
            ><label class="label-check" for="check-{{ $album->id }}"></label></td>
          <td>
            <a href="/albums/{{ $album->alias }}">
              <img src="{{ isset($album->photo_id) ? '/storage/'.$album->company_id.'/media/albums/'.$album->id.'/img/small/'.$album->photo->name : '/img/plug/album_small_default_color.jpg' }}" alt="{{ isset($album->photo_id) ? $album->name : 'Нет фото' }}">
            </a>
          </td>
          <td class="td-name"><a href="/albums/{{ $album->alias }}/edit">{{ $album->name }}</a></td>
          <td class="td-category">{{ $album->albums_category->name }}</td>
          <td class="td-description">{{ $album->description }}</td>
          <td class="td-extra-info">
            <ul>
              <li>Доступ: {{ $album->access == 1 ? 'Личный' : 'Общий' }}</li>
              <li>Кол-во фотографий: {{ $album->photos_count }}</li>
              <li>Дата создания: {{ date('d.m.Y', strtotime($album->created_at)) }}</li>
              <li>Размер, Мб: {{ $album->photos->sum('size')/1024 }}</li>
            </ul>
          </td>
          <td class="td-company-id">@if(!empty($album->company->name)) {{ $album->company->name }} @else @if($album->system_item == null) Шаблон @else Системная @endif @endif</td>
          <td class="td-author">@if(isset($album->author->first_name)) {{ $album->author->first_name . ' ' . $album->author->second_name }} @endif</td>
          @can ('publisher', $album)
          <td class="td-display">


            @if ($album['display'] == 1)
            <a class="icon-display-show sprite" data-open="item-display"></a>
            @else
            <a class="icon-display-hide sprite" data-open="item-display"></a>
            @endif

            @if ($album['system_item'] == 1)
              @if($album['company_id'] != null)
                <a class="icon-system-lock sprite"></a>
              @else               
                <a class="icon-system-programm sprite"></a>
              @endif
            @endif

            @if ($album['system_item'] == null)
              @if($album['company_id'] != null)

              @else               
                <a class="icon-system-template sprite"></a>
              @endif
            @endif

          </td>
          @endcan
          <td class="td-delete">
            @if (($album->system_item != 1) && ($album->photos_count == 0))
              @can('delete', $album)
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
    <span class="pagination-title">Кол-во записей: {{ $albums->count() }}</span>
    {{ $albums->links() }}
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
  {{-- Скрипт чекбоксов, сортировки и перетаскивания для таблицы --}}
  @include('includes.scripts.tablesorter-script')

  {{-- Скрипт чекбоксов --}}
  @include('includes.scripts.checkbox-control')

  {{-- Скрипт модалки удаления --}}
  @include('includes.scripts.modal-delete-script')
  @include('includes.scripts.delete-ajax-script')

  @include('includes.scripts.sortable-table-script')
@endsection
