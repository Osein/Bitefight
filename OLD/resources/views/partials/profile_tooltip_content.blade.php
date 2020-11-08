<div>
	@if(isset($content['total']))
	<div class='tooltipHead'>
		{{$content['total'][0]}}: {{$content['total'][1] > 0 ? '+' : '').prettyNumber($content['total'][1]}}
	</div>
	@endif
	<div class='tooltipContent'>
		<table>
			@foreach($content['detail'] as $d)
			<tr>
				<td>{{$d[0]}}:</td>
				<td align='right'>{{$d[1] > 0 ? '+' : '').prettyNumber($d[1]}}</td>
			</tr>
			@endforeach
		</table>
	</div>
</div>