@extends('layouts.master')

@section('content')

<div class="row">
	<div class="col-md-12 col-sm-12 col-xs-12">
		<div class="panel panel-default">
		    <div class="panel-heading">
			    {{ $partner->name ?? '' }} 
          <br />
          Download Cervical Cancer Excel
		    </div>
			<div class="panel-body" id="user_guide">
				<form action="{{ url('download/cervical-cancer') }}" method="post" class="form-horizontal"> 
					@csrf

          <p style="font-size: 16;">
            After downloading, do not delete any column. They are necessary for the system when you upload the excel. You can delete any row other that the first row that is used as column headers.
          </p>

          <div class="form-group">
              <label class="col-sm-3 control-label">Month</label>
              <select class="col-sm-7 select_tag" required name="period_id">
                <option></option>
                @foreach($periods as $period)
                  <option value="{{ $period->id }} "> FY {{ $period->yr }} Month {{ $period->month_name }} </option>
                @endforeach
              </select>
          </div>

          <div class="form-group">
              <label class="col-sm-3 control-label">Modality</label>
              <select class="col-sm-7 select_tag" name="modalities[]" multiple="multiple">
                <option></option>
                @foreach($modalities as $modality)
                  <optgroup label="{{ $modality->modality_name }}">
                    @foreach($modality->submodalities as $submodality)
                      <option value="{{ $submodality->id }} "> {{ $submodality->modality_name }} </option>
                    @endforeach
                  </optgroup>
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


