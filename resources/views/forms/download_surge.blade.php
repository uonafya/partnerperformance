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
          Download Surge Excel
		    </div>
			<div class="panel-body" id="user_guide">
				<form action="{{ url('download/surge') }}" method="post" class="form-horizontal"> 
					@csrf

          <p style="font-size: 16;">
            After downloading, do not delete the first 6 columns. They are necessary for the system when you upload the excel. You can delete any of the other columns if you do not wish to upload its data. You can also delete any row other that the first row that is used as column headers.
          </p>

          <div class="form-group">
              <label class="col-sm-3 control-label">Week</label>
              <select class="col-sm-7 select_tag" name="week_id">
                <option></option>
                @foreach($weeks as $week)
                  <option value="{{ $week->id }}"> {{ $week->name }} </option>
                @endforeach
              </select>
          </div>

          <div class="form-group">
              <label class="col-sm-3 control-label">Modality (You can select multiple modalities) <b>(Required)</b> </label>
              <select class="col-sm-7 select_tag" name="modalities[]" multiple="multiple" required>
                <option></option>
                @foreach($modalities as $modality)
                  <option value="{{ $modality->id }}"> {{ $modality->modality_name }} </option>
                @endforeach
              </select>
          </div>

          <div class="form-group">
              <label class="col-sm-3 control-label">Gender</label>
              <select class="col-sm-7 select_tag" name="gender_id">
                <option></option>
                @foreach($genders as $gender)
                  <option value="{{ $gender->id }}"> {{ $gender->gender }} </option>
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


