<table class="noBackground">
	<tbody>
	<tr>
		<td nowrap="" style="font-size: 1.1em;">
			{{__('talents.talent_id_'.$talent_obj->id.'_name')}}
		</td>
	</tr>
	<tr>
		<td nowrap="">
			{{__('user.profile_talent_talent_type_' . $talent_obj->active)}}
		</td>
	</tr>
	<tr>
		<td>
			{{__('talents.talent_id_'.$talent_obj->id.'_description')}}
		</td>
	</tr>
	@foreach(getTalentPropertyArray($talent_obj) as $prop)
	<tr>
		<td nowrap="">
			{{sprintf("%+d", $prop[1])}} {{$prop[0]}}
		</td>
	</tr>
	@endforeach
	@if($talent_obj->duration)
	<tr>
		<td nowrap="">
			{{__('user.profile_talent_duration', ['duration' => $talent_obj->duration])}}
		</td>
	</tr>
	@endif
	</tbody>
</table>