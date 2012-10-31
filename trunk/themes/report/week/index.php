<html>
<head>
<title><?=$title?></title>
<link type="text/css" rel="stylesheet" href="<?=base_url('themes/report/css/style.css');?>" />
</head>
<body>
<div class="mask">
    <div class="header">
	         <h3>Laporan Mingguan</h3>
	</div> 
    <div class="colleft">
	<?php 
        $x=1;
        foreach($users as $user):?>    	
        <?php //$records = $this->authprocess->getAllRecords('','',$user['ID']);
            //if(COUNT($records)>0):
        ?>  
	    <div class="col<?=$x % 2;?>">
	        <table>
                <caption>
                <h3><?=$title?></h3>
                <h5><?=indonesian_monthName($this->session->userdata('month_search')).' '.$this->session->userdata('year_search')?></h5>
                </caption>
            	<thead>
            		<tr>
            			<th class="align-left" colspan="7"><?=$user['ID'].' '.$user['Name'];?></th>
            		</tr>
            		<tr>
            			<th class="mini">M</th>
            			<th class="mini">Tanggal</th>
            			<th class="mini">Datang</th>
            			<th class="mini">Pulang</th>
                        <th class="mini">Durasi</th>
                        <th class="mini">Telat</th>
                        <th class="mini">Pul.Cepat</th>
            		</tr>
                </thead>
                <tbody>
                    
                    <?php 
                    $m = days_in_month($this->session->userdata('month_search'),$this->session->userdata('year_search'));
         
                    for($i=1;$i<=$m;$i++): 
                        $row = $this->authprocess->getAllRecords('','',$user['ID'],'row',$i);
                        if($row):
                    ?>
                    <tr>
                        <td class="mini"><?=$row['W']?></td>
                        <td class="mini"><?=$row['MyDate']?></td>
                        <td class="mini"><?=$row['MyTimeStart']?></td>
                        <td class="mini"><?=$row['MyTimeEnd']?></td>
                        <td class="mini"></td>
                        <td class="mini"><?=$row['ProcessDateLate']?></td>
                        <td class="mini"><?=$row['ProcessDateEarly']?></td>
                    </tr>
                    <?php else:?>
                    <tr>
                        <td class="mini">-</td>
                        <td class="mini"></td>
                        <td class="mini"></td>
                        <td class="mini"></td>
                        <td class="mini"></td>
                        <td class="mini"></td>
                        <td class="mini"></td>
                    </tr>
                    <?php
                        endif;
                    endfor;?>
               </tbody>
        	</table>
            
            <table>
                <caption>
                <h5>Total Jam Kehadiran Per Minggu</h5>
                </caption>
                <thead>
            		<tr>
            			<th class="mini">Minggu</th>
            			<th class="mini">Work Time</th>
            			<th class="mini">Keterangan</th>
            			<th class="mini">Datang Telat</th>
                        <th class="mini">Pulang Awal</th>
            		</tr>
                </thead>
                <tbody>
                    <?php for($m=1;$m<=5;$m++):?>
                    <tr>
                        <td class="mini"><?='Minggu ke-'.$m?></td>
                        <td class="mini"></td>
                        <td class="mini"></td>
                        <td class="mini"></td>
                        <td class="mini"></td>
                    </tr>
                    <?php endfor;?>
               </tbody>
        	</table>
            
	    </div>
        <?php
               $x++;
                //endif; 
            
            endforeach;?>
        
	</div>   
 </div>
</body>
</html>