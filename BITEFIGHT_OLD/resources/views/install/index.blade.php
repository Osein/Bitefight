@extends('index')

@section('header', 'Install')

@section('content')
    <form method="POST">
        {{csrf_field()}}
        @foreach($formInputs as $inputGroupLabel => $inputs)
            <div @if(!$loop->first) style="margin-top: 1rem;" @endif>
                <div class="wrap-top-left">
                    <div class="wrap-top-right">
                        <div class="wrap-top-middle"></div>
                    </div>
                </div>
                <div class="wrap-left">
                    <div class="wrap-content wrap-right">
                        <h2><img src="/img/symbols/race1small.gif" alt="">{{$inputGroupLabel}}</h2>
                        <div class="table-wrap">
                            <table cellpadding="2" cellspacing="2" border="0" width="100%">
                                <colgroup>
                                    <col width="300">
                                </colgroup>
                                @foreach($inputs as $input)
                                    <tr>
                                        <td>{{$input['name']}}:</td>
                                        <td><input  type="text" name="env[{{$input['name']}}]" value="{{$input['default']}}"></td>
                                    </tr>
                                @endforeach
                            </table>
                        </div>
                    </div>
                </div>
                <div class="wrap-bottom-left">
                    <div class="wrap-bottom-right">
                        <div class="wrap-bottom-middle"></div>
                    </div>
                </div>
            </div>
        @endforeach
        <br>
        <div class="btn-left center">
            <div class="btn-right"><input type="submit" class="btn" value="Install">
            </div>
        </div>
    </form>
@endsection