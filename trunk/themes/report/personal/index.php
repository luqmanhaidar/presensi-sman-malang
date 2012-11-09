<html>
<head>
<title><?=$title?></title>
<link type="text/css" rel="stylesheet" href="<?=base_url('themes/report/css/style.css');?>" />
</head>
<body>
 <table style="margin:0 auto;width:80%;border-collapse:collapse;background:#ecf3eb">
    <!--<caption>-->
    <h3 class="center"><?=$title?></h3>
    <h5 class="center"><?=indonesian_monthName($this->session->userdata('month_search')).' '.$this->session->userdata('year_search')?></h5>
    <!--</caption>-->
	<thead>
		<tr>
			<th class="align-left darkgrey" colspan="4"><?=$user.'-'.$name?></th>
		</tr>
		<tr>
			<th>No</th>
			<th>Tanggal</th>
			<th>Jenis</th>
			<th>Waktu</th>
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