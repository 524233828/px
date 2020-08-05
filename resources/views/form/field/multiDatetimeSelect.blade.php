<div class="{{$viewClass['form-group']}} {!! !$errors->has($errorKey) ? '' : 'has-error' !!}">

    <label for="{{$id}}" class="{{$viewClass['label']}} control-label">{{$label}}</label>

    <div class="{{$viewClass['field']}}">
        @include('admin::form.error')
        <div id="multi-date-selector-{{$id_name}}" style="width: 100%;"></div>
        @include('admin::form.help-block')
    </div>
</div>
