

<div class="grid-x tabs-wrap inputs">
    <div class="small-12 medium-6 large-6 cell tabs-margin-top">
        <div class="tabs-content" data-tabs-content="tabs">


            @if ($errors->any())

            <div class="alert callout" data-closable>
                <h5>Неправильный формат данных:</h5>
                <ul>
                    @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                    @endforeach
                </ul>
                <button class="close-button" aria-label="Dismiss alert" type="button" data-close>
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>

            @endif

            <div class="grid-x grid-padding-x">

                <div class="small-12 medium-6 cell">
                    <div class="grid-x grid-padding-x">

                        <div class="small-12 medium-12 cell">
                            <label>Название помещения
                                @include('includes.inputs.string', ['name'=>'name', 'value'=>$place->name, 'required' => true])
                            </label>
                        </div>
                        <div class="small-12 medium-12 cell">
                            <label>Описание
                                @include('includes.inputs.varchar', ['name'=>'description', 'value'=>$place->description])
                            </label>
                        </div>

                        <div class="small-12 medium-6 cell">
                            <label>Страна
                                @php
                                $country_id = null;
                                if (isset($place->location->country_id)) {
                                $country_id = $place->location->country_id;
                            }
                            @endphp
                            {{ Form::select('country_id', $countries_list, $country_id)}}
                        </label>
                    </div>
                    <div class="small-12 medium-6 cell">
                        {{-- Город --}}
                       @include('includes.inputs.city_search', ['city' => isset($place->location->city->name) ? $place->location->city : null, 'id' => 'cityForm', 'required' => true])
                </div>
                <div class="small-12 medium-12 cell">
                    <label>Адрес
                        @include('includes.inputs.address', ['value' => isset($place->location->address) ? $place->location->address : null, 'name'=>'address'])
                </label>

            </div>

            <div class="small-12 medium-6 cell">
                <label>Площадь, м2
                    @include('includes.inputs.digit', ['name'=>'square', 'value'=>$place->square, 'required' => true])
                </label>
            </div>
            <div class="small-12 medium-6 cell">
                <label>Форма владения
                    @include('includes.inputs.string', ['name'=>'rent_status', 'value'=>$place->rent_status])
                </label>
            </div>

            @include('includes.scripts.class.checkboxer')

            <div class="small-12 medium-12 cell checkbox checkboxer">
                @include('includes.inputs.checkboxer', ['name'=>'places_types', 'value'=>$places_types_checkboxer])
            </div>

        </div>
    </div>

</div>

</div>
</div>

<div class="small-12 medium-6 large-6 cell tabs-margin-top">
</div>

{{-- Чекбоксы управления --}}
@include('includes.control.checkboxes', ['item' => $place])

<div class="small-4 small-offset-4 medium-2 medium-offset-0 align-center cell tabs-button tabs-margin-top">
    {{ Form::submit($submitButtonText, ['class'=>'button']) }}
</div>
</div>

