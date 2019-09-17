<div class="table-reponsive">
	<div style="display: none;"> {{ print_r($query_log ?? []) }} </div>
	<table id="{{ $div }}"  cellspacing="1" cellpadding="3" class="tablehead table table-striped table-bordered">
		<thead>
			<tr class="colhead">
				<th rowspan="3">No</th>
				@include('partials.columns', ['cols' => 'rowspan=3'])
				<th colspan="6"><center>All Ages</center></th>
				<th colspan="6"><center>Below 1</center></th>
				<th colspan="6"><center>1-4</center></th>
				<th colspan="6"><center>5-9</center></th>
				<th colspan="6"><center>10-14</center></th>
				<th colspan="6"><center>15-19</center></th>
				<th colspan="6"><center>20-24</center></th>
				<th colspan="6"><center>25-29</center></th>
				<th colspan="6"><center>30-34</center></th>
				<th colspan="6"><center>35-39</center></th>
				<th colspan="6"><center>40-44</center></th>
				<th colspan="6"><center>45-49</center></th>
				<th colspan="6"><center>50 and Above</center></th>				
			</tr>
			<tr>
				<th colspan="3"><center>Suppressed</center></th> <th colspan="3"><center>Non Suppressed</center></th>
				<th colspan="3"><center>Suppressed</center></th> <th colspan="3"><center>Non Suppressed</center></th>
				<th colspan="3"><center>Suppressed</center></th> <th colspan="3"><center>Non Suppressed</center></th>
				<th colspan="3"><center>Suppressed</center></th> <th colspan="3"><center>Non Suppressed</center></th>
				<th colspan="3"><center>Suppressed</center></th> <th colspan="3"><center>Non Suppressed</center></th>
				<th colspan="3"><center>Suppressed</center></th> <th colspan="3"><center>Non Suppressed</center></th>
				<th colspan="3"><center>Suppressed</center></th> <th colspan="3"><center>Non Suppressed</center></th>
				<th colspan="3"><center>Suppressed</center></th> <th colspan="3"><center>Non Suppressed</center></th>
				<th colspan="3"><center>Suppressed</center></th> <th colspan="3"><center>Non Suppressed</center></th>
				<th colspan="3"><center>Suppressed</center></th> <th colspan="3"><center>Non Suppressed</center></th>
				<th colspan="3"><center>Suppressed</center></th> <th colspan="3"><center>Non Suppressed</center></th>
				<th colspan="3"><center>Suppressed</center></th> <th colspan="3"><center>Non Suppressed</center></th>
				<th colspan="3"><center>Suppressed</center></th> <th colspan="3"><center>Non Suppressed</center></th>
			</tr>
			<tr>
				<th>Male</th> <th>Female</th> <th>Unknown</th> <th>Male</th> <th>Female</th> <th>Unknown</th>
				<th>Male</th> <th>Female</th> <th>Unknown</th> <th>Male</th> <th>Female</th> <th>Unknown</th>
				<th>Male</th> <th>Female</th> <th>Unknown</th> <th>Male</th> <th>Female</th> <th>Unknown</th>
				<th>Male</th> <th>Female</th> <th>Unknown</th> <th>Male</th> <th>Female</th> <th>Unknown</th>
				<th>Male</th> <th>Female</th> <th>Unknown</th> <th>Male</th> <th>Female</th> <th>Unknown</th>
				<th>Male</th> <th>Female</th> <th>Unknown</th> <th>Male</th> <th>Female</th> <th>Unknown</th>
				<th>Male</th> <th>Female</th> <th>Unknown</th> <th>Male</th> <th>Female</th> <th>Unknown</th>
				<th>Male</th> <th>Female</th> <th>Unknown</th> <th>Male</th> <th>Female</th> <th>Unknown</th>
				<th>Male</th> <th>Female</th> <th>Unknown</th> <th>Male</th> <th>Female</th> <th>Unknown</th>
				<th>Male</th> <th>Female</th> <th>Unknown</th> <th>Male</th> <th>Female</th> <th>Unknown</th>
				<th>Male</th> <th>Female</th> <th>Unknown</th> <th>Male</th> <th>Female</th> <th>Unknown</th>
				<th>Male</th> <th>Female</th> <th>Unknown</th> <th>Male</th> <th>Female</th> <th>Unknown</th>
				<th>Male</th> <th>Female</th> <th>Unknown</th> <th>Male</th> <th>Female</th> <th>Unknown</th>
			</tr>
		</thead>
		<tbody>
			@foreach($rows as $key => $row)
				<tr>
					<td> {{ $key+1 }} </td>

					@include('partials.rows', ['row' => $row])					

					<td> {{ number_format( $row->total_m_sup ) }} </td>
					<td> {{ number_format( $row->total_f_sup ) }} </td>
					<td> {{ number_format( $row->total_u_sup ) }} </td>
					<td> {{ number_format( $row->total_m_nonsup ) }} </td>
					<td> {{ number_format( $row->total_f_nonsup ) }} </td>
					<td> {{ number_format( $row->total_u_nonsup ) }} </td>
					  
					<td> {{ number_format( $row->below1_m_sup ) }} </td>
					<td> {{ number_format( $row->below1_f_sup ) }} </td>
					<td> {{ number_format( $row->below1_u_sup ) }} </td>
					<td> {{ number_format( $row->below1_m_nonsup ) }} </td>
					<td> {{ number_format( $row->below1_f_nonsup ) }} </td>
					<td> {{ number_format( $row->below1_u_nonsup ) }} </td>

					<td> {{ number_format( $row->below5_m_sup ) }} </td>
					<td> {{ number_format( $row->below5_f_sup ) }} </td>
					<td> {{ number_format( $row->below5_u_sup ) }} </td>
					<td> {{ number_format( $row->below5_m_nonsup ) }} </td>
					<td> {{ number_format( $row->below5_f_nonsup ) }} </td>
					<td> {{ number_format( $row->below5_u_nonsup ) }} </td>

					<td> {{ number_format( $row->below10_m_sup ) }} </td>
					<td> {{ number_format( $row->below10_f_sup ) }} </td>
					<td> {{ number_format( $row->below10_u_sup ) }} </td>
					<td> {{ number_format( $row->below10_m_nonsup ) }} </td>
					<td> {{ number_format( $row->below10_f_nonsup ) }} </td>
					<td> {{ number_format( $row->below10_u_nonsup ) }} </td>

					<td> {{ number_format( $row->below15_m_sup ) }} </td>
					<td> {{ number_format( $row->below15_f_sup ) }} </td>
					<td> {{ number_format( $row->below15_u_sup ) }} </td>
					<td> {{ number_format( $row->below15_m_nonsup ) }} </td>
					<td> {{ number_format( $row->below15_f_nonsup ) }} </td>
					<td> {{ number_format( $row->below15_u_nonsup ) }} </td>

					<td> {{ number_format( $row->below20_m_sup ) }} </td>
					<td> {{ number_format( $row->below20_f_sup ) }} </td>
					<td> {{ number_format( $row->below20_u_sup ) }} </td>
					<td> {{ number_format( $row->below20_m_nonsup ) }} </td>
					<td> {{ number_format( $row->below20_f_nonsup ) }} </td>
					<td> {{ number_format( $row->below20_u_nonsup ) }} </td>

					<td> {{ number_format( $row->below25_m_sup ) }} </td>
					<td> {{ number_format( $row->below25_f_sup ) }} </td>
					<td> {{ number_format( $row->below25_u_sup ) }} </td>
					<td> {{ number_format( $row->below25_m_nonsup ) }} </td>
					<td> {{ number_format( $row->below25_f_nonsup ) }} </td>
					<td> {{ number_format( $row->below25_u_nonsup ) }} </td>

					<td> {{ number_format( $row->below30_m_sup ) }} </td>
					<td> {{ number_format( $row->below30_f_sup ) }} </td>
					<td> {{ number_format( $row->below30_u_sup ) }} </td>
					<td> {{ number_format( $row->below30_m_nonsup ) }} </td>
					<td> {{ number_format( $row->below30_f_nonsup ) }} </td>
					<td> {{ number_format( $row->below30_u_nonsup ) }} </td>

					<td> {{ number_format( $row->below35_m_sup ) }} </td>
					<td> {{ number_format( $row->below35_f_sup ) }} </td>
					<td> {{ number_format( $row->below35_u_sup ) }} </td>
					<td> {{ number_format( $row->below35_m_nonsup ) }} </td>
					<td> {{ number_format( $row->below35_f_nonsup ) }} </td>
					<td> {{ number_format( $row->below35_u_nonsup ) }} </td>

					<td> {{ number_format( $row->below40_m_sup ) }} </td>
					<td> {{ number_format( $row->below40_f_sup ) }} </td>
					<td> {{ number_format( $row->below40_u_sup ) }} </td>
					<td> {{ number_format( $row->below40_m_nonsup ) }} </td>
					<td> {{ number_format( $row->below40_f_nonsup ) }} </td>
					<td> {{ number_format( $row->below40_u_nonsup ) }} </td>

					<td> {{ number_format( $row->below45_m_sup ) }} </td>
					<td> {{ number_format( $row->below45_f_sup ) }} </td>
					<td> {{ number_format( $row->below45_u_sup ) }} </td>
					<td> {{ number_format( $row->below45_m_nonsup ) }} </td>
					<td> {{ number_format( $row->below45_f_nonsup ) }} </td>
					<td> {{ number_format( $row->below45_u_nonsup ) }} </td>

					<td> {{ number_format( $row->below50_m_sup ) }} </td>
					<td> {{ number_format( $row->below50_f_sup ) }} </td>
					<td> {{ number_format( $row->below50_u_sup ) }} </td>
					<td> {{ number_format( $row->below50_m_nonsup ) }} </td>
					<td> {{ number_format( $row->below50_f_nonsup ) }} </td>
					<td> {{ number_format( $row->below50_u_nonsup ) }} </td>

					<td> {{ number_format( $row->above50_m_sup ) }} </td>
					<td> {{ number_format( $row->above50_f_sup ) }} </td>
					<td> {{ number_format( $row->above50_u_sup ) }} </td>
					<td> {{ number_format( $row->above50_m_nonsup ) }} </td>
					<td> {{ number_format( $row->above50_f_nonsup ) }} </td>
					<td> {{ number_format( $row->above50_u_nonsup ) }} </td>
				</tr>
			@endforeach
		</tbody>	
	</table>
</div>

@include('partials.table_footer', ['div' => $div])
