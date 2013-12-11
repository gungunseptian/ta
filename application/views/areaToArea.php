<style>
.table_list{
	border-collapse: collapse;
	margin: 5px;
	padding: 15px;
}

.table_list tr,td, th{
	padding: 5px;
	text-align: center;
}
</style>
<?php
if($query)
{
	echo "<h1>Perjalanan Dari ".$area_name_from." Ke ".$area_name_to."</h1>
	<table border='1' class='table_list'>
		<tr>
			<th>Tanggal Keberangkatan</th><th>Harga</th><th>Maskapai</th><th>Bandara Keberangkatan</th><th>Bandara Kedatangan</th><th></th>
		</tr>";

	if($query->num_rows() > 0)
	{
		foreach($query->result() as $row)
		{
			echo "<tr>
			<td>".date('d M Y',strtotime($row->departure_date))."</td>
			<td>Rp ".number_format($row->best_price)."</td>
			<td>".$row->airline_name."</td>
			<td>".$row->area_name_from."</td>
			<td>".$row->area_name_to."</td>
			<td><a target='_blank' href='https://www.dv1.pegipegi.com/tiket-pesawat/userweb/FlightController/search?flight_type=oneway&from_airport=".$row->departure_airport_cd."&to_airport=".$row->arrival_airport_cd."&departure_date=".$row->departure_date."&adult_count=1&child_count=0&infant_count=0&selected_out=RI_1861'><input type='button' value='Pesan'></a></td>
			</tr>";
		}
	}else{
		echo "<tr><td colspan=5 align=center> <font color='red'>Data belum tersedia</font></td></tr>";
	}
}
else
{
	show_404();
}

?>
</table>