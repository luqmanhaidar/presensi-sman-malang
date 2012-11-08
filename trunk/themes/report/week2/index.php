<html>
<head>
<title><?=$title?></title>
<link type="text/css" rel="stylesheet" href="<?=base_url('themes/report/css/style.css');?>" />
</head>
<body>
 <table>
    <caption style="margin:10px 0;text-align:left">
    <h3><?=$title?></h3>
	<hr style="border:1px solid #333"/>
    <h3><?=$periode?></h3>
    </caption>
	<thead>
			<th rowspan="2">NO</th>
			<th rowspan="2" width="20%">NAMA</th>
			<th rowspan="2">STATUS <br/>KEPEG</th>
			<th rowspan="2">JAM KBM <br/>PER<br/>MINGGU</th>
			<th colspan="5">PEMENUHAN 37 1/2 JAM</th>
			<th rowspan="2">KETERANGAN TM</th>
			<th rowspan="2">KETERANGAN KETIDAK HADIRAN</th>
		</tr>
		<tr>
			<th rowspan="1">MINGGU<br/>KE 1</th>
			<th rowspan="1">MINGGU<br/>KE 2</th>
			<th rowspan="1">MINGGU<br/>KE 3</th>
			<th rowspan="1">MINGGU<br/>KE 4</th>
			<th rowspan="1">MINGGU<br/>KE 5</th>
		</tr>
		<tr>
		</tr>
		
		
		
    </thead>
	<tbody>
	<?php 
        $i=1;
        foreach($users as $row):?>
			<tr>
				<td><?=$i;?></td>
				<td><?=$row['Name'];?></td>
				<td><?=$row['Description']?></td>
                <td><?='';?></td>
				<td class="center"><?=numberToTime($w1=$this->authprocess->getWeekDuration(1,$row['ID']))?></td>
				<td class="center"><?=numberToTime($w2=$this->authprocess->getWeekDuration(2,$row['ID']))?></td>
				<td class="center"><?=numberToTime($w3=$this->authprocess->getWeekDuration(3,$row['ID']))?></td>
				<td class="center"><?=numberToTime($w4=$this->authprocess->getWeekDuration(4,$row['ID']))?></td>
				<td class="center"><?=numberToTime($w5=$this->authprocess->getWeekDuration(5,$row['ID']))?></td>
				<td>
					<?php
						if(timeToNumber($var)>=$w1):
							$wx1=1;
							$wm1='M1';
						else:
							$wx1=0;
							$wm1='';
						endif;
						
						if(timeToNumber($var)>=$w2):
							$wx2=1;
							$wm2='M2';
						else:
							$wx2=0;
							$wm2='';
						endif;
						
						if(timeToNumber($var)>=$w3):
							$wx3=1;
							$wm3='M3';
						else:
							$wx3=0;
							$wm3='';
						endif;
						
						if(timeToNumber($var)>=$w4):
							$wx4=1;
							$wm4='M4';
						else:
							$wx4=0;
							$wm4='';
						endif;
						
						if($w5):
							if(timeToNumber($var)>=$w5):
								$wx5=1;
								$wm5='M5';
							else:
								$wx5=0;
								$wm5='';
							endif;
						else:
							$wx5=0;
							$wm5='';
						endif;
						
						print 'TM:'.$wx1+$wx2+$wx3+$wx4+$wx5.'['.$wm1.$wm2.$wm3.$wm4.$wm5.']';
					?>
				</td>
				<td></td>
			</tr>
     <?php 
         $i++;
         endforeach;?>
    </tbody>
 </table>

</body>
</html>