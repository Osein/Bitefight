@extends('index')

@section('content')
	<div class="btn-left left">
		<div class="btn-right">
			<a href="{{url('/clan/index')}}" class="btn">back</a>
		</div>
	</div>
	<br class="clearfloat">
	<div id="donationList">
		<div class="wrap-top-left clearfix">
			<div class="wrap-top-right clearfix">
				<div class="wrap-top-middle clearfix"></div>
			</div>
		</div>
		<div class="wrap-left clearfix">
			<div class="wrap-content wrap-right clearfix">
				<h2>{{user_race_logo_small()}}Clan donation</h2>

				<div class="table-wrap">
					<p>green display = the member has made a donation</p>
					<table width="100%">
						<tbody>
						<tr>
							<td><a href="{{url('clan/donationlist/?order=name&type='.($order == 'name' && $type == 'desc' ? 'asc' : 'desc'))}}">Player</a></td>
							<td><a href="{{url('clan/donationlist/?order=status&type='.($order == 'status' && $type == 'desc' ? 'asc' : 'desc'))}}">Rank</a></td>
							<td align="right"><a href="{{url('clan/donationlist/?order=amount&type='.($order == 'amount' && $type == 'desc' ? 'asc' : 'desc'))}}">Gold</a></td>
							<td align="right"><a href="{{url('clan/donationlist/?order=time&type='.($order == 'time' && $type == 'desc' ? 'asc' : 'desc'))}}">Status</a></td>
						</tr>
						@foreach($userList as $duser)
						<tr>
							<td><a href="{{url('/preview/user/'.$duser->id)}}">{{$duser->name}}</a></td>
							<td>{{$duser->rank_name}}</td>
							<td align="right"><font @if($duser->donate_amount > 0) color="lime" @endif>{{prettyNumber($duser->total_donate)}}</font></td>
							<td align="right"><font color="{{getUserStatusColor($duser->last_activity)}}">{{getClanStatusString($duser->last_activity)}}</font></td>
						</tr>
						@endforeach
						</tbody>
					</table>
				</div>
				<div class="btn-left left"><div class="btn-right">
						<a class="btn" href="{{url('/clan/donationlist/?action=refresh')}}">refresh</a>
					</div>
				</div>

				<br class="clearfloat">

				<h2>{{user_race_logo_small()}}Details about Clan donations</h2>

				<div class="table-wrap">
					<table width="100%">
						<tbody>
						<tr>
							<td><a href="{{url('clan/donationlist/?order2=name&type2='.($order2 == 'name' && $type2 == 'desc' ? 'asc' : 'desc'))}}">Player</a></td>
							<td><a href="{{url('clan/donationlist/?order2=status&type2='.($order2 == 'status' && $type2 == 'desc' ? 'asc' : 'desc'))}}">Rank</a></td>
							<td align="right"><a href="{{url('clan/donationlist/?order2=amount&type2='.($order2 == 'amount' && $type2 == 'desc' ? 'asc' : 'desc'))}}">Gold</a></td>
							<td align="right"><a href="{{url('clan/donationlist/?order2=time&type2='.($order2 == 'time' && $type2 == 'desc' ? 'asc' : 'desc'))}}">Date</a></td>
						</tr>
						@foreach($donateList as $donate)
						<tr>
							<td><a href="{{url('/preview/user/'.$donate->id)}}">{{$donate->name}}</a></td>
							<td>{{$donate->rank_name}}</td>
							<td align="right">{{prettyNumber($donate->donation_amount)}}</td>
							<td align="right">{{date('D, d.m.Y - H:i:s', $donate->donation_time)}}</td>
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
@endsection