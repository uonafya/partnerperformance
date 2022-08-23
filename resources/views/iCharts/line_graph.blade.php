@extends('layouts.master2')

@section('content')

<style type="text/css">
	.display_date {
		width: 130px;
		display: inline;
	}
	.detail_date {
		width: 130px;
		display: inline;
	}
</style>
    

<div class="row">
	<div class="col-md-12 col-sm-12 col-xs-12">
		<div class="panel panel-default">
		    <div class="panel-heading">
			    Tx Curr <div class="display_date"></div>

                <div class="row">
                    <div class="panel-heading">
                    <a href="javascript:downloadPDF()" class="button" id="cmd">Download PDF</a>
                    </div>		
                </div>

		    </div>
			<div class="panel-body" id="testing">
			<center>
                <div id="container"></div>
			</center>
			</div>
		</div>
	</div>

    
@endsection

@section("scripts")

<script>

  console.log({!!  json_encode($outcomes) !!});

  Highcharts.chart('container', {
    chart: {
            zoomType: 'xy'
        },
    plotOptions: {
        column: {
            @if(isset($stacking))
                stacking: 'normal',
            @elseif(isset($stacking_percent))
                stacking: 'percent',
            @endif
            dataLabels: {
                    enabled: true,
                },
            @if(isset($data_labels))  
                dataLabels: {
                    enabled: true,
                },
            @endif
        },
        spline: {
            @if(isset($data_labels))  
                dataLabels: {
                    enabled: true,
                },
            @endif
        },
    },
    xAxis: {
        categories: {!! json_encode($categories ?? []) !!}
    },
    yAxis: {
        title: {
            text: "{{ $yAxis ?? '' }}"
        },
        plotLines: [{
            value: 0,
            width: 1,
            color: '#808080'
        }],
        labels: {
            formatter: function() {
                return this.value + "{{ $suffix ?? '' }}";
            },
            style: {
                
            }
        },
        @if(isset($stack_labels))
            stackLabels: {
                enabled: true,
                /*rotation: -75,
                style: {
                    fontWeight: 'bold',
                    color: (Highcharts.theme && Highcharts.theme.textColor) || 'gray'
                },
                y:-20*/
            },
        @endif
    },
    tooltip: {
        valueSuffix: "{{ $suffix ?? '%' }}",
        valuePrefix: "{{ $prefix ?? '' }}",
        shared: true,
        useHTML: true,
        yDecimals: 0,
        valueDecimale: 0,
        headerFormat: '<table class="tip"><caption>{point.key}</caption>'+'<tbody>',
        pointFormat: '<tr><th style="color:{series.color}">{series.name}:</th>'+'<td style="text-align:right">{point.y}{{ $suffix ?? '' }} ' 
            @if(isset($extra_tooltip))
                + '</td><td>&nbsp;{point.z}'
            @endif
            @if(isset($point_percentage))
                + '</td><td>&nbsp; Contribution <b>({point.percentage:.1f}%)</b>'
            @endif

            + '</td></tr>',
        footerFormat: '<tr><th>Total:</th>'+'<td style="text-align:right"><b>{point.total}</b>' 
            @if(isset($extra_tooltip) || isset($point_percentage))
                + '</td><td>'
            @endif
        +'</td></tr>'+'</tbody></table>'
        // valueDecimals: 2
    },
    legend: {
        /*layout: 'vertical',
        align: 'right',
        verticalAlign: 'bottom',
        floating: false,
        borderWidth: 0*/
        
        layout: 'horizontal',
        align: 'left',
        x: 5,
        verticalAlign: 'bottom',
        y: 5,
        floating: false,
        width: $(window).width() - 20,
        backgroundColor: '#FFFFFF'
    }   
    series: {!! json_encode($outcomes) !!}

  });
  
  </script>
  
@endsection