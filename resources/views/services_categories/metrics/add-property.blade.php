<label class="small-12 cell">Название
	@include('includes.inputs.name', ['value'=>null, 'name'=>'name', 'required' => true])
</label>
<label class="small-12 cell">Описание
	@include('includes.inputs.textarea', ['value'=>null, 'name'=>'description'])
</label>

@switch($type)

@case('numeric')
<label class="small-6 cell">Минимум
	{{ Form::number('min') }}
</label>
<label class="small-6 cell">Максимум
	{{ Form::number('max') }}
</label>
<label class="small-6 cell">Единица измерения
	{{ Form::select('unit_id', $units_list, null) }}
</label>
<label class="small-6 cell">Знаки после запятой
	{{ Form::select('metric_lol', ['1' => '0.0', '2' => '0.00', '3' => '0.000'], null) }}
</label>
{{ Form::hidden('type', 'numeric') }}
@break

@case('percent')
<label class="small-6 cell">Минимум
	{{ Form::number('min') }}
</label>
<label class="small-6 cell">Максимум
	{{ Form::number('max') }}
</label>
<label class="small-6 cell">Единица измерения
	{{ Form::select('unit_id', $units_list, null) }}
</label>
<label class="small-6 cell">Знаки после запятой
	{{ Form::select('metric_lol', ['1' => '0.0', '2' => '0.00', '3' => '0.000'], null) }}
</label>
{{ Form::hidden('type', 'percent') }}
@break

@case('list')
<div class="radiobutton">Тип списка<br>
	{{ Form::radio('list_type', 'list', true, ['id'=>'metric-list-type']) }}
	<label for="metric-list-type"><span>Много значений</span></label>
	{{ Form::radio('list_type', 'select', false, ['id'=>'metric-select-type']) }}
	<label for="metric-select-type"><span>Одно значение</span></label>

</div>
<label class="small-12 cell">Введите значение
	{{ Form::text('value') }}
</label>
<a class="button small-10 cell" id="add-value">Добавить значение</a>
<table id="values-table" class="tablesorter">
	<tbody id="values-tbody">

	</tbody>
</table>
{{ Form::hidden('type', 'list') }}

@break

@endswitch

{{ Form::hidden('property_id', $property_id) }}
{{ Form::hidden('entity', 'products_categories') }}

<div class="small-12 cell text-center">
	<a class="button" id="add-metric">Добавить метрику</a>
</div>



