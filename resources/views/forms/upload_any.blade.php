@extends('layouts.master')

@section('css_scripts')
  <link rel="stylesheet" href="https://unpkg.com/dropzone@5/dist/min/dropzone.min.css" type="text/css" />
@endsection

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
  {{--
	<div class="col-md-12 col-sm-12 col-xs-12">
		<div class="panel panel-default">
		    <div class="panel-heading">
			    {{ $partner->name ?? '' }} 
          <br />
          Upload {{ strtoupper(str_replace('_', ' ', $path)) }} Excel
		    </div>
			<div class="panel-body" id="user_guide">
				<!-- <form action="{{ url($path . '/upload') }}" method="post" class="form-horizontal" enctype="multipart/form-data">  -->
        <form action="{{ url('upload/' . $path) }}" method="post" class="form-horizontal" enctype="multipart/form-data"> 
					@csrf

          @if($modality)
            <input name="modality" type="hidden" value="{{ $modality }} " />
          @endif


          <p style="font-size: 16;">
            If you are getting a <br />
            <b>413 Request Entity Too Large</b> Error  <br />

            try saving the excel file as a csv and then try again.
          </p>

          <div class="form-group">
              <label class="col-sm-5 control-label">Upload {{ strtoupper(str_replace('_', ' ', $path)) }} Data
                @if($path == 'indicators')
                   (Monthly Data, Not Cumulative)
                @endif
              </label>
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
  --}}

  
  <div class="col-md-12 col-sm-12 col-xs-12">
    <div class="alert alert-info">
      <strong>N/B:</strong> Updated records will reflect once the upload is completely processed.
    </div>
    <br />
    <div class="panel panel-default">
      <div class="panel-heading">
          {{ $partner->name ?? '' }} 
          <br />
          Upload {{ strtoupper(str_replace('_', ' ', $path)) }} Excel
      </div>
      <div class="panel-body">
        <div class="col-md-4">
          <p style="font-size: 16;">
            If you are getting a <br />
            <b>413 Request Entity Too Large</b> Error  <br />

            try saving the excel file as a csv and then try again.
          </p>
        </div>
        <div class="col-md-8">
          <form action="{{ url('upload/' . $path) }}" enctype="multipart/form-data">
              @if($modality)
                <input name="modality" id="modality" type="hidden" value="{{ $modality }} " />
              @endif
              <div class="dropzone" id="file-upload"></div>
              <div class="col-sm-6 col-sm-offset-6">
                <br/>
                <button class="btn btn-success" id="submit-upload-file" type="submit" >Submit</button>
              </div>
          </form>
        </div>
      </div>
    </div>
  </div>
</div>

@endsection


@section('scripts')

<script src="{{ secure_asset('js/validate/jquery.validate.min.js') }}"></script>
<script src="https://unpkg.com/dropzone@5/dist/min/dropzone.min.js"></script>

<script type="text/javascript">

    function reload_page(){}

    $(document).ready(function(){
        $(".form-control").attr('autocomplete', 'off');

        $(".form-horizontal select").select2();

        // $("#my-awesome-dropzone").dropzone({
        //   url: "{{ url('upload/' . $path) }}",
        //   uploadMultiple: false,
        //   paramName: "upload",
        //   acceptedFiles: ".xlsx, .xls, .csv",
        //   addRemoveLinks: true,
        // });

        $(".form-horizontal").validate({
            errorPlacement: function (error, element)
            {
            element.before(error);
            }
            {{ $val_rules ?? '' }}
        });
    });

    Dropzone.options.fileUpload = {
      url: "{{ url('upload/' . $path) }}",
      maxFilesize: 10,
      autoProcessQueue: false,
      uploadMultiple: false,
      paramName: "upload",
      acceptedFiles: ".xlsx,.xls,.csv",
      addRemoveLinks: true,
      dictRemoveFileConfirmation: "Are you Sure?", // ask before removing file
      // Language Strings
      dictFileTooBig: "File is to big (10 mb). Max allowed file size is 10 mb",
      dictInvalidFileType: "Invalid File Type",
      dictCancelUpload: "Cancel",
      dictRemoveFile: "Remove",
      dictMaxFilesExceeded: "Only 1 file is allowed",
      dictDefaultMessage: "Click here to upload or drag the {{ strtoupper(str_replace('_', ' ', $path)) }} Excel",
      init: function() {
          dzClosure = this; // Makes sure that 'this' is understood inside the functions below.

          // for Dropzone to process the queue (instead of default form behavior):
          document.getElementById("submit-upload-file").addEventListener("click", function(e) {
              // Make sure that the form isn't actually being sent.
              e.preventDefault();
              e.stopPropagation();
              dzClosure.processQueue();
          });

          //send all the form data along with the files:
          this.on("sending", function(data, xhr, formData) {
            @if($modality)
              formData.append("modality", jQuery("#modality").val());
            @endif
          });
          this.on("error", function(file, response) {
              console.log(response);
          });
      }
  };
</script>

@endsection


