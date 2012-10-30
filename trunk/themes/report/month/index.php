<html>
<head>
<title><?=$title?></title>
<link type="text/css" rel="stylesheet" href="<?=base_url('themes/report/css/style.css');?>" />
</head>
<body>
 <table  class="width140">
    <caption><h3><?=$title?> </h3></caption>
	<thead>		
		<tr>
			<th rowspan="2">No</th>
			<th rowspan="2" width="12%">Nama</th>
			<th rowspan="2">Paraf</th>
			<th colspan="31">Tanggal</th>
			<th rowspan="2">Keterangan</th>
		</tr>
		<tr>
			<?php for($i=1;$i<=31;$i++):?>
			<th><?=$i;?></th>
			<?php endfor;?>
		</tr>
    </thead>
	<tbody>
	<?php 
        $x=1;
        foreach($checks as $row):?>
			<tr>
				<td rowspan="2"><?=$x;?></td>
				<td rowspan="2"><?=$row['Name']?></td>
				<td>TTD</td>
				<?php for($i=1;$i<=31;$i++):?>
				  <td><?=substr($this->authlog->getUserTime($i,$row['UserID'],1),0,5);?></td>
				<?php endfor;?>
				<td></td>
			</tr>
			
			<tr>
				<td>TTD</td>
				<?php for($i=1;$i<=31;$i++):?>
				  <td><?=substr($this->authlog->getUserTime($i,$row['UserID'],2),0,5);?></td>
				<?php endfor;?>
				<td></td>
			</tr>
     <?php 
         $x++;
         endforeach;?>
    </tbody>
 </table>
</body>
</html>