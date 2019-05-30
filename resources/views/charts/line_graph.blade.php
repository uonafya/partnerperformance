<div id="{{$div}}"></div>

<script type="text/javascript">

  
    $("#{{$div}}").highcharts({
        title: {
            text: "",
            x: -20
        },
        chart: {
            zoomType: 'xy'
        },
        @isset($stacking)
            plotOptions: {
                column: {
                    stacking: 'normal'
                }
            },
        @endisset
        xAxis: {
            categories: {!! json_encode($categories) !!}
        },
        yAxis: {
            title: {
                text: "{{ $yAxis ?? '' }} "
            },
            plotLines: [{
                value: 0,
                width: 1,
                color: '#808080'
            }],
            labels: {
                formatter: function() {
                    return this.value + "{{ $suffix ?? '%' }}";
                },
                style: {
                    
                }
            },
        },
        tooltip: {
            valueSuffix: "{{ $suffix ?? '%' }}",
            valuePrefix: "{{ $prefix ?? '' }}"
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
        },
        series: {!! json_encode($outcomes) !!}
            
    });
  

 
</script>