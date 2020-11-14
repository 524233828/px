<div class="{{$viewClass['form-group']}} {!! !$errors->has($errorKey) ? '' : 'has-error' !!}">

    <label for="{{$id}}" class="{{$viewClass['label']}} control-label">{{$label}}</label>

    <div class="{{$viewClass['field']}}">
        @include('admin::form.error')
        {{--<span class="icheck">--}}
            {{--<label class="checkbox-inline">--}}
                <input type="checkbox" name="{{$column}}[{{$week}}][]" value="7" class="iCheck-helper" @if(in_array(7, $value['week']))checked="checked"@endif/>&nbsp;星期日&nbsp;<input name="{{$column}}[{{$time}}][]" value="@if(isset($value["time"][0])){{$value["time"][0]}}@endif" class="timePicker"> <br />
            {{--</label>--}}
        {{--</span>--}}
        {{--<span class="icheck">--}}
            {{--<label class="checkbox-inline">--}}
                <input type="checkbox" name="{{$column}}[{{$week}}][]" value="1" class="iCheck-helper"  @if(in_array(1, $value['week']))checked="checked"@endif/>&nbsp;星期一&nbsp;<input name="{{$column}}[{{$time}}][]" value="@if(isset($value["time"][1])){{$value["time"][1]}}@endif" class="timePicker"> <br />
            {{--</label>--}}
        {{--</span>--}}
        {{--<span class="icheck">--}}
            {{--<label class="checkbox-inline">--}}
                <input type="checkbox" name="{{$column}}[{{$week}}][]" value="2" class="iCheck-helper"  @if(in_array(2, $value['week']))checked="checked"@endif/>&nbsp;星期二&nbsp;<input name="{{$column}}[{{$time}}][]" value="@if(isset($value["time"][2])){{$value["time"][2]}}@endif" class="timePicker"> <br />
            {{--</label>--}}
        {{--</span>--}}
        {{--<span class="icheck">--}}
            {{--<label class="checkbox-inline">--}}
                <input type="checkbox" name="{{$column}}[{{$week}}][]" value="3" class="iCheck-helper"  @if(in_array(3, $value['week']))checked="checked"@endif/>&nbsp;星期三&nbsp;<input name="{{$column}}[{{$time}}][]" value="@if(isset($value["time"][3])){{$value["time"][3]}}@endif" class="timePicker"> <br />
            {{--</label>--}}
        {{--</span>--}}
        {{--<span class="icheck">--}}
            {{--<label class="checkbox-inline">--}}
                <input type="checkbox" name="{{$column}}[{{$week}}][]" value="4" class="iCheck-helper"  @if(in_array(4, $value['week']))checked="checked"@endif/>&nbsp;星期四&nbsp;<input name="{{$column}}[{{$time}}][]" value="@if(isset($value["time"][4])){{$value["time"][4]}}@endif" class="timePicker"> <br />
            {{--</label>--}}
        {{--</span>--}}
        {{--<span class="icheck">--}}
            {{--<label class="checkbox-inline">--}}
                <input type="checkbox" name="{{$column}}[{{$week}}][]" value="5" class="iCheck-helper"  @if(in_array(5, $value['week']))checked="checked"@endif/>&nbsp;星期五&nbsp;<input name="{{$column}}[{{$time}}][]" value="@if(isset($value["time"][5])){{$value["time"][5]}}@endif" class="timePicker"> <br />
            {{--</label>--}}
        {{--</span>--}}
        {{--<span class="icheck">--}}
            {{--<label class="checkbox-inline">--}}
                <input type="checkbox" name="{{$column}}[{{$week}}][]" value="6" class="iCheck-helper"  @if(in_array(6, $value['week']))checked="checked"@endif/>&nbsp;星期六&nbsp;<input name="{{$column}}[{{$time}}][]" value="@if(isset($value["time"][6])){{$value["time"][6]}}@endif" class="timePicker"> <br />
            {{--</label>--}}
        {{--</span>--}}
        @include('admin::form.help-block')
    </div>
</div>
