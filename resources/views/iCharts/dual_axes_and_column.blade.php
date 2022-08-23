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
		    {{-- <div class="panel-heading">
			    HTS Testing & Yield <div class="display_date"></div> --}}

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
    title: {
                  text: "{{ $chart_title ?? '' }}"
    },
    xAxis: [{
        categories: {!! json_encode($categories ?? []) !!}
    }],
              yAxis: [{ // Primary yAxis
                  labels: {
                      formatter: function() {
                          return this.value + "{{ $suffix ?? '%' }}";
                      },
                      style: {
                          
                      }
                  },
                  title: {
                      text: "{{ $yAxis2 ?? 'Percentage' }} ",
                      style: {
                          color: '#89A54E'
                      }
                  },
                  opposite: true
      
              }, { // Secondary yAxis
                  gridLineWidth: 0,
                  title: {
                      text: "{{ $yAxis ?? 'Tests' }} ",
                      style: {
                          color: '#4572A7'
                      }
                  },
                  labels: {
                      formatter: function() {
                          return this.value  + "{{ $suffix2 ?? '' }}";
                      },
                      style: {
                          color: '#4572A7'
                      }
                  }
                  // min: 0, 
                  // max: 70000,
                  // tickInterval: 1
              }],tooltip: {
                  borderRadius: 2,
                  borderWidth: 1,
                  borderColor: '#999',
                  shadow: false,
                  shared: true,
                  useHTML: true,
                  yDecimals: 0,
                  valueDecimale: 0,
                  headerFormat: '<table class="tip"><caption>{point.key}</caption>'+'<tbody>',
                  pointFormat: '<tr><th style="color:{series.color}">{series.name}:</th>'+'<td style="text-align:right">{point.y}' 
                      @if(isset($extra_tooltip))
                          + '</td><td> {point.z}'
                      @endif
                      @if(isset($point_percentage))
                          + '</td><td> Contribution <b>({point.percentage:.1f}%)</b>'
                      @endif
  
                  + '</td></tr>',
                  footerFormat: '<tr><th>Total:</th>'+'<td style="text-align:right"><b>{point.total}</b>' 
                      @if(isset($extra_tooltip) || isset($point_percentage))
                          + '</td><td>'
                      @endif
                  +'</td></tr>'+'</tbody></table>'
              },
              legend: {
                layout: 'horizontal',
                align: 'left',
                x: 5,
                verticalAlign: 'bottom',
                y: 5,
                floating: false,
                width: $(window).width() - 20,
                backgroundColor: '#FFFFFF'
            },
    series: {!! json_encode($outcomes) !!}
  });
  
  </script>
  
@endsection