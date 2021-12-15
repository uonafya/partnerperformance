@extends('layouts.master')

@section('content')

<style type="text/css">
	.display_date {
		width: 130px;
		display: inline;
	}
</style>






<div class="row">
	<div class="col-md-12 col-sm-12 col-xs-12">
		<div class="panel panel-default">
		    <div class="panel-heading">
			    Cervical Cancer Screenings <div class="display_date"></div>
		    </div>
			<div class="panel-body" id="testing">
				<center><div class="loader"></div></center>
			</div>
		</div>
	</div>
</div>



@endsection


@section('scripts')

<script type="text/javascript">

	function reload_page()
	{
		$("#testing").html("<center><div class='loader'></div></center>");

		// $("#testing").load("{{ url('hfr/testing') }}");
        
		


		
	}
    Highcharts.chart('testing', {
            chart: {
            type: 'column'
                    },
                    title: {
                        text: ' Cervical Cancer Dashboard'
                    },
                    subtitle: {
                        text: ''
                    },
                    xAxis: {
                    categories: ['CXCA_SCRN D', 'CXCA_SCRN N', 'CXCA_SCRN_POS (CXCA_TX D)', 'CXCA_TX_N'],
                        title: {
                            text: null
                        }
                    },
                    yAxis: {
                        min: 0,
                        title: {
                            text: 'Population ',
                            align: 'middle'
                        },
                        labels: {
                            overflow: 'justify'
                        }
                    },
                    tooltip: {
                        valueSuffix: ' '
                    },
                    plotOptions: {
                        bar: {
                            dataLabels: {
                                enabled: true
                            }
                        }
                    },
                    legend: {
                        layout: 'vertical',
                        align: 'right',
                        verticalAlign: 'top',
                        x: -40,
                        y: 80,
                        floating: true,
                        borderWidth: 1,
                        backgroundColor:
                            Highcharts.defaultOptions.legend.backgroundColor || '#FFFFFF',
                        shadow: true
                    },
                    credits: {
                        enabled: false
                    },
                    series:             
                        [{
                            name: 'Number of women',
                            data: [133,123,178,199]  
                        }]
});     

        
    


</script>

@endsection


