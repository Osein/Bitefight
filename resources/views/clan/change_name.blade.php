@extends('index')

@section('content')
	<div class="btn-left left">
		<div class="btn-right">
			<a class="btn" href="{{url('/clan/index')}}">{{__('general.back')}}</a>
		</div>
	</div>
	<br class="clearfloat">
	<div id="changeName">
		<div class="wrap-top-left clearfix">
			<div class="wrap-top-right clearfix">
				<div class="wrap-top-middle clearfix"></div>
			</div>
		</div>
		<div class="wrap-left clearfix">
			<div class="wrap-content wrap-right clearfix">
				<h2>{{user_race_logo_small()}}{{__('clan.clan_rename_rename_clan')}}</h2>
				@foreach($errors->all() as $error)
					<div class="error">{{$error}}</div>
				@endforeach
				<div class="table-wrap">
					<form method="POST">
						{{csrf_field()}}
						<table width="100%" cellpadding="15">
							<tbody>
							<tr>
								<th align="right">{{__('general.name')}}:</th>
								<th align="left">{{$clan->name}}</th>
								<th align="right">{{__('general.clan_tag')}}:</th>
								<th align="left">{{$clan->tag}}</th>
							</tr>
							<tr>
								<th align="right">{{__('clan.clan_rename_new_name')}}:</th>
								<th align="left"><input type="text" name="name" value="" size="20" maxlength="35"></th>
								<th align="right">{{__('clan.clan_rename_new_tag')}}:</th>
								<th align="left"><input type="text" name="tag" value="" size="20" maxlength="35"></th>
							</tr>
							<tr>
								<th colspan="4" align="left">
									<div class="btn-left center">
										<div class="btn-right">
											<input class="btn" type="submit" value="{{__('general.save')}}">
										</div>
									</div>
								</th>
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