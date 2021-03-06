<div class="small-12 medium-6 large-6 cell">
    <legend>Фильтры:</legend>
    <div class="grid-x grid-padding-x">

        <div class="small-12 medium-6 cell">
            <div class="grid-x">

                <div class="small-12 cell">
                    <div class="grid-x">
                        <div class="small-12 cell checkbox checkboxer">
                            @include('includes.inputs.checkboxer', ['name'=>'city', 'value' => $filter])
                        </div>
                    </div>
                </div>

                <div class="small-12 cell">
                    <div class="grid-x">
                        <div class="small-12 cell checkbox checkboxer">
                            @include('includes.inputs.checkboxer', ['name'=>'stage', 'value' => $filter])
                        </div>
                    </div>
                </div>

                <div class="small-12 cell">
                    <div class="grid-x">
                        <div class="small-12 cell checkbox checkboxer">
                            @include('includes.inputs.checkboxer', ['name'=>'manager', 'value' => $filter])
                        </div>
                    </div>
                </div>


                <div class="small-5 medium-5 cell">
                    <label>Начало периода:
                        @include('includes.inputs.date', ['name'=>'date_start', 'value' => ''])
                    </label>
                </div>
                <div class="small-2 medium-2 cell">
                </div>
                <div class="small-5 medium-5 cell">
                    <label>Окончание периода:
                        @include('includes.inputs.date', ['name'=>'date_end', 'value' => ''])
                    </label>
                </div>


            </div>
        </div>

        <div class="small-12 medium-6 cell">
          <div class="grid-x">

            <div class="small-12 cell">
                <div class="grid-x">
                    <div class="small-12 cell checkbox checkboxer">
                        @include('includes.inputs.checkboxer', ['name'=>'lead_method', 'value' => $filter])
                    </div>
                </div>
            </div>

            <div class="small-12 cell">
                <div class="grid-x">
                    <div class="small-12 cell checkbox checkboxer">
                        @include('includes.inputs.checkboxer', ['name'=>'lead_type', 'value' => $filter])
                    </div>
                </div>
            </div>

            <div class="small-12 cell">
                <div class="grid-x">
                    <div class="small-12 cell checkbox checkboxer">

                        {!! Form::select('challenges_active_count', ['2' => 'Все', '0' => 'Нет активных задач', '1' => 'Только с задачами'], null) !!}

                    </div>
                </div>
            </div>

        </div>
    </div>

</div>
</div>

<div class="small-12 medium-6 large-6 cell checkbox checkboxer">
    <legend>Мои списки:</legend>
    <div id="booklists">
        @include('includes.inputs.booklister', ['name'=>'booklist', 'value' => $filter])
    </div>
</div>