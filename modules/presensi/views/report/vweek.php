       <hgroup id="main-title" class="thin">
			<h1><?=$title?></h1>
		</hgroup>

		<div class="with-padding">
            
			<h4>Print Preview <?=$title?></h4>

			<p>Simple sorting and manual controls if you prefer to handle the table output server-side:</p>

			<p><?=$this->session->flashdata('message')?></p>
            
			<div class="table-header button-height">
				<div class="float-right">
                    <form method="post"  action="<?=site_url('presensi/report/week_search')?>">
                    <?=form_dropdown('group',$groups,$this->session->userdata('week_group'),'id="week" class="select white-gradient glossy" ');?>
					<?=form_dropdown('day',config_item('day'),substr($this->session->userdata('week_start'),3,2),'class="select"')?>
                    <?=form_dropdown('month',config_item('month'),substr($this->session->userdata('week_start'),0,2),'class="select"')?>
                    <?=form_dropdown('year',config_item('year'),substr($this->session->userdata('week_start'),6,4),'class="select"')?>
                    s/d
                    <?=form_dropdown('day2',config_item('day'),substr($this->session->userdata('week_finish'),3,2),'class="select"')?>
                    <?=form_dropdown('month2',config_item('month'),substr($this->session->userdata('week_finish'),0,2),'class="select"')?>
                    <?=form_dropdown('year2',config_item('year'),substr($this->session->userdata('week_finish'),6,4),'class="select"')?>
                    <?=form_dropdown('type',config_item('week_type'),$this->session->userdata('week_type'),'id="week_type" class="select white-gradient glossy" ');?>
                    <input type="submit" class="button blue-gradient glossy" value="Go" />
                    </form>
                </div>
           
                <?=form_dropdown('show',config_item('per_page'),$this->session->userdata('week_paging'),'id="show" class="select blue-gradient glossy" onchange="changeUrl();" ');?>
			</div>
            
            <?php if($this->session->userdata('week_type')=='M2'):?>
            <table class="table responsive-table" id="sorting-example1">

				<thead>
					<tr>
						<th scope="col" width="3%">No</th>
						<th scope="col" width="5%" class="align-left hide-on-mobile">ID</th>
                        <th scope="col" class="align-left hide-on-mobile">Nama</th>
                        <th scope="col" class="align-left hide-on-mobile">Minggu Ke</th>
                        <th scope="col" class="align-left hide-on-mobile">Total</th>
                        <th scope="col" class="align-left hide-on-mobile">Keterangan</th>
					</tr>
				</thead>
                <tbody>
                    <?php 
                        $x=1;
                        foreach($checks as $row):?>
                        <tr>
    						<td><?=$x;?></td>
    						<td><?=$row['UserID'];?></td>
                            <td><?=$row['Name'];?></td>
                            <td><?=$row['Week_'];?></td>
                            <td>
                            <?php
                                $d = $row['Total'];
                                $hours = code(floor($d / 3600));
                                $mins = code(floor(($d - ($hours*3600)) / 60));
                                $seconds = code($d % 60);
                                $tot_hour = $hours.":".$mins.":".$seconds;
                                echo $tot_hour;
                            ?>
                            </td>
                            <td>
                            <?php 
                                $dmk = (substr($var,0,2) * 3600) + (substr($var,3,2)*60) + (substr($var,6,2));
                                if($d>=$dmk)
                                    print "M";
                                else
                                    print "TM";    
                            ?>
                            </td>
					   </tr>
                     <?php 
                        $x=$x+1;
                        endforeach;
                     ?>   
                </tbody>
                <tfoot>
                    <tr>
                        <td colspan="6"><?='DMK :'.$var;?></td>
                    </tr>
                </tfoot>
            </table>    
            <form method="post" target="_blank" action="<?=site_url('presensi/report/week_preview')?>" class="table-footer button-height large-margin-bottom">
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
            <?php else:?>
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
				
				<?php if($this->session->userdata('week_group')): ?>
				<tfoot>
					<tr>
						<td colspan="12">
							<?=COUNT($checks).' Data ditemukan.'?>
						</td>
					</tr>
				</tfoot>
				
				<tbody>
					<?php 
                        $x=1;
                        foreach($checks as $row):?>
					<tr <?php if($row['DayName']=='Sunday') print 'style="background:#FF0000;color:#222"'; ?>  >
						<td><?=$x;?></td>
						<td><?=$row['UserID'];?></td>
                        <td><?=$row['Name'];?></td>
                         <td><?=$row['W'];?></td>
                        <td><?=indonesianDayName($row['DayName']);?></td>
						<td><?=$row['MyDate'];?></td>
                        <td><?=$row['ProcessDateWorkStart']?></td>
                        <td><?=$row['MyTimeStart'];?><br /></td>
                        <td><?=$row['ProcessDateWorkEnd']?></td>
						<td><?=$row['MyTimeEnd'];?></td>
                        <td><?=$row['ProcessDateLate']?></td>
                        <td><?=$row['ProcessDateEarly']?></td>
					</tr>
                    <?php 
                        $x=$x+1;
                        endforeach;?>
				
				</tbody>
				
				<?php endif;?>

			</table>
            

			<form method="post" target="_blank" action="<?=site_url('presensi/report/week_preview')?>" class="table-footer button-height large-margin-bottom">
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
            <?php endif;?>
			
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