<html>
<head>
<title><?=$title?></title>
<link type="text/css" rel="stylesheet" href="<?=base_url('themes/report/css/style.css');?>" />
</head>
<body>
 <table>
    <caption style="margin:10px 0;text-align:left">
    <h3><?=$title?></h3>
	<hr style="border:1px solid #333"/>
    <h3><?=$periode?></h3>
    </caption>
	<thead>
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
        foreach($users as $row):?>
			<tr>
				<td><?=$i;?></td>
				<td><?=$row['Name'];?></td>
				<td><?=$row['Description']?></td>
                <td><?='';?></td>
				<td class="center"><?=$this->authprocess->getWeekDuration(1,$row['ID'])?></td>
				<td class="center"><?=$this->authprocess->getWeekDuration(2,$row['ID'])?></td>
				<td class="center"><?=$this->authprocess->getWeekDuration(3,$row['ID'])?></td>
				<td class="center"><?=$this->authprocess->getWeekDuration(4,$row['ID'])?></td>
				<td class="center"><?=$this->authprocess->getWeekDuration(5,$row['ID'])?></td>
				<td></td>
				<td></td>
			</tr>
     <?php 
         $i++;
         endforeach;?>
    </tbody>
 </table>

</body>
</html>