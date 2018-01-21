@extends('index')

@section('content')
	<div id="news">
		<div class="wrap-top-left clearfix">
			<div class="wrap-top-right clearfix">
				<div class="wrap-top-middle clearfix"></div>
			</div>
		</div>
		<div class="wrap-left clearfix">
			<div class="wrap-content wrap-right clearfix">
				<h2><img src="{{asset('img/symbols/race'.(\Illuminate\Support\Facades\Auth::user() ? \Illuminate\Support\Facades\Auth::user()->getRace() : 1).'small.gif')}}" alt="">{{__('general.menu_news')}}</h2>
				<div class="table-wrap">
					<table cellpadding="2" cellspacing="10" border="0" width="100%">
						<tbody>
						@foreach($news as $new)
						<tr id="short_news_{{$new->id}}" class="news_{{$new->id}}">
							<td width="100%" style="cursor: pointer;">
								<b style="font-size:1.2em;">{{$new->title}}</b>
								<span style="float: right;">
                                    <b>{{date('d.m.Y H:i', $new->added_time)}}</b>&nbsp;&nbsp;<img border="0" src="{{asset('img/symbols/expand.png')}}">
                                </span>
							</td>
						</tr>
						<tr id="full_news_{{$new->id}}" class="news_{{$new->id}} hidden">
							<td width="100%" style="cursor: pointer;">
                                <span>
                                    <b style="font-size:1.2em;">{{$new->title}}</b>
                                    <span style="float: right;">
                                        <b>{{date('d.m.Y H:i', $new->added_time)}}</b>&nbsp;&nbsp;<img border="0" src="{{asset('img/symbols/collapse.png')}}">
                                    </span>
                                </span>
								<br>
								<hr>
								{{$new->message}}
							</td>
						</tr>
						@endforeach
						</tbody>
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

	<script type="text/javascript">
		$('[class^="news_"]').click(function () {
			$('.' + $(this).attr('class')).toggleClass('hidden');
		});
	</script>
@endsection