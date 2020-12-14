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
          Download HFR Submission
		    </div>
			<div class="panel-body" id="user_guide">
        <form action="{{ url('download/hfr') }}" method="post" class="form-horizontal"> 
					@csrf


          <div class="form-group">
              <label class="col-sm-3 control-label">Week</label>
              <select class="col-sm-7 select_tag" name="week_id" required>
                <option></option>
                @foreach($weeks as $w)
                  <option value="{{ $w->id }}">{{ $w->name }}</option>
                @endforeach
              </select>
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

        // $(".select_tag").select2();

        $(".form-horizontal select").select2({
            placeholder: "Select One",
            allowClear: true
        }); 

        // $(".form-horizontal select").select2();

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


