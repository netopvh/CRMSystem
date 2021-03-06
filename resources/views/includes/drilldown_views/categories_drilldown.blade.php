<ul class="vertical menu drilldown" data-drilldown data-back-button='<li class="js-drilldown-back"><a tabindex="0">Назад</a></li>'>

    @foreach($grouped_items as $categories)

    @foreach ($categories as $category)
    @if($category->parent_id == null)
    {{-- Если категория --}}
    <li class="item-catalog">
        <a class="get-products" id="{{ $entity }}-{{ $category->id }}">{{ $category->name }}</a>
        @if(isset($grouped_items[$category->id]))
        <ul class="menu vertical nested">
            @include('includes.drilldown_views.items_drilldown', ['items' => $grouped_items[$category->id], 'grouped_items' => $grouped_items, 'entity' => $entity])
        </ul>
        @endif
    </li>
    @endif

    @endforeach

    @endforeach

</ul>