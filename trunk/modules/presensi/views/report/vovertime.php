       <hgroup id="main-title" class="thin">
			<h1><?=$title?></h1>
		</hgroup>

		<div class="with-padding">
            
			<h4>Print Preview <?=$title?></h4>

			<p>Simple sorting and manual controls if you prefer to handle the table output server-side:</p>

			<p><?=$this->session->flashdata('message')?></p>
            
			<div class="table-header button-height">
				<div class="float-right">
                    <form method="post"  action="<?=site_url('presensi/report/overtime_search')?>">
                    <?=form_dropdown('group',$groups,$this->session->userdata('overtime_group'),'id="week" class="select white-gradient glossy" ');?>
					<?=form_dropdown('day',config_item('day'),substr($this->session->userdata('overtime_start'),3,2),'class="select"')?>
                    <?=form_dropdown('month',config_item('month'),substr($this->session->userdata('overtime_start'),0,2),'class="select"')?>
                    <?=form_dropdown('year',config_item('year'),substr($this->session->userdata('overtime_start'),6,4),'class="select"')?>
                    s/d
                    <?=form_dropdown('day2',config_item('day'),substr($this->session->userdata('overtime_finish'),3,2),'class="select"')?>
                    <?=form_dropdown('month2',config_item('month'),substr($this->session->userdata('overtime_finish'),0,2),'class="select"')?>
                    <?=form_dropdown('year2',config_item('year'),substr($this->session->userdata('overtime_finish'),6,4),'class="select"')?>
                    <input type="submit" class="button blue-gradient glossy" value="Go" />
                    </form>
                </div>
                
				Show&nbsp;
                <?=form_dropdown('show',config_item('per_page'),$this->session->userdata('overtime_paging'),'id="show" class="select blue-gradient glossy" onchange="changeUrl();" ');?>
			</div>
			<table class="table responsive-table" id="sorting-example1">

				<thead>
					<tr>
						<th scope="col" width="3%">No</th>
						<th scope="col" width="5%" class="align-left hide-on-mobile">ID</th>
                        <th scope="col" class="align-left hide-on-mobile">Nama</th>
						<th scope="col" class="align-left hide-on-mobile">Tanggal</th>
                        <th scope="col">Mulai</th>
                        <th scope="col">Selesai</th>
                        <th scope="col">Durasi</th>
						<th scope="col">Nominal</th>
                        <th scope="col">Uang Makan</th>
                        <th scope="col">Total</th>
					</tr>
				</thead>
				
				<?php if($this->session->userdata('overtime_start')): ?>
				<tfoot>
					<tr>
						<td colspan="10">
							<?=COUNT($checks).' Data ditemukan.'?>
						</td>
					</tr>
				</tfoot>
				
				<tbody>
					<?php 
                        $x=1;
                        foreach($checks as $row):?>
					<tr >
						<td><?=$x;?></td>
						<td><?=$row['UserID'];?></td>
                        <td><?=$row['Name'];?></td>
                        <td><?=$row['MyDate'];?></td>
                        <td><?=$row['OvertimeStart'];?></td>
						<td><?=$row['OvertimeEnd'];?></td>
						<td class="align-right"><?=$row['OvertimeDuration'];?></td>
                        <td class="align-right"><?=number_format($total=$row['OvertimeDuration']* $overtime,0);?></td>
                        <td class="align-right"><?=number_format($row['OvertimeMeal'],0);?></td>
                        <td class="align-right"><?=number_format($total + $row['OvertimeMeal'],0);?></td>
					</tr>
                    <?php 
                        $x=$x+1;
                        endforeach;?>
				
				</tbody>
				
				<?php endif;?>

			</table>

			<form method="post" target="_blank" action="<?=site_url('presensi/report/overtime_preview')?>" class="table-footer button-height large-margin-bottom">
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
            document.location.href = '<?=site_url("presensi/report/overtime_paging")?>/' + redirect;
        }
        
     </script>   