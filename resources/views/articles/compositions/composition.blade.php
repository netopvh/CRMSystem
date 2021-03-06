<tr class="item" id="compositions-{{ $composition->id }}" data-name="{{ $composition->name }}">
	<td>{{ $composition->name }}</td>
	<td>
		@if (count($composition->articles) > 0)
		<select class="compact" name="compositions[{{ $composition->id }}][article]">
			@foreach ($composition->articles as $article)
			@php
			$selected = '';
			@endphp
			@if ($compositions_values->find($article->id))
			@php
			$selected = 'selected';
			@endphp
			@endif
			<option value="{{ $article->id }}" {{ $selected }}>{{ $article->name }}</option>
			@endforeach
		</select>
		@else
		Нет артикулов сырья
		@endif
	</td>
	<td>
		<div class="wrap-input-table">
			@php
			$value = null;
			@endphp
			@if(isset($compositions_values[$composition->id]->pivot->value))
			@php
			$value = $compositions_values[$composition->id]->pivot->value;
			@endphp
			@endif
			{{-- Количество чего-либо --}}
			{{ Form::text('compositions['.$composition->id.'][count]', $value, ['class'=>'digit-field name-field compact w12 padding-to-placeholder', 'id'=>'1', 'maxlength'=>'7', 'autocomplete'=>'off', 'pattern'=>'[0-9\W\s]{0,10}', 'placeholder'=>'0']) }}
			<label for="1" class="text-to-placeholder">{{ $composition->unit->abbreviation}}.</label>
			<div class="sprite-input-right find-status" id="name-check"></div>
			<span class="form-error">Введите количество</span>
		</div>
	</td>
	<td>
		<div class="wrap-input-table">
			{{-- Количество чего-либо --}}
			{{ Form::text('raw_count', '', ['class'=>'digit-field name-field compact w12 padding-to-placeholder', 'id'=>'2', 'maxlength'=>'7', 'autocomplete'=>'off', 'pattern'=>'[0-9\W\s]{0,10}', 'placeholder'=>'0']) }}
			<label for="2" class="text-to-placeholder">{{ $composition->unit->abbreviation}}</label>
			<div class="sprite-input-right find-status" id="name-check"></div>
			<span class="form-error">Введите количество</span>
		</div>
	</td>
	<td>
		<div class="wrap-input-table">
			{{-- Количество чего-либо --}}
			{{ Form::text('raw_count', '', ['class'=>'digit-field name-field compact w12 padding-to-placeholder', 'id'=>'3', 'maxlength'=>'7', 'autocomplete'=>'off', 'pattern'=>'[0-9\W\s]{0,10}', 'placeholder'=>'0']) }}
			<label for="3" class="text-to-placeholder">{{ $composition->unit->abbreviation}}</label>
			<div class="sprite-input-right find-status" id="name-check"></div>
			<span class="form-error">Введите количество</span>
		</div>
	</td>
	<td>
		<div class="wrap-input-table">
			{{-- Количество чего-либо --}}
			{{ Form::text('raw_count', '', ['class'=>'digit-field name-field compact w12 padding-to-placeholder', 'id'=>'4', 'maxlength'=>'7', 'autocomplete'=>'off', 'pattern'=>'[0-9\W\s]{0,10}', 'placeholder'=>'0']) }}
			<label for="4" class="text-to-placeholder">{{ $composition->unit->abbreviation}}.</label>
			<div class="sprite-input-right find-status" id="name-check"></div>
			<span class="form-error">Введите количество</span>
		</div>
	</td>
	<td>
		{{ Form::select('unit_id', ['1' => 'Списать', '2' => 'Вернуть на склад', '3' => 'Создать новый товар'], 0, ['id' => 'units-list', 'class' => 'compact']) }}
	</td>
<!-- 
	<td class="td-delete">
		<a class="icon-delete sprite" data-open="delete-composition"></a>
	</td> -->
</tr>
