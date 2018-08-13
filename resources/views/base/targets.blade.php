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
				<form action="{{ secure_url('target') }}" method="post" class="form-horizontal"> 

					<div class="form-group">
					  <label class="col-sm-4 control-label">Financial Year 
					    <strong><div style='color: #ff0000; display: inline;'>*</div></strong>
					  </label>
					  <div class="col-sm-8">
					    <select class="form-control requirable" required name="financial_year" id="financial_year">
					    	<option> Select One </option>
					    	<option value="2017"> 2017 </option>
					    	<option value="2018"> 2018 </option>
					    </select>
					  </div>
					</div>


					<div class="form-group">
					  <label class="col-sm-4 control-label">Facility 
					    <strong><div style='color: #ff0000; display: inline;'>*</div></strong>
					  </label>
					  <div class="col-sm-8">
					    <select class="form-control requirable" required name="facility_id" id="facility_id">
					    	<option> Select One </option>
					    	@foreach($facilities as $facility)
					    		<option value="{{ $facility->id }}"> {{ $facility->name }} </option>
					    	@endforeach

					    </select>
					  </div>
					</div>


                    <div class="form-group">
                        <label class="col-sm-4 control-label">Viremia Beneficiaries</label>
                        <div class="col-sm-8">
                            <input class="form-control" name="viremia_beneficiaries" id="viremia_beneficiaries" number="number"  type="text" >
                        </div>
                    </div>


                    <div class="form-group">
                        <label class="col-sm-4 control-label">Viremia Target</label>
                        <div class="col-sm-8">
                            <input class="form-control" name="viremia_target" id="viremia_target" number="number" type="text" >
                        </div>
                    </div>


                    <div class="form-group">
                        <label class="col-sm-4 control-label">DSD Beneficiaries</label>
                        <div class="col-sm-8">
                            <input class="form-control" name="dsd_beneficiaries" id="dsd_beneficiaries" number="number"  type="text" >
                        </div>
                    </div>


                    <div class="form-group">
                        <label class="col-sm-4 control-label">DSD Target</label>
                        <div class="col-sm-8">
                            <input class="form-control" name="dsd_target" id="dsd_target" number="number" type="text" >
                        </div>
                    </div>


                    <div class="form-group">
                        <label class="col-sm-4 control-label">DSD Beneficiaries</label>
                        <div class="col-sm-8">
                            <input class="form-control" name="dsd_beneficiaries" id="dsd_beneficiaries" number="number"  type="text" >
                        </div>
                    </div>


                    <div class="form-group">
                        <label class="col-sm-4 control-label">DSD Target</label>
                        <div class="col-sm-8">
                            <input class="form-control" name="dsd_target" id="dsd_target" number="number" type="text" >
                        </div>
                    </div>


                    <div class="form-group">
                        <label class="col-sm-4 control-label">OTZ Beneficiaries</label>
                        <div class="col-sm-8">
                            <input class="form-control" name="otz_beneficiaries" id="otz_beneficiaries" number="number"  type="text" >
                        </div>
                    </div>


                    <div class="form-group">
                        <label class="col-sm-4 control-label">OTZ Target</label>
                        <div class="col-sm-8">
                            <input class="form-control" name="otz_target" id="otz_target" number="number" type="text" >
                        </div>
                    </div>


                    <div class="form-group">
                        <label class="col-sm-4 control-label">Men Clinic Beneficiaries</label>
                        <div class="col-sm-8">
                            <input class="form-control" name="men_clinic_beneficiaries" id="men_clinic_beneficiaries" number="number"  type="text" >
                        </div>
                    </div>


                    <div class="form-group">
                        <label class="col-sm-4 control-label">Men Clinic Target</label>
                        <div class="col-sm-8">
                            <input class="form-control" name="men_clinic_target" id="men_clinic_target" number="number" type="text" >
                        </div>
                    </div>



				{{ Form::close() }}

			</div>
		</div>
	</div>
</div>

@endsection


@section('scripts')

<script src="{{ secure_asset('js/validate/jquery.validate.min.js') }}"></script>
<script type="text/javascript">

    function get_values(facility_id, $year){    

        $.ajax({
           type: "POST",
           data: {
			year : year,
            facility_id : facility_id
           },
           url: "{{ url('/sample/new_patient') }}",
           success: function(data){
           		// $("#patient_name").val(patient.patient_name);
           }
        });
    }

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


