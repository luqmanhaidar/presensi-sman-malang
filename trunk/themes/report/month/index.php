<html>
<head>
<title><?=$title?></title>
<link type="text/css" rel="stylesheet" href="<?=base_url('themes/report/css/style.css');?>" />
</head>
<body style="font-size:10px;">
    <h3>KEMENTRIAN AGAMA</h3>
    <h3>MADRASAH ALIYAH NEGERI 3 MALANG</h3>
    <h3>JL.BANDUNG NO.7 Telp.0341-551357,588333</h3>
    <hr style="border:2px solid #222;" />
    <table class="vmonth">
    <!--<caption style="margin-bottom:15px;margin-top:5px;">-->
		<h3 class="center"><?=$title?></h3>
		<h3 class="center"><?='Bulan : '.indonesian_monthName($month).' '.$year;?></h3>
	<!--</caption>-->
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
	   <th rowspan="4" class="white align-left"><?=$row['Name'].'<br/>'.$row['Description'].'<br/>'.$row['Department']?></th>
	   <th class="white">Paraf</th>
	   <?php for($i=1;$i<=$days;$i++):
            $date = $year."/".$month."/".code($i);
            $datename = date('l', strtotime($date));
            if($datename=="Sunday")
                $bg = "#CCC";
            else
                $bg = "#FFF";    
       ?>
	   <th style="background:<?=$bg?>;">
            <?php if(strlen($this->authlog->getUserTime(code($i).'-'.$month.'-'.$year,$row['UserID'],1))>2): ?>
                <?php if(file_exists('./assets/signature/'.$row['UserID'].'.jpg')): ?>
                    <img src="<?=base_url('assets/signature/'.$row['UserID'].'.jpg');?>" />
                <?php else:?>
                    <?php // file_exists('./assets/signature/'.$row['UserID'].'.jpg')?>
                <?php endif;?>
            <?php else: ?>               
            <?php endif;?>    
       </th>
	   <?php endfor;?>
	   <th class="white"><?=$this->others->getUserTime($row['UserID'],$this->session->userdata('month_month'),$this->session->userdata('month_year'),"Sakit")?></th>
	   <th class="white"><?=$this->others->getUserTime($row['UserID'],$this->session->userdata('month_month'),$this->session->userdata('month_year'),"Ijin")?></th>
	   <th class="white"><?=$this->others->getUserTime($row['UserID'],$this->session->userdata('month_month'),$this->session->userdata('month_year'),"Cuti")?></th>
       <th class="white"><?=$this->others->getUserTime($row['UserID'],$this->session->userdata('month_month'),$this->session->userdata('month_year'),"Tugas")?></th>
	</tr>
	<tr>
        <th class="white">Dtg.PK</th>
	     <?php for($i=1;$i<=$days;$i++):
            $date = $year."/".$month."/".code($i);
            $datename = date('l', strtotime($date));
            if($datename=="Sunday")
                $bg = "#CCC";
            else
                $bg = "#FFF";
         ?>
         
	    <th style="background:<?=$bg?> ;"><?=substr($this->authlog->getUserTime(code($i).'-'.$month.'-'.$year,$row['UserID'],1),0,5);?></th>
	    <?php endfor;?>	
        <th class="white">-</th>
	    <th class="white">-</th>
	    <th class="white">-</th>
        <th class="white">-</th>
	</tr>
    <tr>
	   <th class="white">Paraf</th>
	    <?php for($i=1;$i<=$days;$i++):
            $date = $year."/".$month."/".code($i);
            $datename = date('l', strtotime($date));
            if($datename=="Sunday")
                $bg = "#CCC";
            else
                $bg = "#FFF";
        ?>
	   <th style="background:<?=$bg?> ;">
            <?php if(strlen($this->authlog->getUserTime(code($i).'-'.$month.'-'.$year,$row['UserID'],2))>2): ?>
                <?php if(file_exists('./assets/signature/'.$row['UserID'].'.jpg')): ?>
                    <img src="<?=base_url('assets/signature/'.$row['UserID'].'.jpg');?>" />
                <?php else:?>
                    <?php // file_exists('./assets/signature/'.$row['UserID'].'.jpg')?>
                <?php endif;?>
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
	     <?php for($i=1;$i<=$days;$i++):
            $date = $year."/".$month."/".code($i);
            $datename = date('l', strtotime($date));
            if($datename=="Sunday")
                $bg = "#CCC";
            else
                $bg = "#FFF";
         ?>
	    <th style="background:<?=$bg?>;"><?=substr($this->authlog->getUserTime(code($i).'-'.$month.'-'.$year,$row['UserID'],2),0,5);?></th>
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