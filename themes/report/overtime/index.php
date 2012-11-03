<html>
<head>
<title><?=$title?></title>
<link type="text/css" rel="stylesheet" href="<?=base_url('themes/report/css/style.css');?>" />
</head>
<body>
 <table>
    <caption>
    <h3><?=$title?></h3>
    <h5><?=$this->session->userdata('overtime_start').' s/d '.$this->session->userdata('overtime_finish')?></h5>
	<br/>
    </caption>
	<thead>
		<tr>
			<th class="mini" width="5%">No</th>
			<th class="mini">ID User</th>
            <th class="mini">Nama</th>
			<th class="mini">Tanggal</th>
            <th class="mini">Mulai</th>
			<th class="mini">Selesai</th>
			<th class="mini">Durasi</th>
            <th class="mini">Nominal</th>
            <th class="mini">Uang Makan</th>
            <th class="mini">Total</th>
		</tr>
    </thead>
	<tbody>
	<?php 
        $i=1;
        $grand_duration=0;
        $grand_total=0;
        $grand_meal=0;
        $grand_sub=0;
        foreach($checks as $row):?>
			<tr>
				<td class="mini" style="width:3%;"><?=$i;?></td>
				<td class="mini"><?=$row['UserID'];?></td>
                <td class="mini"><?=$row['Name'];?></td>
				<td class="mini"><?=$row['MyDate'];?></td>
                <td class="mini align-right"><?=$row['OvertimeStart'];?></td>
                <td class="mini align-right"><?=$row['OvertimeEnd'];?></td>
                <td class="mini align-right"><?=$duration=$row['OvertimeDuration'];?></td>
                <td class="mini align-right"><?=number_format($total=$row['OvertimeDuration']* $var,0);?></td>
                <td class="mini align-right"><?=number_format($meal = $row['OvertimeMeal'],0);?></td>
                <td class="mini align-right"><?=number_format($sub = $total + $row['OvertimeMeal'],0);?></td>
			</tr>
     <?php 
         $i++;
         $grand_duration=$grand_duration+$duration;
         $grand_total= $grand_total + $total;
         $grand_meal = $grand_meal + $meal;
         $grand_sub  = $grand_sub + $sub;
         endforeach;?>
    </tbody>
    <thead>
		<tr>
            <th class="mini align-right" colspan="6">Total</th>
            <th class="mini align-right"><?=number_format($grand_duration)?></th>
			<th class="mini align-right"><?=number_format($grand_total)?></th>
            <th class="mini align-right"><?=number_format($grand_meal)?></th>
            <th class="mini align-right"><?=number_format($grand_sub)?></th>
		</tr>
    </thead>
 </table>

</body>
</html>