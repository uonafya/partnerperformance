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
          <br />
          Upload Early Warning Indicators Excel
		    </div>
			<div class="panel-body" id="user_guide">
				<form action="{{ url('indicators/upload') }}" method="post" class="form-horizontal" enctype="multipart/form-data"> 
					@csrf

          <div class="form-group">
              <label class="col-sm-5 control-label">Upload Early Warning Indicators File (Monthly Data, Not Cumulative)</label>
              <div class="col-sm-7">
                  <input class="form-control" name="upload" id="upload" type="file" accept=".xlsx, .xls, .csv" />
              </div>
          </div>

          <div class="col-sm-6 col-sm-offset-6">
              <button class="btn btn-success" type="submit" >Submit</button>
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

        $(".form-horizontal").validate({
            errorPlacement: function (error, element)
            {
            element.before(error);
            }
            {{ $val_rules ?? '' }}
        });
    });

</script>

@endsection


