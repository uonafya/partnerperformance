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
            }]
        },
        tooltip: {
            valueSuffix: ""
        },
        legend: {
            /*layout: 'vertical',
            align: 'right',
            verticalAlign: 'bottom',
            floating: false,
            borderWidth: 0*/
            
            layout: 'horizontal',
            align: 'right',
            x: -100,
            verticalAlign: 'bottom',
            y: -25,
            floating: false,
            backgroundColor: '#FFFFFF'
        },
        series: {!! json_encode($outcomes) !!}
            
    });
  

 
</script>