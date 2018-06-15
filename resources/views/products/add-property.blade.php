<label class="small-12 cell">Название
	@include('includes.inputs.name', ['value'=>null, 'name'=>'name', 'required'=>'required'])
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
@break

@case('list')
<label class="small-12 cell">Введите значение
	{{ Form::text('value') }}
</label>
<a class="button small-12 cell" id="add-value">Добавить значение</a>
<table id="values-table">
	<tbody id="values-tbody">
		
	</tbody>
</table>


@break



@default
Default case...
@endswitch

<div class="small-12 cell text-center">
	<a class="button" id="add-metric">Добавить метрику</a>
</div>



