{{-- Чекбокс отображения на сайте --}}
@can ('display', $item)
<div class="small-12 cell checkbox">
    {{ Form::checkbox('display', 1, $item->display, ['id' => 'display']) }}
    <label for="display"><span>Отображать на сайте</span></label>
</div>
@endcan

{{-- Чекбокс модерации --}}
@can ('moderator', $item)
@moderation ($item)
<div class="small-12 cell checkbox">
    @include('includes.inputs.moderation', ['value'=>$item->moderation, 'name'=>'moderation'])
</div>
@endmoderation
@endcan

{{-- Чекбокс системной записи --}}
@can ('system', $item)
<div class="small-12 cell checkbox">
    @include('includes.inputs.system', ['value'=>$item->system_item, 'name'=>'system_item']) 
</div>
@endcan  