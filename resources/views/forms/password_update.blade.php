@extends('layouts.master')

@section('content')

<style type="text/css">
	.display_date {
		width: 130px;
		display: inline;
	}
	.display_date {
		width: 130px;
		display: inline;
	}
</style>


<div class="row">
	<div class="col-md-12 col-sm-12 col-xs-12">
		<div class="panel panel-default">
		    <div class="panel-heading">
			    {{ $partner->name ?? '' }} 
		    </div>
			<div class="panel-body" id="user_guide">
				<form action="{{ url('user/' . $user->id) }}" method="post" class="form-horizontal" id="my_form"> 
					@csrf
                    @method('PUT')

                    <div class="form-group">
                        <label class="col-sm-4 control-label">Password</label>
                        <div class="col-sm-8">
                            <input class="form-control" required name="password" id="password" type="password" />
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="col-sm-4 control-label">Confirm Password</label>
                        <div class="col-sm-8">
                            <input class="form-control" id="confirm_password" name="confirm_password" type="password" />
                        </div>
                    </div>


                    <div class="col-sm-6 col-sm-offset-6">
                        <button class="btn btn-success" type="submit" value="release">Submit</button>
                    </div>
                </form>
			</div>
		</div>
	</div>
</div>

@endsection


@section('scripts')

<script src="{{ secure_asset('js/validate/jquery.validate.min.js') }}"></script>
<script type="text/javascript">


    function reload_page(){}

    $(document).ready(function(){
    	$(".form-control").attr('autocomplete', 'off');

    	$(".form-horizontal select").select2();

        $("#my_form").validate({
            errorPlacement: function (error, element)
            {
                element.before(error);
            },
            rules: {
                confirm_password: {
                    equalTo: "#password"
                }
            }
        });
    });

</script>

@endsection


