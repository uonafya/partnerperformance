<div class="table-responsive">
    <table id="{{ $div }}"  cellspacing="1" cellpadding="3" class="tablehead table table-striped table-bordered">
        <thead>
        <tr class="colhead">
            <th>County</th>
            <th>PrEP New</th>
        </tr>
        </thead>
        <tbody>
        </tbody>
    </table>
</div>


<script type="text/javascript" charset="utf-8">
    $(document).ready(function() {

        $('#{{ $div }}').DataTable({
            dom: '<"btn"B>lTfgtip',
            // responsive: true,
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
