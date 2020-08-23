@extends('index')

@section('content')
	<div class="btn-left left"><div class="btn-right"><a href="{{url('/clan/index')}}" class="btn">back</a></div></div>
	<br class="clearfloat">
	<div id="create">
		<div class="wrap-top-left clearfix"><div class="wrap-top-right clearfix"><div class="wrap-top-middle clearfix"></div></div></div>
		<div class="wrap-left clearfix">
			<div class="wrap-content wrap-right clearfix">
				<h2>{{user_race_logo_small()}}Found a clan</h2>
				<br class="clearfloat">
				<form class="clearfix" method="POST">
					{{csrf_field()}}
					<div class="table-wrap">
						<table width="100%" border="0">
							<tbody>
							<tr>
								<td>Clan-Clan tag (2..8 Characters)</td>
								<td><input type="text" name="tag" maxlength="8" size="8" value="{{old('tag')}}"></td>
							</tr><tr>
								<td>Clan-Name (2..35 Characters)
								</td><td><input type="text" name="name" maxlength="35" size="20" value="{{old('name')}}"></td>
							</tr>
							</tbody>
						</table>
						<div class="btn-left center"><div class="btn-right"><input type="submit" class="btn" name="create" value="found"></div></div>
					</div>
				</form>
				<br class="clearfloat">
				@foreach($errors->all() as $error)
					<div class="error">{{$error}}</div>
				@endforeach
			</div>
		</div>
		<div class="wrap-bottom-left"><div class="wrap-bottom-right"><div class="wrap-bottom-middle"></div></div></div>
	</div>
@endsection