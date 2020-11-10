<div class="{{$viewClass['form-group']}} {!! !$errors->has($errorKey) ? '' : 'has-error' !!}">

    <label for="{{$id}}" class="{{$viewClass['label']}} control-label">{{$label}}</label>

    <div class="{{$viewClass['field']}}">
        @include('admin::form.error')
        {{--<span class="icheck">--}}
            {{--<label class="checkbox-inline">--}}
                <input type="checkbox" name="{{$week}}[]" value="7" class="iCheck-helper"/>&nbsp;星期日 <br />
            {{--</label>--}}
        {{--</span>--}}
        {{--<span class="icheck">--}}
            {{--<label class="checkbox-inline">--}}
                <input type="checkbox" name="{{$week}}[]" value="1" class="iCheck-helper"/>&nbsp;星期一<br />
            {{--</label>--}}
        {{--</span>--}}
        {{--<span class="icheck">--}}
            {{--<label class="checkbox-inline">--}}
                <input type="checkbox" name="{{$week}}[]" value="2" class="iCheck-helper"/>&nbsp;星期二<br />
            {{--</label>--}}
        {{--</span>--}}
        {{--<span class="icheck">--}}
            {{--<label class="checkbox-inline">--}}
                <input type="checkbox" name="{{$week}}[]" value="3" class="iCheck-helper"/>&nbsp;星期三<br />
            {{--</label>--}}
        {{--</span>--}}
        {{--<span class="icheck">--}}
            {{--<label class="checkbox-inline">--}}
                <input type="checkbox" name="{{$week}}[]" value="4" class="iCheck-helper"/>&nbsp;星期四<br />
            {{--</label>--}}
        {{--</span>--}}
        {{--<span class="icheck">--}}
            {{--<label class="checkbox-inline">--}}
                <input type="checkbox" name="{{$week}}[]" value="5" class="iCheck-helper"/>&nbsp;星期五<br />
            {{--</label>--}}
        {{--</span>--}}
        {{--<span class="icheck">--}}
            {{--<label class="checkbox-inline">--}}
                <input type="checkbox" name="{{$week}}[]" value="6" class="iCheck-helper"/>&nbsp;星期六<br />
            {{--</label>--}}
        {{--</span>--}}
        @include('admin::form.help-block')
    </div>
</div>
