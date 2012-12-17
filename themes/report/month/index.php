<html>
<head>
<title><?=$title?></title>
<link type="text/css" rel="stylesheet" href="<?=base_url('themes/report/css/style.css');?>" />
<style>

table{width:1130px;font-size:9px;}
.smallfont{font-size:7px;}
/*th,td,.desx{font-size:9px;width:2.35%;padding-right:2px;padding-left:2px;text-align:center}*/
.desx{font-size:9px;width:2.2%;padding-right:2px;padding-left:2px;text-align:center}
img {width:9px;height:9px;padding:0;margin:0;}
ul{margin:0;}
th{font-size:10px}
.mini{font-size:9px}
.name {font-size:10px;text-transform:capitalize}

</style>
</head>
<body style="font-size:8px;">
    <h3>KEMENTRIAN AGAMA</h3>
    <h3>MADRASAH ALIYAH NEGERI 3 MALANG</h3>
    <h3>JL.BANDUNG NO.7 Telp.0341-551357,588333</h3>
    <hr style="border:2px solid #222;" />
    
    <h3 class="center"><?=$title?></h3>
    <h3 class="center"><?='Bulan : '.indonesian_monthName($month).' '.$year;?></h3>
        
    <table class="vmonth" style="margin:9px auto;">
    <tbody>	
	<!--<thead>	-->	
		<tr>
			<th rowspan="2" style="9px">No</th>
			<th rowspan="2" style="width:150px">Nama</th>
			<th rowspan="2" style="5px">Paraf</th>
			<th class="center" colspan="<?=$days?>">Tanggal</th>
			<th colspan="6"><br/>Ket.(<?=$k=days_in_month($this->session->userdata('month_month'),$this->session->userdata('month_year')) - $this->holidays->getHolidayDate('',$this->session->userdata('month_month'),$this->session->userdata('month_year'))- getCountSundayInMonth($this->session->userdata('month_month'),$this->session->userdata('month_year'));?> Hari Kerja)</th>
		</tr>
		<tr>
			<?php for($i=1;$i<=$days;$i++):?>
			<th class="desx"><?=code($i,2);?></th>
			<?php endfor;?>
            <th>M<span style="color:#CCC">_</span></th>
			<th>S<span style="color:#CCC">_</span></th>
			<th>I<span style="color:#CCC">_</span></th>
            <th>C<span style="color:#CCC">_</span></th>
			<th>DL</th>
            <th>TK</th>
		</tr>
    <!--</thead>-->
	
 <?php
    $x=1;
    foreach($checks as $row): 
 ?>
    <tr>
       <th rowspan="4" class="white"><?=$x?></th>
	   <th rowspan="4" class="white align-left name" style="padding-left:0px"><?=$row['Name'].'<br/>'.$row['Description'].'<br/>'.$row['Department']?></th>
	   <th class="white" style="margin-right:50px">Paraf</th>
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
	   <th class="white desx" style="background:<?=$bg?>;">
            <?php if(strlen($this->authlog->getUserTime(code($i).'-'.$month.'-'.$year,$row['UserID'],1))>2): ?>
				<?php $m=$m+1;?>
                <?php if(file_exists('./assets/signature/'.$row['UserID'].'.jpg')): ?>
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
        <th class="white desx">Dtg.PK</th>
	     <?php for($i=1;$i<=$days;$i++):
            $date = $year."/".$month."/".code($i);
            $datename = date('l', strtotime($date));
            if($datename=="Sunday")
                $bg = "#CCC";
            else
                $bg = "#FFF";
         ?>
        <?php
			//if(COUNT($this->authlog->getUserTime(code($i).'-'.$month.'-'.$year,$row['UserID'],1))>0)
				//$m = $m + 1;
		?>	
	    <th class="white desx" style="font-size:8px;background:<?=$bg?>;text-align:center"><?=substr($this->authlog->getUserTime(code($i).'-'.$month.'-'.$year,$row['UserID'],1),0,5);?></th>
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
	   <th class="desx" style="background:<?=$bg?> ;">
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
        <th class="white desx">Plg.PK</th>
	     <?php for($i=1;$i<=$days;$i++):
            $date = $year."/".$month."/".code($i);
            $datename = date('l', strtotime($date));
            if($datename=="Sunday")
                $bg = "#CCC";
            else
                $bg = "#FFF";
         ?>
	    <th class="white desx" style="font-size:8px;background:<?=$bg?>;"><?=substr($this->authlog->getUserTime(code($i).'-'.$month.'-'.$year,$row['UserID'],2),0,5);?></th>
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
  
       <tr>
			<th class="mini align-left white no-border"></th>
			<th class="mini align-left white no-border"></th>
			<th class="mini align-left white no-border"></th>
			<th class="mini align-left white no-border center" colspan="<?=$days?>"></th>
			<th class="mini align-left white no-border center" colspan="6"></th>
		</tr>
		<tr>
			<th class="mini align-left white no-border"></th>
			<th colspan="2" class="mini align-left white no-border"><br/><br/></th>
			<th class="mini align-left white no-border center" colspan="<?=$days?>"></th>
			<th class="mini align-left white no-border center" colspan="6"><br/><br/></th>
		</tr>
		
		<tr>
			<th class="mini align-left white no-border"></th>
			<th colspan="2" class="mini align-left white no-border"></th>
			<th class="mini align-left white no-border center" colspan="<?=$days?>"></th>
			<th class="mini align-left white no-border center" colspan="6"></th>
		</tr>
        
	<!--<tfoot>-->
		<tr>
			<th class="mini align-left white no-border"></th>
			<th class="mini align-left white no-border"></th>
			<th class="mini align-left white no-border"></th>
			<th class="mini align-left white no-border center" colspan="<?=$days?>"></th>
			<th class="mini align-left white no-border center" colspan="6">Malang, ..... ...................... ..........</th>
		</tr>
		<tr>
			<th class="mini align-left white no-border"></th>
			<th class="mini align-left white no-border">Kepala Madrasah <br/><br/><br/><br/><br/></th>
			<th class="mini align-left white no-border"></th>
			<!--<th class="mini align-left white no-border"></th>-->
			<th class="mini align-left white no-border center" colspan="<?=$days?>"></th>
			<th class="mini align-left white no-border center" colspan="6">Ka. Tata Usaha<br/><br/><br/><br/><br/></th>
		</tr>
		
		<tr>
			<th class="mini align-left white no-border"></th>
			<th class="mini align-left white no-border"><p style="border-bottom:1px solid #222;margin:0;width:140px;">Drs.H.Ahmad Hidayatullah, M.Pd</p><p style="margin:0">NIP. 19680622 200012 1 002</p></th>
			<th class="mini align-left white no-border"></th>
			<!--<th class="mini align-left white no-border"></th>-->
			<th class="mini align-left white no-border center" colspan="<?=$days?>"></th>
			<th class="mini align-left white no-border center" colspan="6"><p style="border-bottom:1px solid #222;margin:0;width:140px;">T.Agus Tjahjono</p><p style="margin:0">NIP. 19630816 198703 1 003</p></th>
		</tr>
		
    <!--</tfoot>-->
    
      </tbody>
	
	
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