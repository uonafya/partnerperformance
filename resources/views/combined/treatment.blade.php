<div class="row">
	<div class="col-md-6">
		<table class="tablehead table table-striped table-bordered">
			<tr>
				<td></td>
				<td><b>Target</b></td>
				<td><b>Result</b></td>
				<td><b>% Achievement</b></td>
			</tr>
			<tr>
				<td><b>Current on tx</b></td>
				<td> {{ number_format($target->current) }} </td>	
				<!-- Actual current is from a different object  -->
				<td> {{ number_format($current_art) }} </td>				
				<td> {{ number_format($current_completion) }} </td>				
			</tr>
			<tr>
				<td><b>New on tx</b></td>
				<td> {{ number_format($target->new_art) }} </td>				
				<td> {{ number_format($new_art) }} </td>				
				<td> {{ number_format($new_completion) }} </td>				
			</tr>
		</table>
		
	</div>
	<div class="col-md-6">
		<br />
		<br />
		<div class="progress">
			<div class="progress-bar progress-bar-striped {{ $current_status }}" role="progressbar" style="width: {{ $current_completion }}%" aria-valuenow="{{ $current_completion }}" aria-valuemin="0" aria-valuemax="100">
				{{ $current_completion }}%
			</div>
		</div>

		<div class="progress">
			<div class="progress-bar progress-bar-striped {{ $new_status }}" role="progressbar" style="width: {{ $new_completion }}%" aria-valuenow="{{ $new_completion }}" aria-valuemin="0" aria-valuemax="100">
				{{ $new_completion }}%
			</div>
		</div>
		
	</div>
	
</div>