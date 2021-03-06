<div class="grid-x tabs-wrap">
    <div class="small-12 cell">
        <ul class="tabs-list" data-tabs id="tabs">
            <li class="tabs-title is-active"><a aria-selected="true" href="#content-panel-1">Общая информация</a></li>
            <li class="tabs-title"><a data-tabs-target="content-panel-2" href="#content-panel-2">Реквизиты</a></li>
            <li class="tabs-title"><a data-tabs-target="content-panel-3" href="#content-panel-3">Банковские реквизиты</a></li>
            <li class="tabs-title"><a data-tabs-target="content-panel-4" href="#content-panel-4">График работы</a></li>
            <li class="tabs-title"><a data-tabs-target="content-panel-5" href="#content-panel-5">Настройка</a></li>
        </ul>
    </div>
</div>

<div class="grid-x tabs-wrap inputs">
    <div class="small-12 medium-7 large-5 cell tabs-margin-top">
        <div class="tabs-content" data-tabs-content="tabs">

            <!-- Общая информация -->
            <div class="tabs-panel is-active" id="content-panel-1">
                <div class="grid-x grid-padding-x">
                    <div class="small-12 medium-6 cell">
                        <label>Название компании
                            @include('includes.inputs.name', ['value'=>$company->name, 'name'=>'name', 'required' => true])
                        </label>
                    </div>
                    <div class="small-12 medium-6 cell">
                        <label>Вид деятельности компании
                            <select name="sector_id" class="sectors-list">

                                {!! $sectors_list !!}

                            </select>
                        </label>
                    </div>
                    <div class="small-12 medium-6 cell">
                        <label>Телефон
                            @include('includes.inputs.phone', ['value' => isset($company->main_phone->phone) ? $company->main_phone->phone : null, 'name'=>'main_phone', 'required' => true])
                        </label>
                    </div>
                    <div class="small-12 medium-6 cell" id="extra-phones">
                        @if (count($company->extra_phones) > 0)
                            @foreach ($company->extra_phones as $extra_phone)
                                @include('includes.extra-phone', ['extra_phone' => $extra_phone])
                            @endforeach
                        @else
                            @include('includes.extra-phone')
                        @endif

                        <!-- <span id="add-extra-phone">Добавить номер</span> -->
                    </div>

                    <div class="small-12 medium-6 cell">
                        <label>Почта
                            @include('includes.inputs.email', ['value'=>$company->email, 'name'=>'email'])
                        </label>
                        <label>Страна
                            @php

                                $country_id = null;
                                if (isset($company->location->country_id)) {
                                    $country_id = $company->location->country_id;
                                }

                            @endphp
                            {{ Form::select('country_id', $countries_list, $country_id)}}
                        </label>
                    </div>

                    <div class="small-12 medium-6 cell">
                        @include('includes.inputs.city_search', ['city' => isset($company->location->city->name) ? $company->location->city : null, 'id' => 'cityForm', 'required' => true])

                        <label>Адрес
                            @php
                                $address = null;
                                if (isset($company->location->address)) {
                                    $address = $company->location->address;
                                }
                            @endphp
                            @include('includes.inputs.address', ['value'=>$address, 'name'=>'address'])
                        </label>
                    </div>

                    {{-- Дополнительная секция --}
                    @yield('extra-company')

                </div>
            </div>

            <!-- Реквизиты -->
            <div class="tabs-panel" id="content-panel-2">
                <div class="grid-x grid-padding-x">
                    <div class="small-12 medium-6 cell">
                        <label>ИНН
                            @include('includes.inputs.inn', ['value'=>$company->inn, 'name'=>'inn'])
                        </label>
                    </div>
                    <div class="small-12 medium-6 cell">
                        <label>КПП
                            @include('includes.inputs.kpp', ['value'=>$company->kpp, 'name'=>'kpp'])
                        </label>
                    </div>
                    <div class="small-12 medium-6 cell">
                        <label>ОГРН
                            @include('includes.inputs.ogrn', ['value'=>$company->ogrn, 'name'=>'ogrn'])
                        </label>
                    </div>
                    <div class="small-12 medium-6 cell">
                        <label>ОКПО
                            @include('includes.inputs.okpo', ['value'=>$company->okpo, 'name'=>'okpo'])
                        </label>
                    </div>
                </div>
            </div>
            <!-- Конец реквизиты -->



            <!-- Банковские реквизиты -->
            <div class="tabs-panel" id="content-panel-3">
                <div class="grid-x grid-padding-x">
                    <div class="small-12 cell" id="bank-accounts-list">

                        {{-- Подключаем банковские аккаунты --}}
                        @include('includes.bank_accounts.fieldset', ['company' => $company])

                    </div>
                </div>
            </div>
            <!-- Конец банковские реквизиты -->


            <!-- Настройки -->
            <div class="tabs-panel" id="content-panel-5">
                <div class="grid-x grid-padding-x">
                    <div class="small-12 medium-6 cell">
                        <label>Алиас
                            @include('includes.inputs.alias', ['value'=>$company->alias, 'name'=>'alias'])
                        </label>
                    </div>

                    @include('includes.scripts.class.checkboxer')

                    <div class="small-12 medium-12 cell checkbox checkboxer">
                        @include('includes.inputs.checkboxer', ['name'=>'services_types', 'value'=>$services_types_checkboxer])
                    </div>

                    {{-- Чекбоксы управления --}}
                    @include('includes.control.checkboxes', ['item' => $company])
                </div>
            </div>
            <!-- Конец настройки -->


            <!-- График работы -->
            <div class="tabs-panel" id="content-panel-4">
                <div class="grid-x grid-padding-x">
                    <div class="small-12 medium-6 cell">

                        @include('includes.inputs.schedule', ['value'=>$worktime])

                    </div>
                </div>
            </div>
            <!-- Конец график работы -->

        </div>

        <div class="small-12 medium-5 large-7 cell tabs-margin-top">
        </div>

        <div class="small-4 small-offset-4 medium-2 medium-offset-0 align-center cell tabs-button tabs-margin-top">
            {{ Form::submit($submitButtonText, ['class'=>'button']) }}
        </div>
    </div>
</div>

