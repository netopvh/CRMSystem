<div class=" up-input-button">
	@if ($services_products_count > 0)
	<a id="mode-select" class="modes">Добавить в группу</a> <span>|</span>
	@endif
	<a id="mode-add" class="modes">Создать группу</a>
</div>
<label>Название услуги
	@include('includes.inputs.string', ['value'=>null, 'name'=>'name', 'required' => true])
	<div class="item-error">Такая услуга уже существует!</div>
</label>
{{ Form::hidden('mode', 'mode-default') }}

