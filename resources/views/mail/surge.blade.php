
<style type="text/css">
	p {
		font-size: 16px;
	}
</style>

<p>
	Dear {{ $user->name ?? '' }}, <br />
	The Surge Reporting on Partner Performance is ready. Please <a href="{{ url('/login') }}">login</a> to the system. On the second row of facilities, click on the surge with a downward-facing arrow, then you can set the facilities that are surge facilities <a href="{{ url('/surge/set_surge_facilities') }}">here.</a> Afterwards, download the surge template  <a href="{{ url('/surge/download') }}">here.</a> The options are to allow you to set which fields that you would like to upload.
</p>