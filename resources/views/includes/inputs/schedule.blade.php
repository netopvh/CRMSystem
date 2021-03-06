{{-- Расписание / График работы --}}

<table class="worktime_edit unstriped">
    <tr>
        <td>Понедельник</td>
        <td>
            <div class="small-12 medium-6 cell">
                @include('includes.inputs.time', ['value'=>$worktime[1]['begin'], 'name'=>'mon_begin'])
            </div>
        </td>
        <td>-</td>
        <td>
            <div class="small-12 medium-6 cell">
                @include('includes.inputs.time', ['value'=>$worktime[1]['end'], 'name'=>'mon_end'])
            </div>
        </td>
    </tr>
    <tr>
        <td>Вторник</td>
        <td>
            <div class="small-12 medium-6 cell">
                @include('includes.inputs.time', ['value'=>$worktime[2]['begin'], 'name'=>'tue_begin'])
            </div>
        </td>
        <td>-</td>
        <td>
            <div class="small-12 medium-6 cell">
                @include('includes.inputs.time', ['value'=>$worktime[2]['end'], 'name'=>'tue_end'])
            </div>
        </td>
    </tr>
    <tr>
        <td>Среда</td>
        <td>
            <div class="small-12 medium-6 cell">
                @include('includes.inputs.time', ['value'=>$worktime[3]['begin'], 'name'=>'wed_begin'])
            </div>
        </td>
        <td>-</td>
        <td>
            <div class="small-12 medium-6 cell">
                @include('includes.inputs.time', ['value'=>$worktime[3]['end'], 'name'=>'wed_end'])
            </div>
        </td>
    </tr>
    <tr>
        <td>Четверг</td>
        <td>
            <div class="small-12 medium-6 cell">
                @include('includes.inputs.time', ['value'=>$worktime[4]['begin'], 'name'=>'thu_begin'])
            </div>
        </td>
        <td>-</td>
        <td>
            <div class="small-12 medium-6 cell">
                @include('includes.inputs.time', ['value'=>$worktime[4]['end'], 'name'=>'thu_end'])
            </div>
        </td>
    </tr>
    <tr>
        <td>Пятница</td>
        <td>
            <div class="small-12 medium-6 cell">
                @include('includes.inputs.time', ['value'=>$worktime[5]['begin'], 'name'=>'fri_begin'])
            </div>
        </td>
        <td>-</td>
        <td>
            <div class="small-12 medium-6 cell">
                @include('includes.inputs.time', ['value'=>$worktime[5]['end'], 'name'=>'fri_end'])
            </div>
        </td>
    </tr>
    <tr>
        <td>Суббота</td>
        <td>
            <div class="small-12 medium-6 cell">
                @include('includes.inputs.time', ['value'=>$worktime[6]['begin'], 'name'=>'sat_begin'])
            </div>
        </td>
        <td>-</td>
        <td>
            <div class="small-12 medium-6 cell">
                @include('includes.inputs.time', ['value'=>$worktime[6]['end'], 'name'=>'sat_end'])
            </div>
        </td>
    </tr>
    <tr>
        <td>Воскресенье</td>
        <td>
            <div class="small-12 medium-6 cell">
                @include('includes.inputs.time', ['value'=>$worktime[7]['begin'], 'name'=>'sun_begin'])
            </div>
        </td>
        <td>-</td>
        <td>
            <div class="small-12 medium-6 cell">
                @include('includes.inputs.time', ['value'=>$worktime[7]['end'], 'name'=>'sun_end'])
            </div>
        </td>
    </tr>
</table>

