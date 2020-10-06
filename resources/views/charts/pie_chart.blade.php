<div id="{{$div}}"></div>

@if(isset($paragraph))
	{!! $paragraph !!}
@endif


<script type="text/javascript">
	$().ready(function(){

		@isset($logs)
			console.log({!! json_encode($logs) !!});
		@endisset

		$('#{{$div}}').highcharts({
			chart: {
				plotBackgroundColor: null,
				plotBorderWidth: null,
				plotShadow: false,
				type: 'pie'
			},
			title: {
			    text: "{{ $chart_title ?? '' }}"
			},
			tooltip: {
			    pointFormat: '{series.name}:  <b> {point.y} ({point.percentage:.1f}%)</b>'
			},
			plotOptions: {
			    pie: {
			        allowPointSelect: true,
			        cursor: 'pointer',
			        dataLabels: {
			            enabled: true,
			            distance: -50,
			            format: '<b>{point.name}</b>: {point.y} ({point.percentage:.1f}%)',
			            style: {
			                color: (Highcharts.theme && Highcharts.theme.contrastTextColor) || 'black'
			            }
			        },
			        showInLegend: true
			    }
			},
            colors: [
                '#F2784B',
                '#1BA39C',
                '#913D88'
            ],     
            series: [{!! json_encode($outcomes) !!}]

		});
    });
</script>
