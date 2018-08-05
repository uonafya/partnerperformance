<?php
	if(!$target->current){
		$current_completion = 0;
	}else{
		$current_completion = round(($actual->current / $target->current * 100), 2);
	}

	if(!$target->new_art){
		$new_completion = 0;
	}else{
		$new_completion = round(($actual->new_art / $target->new_art * 100), 2);
	}

?>

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
				<td> {{ number_format($actual->current) }} </td>				
				<td> {{ number_format($current_completion) }} </td>				
			</tr>
			<tr>
				<td><b>New on tx</b></td>
				<td> {{ number_format($target->new_art) }} </td>				
				<td> {{ number_format($actual->new_art) }} </td>				
				<td> {{ number_format($new_completion) }} </td>				
			</tr>
		</table>
		
	</div>
	<div class="col-md-6">
		
	</div>
	
</div>