<div class="col-md-12">
    
    <div class="table-responsive">
        <table id="{{ $div_id }}"  cellspacing="1" cellpadding="3" class="tablehead table table-striped table-bordered">
            <thead>
                <?php $sessions = session('filter_partner');?>
                <tr class="colhead">
                    <th>#</th>
                    @if (session('filter_partner') == null)
                     <th>Partner</th>
                    @else
                     <th>County</th>
                    @endif
                    <th>VMMC Circ Results</th>
                    <th><center>% Achivement (VMMC_Circ vs Target)</center></th>
                    <th>Contribution</th>
                </tr>
            </thead>
            <tbody id="vxvq">
            </tbody>
        </table>
    </div>
</div>


<script type="text/javascript" charset="utf-8">
    $(document).ready(function() {

        var targets = {!! json_encode($target->toArray()) !!};
        var rows = {!! json_encode($rows->toArray()) !!};
        var divisor = {!! json_encode($divisor) !!};
        console.log(targets)
        var div_id = {!! json_encode($div_id) !!};

        $('#div_id').DataTable({
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


        var row_html = ''
        var session = '<?php echo $sessions;?>';
        var row_total_array = []
        rows.forEach((row1,index)=>{
			row_total_array.push(parseInt(row1.vmmc_circ))
		})
		function getSum(total, num) {
  				return total + Math.round(num);
		}
        var total_array = row_total_array.filter(function (value) {
            return !Number.isNaN(value);
            });
		var total_results = total_array.reduce(getSum, 0);
        rows.forEach((row,index) => {
            if(parseInt(row.vmmc_circ) > 0){
                if(session == ''){
                        var target = targets.find(tgt=>tgt.div_id==row.div_id)
                        
                    }else{
                        var target = targets.find(tgt=>tgt.county_id==row.div_id)   
                    }    
                    var gap = parseInt(target?.val / divisor || 0)-parseInt(row.vmmc_circ) || 0 
                    var gap_final = gap < 0 ? 0 : gap
                    var achieved = parseFloat((parseInt(row.vmmc_circ)/parseInt(target?.val / divisor ))*100)
                    achieved = achieved >= 0 ? achieved.toFixed(1):0
                    var contribiutions = parseFloat((parseInt(row.vmmc_circ)/parseInt(total_results))*100)
 
                    contribiutions = contribiutions ? contribiutions.toFixed(1):0
                    achieved = achieved +"%"
                    contribiutions = contribiutions+"%"
                    row_html += '<tr>'
                    row_html += '<td>' + (index+1) + '</td>'
                    row_html += '<td>'+ row.name + '</td>'
                    row_html += '<td>' + row.vmmc_circ + '</td>'
                    row_html += '<td><center>' +achieved + '</center></td>'
                    row_html += '<td>'+ contribiutions + '</td>'
                    row_html += '</tr>'
                }
        });
        $('#vxvq').html(row_html);
        
    });

</script>
