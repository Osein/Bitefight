@extends('index')

@section('content')
	<div class="btn-left left">
		<div class="btn-right">
			<a class="btn" href="{{url('/clan/index')}}">{{__('general.back')}}</a>
		</div>
	</div>
	<br class="clearfloat">
	<div id="changeHomepage">
		<div class="wrap-top-left clearfix">
			<div class="wrap-top-right clearfix">
				<div class="wrap-top-middle clearfix"></div>
			</div>
		</div>
		<div class="wrap-left clearfix">
			<div class="wrap-content wrap-right clearfix">
				<h2>{{user_race_logo_small()}}{{__('clan.clan_homepage_header')}}</h2>
				<div class="table-wrap">
					<form method="POST">
						{{csrf_field()}}
						<table width="100%" cellpadding="15">
							<tbody>
							<tr>
								<th align="right">{{__('clan.clan_homepage_homepage')}}:</th>
								<th align="left">
									@if($clan && !empty($clan->website))
										{{$clan->website}}
										<a href="{{url('profile/player/'.$clan->website_set_by)}}">
											{{$clan->website_editor_name}}
										</a>
									@endif
								</th>
							</tr>
							<tr>
								<th align="right">{{__('clan.clan_homepage_homepage')}}:</th>
								<th align="left"><input type="text" name="homepage" value="@if($clan && !empty($clan->website)) {{$clan->website}} @else http:// @endif" size="50" maxlength="255"></th>
							</tr>
							<tr>
								<th>&nbsp;</th>
								<th>
									<div class="btn-left left">
										<div class="btn-right">
											<input class="btn" type="submit" value="{{__('general.save')}}">
										</div>
									</div>
									<div class="btn-left left">
										<div class="btn-right">
											<input class="btn" type="submit" name="delete" value="{{__('general.delete')}}">
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