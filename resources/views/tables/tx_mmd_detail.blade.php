<div class="col-md-12">
    <div class="table-responsive">
        <table id="{{ $div }}"  cellspacing="1" cellpadding="3" class="tablehead table table-striped table-bordered">
            <thead>
                <tr class="colhead">
                    <th>#</th>
                    @if (session('filter_partner') == null)
                     <th>Partner</th>
                    @else
                     <th>County</th>
                    @endif
                    <th>TX Curr < 3 months</th>
                    <th>TX Curr 3 - 5 months</th>
                    <th>TX Curr 6+ months</th>
                </tr>
            </thead>
            <tbody>
                <?php
                    $i=0;
                    $calc_percentage = function($num, $onerow)
                    {
                        $total = 0;
                        $total = $onerow->less_3m + $onerow->less_5m + $onerow->above_6m;
                        if(!$total || $total == 0){
                            $val = $total;
                        }else{
                            $val = round(($num / $total * 100), 1) . "%";
                        }
                        return $val;
                    };
                    $x = (count($rows)-1);
                ?>
            @foreach($rows as $key => $row)
            @if($row->less_3m > 0)
                <tr>
                    <td> {{ $key+1 }} </td>
                    <td> 
                        <span>{{ $row->name}}</span>
                    </td>
                    <td> 
                        <span>{{ number_format($row->less_3m)}}</span> &nbsp;
                        <span>({{$calc_percentage($row->less_3m, $row) ?? '' }})</span>
                    </td>
                    <td> 
                        <span>{{ number_format($row->less_5m)}}</span> &nbsp;
                        <span>({{$calc_percentage($row->less_5m, $row) ?? '' }})</span>
                    </td>
                    <td> 
                        <span>{{ number_format($row->above_6m)}}</span> &nbsp;
                        <span>({{$calc_percentage($row->above_6m, $row) ?? '' }})</span>
                    </td>
                </tr>
                @endif
            @endforeach
            </tbody>
        </table>
    </div>
</div>


<script type="text/javascript" charset="utf-8">
    $(document).ready(function() {

        $('#{{ $div }}').DataTable({
            dom: '<"btn"B>lTfgtip',
            // responsive: true,
            paging:false,
            buttons : [
                {
                    text:  'Export to CSV',
                    extend: 'csvHtml5',
                    title: 'Download'
                },
                {
                    text:  'Export to Excel',
                    extend: 'excelHtml5',
                    title: 'Download'
                }
            ]
        });
    });

</script>
