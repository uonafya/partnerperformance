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
          Download GBV Quarterly Report
		    </div>
			<div class="panel-body" id="user_guide">
				<form action="{{ url('download/quarterly-gbv') }}" method="post" class="form-horizontal"> 
					@csrf

          <div class="form-group">
              <label class="col-sm-3 control-label">Financial Year</label>
              <select class="col-sm-7 select_tag" name="financial_year">
                <option></option>
                @foreach($financial_years as $financial_year)
                  <option value="{{ $financial_year->financial_year }}" @if($loop->last) selected @endif>FY {{ $financial_year->yr }}</option>
                @endforeach
              </select>
          </div>

          <div class="form-group">
              <label class="col-sm-3 control-label">Quarter</label>
              <select class="col-sm-7 select_tag" name="quarter">
                <option></option>
                <option value="1">Q1</option>
                <option value="2">Q2</option>
                <option value="3">Q3</option>
                <option value="4">Q4</option>
              </select>
          </div>

          <div class="form-group">
              <label class="col-sm-3 control-label">Months</label>
              <select class="col-sm-7 select_tag" name="periods[]" multiple>
                <option></option>
                @foreach($periods as $p)
                  <option value="{{ $p->id }}">{{ $p->full_name }}</option>
                @endforeach
              </select>
          </div>

          <div class="form-group">
              <label class="col-sm-3 control-label">Partner</label>
              <select class="col-sm-7 select_tag" name="partners[]" multiple>
                <option></option>
                @foreach($partners as $p)
                  <option value="{{ $p->id }}">{{ $p->name }}</option>
                @endforeach
              </select>
          </div>

          <div class="form-group">
              <label class="col-sm-3 control-label">Modality</label>
              <select class="col-sm-7 select_tag" name="modalities[]" multiple>
                <option></option>
                @foreach($modalities as $modality)
                  <option value="{{ $modality->id }}">{{ $modality->modality_name }}</option>
                @endforeach
              </select>
          </div>

          <div class="form-group">
              <label class="col-sm-3 control-label">Age</label>
              <select class="col-sm-7 select_tag" name="ages[]" multiple>
                <option></option>
                @foreach($ages as $age)
                  <option value="{{ $age->id }}">{{ $age->age_name }}</option>
                @endforeach
              </select>
          </div>

          <div class="form-group">
              <label class="col-sm-3 control-label">Gender</label>
              <select class="col-sm-7 select_tag" name="gender">
                <option></option>
                @foreach($genders as $gender)
                  <option value="{{ $gender->id }}">{{ $gender->gender }}</option>
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


