<html>
<head>
<title><?=$title?></title>
<link type="text/css" rel="stylesheet" href="<?=base_url('themes/report/css/style.css');?>" />
</head>
<body>
 <table class="vmonth">
    <caption>
		<h3><?=$title?></h3>
		<h4><?='Group :' .$position['Name'];?></h4>
	</caption>
	<thead>		
		<tr>
			<th rowspan="2">No</th>
			<th rowspan="2">Nama</th>
			<th rowspan="2">Paraf</th>
			<th colspan="<?=COUNT($days)?>">Tanggal</th>
			<th colspan="3">Keterangan</th>
		</tr>
		<tr>
			<?php foreach($days as $rec):?>
			<th><?=substr($rec['DAY'],0,2);?></th>
			<?php endforeach;?>
			<th>S</th>
			<th>I</th>
			<th>T</th>
		</tr>
    </thead>
	<tbody>
	<?php 
        $x=1;
        foreach($checks as $row):?>
			<tr>
				<td rowspan="4"><?=$x;?></td>
				<td rowspan="4"><?=$row['Name'].'<br/>'.$row['Department']?></td>
				<td>Paraf</td>
				<?php foreach($days as $rec):?>
				  <td>
                    <?php if(strlen($this->authlog->getUserTime($rec['DAY'],$row['UserID'],1))>2): ?>
                        <img src="<?=base_url('assets/signature/'.$row['UserID'].'.jpg');?>" />
                    <?php else: ?>   
                        
                    <?php endif;?>        
                  </td>
				<?php endforeach;?>
				<td rowspan="4">-</td>
				<td rowspan="4">-</td>
				<td rowspan="4">-</td>
			</tr>
            
            <tr>
				<td>Dtg.PK</td>
				<?php foreach($days as $rec):?>
				  <td><?=substr($this->authlog->getUserTime($rec['DAY'],$row['UserID'],1),0,5);?></td>
				<?php endforeach;?>
			</tr>
            
            <tr>
				<td>Paraf</td>
				<?php foreach($days as $rec):?>
				  <td>
                    <?php if(strlen($this->authlog->getUserTime($rec['DAY'],$row['UserID'],2))>2): ?>
                        <img src="<?=base_url('assets/signature/'.$row['UserID'].'.jpg');?>" />
                    <?php else: ?>   
                    <?php endif;?>
                  </td>
				<?php endforeach;?>
			</tr>
            
			<tr>
				<td>Plg.PK</td>
				<?php foreach($days as $rec):?>
				  <td><?=substr($this->authlog->getUserTime($rec['DAY'],$row['UserID'],2),0,5);?></td>
				<?php endforeach;?>
			</tr>		
     <?php 
         $x++;
         endforeach;?>
    </tbody>
 </table>
</body>
</html>