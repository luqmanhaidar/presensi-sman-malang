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
	        <table class="center">
                <caption>
                <h3><?=$title?></h3>
                <h5><?=indonesian_monthName($this->session->userdata('week_month')).' '.$this->session->userdata('week_year')?></h5>
                </caption>
            	<thead>
            		<tr>
            			<th class="align-left mini" colspan="7"><?=$user['ID'].' '.$user['Name'];?></th>
            		</tr>
            		<tr>
            			<th class="mini">M</th>
            			<th class="mini">Tanggal</th>
            			<th class="mini">Datang</th>
            			<th class="mini">Pulang</th>
                        <th class="mini">Durasi</th>
                        <th class="mini">Datang Telat</th>
                        <th class="mini">Pulang Awal</th>
            		</tr>
                </thead>
                <tbody>
                    
                    <?php 
                    $m = days_in_month($this->session->userdata('week_month'),$this->session->userdata('week_year'));
                    $w[1]=0;
                    $l[1]=0;
                    $e[1]=0;
                    $w[2]=0;
                    $l[2]=0;
                    $e[2]=0;
                    $w[3]=0;
                    $l[3]=0;
                    $e[3]=0;
                    $w[4]=0;
                    $l[4]=0;
                    $e[4]=0;
                    $w[5]=0;
                    $l[5]=0;
                    $e[5]=0;
                    for($i=1;$i<=$m;$i++): 
                        $row = $this->authprocess->getAllRecords('','',$user['ID'],'row',$i);
                        if($row):
                            
                    ?>
                    <tr>
                        <td class="mini"><?=$row['W']?></td>
                        <td class="mini"><?=$row['MyDate']?></td>
                        <td class="mini"><?=$row['MyTimeStart']?></td>
                        <td class="mini"><?=$row['MyTimeEnd']?></td>
                        <td class="mini">
                            <?php
                                if(empty($row['MyTimeEnd']))
                                    $end=0;
                                else
                                    $end   = (substr($row['MyTimeEnd'],0,2) * 3600) + (substr($row['MyTimeEnd'],3,2)*60) + (substr($row['MyTimeEnd'],6,2));
                                
                                if(empty($row['MyTimeStart']))
                                    $start=0;
                                else
                                    $start = (substr($row['MyTimeStart'],0,2) * 3600) + (substr($row['MyTimeStart'],3,2)*60) + (substr($row['MyTimeStart'],6,2));
                                        
                                $range = $end - $start;
                                
                                if($range<=0)
                                    $range = 0;
                                else
                                    $range=$range;    
                                
                                $hours = code(floor($range / 3600));
                                $mins  = code(floor(($range - ($hours*3600)) / 60));
                                $seconds = code($range % 60);
                                  
                                print $time  = $hours.':'.$mins.':'.$seconds; 
                            ?>
                        </td>
                        <td class="mini"><?=$row['ProcessDateLate']?></td>
                        <td class="mini"><?=$row['ProcessDateEarly']?></td>
                    </tr>
                    
                    <?php
                        $lt = (substr($row['ProcessDateLate'],0,2) * 3600) + (substr($row['ProcessDateLate'],3,2)*60) + (substr($row['ProcessDateLate'],6,2));
                        $el = (substr($row['ProcessDateEarly'],0,2) * 3600) + (substr($row['ProcessDateEarly'],3,2)*60) + (substr($row['ProcessDateEarly'],6,2));
                        if($row['W']==1):
                                $w[1] = $w[1] + $range;
                                $l[1] = $l[1]+ $lt;
                                $e[1] = $e[1]+ $el;
                        elseif($row['W']==2):
                                $w[2] = $w[2] + $range;
                                $l[2] = $l[2]+ $lt;
                                $e[2] = $e[2]+ $el;        
                        elseif($row['W']==3):
                                $w[3] = $w[3] + $range;
                                $l[3] = $l[3]+ $lt;
                                $e[3] = $e[3]+ $el;
                        elseif($row['W']==4):
                                $w[4] = $w[4] + $range;
                                $l[4] = $l[4]+ $lt;
                                $e[4] = $e[4]+ $el;
                        elseif($row['W']==5):
                                $w[5] = $w[5] + $range;  
                                $l[5] = $l[5]+ $lt; 
                                $e[5] = $e[5]+ $el;
                        endif;                                 
                                
                    ?>
                    
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
            
            <table class="center">
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
                    <?php 
                        for($m=1;$m<=5;$m++):
                            $whours = code(floor($w[$m] / 3600));
                            $wmins  = code(floor(($w[$m] - ($whours*3600)) / 60));
                            $wseconds = code($w[$m] % 60);
                            
                            $lhours = code(floor($l[$m] / 3600));
                            $lmins  = code(floor(($l[$m] - ($lhours*3600)) / 60));
                            $lseconds = code($l[$m] % 60);
                            
                            $ehours = code(floor($e[$m] / 3600));
                            $emins  = code(floor(($e[$m] - ($ehours*3600)) / 60));
                            $eseconds = code($e[$m] % 60);
                            
                            $week[$m] = $whours.':'.$wmins.':'.$wseconds; 
                            $late[$m] = $lhours.':'.$lmins.':'.$lseconds;
                            $early[$m]= $ehours.':'.$emins.':'.$eseconds;
                         
                    ?>
                    <tr>
                        <td class="mini"><?='Minggu ke-'.$m?></td>
                        <td class="mini"><?=$week[$m];?></td>
                        <td class="mini"></td>
                        <td class="mini"><?=$late[$m];?></td>
                        <td class="mini"><?=$early[$m];?></td>
                    </tr>
                    <?php endfor;?>
               </tbody>
               <tfoot>
                    <tr>
                        <th class="mini" colspan="5">DWK = 70:00:00</th>
                    </tr>
               </tfoot>
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