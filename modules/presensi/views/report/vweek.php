<hgroup id="main-title" class="thin">
			<h1><?=$title?></h1>
		</hgroup>

		<div class="with-padding">
            
			<h4>Print Preview <?=$title?></h4>

			<p>Simple sorting and manual controls if you prefer to handle the table output server-side:</p>

			<p><?=$this->session->flashdata('message')?></p>
            
			<div class="table-header button-height">
				<div class="float-right">
                    <form method="post" action="<?=site_url('presensi/report/week_search')?>">
                    <?=form_dropdown('group',$groups,$this->session->userdata('week_group'),'id="week" class="select white-gradient glossy" ');?>
					<?=form_dropdown('month',config_item('month'),$this->session->userdata('week_month'),'class="select"')?>
                    <?=form_dropdown('year',config_item('year'),$this->session->userdata('week_year'),'class="select"')?>
                    <input type="submit" class="button blue-gradient glossy" value="Go" />
                    </form>
                </div>
                
				Show&nbsp;
                <?=form_dropdown('show',config_item('per_page'),$this->session->userdata('week_paging'),'id="show" class="select blue-gradient glossy" onchange="changeUrl();" ');?>
			</div>
			<table class="table responsive-table" id="sorting-example1">

				<thead>
					<tr>
						<th scope="col" width="3%">No</th>
						<th scope="col" width="5%" class="align-left hide-on-mobile">ID</th>
                        <th scope="col" class="align-left hide-on-mobile">Nama</th>
                        <th scope="col" class="align-left hide-on-mobile">M</th>
                        <th scope="col" class="align-left hide-on-mobile">Hari</th>
						<th scope="col" class="align-left hide-on-mobile">Tanggal</th>
                        <th scope="col">WMK</th>
                        <th scope="col">Masuk</th>
                        <th scope="col">WSK</th>
						<th scope="col">Pulang</th>
                        <th scope="col">Telat</th>
                        <th scope="col">PulCep</th>
					</tr>
				</thead>

				<tfoot>
					<tr>
						<td colspan="6">
							<?=COUNT($checks).' Data ditemukan.'?>
						</td>
					</tr>
				</tfoot>

				<tbody>
					<?php 
                        $x=1;
                        foreach($checks as $row):?>
					<tr>
						<td><?=$x;?></td>
						<td><?=$row['UserID'];?></td>
                        <td><?=$row['Name'];?></td>
                         <td><?=$row['W'];?></td>
                        <td><?=indonesianDayName($row['DayName']);?></td>
						<td><?=$row['MyDate'];?></td>
                        <td>
                        <?php
                            $sk1 = (6 * 3600) + (30*60) + (0);
                            $dt1 = (6 * 3600) + (45*60) + (0);
                            $sd1 = '06:30:00';
                            
                            $sk2 = (7 * 3600) + (0*60) + (0);
                            $dt2 = (7 * 3600) + (15*60) + (0);
                            $sd2 = '07:00:00';
                              
                            $wp1 =  (12 * 3600) + (30*60) + (0);
                            $w1  =  '12:30:00';
                            
                            $wp2 =  (14 * 3600) + (0*60) + (0);
                            $w2  =  '14:00:00';
                            
                            $wp3 =  (14 * 3600) + (30*60) + (0);
                            $w3  =  '14:30:00'; 
                            
                            $wp4 =  (15 * 3600) + (0*60) + (0);
                            $w4  =  '15:00:00';
                            
                            $wp5 =  (15 * 3600) + (30*60) + (0);
                            $w5  =  '15:30:00';
                            
                            $sk  =  $this->usergroup->getGroupWorkData($row['GroupWork']);
                            $jm  =  $this->usergroup->getGroupFridayData($row['GroupFriday']);
                            
                            if($sk):
                                $dbSkStart = $sk['GroupWorkStart'];
                                $dbSpStart = (substr($sk['GroupWorkStart'],0,2) * 3600) + (substr($sk['GroupWorkStart'],3,2)*60) + (substr($sk['GroupWorkStart'],6,2));
                                $dbSpWork  = (substr($sk['GroupWorkStart'],0,2) * 3600) + ((substr($sk['GroupWorkStart'],3,2)+15)*60) + (substr($sk['GroupWorkStart'],6,2));   
                                $dbSkEnd   = $sk['GroupWorkEnd'];
                                $dbSpEnd   = (substr($sk['GroupWorkEnd'],0,2) * 3600) + (substr($sk['GroupWorkEnd'],3,2)*60) + (substr($sk['GroupWorkEnd'],6,2));
                            else:
                                $dbSkStart = $sd1;
                                $dbSpStart = $sk1;
                                $dbSpWork  = $dt1;
                                $dbSkEnd   = $w3;
                                $dbSpEnd   = $wp3; 
                            endif;
                                
                            $wm = (substr($row['MyTimeStart'],0,2) * 3600) + (substr($row['MyTimeStart'],3,2)*60) + (substr($row['MyTimeStart'],6,2));
                            $ws = (substr($row['MyTimeEnd'],0,2) * 3600) + (substr($row['MyTimeEnd'],3,2)*60) + (substr($row['MyTimeEnd'],6,2));
                            
                            if(($row['DayName']=='Saturday')):
                                $wmk = $sd1;
                                $dt  = $dt1;
                                $sk  = $sk1;
                                $wsk = $w1; // Wsk Senin-Kamis
                                $wsp = $wp1;
                            elseif($row['DayName']=='Sunday'):
                                $wmk = '';
                                $dt  = 0;
                                $sk  = 0;
                                $wsk = ''; // Wsk Senin-Kamis
                                $wsp = 0;
                            elseif(($row['DayName']<>'Saturday') && ($wm >= $sk1)  && ( ($row['GroupID']==1) || ($row['GroupID']==2) ) ):
                                $wmk = $sd2;
                                $dt  = $dt2;
                                $sk  = $sk2;
                                if($row['DayName']<>"Friday"):
                                    $wsk = $w5; // Wsk Senin-Kamis
                                    $wsp = $wp5;
                                else:
                                    $wsk = $w4; // Wsk Jumat
                                    $wsp = $wp4;
                                endif;       
                            elseif( ( ($row['GroupID']==1) || ($row['GroupID']==2) )):
                                $wmk = $sd1;
                                $dt  = $dt1;
                                $sk  = $sk1;
                                if($row['DayName']<>"Friday"):
                                    $wsk = $w3;
                                    $wsp = $wp3;
                                else:
                                    $wsk = $w2; 
                                    $wsp = $wp2;
                                endif;
                            elseif(($row['GroupID']>=3)):
                                $wmk = $dbSkStart;
                                $dt  = $dbSpWork;
                                $sk  = $dbSpStart;
                                if($row['DayName']<>"Friday"):
                                    $wsk = $dbSkEnd;
                                    $wsp = $dbSpEnd;
                                else:
                                    $wsk = $w2; 
                                    $wsp = $wp2;
                                endif;
                            endif;
                            echo $wmk;
                        ?>
                        </td>
                        <td><?=$row['MyTimeStart'];?><br /></td>
                        <td><?=$wsk;?></td>
						<td><?=$row['MyTimeEnd'];?></td>
                        <td>
                            <?php
                               if ($wm > $dt):
                                    $d = $wm - $sk;
                                    $hours = code(floor($d / 3600));
                                    $mins = code(floor(($d - ($hours*3600)) / 60));
                                    $seconds = code($d % 60);
                                    $timestart = $hours.':'.$mins.':'.$seconds;     
                               else:
                                    $d = "-";
                                    $timestart = "-";
                               endif;          
                               echo $timestart; 
                            ?>
                        </td>
                        <td>
                            <?php
                               if ($ws >= $wsp):
                                    $timeend = "-";  
                               else:
                                    $e = $wsp - $ws;
                                    $hours = code(floor($e / 3600));
                                    $mins = code(floor(($e - ($hours*3600)) / 60));
                                    $seconds = code($e % 60);
                                    $timeend = $hours.':'.$mins.':'.$seconds;   
                               endif;          
                               echo $timeend; 
                            ?>
                        </td>
					</tr>
                    <?php 
                        $x=$x+1;
                        endforeach;?>
				
				</tbody>

			</table>

			<form method="post" action="<?=site_url('presensi/report/week_preview')?>" class="table-footer button-height large-margin-bottom">
                <div class="float-right">
                    <div class="button-group">
                      <?php if(!empty($pagination))
                                echo $pagination;
                      ?>
                    </div>
				</div>
                 <?php // form_dropdown('show2',config_item('per_page'),$this->session->userdata('log_paging'),'id="show2" class="select blue-gradient glossy" onchange="changeUrl2();" ');?>
				
                <?=form_dropdown('export',config_item('print'),'','id="print" class="select blue-gradient glossy" ');?>
				<button type="submit" class="button blue-gradient glossy">Go</button>
			</form>
			
		</div>
        
     <script>
        function changeUrl() {
            var redirect;
            redirect = document.getElementById('show').value;
            document.location.href = '<?=site_url("presensi/report/week_paging")?>/' + redirect;
        }
        
        /*function changeUrl2() {
            var redirect;
            redirect = document.getElementById('show2').value;
            document.location.href = '<?=site_url("presensi/report/week_paging")?>/' + redirect;
        }*/
     </script>   