<div id="{{$div}}"></div>

<script type="text/javascript">

  
    $("#{{$div}}").highcharts({
        title: {
            text: "",
            x: -20
        },
        xAxis: {
            categories: {!! json_encode($categories) !!}
        },
        yAxis: {
            title: {
                text: "Totals"
            },
            plotLines: [{
                value: 0,
                width: 1,
                color: '#808080'
            }]
        },
        tooltip: {
            valueSuffix: "",

        },
        legend: {
            layout: 'vertical',
            align: 'right',
            verticalAlign: 'bottom',
            floating: false,
            borderWidth: 0
        },
        series: {!! json_encode($outcomes) !!}
            
    });
  

 
</script>