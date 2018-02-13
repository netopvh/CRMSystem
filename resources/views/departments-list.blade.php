{{-- Если вложенный --}}
@php
  $count = 0;
@endphp
@if (isset($department['children']))
  @php
    $count = count($department['children']) + $count;
  @endphp
@endif
@if (isset($department['staff']))
  @php
    $count = count($department['staff']) + $count;
  @endphp
@endif
<li class="medium-item parent
@if (isset($department['children']) && isset($department['staff']))
parent-item
@endif" id="departments-{{ $department['id'] }}" data-name="{{ $department['department_name'] }}">
  <a class="medium-link">
    <div class="list-title">
      <div class="icon-open sprite"></div>
      <span>{{ $department['department_name'] }}</span>
      <span class="number">{{ $count }}</span>
    </div>
  </a>
  <ul class="icon-list">
    <li>
      @can('create', App\Department::class)
      <div class="icon-list-add sprite" data-open="department-add"></div>
      @endcan
    </li>
    <li>
      @if($department['edit'] == 1)
      <div class="icon-list-edit sprite" data-open="department-edit"></div>
      @endif
    </li>
    <li>
      @if ((count($department['staff']) == 0) && !isset($department['children']) && ($department['system_item'] != 1) && $department['delete'] == 1)
        <div class="icon-list-delete sprite" data-open="item-delete"></div>
      @endif
    </li>
  </ul>
  @if (isset($department['staff']) || isset($department['children']))
    <ul class="menu vertical medium-list accordion-menu" data-accordion-menu data-allow-all-closed data-multi-open="false">
      @if (isset($department['staff']))
        @foreach($department['staff'] as $staffer)
          <li class="medium-item parent" id="staff-{{ $staffer['id'] }}" data-name="{{ $staffer['position']['position_name'] }}">
            <div class="medium-as-last">{{ $staffer['position']['position_name'] }} ( <a href="/staff/{{ $staffer['id'] }}/edit" class="link-recursion">
            @if (isset($staffer['user_id']))
              {{ $staffer['user']['first_name'] }} {{ $staffer['user']['second_name'] }}
            @else
              Вакансия
            @endif
            </a> ) 
              <ul class="icon-list">
                <li>
                 @if(($staffer['system_item'] != 1) && ($staffer['delete'] == 1) && !isset($staffer['user']))
                  <div class="icon-list-delete sprite" data-open="item-delete"></div>
                @endif
                </li>
              </ul>
            </div>
          </li>
        @endforeach
      @endif
      @if (isset($department['children']))
        @foreach($department['children'] as $department)
          @include('departments-list', $department)
        @endforeach
      @endif
    </ul>
  @endif
</li>


 
              
    









 

         