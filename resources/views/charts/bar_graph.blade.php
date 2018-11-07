<div id="{{$div}}"></div>

{!! $paragraph ?? '' !!}

<script type="text/javascript">
	
    $(function () {
        $('#{{$div}}').highcharts({
            plotOptions: {
                column: {
                    @if(isset($stacking_false))
                        stacking: false
                    @else
                        stacking: 'normal'
                    @endif
                }
            },
            title: {
                text: ''
            },
            chart: {
                zoomType: 'xy'
            },
            xAxis: [{
                categories: {!! json_encode($categories) !!}
            }],
            yAxis: {
                title: {
                    text: "{{ $yAxis ?? 'Totals' }}"
                }
            },

            tooltip: {
                borderRadius: 2,
                borderWidth: 1,
                borderColor: '#999',
                shadow: false,
                shared: true,
                useHTML: true,
                yDecimals: 0,
                valueDecimale: 0,
                headerFormat: '<table class="tip"><caption>{point.key}</caption>'+'<tbody>',
                pointFormat: '<tr><th style="color:{series.color}">{series.name}:</th>'+'<td style="text-align:right">{point.y}</td></tr>',
                footerFormat: '<tr><th>Total:</th>'+'<td style="text-align:right"><b>{point.total}</b></td></tr>'+'</tbody></table>'
            },
            legend: {
                layout: 'horizontal',
                align: 'right',
                x: -35,
                verticalAlign: 'bottom',
                y: 5,
                floating: false,
                backgroundColor: '#FFFFFF'
            },
            navigation: {
                buttonOptions: {
                    verticalAlign: 'bottom',
                    y: -20
                }
            },
            colors: [
                '#F2784B',
                '#1BA39C',
                '#913D88',
                '#4d79ff',
                '#80ff00',
                '#ff8000',
                '#00ffff',
                '#ff4000'
            ],     
            series: {!! json_encode($outcomes) !!}
        });
    });
</script>