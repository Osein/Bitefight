@extends('index')

@section('content')
	<div class="btn-left left"><div class="btn-right"><a href="{{url('/message/index')}}" class="btn">back</a></div></div>
	<br class="clearfloat">
	<div id="handleFolders">
		<div class="wrap-top-left clearfix">
			<div class="wrap-top-right clearfix">
				<div class="wrap-top-middle clearfix"></div>
			</div>
		</div>
		<div class="wrap-left clearfix">
			<div class="wrap-content wrap-right clearfix">
				<h2>{{user_race_logo_small()}}manage folders</h2>
				<div class="table-wrap">
					<table cellpadding="2" cellspacing="2" border="0" width="100%">
						<tbody><tr>
							<td class="no-bg">Folder</td>
							<td class="no-bg">Action</td>
						</tr>
						<tr>
							<td class="tdn"><span style="color:#fff; font-weight:bold;">Inbox</span></td>
							<td class="tdn">-</td>
							<td class="tdn">-</td>
						</tr>

						@foreach($folders as $folder)
						<tr>
							<form method="POST">
								{{csrf_field()}}
								<td class="tdn"><span style="color:#fff; font-weight:bold;;">{{$folder->folder_name}}</span></td>
								<td class="tdn">
									<input type="hidden" name="folderid" value="{{$folder->id}}">
									<div class="btn-left left">
										<div class="btn-right">
											<input class="btn" type="submit" name="action" value="delete">
										</div>
									</div>
									<div class="btn-left left">
										<div class="btn-right">
											<input class="btn" type="submit" name="action" value="rename">
										</div>
									</div>
								</td>
								<td class="tdn">
									@if($folder_max_min->min !== $folder->folder_order)
									<div class="btn-left left">
										<div class="btn-right">
											<input class="btn" type="submit" name="action" value="move up">
										</div></div>
									@endif
									@if($folder_max_min->max !== $folder->folder_order)
									<div class="btn-left left">
										<div class="btn-right">
											<input class="btn" type="submit" name="action" value="move down">
										</div>
									</div>
									@endif
								</td>
							</form>
						</tr>
						@if($folder_rename_id == $folder->id)
						<tr>
							<td class="tdn" colspan="3">
								<form action="{{url('message/folders')}}" method="POST">
									{{csrf_field()}}
									<label class="left">new name:</label>
									<input class="input left" type="text" name="name" value="" maxlength="50" size="20">
									<input type="hidden" name="folderid" value="{{$folder->id}}">
									<input type="hidden" name="action" value="save new name">
									<div class="btn-left left"><div class="btn-right"><input class="btn" type="submit" name="rename" value="Save"></div></div>
								</form>
							</td>
						</tr>
						@endif
						@endforeach

						<tr>
							<td class="tdn"><span style="color:#fff; font-weight:bold;">Outbox</span></td>
							<td class="tdn">-</td>
							<td class="tdn">-</td>
						</tr>
						@if(isset($folder_action_info))
						<tr>
							<td colspan="3">
								<div class="error">{{$folder_action_info}}</div>
							</td>
						</tr>
						@endif
						</tbody>
					</table>
				</div>
				<br>
				<form action="{{url('message/folders')}}" method="POST">
					{{csrf_field()}}
					<h2>{{user_race_logo_small()}}create new folder...</h2>
					<div class="table-wrap">
						<table cellpadding="2" cellspacing="2" border="0" width="100%">
							<tbody><tr>
								<td class="tdn">
									<label>Name:</label>
									<input class="left" type="text" name="name" value="" maxlength="50">
									<div class="btn-left left ">
										<div class="btn-right">
											<input class="btn" type="submit" name="action" value="create folder">
										</div>
									</div>
									<br class="clearfloat">
									@if(isset($folder_create_info))
									<div class="error">{{$folder_create_info}}</div>
									@endif
								</td>
							</tr>
							</tbody>
						</table>
					</div>
				</form>
			</div>
		</div>
		<div class="wrap-bottom-left">
			<div class="wrap-bottom-right">
				<div class="wrap-bottom-middle"></div>
			</div>
		</div>
	</div>
@endsection