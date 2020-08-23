@extends('index')

@section('content')
	<div class="btn-left left">
		<div class="btn-right"><a href="{{isset($nameChanged) ? url('/city/library') : url('/city/index')}}" class="btn">back</a></div>
	</div>
	<br class="clearfloat">
	<div id="counterFeiter">
		<!-- box HEADER START -->
		<div class="wrap-top-left clearfix">
			<div class="wrap-top-right clearfix">
				<div class="wrap-top-middle clearfix"></div>
			</div>
		</div>
		<!-- box HEADER END -->
		<!-- box CONTENT START -->
		<div class="wrap-left clearfix">
			<div class="wrap-content wrap-right clearfix">
				<!-- CONTENT START -->
				<h2>{{user_race_logo_small()}}Library</h2>
				@if(isset($nameChanged))
					<h3>So, here are your documents now</h3>
					<p>Your bloodline now bears the name <b>{{user()->getName()}}</b></p>
				@else
					<div class="buildingDesc"> <img class="npc-logo" src="{{asset('img/city/npc/0_3.jpg')}}" align="left">
						<h3>
							Welcome to my humble abode, {{user()->getName()}}</h3>
						<p>
							`You don`t like your name anymore? Your enemies don`t shiver in their bones when they hear your name? Or is your name so famous that you haven`t a minute`s peace? In both cases I can help you - in exchange for a little bit golden gratitude.`        Nobody fakes a bloodline as well as I do. New documents, modified origins.
							It`s as if you`ve never been anyone else.    </p>
					</div>
					<br class="clearfloat">
					<h2>{{user_race_logo_small()}} What would you like?</h2>
					<form id="libraryOptions" method="POST">
						{{csrf_field()}}
						<div class="radio">
							<input type="radio" name="method" value="1" checked="">
							<label>
								New documents(costs: 10% Entire Booty, {{prettyNumber(getNameChangeCost(user()->getNameChange(), user()->getExp()))}} {{gold_image_tag()}})
							</label>
							<br class="clearfloat">
							<p>You will receive new documents for a small fee. In doing so, you will lose publicity.</p>
						</div>
						<div class="radio">
							<input type="radio" name="method" value="2">
							<label>	Rewrite documents(costs: 10 {{hellstone_image_tag()}})
							</label>
							<br class="clearfloat">
							<p>Your documents are being forged.</p>
						</div>
						<h3>Your new name:</h3>
						<div>
							<input type="text" class="input" name="name" value="" size="30">
							<input type="submit" class="btn-small" name="rename" value="rename">
						</div>
					</form>
					<br class="clearfloat">
					<!-- CONTENT END -->
				@endif
			</div>
		</div>
		<!-- box CONTENT END -->
		<!-- box FOOTER START -->
		<div class="wrap-bottom-left">
			<div class="wrap-bottom-right">
				<div class="wrap-bottom-middle"></div>
			</div>
		</div>
		<!-- box CONTENT END -->
	</div>
@endsection