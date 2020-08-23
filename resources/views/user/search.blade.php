@extends('index')

@section('content')
	<div id="search">
		<div class="wrap-top-left clearfix">
			<div class="wrap-top-right clearfix">
				<div class="wrap-top-middle clearfix"></div>
			</div>
		</div>
		<div class="wrap-left clearfix">
			<div class="wrap-content wrap-right clearfix">
				<h2>{{user_race_logo_small()}}{{__('user.search_header_form')}}</h2>
				<form method="POST" class="clearfix">
					{{csrf_field()}}
					<div id="searchOptions">
						<div>
							<input type="radio" name="searchtyp" value="name" @if((isset($searchType) && $searchType == 'name') || !isset($searchType)) checked @endif >
							<label>{{__('user.search_type_player')}}</label>
						</div>
						<div>
							<input type="radio" name="searchtyp" value="clan" @if(isset($searchType) && $searchType == 'clan') checked @endif >
							<label>{{__('user.search_type_clan_name')}}</label>
						</div>
						<div>
							<input type="radio" name="searchtyp" value="tag" @if(isset($searchType) && $searchType == 'tag') checked @endif >
							<label>{{__('user.search_type_clan_tag')}}</label>
						</div>
					</div>
					<div id="searchField">
						<div class="btn-left right">
							<div class="btn-right">
								<input type="submit" name="search" value="{{__('user.search_button')}}" class="btn">
							</div>
						</div>
						<label>Text:</label>
						<input type="text" name="text" size="30" maxlength="30" value="{{old('text')}}">
						<input id="exact" type="checkbox" class="check" name="exakt" @if(old('exakt')) checked @endif>
						<label class="checklabel" for="exact">{{__('user.search_only_exact_results')}}</label>
					</div>
				</form>
				@if(isset($results))
				<h2 style="margin-bottom: 1rem;">{{user_race_logo_small()}}{{__('user.header_results')}}</h2>
				<table width="80%">
					<tbody>
					<tr>
						<td>{{__('general.race')}}</td>
						@if($searchType == 'name')
						<td>{{__('general.player')}}</td>
						@else
						<td>{{__('general.clan')}}</td>
						<td>{{__('general.members')}}</td>
						@endif
						<td>{{__('general.entire_booty')}}</td>
					</tr>
					@foreach($results as $res)
					<tr>
						<td>
							<img src="{{asset('img/symbols/race'.$res->race.'small.gif')}}" title="{{getRaceString($res->race)}}" border="0">
						</td>
						@if($searchType == 'name')
							<td>
								<a href="{{url('/preview/player/'.$res->id)}}">{{$res->name}}</a>
							</td>
							<td>{{prettyNumber($res->s_booty)}}</td>
						@else
							<td>
								<a href="{{url('/preview/clan/'.$res->id)}}">{{$res->name}} [{{$res->tag}}]</a>
							</td>
							<td>{{$res->members}} / {{$res->stufe == 0 ? 1 : $res->stufe * 3}}</td>
							<td>{{prettyNumber($res->booty)}}</td>
						@endif
					</tr>
					@endforeach
					<tr>
						<td class="no-bg" colspan="2">@if(count($results)) {{__('general.results_with_max', ['result' => count($results), 'max' => 25])}} @else {{__('general.no_result')}} @endif</td>
					</tr>
					</tbody>
				</table>
				@endif
			</div>
		</div>
		<div class="wrap-bottom-left">
			<div class="wrap-bottom-right">
				<div class="wrap-bottom-middle"></div>
			</div>
		</div>
	</div>
@endsection