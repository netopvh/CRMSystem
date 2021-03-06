<div class="small-12 medium-6 large-6 cell">
  <legend>Фильтры:</legend>
  <div class="grid-x">
    <div class="small-12 cell">
      <div class="grid-x">
        <div class="small-12 medium-6 cell checkbox checkboxer">
          @include('includes.inputs.checkboxer', ['name'=>'stage', 'value' => $filter])      
        </div>
      </div>
    </div>

    <div class="small-12 cell">
      <div class="grid-x">
        <div class="small-12 medium-6 cell checkbox checkboxer">
          @include('includes.inputs.checkboxer', ['name'=>'appointed', 'value' => $filter])      
        </div>
      </div>
    </div>

    <div class="small-12 cell">
      <div class="grid-x">
        <div class="small-12 medium-6 cell checkbox checkboxer">
          @include('includes.inputs.checkboxer', ['name'=>'author', 'value' => $filter])      
        </div>
      </div>
    </div>

    <div class="small-12 cell">
      <div class="grid-x">
        <div class="small-12 medium-6 cell checkbox checkboxer">
          @include('includes.inputs.checkboxer', ['name'=>'challenge_status', 'value' => $filter])      
        </div>
      </div>
    </div>

      <div class="grid-x">
        <div class="small-5 medium-5 cell">
          <label>Начало периода:
            @include('includes.inputs.date', ['name'=>'date_start', 'value' => '', 'required' => ''])
          </label>
        </div>
        <div class="small-2 medium-2 cell">
        </div>
        <div class="small-5 medium-5 cell">
          <label>Окончание периода:
            @include('includes.inputs.date', ['name'=>'date_end', 'value' => '', 'required' => ''])
          </label>
        </div>
      </div>

    <div class="small-12 medium-6 large-6 cell date-interval-block">


    </div> 
  </div>
</div>

<div class="small-12 medium-6 large-6 cell checkbox checkboxer">
  <legend>Мои списки:</legend>
  <div id="booklists">
    @include('includes.inputs.booklister', ['name'=>'booklist', 'value' => $filter])
  </div>
</div>