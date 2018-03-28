<div class="reveal" id="medium-edit" data-reveal data-close-on-click="false">
	<div class="grid-x">
		<div class="small-12 cell modal-title">
			<h5>Редактирование отдел</h5>
		</div>
	</div>
	{{ Form::open(['id'=>'form-medium-edit', 'data-abide', 'novalidate']) }}
	<div class="grid-x grid-padding-x align-center modal-content inputs">
		<div class="small-10 cell">
			<label>Отдел находится в:
				<select class="departments-list" name="department_parent_id">
					@php
					echo $departments_list;
					@endphp
				</select>
			</label>
			<label>Название отдела
				@include('includes.inputs.name', ['value'=>$department->department_name, 'name'=>'department_name', 'required'=>'required'])
				<div class="item-error">Данный отдел уже существует в этом филиале!</div>
			</label>
			<label class="input-icon">Введите город
				@php
          $city_name = null;
          $city_id = null;
          if(isset($department->city->city_name)) {
            $city_name = $department->city->city_name;
            $city_id = $department->city->city_id;
          }
        @endphp
        @include('includes.inputs.city_search', ['city_value'=>$city_name, 'city_id_value'=>$city_id, 'required'=>'required'])
			</label>
			<label>Адресс отдела
				@include('includes.inputs.address', ['value'=>$department->address, 'name'=>'address', 'required'=>''])
			</label>
			<label>Телефон отдела
				@include('includes.inputs.phone', ['value'=>$department->phone, 'name'=>'phone', 'required'=>''])
			</label>
			{{ Form::hidden('filial_id', $department->filial_id, ['id' => 'filial-id']) }}
			{{ Form::hidden('department_id', $department->id, ['id' => 'department-id']) }}
			{{ Form::hidden('medium_item', 1, ['class' => 'medium-item', 'pattern' => '[0-9]{1}']) }}
		</div>
	</div>
	<div class="grid-x align-center">
		<div class="small-6 medium-4 cell">
			{{ Form::submit('Редактировать отдел', ['data-close', 'class'=>'button modal-button submit-edit']) }}
		</div>
	</div>
	{{ Form::close() }}
	<div data-close class="icon-close-modal sprite close-modal add-item"></div> 
</div>

@include('includes.scripts.inputs-mask')



