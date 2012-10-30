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
                            
                            
                            $sk2 = (7 * 3600) + (0*60) + (0);
                            $dt2 = (7 * 3600) + (15*60) + (0);
                            $wm = (substr($row['MyTimeStart'],0,2) * 3600) + (substr($row['MyTimeStart'],3,2)*60) + (substr($row['MyTimeStart'],6,2));
                            //echo $dt1."<br/>"; 
                            //echo $dt2;
                            if(($row['DayName']<>'Saturday') && ($wm >= $sk1)):
                                $wmk = '07:00:00';
                                $dt  = $dt2;
                                $sk  = $sk2;
                                if($row['DayName']<>"Friday")
                                    $wpk = '15:30:00';
                                else
                                    $wpk = '15:00:00';    
                            else:
                                $wmk = '06:30:00';
                                $dt  = $dt1;
                                $sk  = $sk1;
                                if($row['DayName']<>"Friday")
                                    $wpk = '15:00:00';
                                else
                                    $wpk = '14:00:00';  
                            endif;
                            echo $wmk;
                        ?>
                        </td>
                        <td><?=$row['MyTimeStart'];?><br />
                        <?php
                            //echo substr($row['MyTimeStart'],6,2);
                            //$wm = (substr($row['MyTimeStart'],0,2) * 3600) + (substr($row['MyTimeStart'],3,2)*60) + (substr($row['MyTimeStart'],6,2));
                            //echo $wm;
                        ?>    
                        </td>
                        <td><?=$wpk;?></td>
						<td><?=$row['MyTimeEnd'];?></td>
                        <td>
                            <?php
                               if ($wm > $dt):
                                    $d = $wm - $sk;
                                    $hours = code(floor($d / 3600));
                                    $mins = code(floor(($d - ($hours*3600)) / 60));
                                    $seconds = code($d % 60);
                                    $time = $hours.':'.$mins.':'.$seconds;     
                               else:
                                    $d = "-";
                                    $time = "-";
                               endif;          
                               echo $time; 
                            ?>
                        </td>
                        <td><?=$row['MyTimeEnd'];?></td>
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