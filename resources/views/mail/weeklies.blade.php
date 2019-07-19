
<style type="text/css">
	p {
		font-size: 16px;
	}
</style>

<p>
	Dear {{ $user->name ?? '' }}, <br />
	There are new reports to be filled on Partner Performance, namely VMMC CIRC, PREP new, tx current, and multi month dispensing. Please <a href="{{ url('/login') }}">login</a> to the system. On the second row of links, click on <b>Download Other Templates</b> and then select the template you want to download. After filling the data, you can upload the template (links found in the upload templates tab).
</p>