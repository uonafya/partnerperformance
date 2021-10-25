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
                    <th>PrEP New</th>
                </tr>
            </thead>
            <tbody>
            @foreach($rows as $key => $row)
                <tr>
                    <td> {{ $key+1 }} </td>
                    <td> {{ $row->name ?? '' }} </td>
                    <td> {{ number_format($row->prep_new) ?? '' }} </td>
                </tr>
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
