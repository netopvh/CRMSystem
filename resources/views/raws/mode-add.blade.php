<div class="up-input-button">
<a id="mode-default" class="modes">Вернуться</a>
</div>
<label>Введите название новой группы
  @include('includes.inputs.string', ['value'=>null, 'name'=>'goods_product_name', 'required'=>'required'])
</label>
<label>Название услуги
  @include('includes.inputs.string', ['value'=>null, 'name'=>'name', 'required'=>'required'])
  <div class="item-error">Названия услуги и группы услуг не должны совпадать!</div>
</label>

{{ Form::hidden('mode', 'mode-add') }}