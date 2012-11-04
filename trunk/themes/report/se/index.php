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
			<th colspan="<?=COUNT($days)?>">Sesi</th>
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
    foreach($checks as $row): 
 ?>
    <tr>
       <th rowspan="4" class="white"><?=$x?></th>
	   <th rowspan="4" class="white"><?=$row['Name'].'<br/>'.$row['Department']?></th>
	   <th class="white">Paraf</th>
	   <?php foreach($days as $rec):?>
	   <th class="white">
            <?php if(strlen($this->authlog->getUserTime($rec['DAY'],$row['UserID'],1))>2): ?>
            <img src="<?=base_url('assets/signature/'.$row['UserID'].'.jpg');?>" />
            <?php else: ?>               
            <?php endif;?>    
       </th>
	   <?php endforeach;?>
	   <th class="white">-</th>
	   <th class="white">-</th>
	   <th class="white">-</th>
	</tr>
	<tr>
        <th class="white">Dtg.PK</th>
	    <?php foreach($days as $rec):?>
	    <th class="white"><?=substr($this->authlog->getUserTime($rec['DAY'],$row['UserID'],1),0,5);?></th>
	    <?php endforeach;?>	
        <th class="white">-</th>
	    <th class="white">-</th>
	    <th class="white">-</th>
	</tr>
    <tr>
	   <th class="white">Paraf</th>
	   <?php foreach($days as $rec):?>
	   <th class="white">
            <?php if(strlen($this->authlog->getUserTime($rec['DAY'],$row['UserID'],2))>2): ?>
            <img src="<?=base_url('assets/signature/'.$row['UserID'].'.jpg');?>" />
            <?php else: ?>               
            <?php endif;?>    
       </th>
	   <?php endforeach;?>
       <th class="white">-</th>
	   <th class="white">-</th>
	   <th class="white">-</th>
	</tr>
    
    <tr>
        <th class="white">Plg.PK</th>
	    <?php foreach($days as $rec):?>
	    <th class="white"><?=substr($this->authlog->getUserTime($rec['DAY'],$row['UserID'],2),0,5);?></th>
	    <?php endforeach;?>	
        <th class="white">-</th>
	    <th class="white">-</th>
	    <th class="white">-</th>
	</tr>
    
 <?php 
    $x++;
    endforeach;
 ?>   
    </tbody>
    </table>
    
</body>
</html>