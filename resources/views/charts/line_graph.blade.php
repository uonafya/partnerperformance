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
        xAxis: {
            categories: {!! json_encode($categories) !!}
        },
        yAxis: {
            title: {
                text: "{{ $ytitle ?? '' }} "
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
            // x: -100,
            verticalAlign: 'bottom',
            // y: -25,
            floating: false,
            width: $(window).width() - 20,
            backgroundColor: '#FFFFFF'
        },
        series: {!! json_encode($outcomes) !!}
            
    });
  

 
</script>