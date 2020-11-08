@extends('index')

@section('content')
    <div class="btn-left left"><div class="btn-right"><a href="{{url('/message/index')}}" class="btn">back</a></div>
    </div>
    <br class="clearfloat">
    <div id="settings">
        <div class="wrap-top-left clearfix">
            <div class="wrap-top-right clearfix">
                <div class="wrap-top-middle clearfix"></div>
            </div>
        </div>
        <div class="wrap-left clearfix">
            <div class="wrap-content wrap-right clearfix">
                <h2>{{user_race_logo_small()}}Settings</h2>
                <div class="table-wrap">
                    <p>Here you can set up which messages should be shifted to which folder. Folders can also be deleted here.</p>
                    <form method="POST">
                        {{csrf_field()}}
                        <table cellpadding="3" cellspacing="2" border="0" width="100%">
                            <tbody>
                            @foreach ($msgSettings as $setting)
                            <tr>
                                <td class="tdn">{{\Database\Models\UserMessageSettings::getMessageSettingStringByType($setting->setting)}}</td>
                                <td class="tdn">
                                    <select class="input" name="x{{\Database\Models\UserMessageSettings::getMessageSettingViewIdFromType($setting->setting)}}" size="1">
                                        <option value="0" @if($setting->folder_id == 0) selected @endif>{{__('user.message_folder_select', ['folder' => __('general.inbox')])}}</option>
                                        @foreach($folders as $folder)
                                        <option value="{{$folder->id}}" @if($setting->folder_id == $folder->id) selected @endif >{{__('user.message_folder_select', ['folder' => e($folder->folder_name)])}}</option>
                                        @endforeach
                                        <option value="-2" @if($setting->folder_id == -2) selected @endif>{{__('user.message_delete_immediately')}}</option>
                                    </select>
                                </td>
                                <td><input type="checkbox" name="m{{\Database\Models\UserMessageSettings::getMessageSettingViewIdFromType($setting->setting)}}" @if($setting->mark_read) checked @endif > {{__('user.messages_mark_read')}}</td>
                            </tr>
                            @endforeach
                            <tr>
                                <td class="tdn no-bg" colspan="2" align="center">
                                    <div class="btn-left right">
                                        <div class="btn-right">
                                            <input class="btn" type="submit" name="save" value="Save">
                                        </div>
                                    </div>
                                </td>
                            </tr>
                            </tbody>
                        </table>
                    </form>
                </div>
            </div>
        </div>
        <div class="wrap-bottom-left">
            <div class="wrap-bottom-right">
                <div class="wrap-bottom-middle"></div>
            </div>
        </div>
    </div>
@endsection