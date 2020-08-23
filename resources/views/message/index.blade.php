@extends('index')

@section('content')
	<div id="msg">
		<div class="wrap-top-left clearfix">
			<div class="wrap-top-right clearfix">
				<div class="wrap-top-middle clearfix"></div>
			</div>
		</div>
		<div class="wrap-left clearfix">
			<div class="wrap-content wrap-right clearfix">
				<h2>{{user_race_logo_small()}}messages</h2>
				<div class="clearfix">
					@include('partials.write_message')
					<div class="btn-left left">
						<div class="btn-right">
							<a href="#" class="btn" onclick="writeMessageSplash()">Write message</a>
						</div>
					</div>
					<div class="btn-left right">
						<div class="btn-right">
							<a href="{{url('message/settings')}}" class="btn">Settings</a>
						</div>
					</div>
					<div class="btn-left right">
						<div class="btn-right">
							<a href="{{url('message/folders')}}" class="btn">manage folders</a>
						</div>
					</div>
					<br class="clearfloat">
					<div class="table-wrap">
						<table width="100%" cellspacing="0" style="margin:0px;">
							<tbody>
							@foreach($folders as $folder)
							<tr>
								<td class="tdn" width="25" style="padding:1px 5px;">
									<img alt="new Message" src="{{asset('img/symbols/mail_status'.($folder->newMsgCount > 0 ? 1 : 0).'.png')}}">
								</td>
								<td class="tdn" style="padding:1px 5px;">
									<a href="{{url('/message/read?folder='.$folder->id)}}">{{$folder->folder_name}}</a> ({{$folder->id != -1 ? prettyNumber($folder->newMsgCount) . ' / ' : ''}}{{prettyNumber($folder->msgCount)}} messages)
								</td>
							</tr>
							@endforeach
							</tbody>
						</table>
					</div>
					<div class="btn-left right">
						<div class="btn-right">
							<a href="{{url('message/block')}}" class="btn">blocked players</a>
						</div>
					</div>
					<br class="clearfloat">
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