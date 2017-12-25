<div class="grid-x grid-padding-x inputs">
  <div class="small-12 medium-7 large-5 cell tabs-margin-top">
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
    <!-- Сотрудник -->
    <label>Название должности
    {{ Form::text('position_name', $vacancy->position->position_name, ['class'=>'position-name-field', 'maxlength'=>'40', 'autocomplete'=>'off', 'readonly']) }}
    </label>
    <label>Сотрудник:
      {{ Form::select('user_id', $users, $vacancy->user_id, ['id'=>'vacancy-select', 'placeholder'=>'Вакансия']) }}
    </label>
    <label>Дата приема
        @foreach ($vacancy->employees as $employee)
          @if (($employee->user_id == $vacancy->user_id) && ($employee->date_dismissal == null))
            {{ Form::text('date_employment', $employee->date_employment, ['class'=>'date_employment date-field', 'pattern'=>'[0-9]{2}.[0-9]{2}.[0-9]{4}', 'autocomplete'=>'off']) }}
          @else
            {{ Form::text('date_employment', null, ['class'=>'date_employment date-field', 'pattern'=>'[0-9]{2}.[0-9]{2}.[0-9]{4}', 'autocomplete'=>'off']) }}
          @endif
        @endforeach
        
    </label>
    <label>Дата увольнения
      {{ Form::text('date_dismissal', null, ['class'=>'date_dismissal date-field', 'pattern'=>'[0-9]{2}.[0-9]{2}.[0-9]{4}', 'autocomplete'=>'off']) }}
    </label>
    
  </div>
  <div class="small-12 medium-5 large-7 cell tabs-margin-top">

  </div>
  <div class="small-4 small-offset-4 medium-2 medium-offset-0 align-center cell tabs-button tabs-margin-top">
    {{ Form::submit($submitButtonText, ['class'=>'button']) }}
  </div>
</div>

