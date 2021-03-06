{{-- Имя записи сущности --}}
@php
$max = 40;
@endphp

{{ Form::text(($name ?? 'name'), ($value ?? null),
	[
		'class' => 'varchar-field name-field' . (isset($check) ? ' check-field' : ''),
		'maxlength' => $max,
		'autocomplete' => 'off',
		'pattern'=>'[A-Za-zА-Яа-яЁё0-9\W\s]{3,'.$max.'}',
		(isset($required) ? 'required' : ''),
		(isset($autofocus) ? 'autofocus' : ''),
	]
	) }}
	<div class="sprite-input-right find-status" id="name-check"></div>
	<span class="form-error">Уж постарайтесь, введите хотя бы 3 символа!</span>
