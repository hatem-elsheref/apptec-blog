@extends('layouts.master')
@section('content')
    <div class="row">
        <div class="row justify-content-center">
            <div class="col-md-8">
                @if(session()->has('type'))
                    <div class="alert alert-{{session('type')}}">
                        {{session('message')}}
                    </div>
                @endif
               @foreach($errors->all() as $error)
                   <div class="alert alert-danger">
                       {{$error}}
                   </div>
               @endforeach
                <form method="POST" action="{{ route('setting.update') }}" enctype="multipart/form-data">
                    @csrf
                    @method('put')
                @foreach($settings as $setting)
                    @if($setting->is_simple)
                        <div class="mb-3">
                            <label for="setting-{{$setting->id}}" class="form-label">{{$setting->key_name}}</label>
                            <input {{$setting->html}} class="form-control" name="{{sprintf('setting_%s', $setting->id)}}" type="{{$setting->type}}" id="setting-{{$setting->id}}" value="{{$setting->value}}">
                        </div>
                        @elseif($setting->is_radio_or_check)
                            <div class="mb-3">
                                <input {{$setting->html}} class="form-check-input" name="{{sprintf('setting_%s', $setting->id)}}" type="{{$setting->type}}" id="setting-{{$setting->id}}" value="1" @checked($setting->value == 1)>
                                <label for="setting-{{$setting->id}}" class="form-label">{{$setting->key_name}}</label>
                            </div>
                        @elseif($setting->is_file)
                        <div class="mb-3">
                            <label for="setting-{{$setting->id}}" class="form-label">{{$setting->key_name}}</label>
                            <input {{$setting->html}} class="form-control" name="{{sprintf('setting_%s', $setting->id)}}" type="{{$setting->type}}" id="setting-{{$setting->id}}">
                            <embed src="{{$setting->url}}" style="width: 120px;height: 120px">
                        </div>
                    @endif
                @endforeach

                    <div class="mb-3 text-lg-end">
                        <button type="submit" class="btn btn-success">
                            {{ __('Save') }}
                        </button>
                    </div>
                </form>

            </div>
        </div>
    </div>
@endsection
