<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title><?=$title?></title>

<style type="text/css">
body{margin:0;font-family: Arial;font-size:10px;}
h3{margin:0;text-align: center;font-size:12px;}
h5{margin:0;text-align: center;font-size:10px;}
table {margin:0 auto;width:100%;border-collapse:collapse;}
th,td{border:1px solid #222;font-size:12px;margin:0;font-size:9px;text-align: center;}
th{background:#CCC;}
div.my_wrapper{
    width: 750px;
}

div.my_1{
    float: left;
    padding: 2px;
    width: 360px;
    /**border: 1px solid gray;**/
    margin-bottom:10px;
}

div.my_0{
    float: right;
    padding: 2px;
    width: 360px;
    /**border: 1px solid gray;**/
    margin-bottom:10px;
}

.clear{clear: both;}

</style>

</head>

<body>
<div class="my_wrapper">
    <?php 
    $x=1;
    foreach($users as $user):?>    	
    <div class="my_<?=$x%2?>">
        <h3 class="center"><?=$title?></h3>
        <h5 class="center"><?=$periode?></h5>
        <table class="center">  
       	    <thead>
      		<tr>
     			<th colspan="7"><?=$user['ID'].' '.$user['Name'];?></th>
      		</tr>
      		<tr>
     			<th>M</th>
     			<th>Tanggal</th>
     			<th>Datang</th>
     			<th>Pulang</th>
                <th>Durasi</th>
                <th>Dtg Telat</th>
                <th>Plg Awal</th>
      		</tr>
            </thead>
            <tbody>
                    
                    <?php 
                    //$m = COUNT($days);
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
                    //echo count($days);
                    foreach($days as $rec): 
                        $row = $this->authprocess->getAllRecords('','',$user['ID'],'row',$rec['DAY']);
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
                    endforeach;?>
               </tbody>
        	</table>
            
            <table>
                <h5>Total Jam Kehadiran Per Minggu</h5>
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
                        <td class="mini">
							<?php if($week[$m])
									echo $week[$m];
								 else
									echo '-';
									 ?>
						</td>
                        <td class="mini">
                        <?php 
							if($week[$m]):
								$v = (substr($var,0,2) * 3600) + (substr($var,3,2)*60) + (substr($var,6,2));
								if($w[$m]>=$v)
									print "Memenuhi";
								else
									print "Tidak Memenuhi"; 
							endif;		
                        ?>
                        </td>
                        <td class="mini"><?=$late[$m];?></td>
                        <td class="mini"><?=$early[$m];?></td>
                    </tr>
                    <?php endfor;?>
               </tbody>
               <tfoot>
                    <tr>
                        <th colspan="5"><?='DMK='.$var;?></th>
                    </tr>
               </tfoot>
        	</table>
    </div>
    
    <?php if($x%2==0):?>
    <div class="clear"></div>
    <?php endif;?>
    
    <?php 
    $x++;
    endforeach;?>
</div>
</body>
</html>
