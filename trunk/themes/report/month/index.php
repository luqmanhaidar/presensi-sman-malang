<html>
<head>
<title><?=$title?></title>
<link type="text/css" rel="stylesheet" href="<?=base_url('themes/report/css/style.css');?>" />
</head>
<body>
    <table class="vmonth">
    <caption>
		<h3><?=$title?></h3>
		<h3><?='Bulan : '.indonesian_monthName($month).' '.$year;?></h3>
	</caption>
	<thead>		
		<tr>
			<th rowspan="2">No</th>
			<th rowspan="2">Nama</th>
			<th rowspan="2">Paraf</th>
			<th colspan="<?=$days?>">Tanggal</th>
			<th colspan="4">Keterangan</th>
		</tr>
		<tr>
			<?php for($i=1;$i<=$days;$i++):?>
			<th><?=code($i,2);?></th>
			<?php endfor;?>
			<th>S</th>
			<th>I</th>
            <th>C</th>
			<th>DL</th>
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
	   <?php for($i=1;$i<=$days;$i++):?>
	   <th class="white">
            <?php if(strlen($this->authlog->getUserTime(code($i).'-'.code($month).'-'.$year,$row['UserID'],1))>2): ?>
            <img src="<?=base_url('assets/signature/'.$row['UserID'].'.jpg');?>" />
            <?php else: ?>               
            <?php endif;?>    
       </th>
	   <?php endfor;?>
	   <th class="white"><?=$this->others->getUserTime($row['UserID'],$this->session->userdata('month_start'),$this->session->userdata('month_finish'),"Sakit")?></th>
	   <th class="white"><?=$this->others->getUserTime($row['UserID'],$this->session->userdata('month_start'),$this->session->userdata('month_finish'),"Ijin")?></th>
	   <th class="white"><?=$this->others->getUserTime($row['UserID'],$this->session->userdata('month_start'),$this->session->userdata('month_finish'),"Cuti")?></th>
       <th class="white"><?=$this->others->getUserTime($row['UserID'],$this->session->userdata('month_start'),$this->session->userdata('month_finish'),"Tugas")?></th>
	</tr>
	<tr>
        <th class="white">Dtg.PK</th>
	     <?php for($i=1;$i<=$days;$i++):?>
	    <th class="white"><?=substr($this->authlog->getUserTime(code($i).'-'.code($month).'-'.$year,$row['UserID'],1),0,5);?></th>
	    <?php endfor;?>	
        <th class="white">-</th>
	    <th class="white">-</th>
	    <th class="white">-</th>
        <th class="white">-</th>
	</tr>
    <tr>
	   <th class="white">Paraf</th>
	    <?php for($i=1;$i<=$days;$i++):?>
	   <th class="white">
            <?php if(strlen($this->authlog->getUserTime(code($i).'-'.code($month).'-'.$year,$row['UserID'],2))>2): ?>
            <img src="<?=base_url('assets/signature/'.$row['UserID'].'.jpg');?>" />
            <?php else: ?>               
            <?php endif;?>    
       </th>
	   <?php endfor;?>
       <th class="white">-</th>
	   <th class="white">-</th>
	   <th class="white">-</th>
       <th class="white">-</th>
	</tr>
    
    <tr>
        <th class="white">Plg.PK</th>
	     <?php for($i=1;$i<=$days;$i++):?>
	    <th class="white"><?=substr($this->authlog->getUserTime(code($i).'-'.code($month).'-'.$year,$row['UserID'],2),0,5);?></th>
	    <?php endfor;?>	
        <th class="white">-</th>
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