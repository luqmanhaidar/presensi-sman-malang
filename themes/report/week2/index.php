<html>
<head>
<title><?=$title?></title>
<link type="text/css" rel="stylesheet" href="<?=base_url('themes/report/css/style.css');?>" />
</head>
<body>
 <table style="margin:0 auto;width:80%;border-collapse:collapse;background:#ecf3eb">
    <caption>
    <h3><?=$title?></h3>
    <h5><?=indonesian_monthName($this->session->userdata('month_search')).' '.$this->session->userdata('year_search')?></h5>
    </caption>
	<thead>
		<tr>
			<th class="align-left darkgrey" colspan="4"><?=$user.'-'.$name?></th>
		</tr>
		<tr>
			<th rowspan="2">NO</th>
			<th rowspan="2" width="20%">NAMA</th>
			<th rowspan="2">STATUS <br/>KEPEG</th>
			<th rowspan="2">JAM KBM <br/>PER<br/>MINGGU</th>
			<th colspan="5">PEMENUHAN 37 1/2 JAM</th>
			<th rowspan="2">KETERANGAN TM</th>
			<th rowspan="2">KETERANGAN KETIDAK HADIRAN</th>
		</tr>
		<tr>
			<th rowspan="1">MINGGU<br/>KE 1</th>
			<th rowspan="1">MINGGU<br/>KE 2</th>
			<th rowspan="1">MINGGU<br/>KE 3</th>
			<th rowspan="1">MINGGU<br/>KE 4</th>
			<th rowspan="1">MINGGU<br/>KE 5</th>
		</tr>
		<tr>
		</tr>
		
		
		
    </thead>
	<tbody>
	<?php 
        $i=1;
        foreach($checks as $row):?>
			<tr>
				<td><?=$i;?></td>
				<td><?=indonesian_shortDate($row['TransactionTime']);?></td>
				<td><?=$row['FunctionKey']?> (<?=functionKey($row['FunctionKey']);?>)</td>
                <td><?=substr($row['TransactionTime'],11,8);?></td>
			</tr>
     <?php 
         $i++;
         endforeach;?>
    </tbody>
 </table>

</body>
</html>