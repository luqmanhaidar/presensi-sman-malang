<html>
<head>
<title><?=$title?></title>
<link type="text/css" rel="stylesheet" href="<?=base_url('themes/report/css/style.css');?>" />
<style>
th,td,.desx{font-size:7px;min-width:2%;padding-right:2px;padding-left:2px;text-align: center;}
img {width:10px;height:10px;padding:0;margin:0;}
ul{margin:0;}
</style>
</head>
<body style="font-size:8px;">
    <h3>KEMENTRIAN AGAMA</h3>
    <h3>MADRASAH ALIYAH NEGERI 3 MALANG</h3>
    <h3>JL.BANDUNG NO.7 Telp.0341-551357,588333</h3>
    <hr style="border:2px solid #222;" />
    
    <h3 class="center"><?=$title?></h3>
    <h3 class="center"><?='Bulan : '.indonesian_monthName($month).' '.$year;?></h3>
        
    <table class="vmonth" style="margin:10px auto;">	
	<thead>		
		<tr>
			<th rowspan="2" style="10px">No</th>
			<th rowspan="2" style="100px">Nama</th>
			<th rowspan="2">Paraf</th>
			<th class="center" colspan="<?=$days?>">Tanggal</th>
			<th colspan="6">Ket.(<?=$k=days_in_month($this->session->userdata('month_month'),$this->session->userdata('month_year')) - $this->holidays->getHolidayDate('',$this->session->userdata('month_month'),$this->session->userdata('month_year'))- getCountSundayInMonth($this->session->userdata('month_month'),$this->session->userdata('month_year'));?> Hari Kerja)</th>
		</tr>
		<tr>
			<?php for($i=1;$i<=$days;$i++):?>
			<th><?=code($i,2);?></th>
			<?php endfor;?>
            <th>M<span style="color:#CCC">_</span></th>
			<th>S<span style="color:#CCC">_</span></th>
			<th>I<span style="color:#CCC">_</span></th>
            <th>C<span style="color:#CCC">_</span></th>
			<th>DL</th>
            <th>TK</th>
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
	   <?php
       $m=0; 
       for($i=1;$i<=$days;$i++):
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
                    <?php $m=$m+1;?>
                    <img src="<?=base_url('assets/signature/'.$row['UserID'].'.jpg');?>" />
                <?php else:?>
                <?php endif;?>
            <?php else: ?>
                                
            <?php endif;?>    
       </th>
	   <?php 
       endfor;?>
       <th class="white desx"><?=$m;?></th>
	   <th class="white desx"><?=$s=$this->others->getUserTime($row['UserID'],$this->session->userdata('month_month'),$this->session->userdata('month_year'),"Sakit")?></th>
	   <th class="white desx"><?=$i=$this->others->getUserTime($row['UserID'],$this->session->userdata('month_month'),$this->session->userdata('month_year'),"Ijin")?></th>
	   <th class="white desx"><?=$c=$this->others->getUserTime($row['UserID'],$this->session->userdata('month_month'),$this->session->userdata('month_year'),"Cuti")?></th>
       <th class="white desx"><?=$dl=$this->others->getUserTime($row['UserID'],$this->session->userdata('month_month'),$this->session->userdata('month_year'),"Tugas")?></th>
       <th class="white desx"><?=$k-($m+$s+$i+$c+$dl)?></th>
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
        <th class="white">-</th>
        <th class="white">-</th>
       
	</tr>
    
 <?php 
    $x++;
    $m=0;
    endforeach;
 ?>   
    </tbody>
	
	<tfoot>
		<tr>
            <th class="mini align-right" colspan="4">Total</th>
			<th class="mini align-right"><?=number_format($sub_eat,0);?></th>
            <th class="mini align-right"><?=number_format($sub_trp,0);?></th>
			<th class="mini"></th>
		</tr>
        <tr>
            <th class="mini align-right white no-border"></th>
            <th class="mini align-right white no-border"></th>
            <th class="mini align-right white no-border"></th>
            <th class="mini align-right white no-border"></th>
			<th class="mini align-right white no-border"></th>
            <th class="mini white no-border"></th>
            <th class="mini align-left white no-border">Malang,...........</th>
		</tr>
        <tr>
            <th style="padding-bottom:100px;"class="mini align-right white no-border"></th>
            <th style="padding-bottom:100px;" class="mini align-left white no-border">Kepala Madrasah</th>
            <th style="padding-bottom:100px;" class="mini align-right white no-border"></th>
            <th style="padding-bottom:100px;" class="mini align-right white no-border"></th>
			<th style="padding-bottom:100px;" class="mini align-right white no-border"></th>
            <th style="padding-bottom:100px;" class="mini align-left white no-border"></th>
			<th style="padding-bottom:100px;" class="mini align-left white no-border">Bendahara</th>
		</tr>
        
        <tr>
            <th class="mini align-right white no-border"></th>
            <th class="mini align-left white no-border"><p style="border-bottom:1px solid #222;width:170px;">Drs.H.Ahmad Hidayatullah, M.Pd</p><p>NIP. 19680622 200012 1 002</p></th>
            <th class="mini align-right white no-border"></th>
            <th class="mini align-right white no-border"></th>
			<th class="mini align-right white no-border"></th>
            <th class="mini white no-border"></th>
            <th class="mini align-left white no-border"><p style="border-bottom:1px solid #222;width:170px;">Drs. Suwito</p><p>NIP. 19601010 199503 1 001</p></th>
		</tr>
        
    </tfoot>
	
    </table>
    
    <br />
    <h5>Hari Libur</h5>
    <ul>
        <?php foreach($holidays as $rec):?>
        <li><?=$rec['HolidayDate'].':'.$rec['HolidayDescription']?></li>
        <?php endforeach;?>
    </ul>
    
    
</body>
</html>