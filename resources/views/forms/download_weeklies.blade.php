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
          Download {{ strtoupper(str_replace('_', ' ', $modality)) }} Excel
		    </div>
			<div class="panel-body" id="user_guide">
				<form action="{{ url('download/weekly') }}" method="post" class="form-horizontal"> 
					@csrf


          <p style="font-size: 16;">
            After downloading, do not delete any column. They are necessary for the system when you upload the excel. You can delete any row other that the first row that is used as column headers.
          </p>

          <input name="modality" type="hidden" value="{{ $modality }} " />

          <div class="form-group">
              <label class="col-sm-3 control-label">Week</label>
              <select class="col-sm-7 select_tag" name="week_id">
                <option></option>
                @foreach($weeks as $week)
                  <option value="{{ $week->id }}"> {{ $week->name }} </option>
                @endforeach
              </select>
          </div>

          @if($modality != 'vmmc_circ')

            <div class="form-group">
                <label class="col-sm-3 control-label">Gender (Optional)</label>
                <select class="col-sm-7 select_tag" name="gender_id">
                  <option></option>
                  @foreach($genders as $gender)
                    <option value="{{ $gender->id }}"> {{ $gender->gender }} </option>
                  @endforeach
                </select>
            </div>

          @endif

          <div class="form-group">
              <label class="col-sm-3 control-label">Age Category (Optional)</label>
              <select class="col-sm-7 select_tag" name="age_category_id">
                <option></option>
                  @foreach($age_categories as $key => $age_category)
                    <option value="{{ $age_category->id }}"> {{ $age_category->age_category }} </option>
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


