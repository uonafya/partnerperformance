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
          Set Surge Facilities
		    </div>
			<div class="panel-body" id="user_guide">
				<form action="{{ url('surge/set_surge_facilities') }}" method="post" class="form-horizontal"> 
					@csrf

          
          <div class="form-group">
            <label class="col-sm-4 control-label">Surge Facilities</label>
            @foreach($facilities as $facility)
              <div>
                <label>
                  <input type="checkbox" name="facilities[]" value="{{ $facility->id }}" 
                    @if($facility->is_surge)
                      checked='true'
                    @endif
                  />
                  {{ $facility->facilitycode . ' ' . $facility->name }}
                </label>
              </div>
            @endforeach            
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

        $(".select_tag").select2();

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


