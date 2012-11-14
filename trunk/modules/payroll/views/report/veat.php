<hgroup id="main-title" class="thin">
			<h1><?=$title?></h1>
		</hgroup>

		<div class="with-padding">
            
			<h4>Print Preview <?=$title?></h4>

			<p>Simple sorting and manual controls if you prefer to handle the table output server-side:</p>

			<p><?=$this->session->flashdata('message')?></p>
            
			<div class="table-header button-height">
				<div class="float-right">
                    <form method="post" action="<?=site_url('payroll/report/eat_search')?>">
                    <?=form_dropdown('group',$groups,$this->session->userdata('eat_group'),'id="group" class="select white-gradient glossy" ');?>
					<?=form_dropdown('day',config_item('day'),substr($this->session->userdata('eat_start'),3,2),'class="select"')?>
                    <?=form_dropdown('month',config_item('month'),substr($this->session->userdata('eat_start'),0,2),'class="select"')?>
                    <?=form_dropdown('year',config_item('year'),substr($this->session->userdata('eat_start'),6,4),'class="select"')?>
                    s/d
                    <?=form_dropdown('day2',config_item('day'),substr($this->session->userdata('eat_finish'),3,2),'class="select"')?>
                    <?=form_dropdown('month2',config_item('month'),substr($this->session->userdata('eat_finish'),0,2),'class="select"')?>
                    <?=form_dropdown('year2',config_item('year'),substr($this->session->userdata('eat_finish'),6,4),'class="select"')?>
                    
                    <?=form_input('holiday',$this->session->userdata('eat_holiday'),'id="holiday" class="input white-gradient glossy" ');?>
                    <input type="submit" class="button blue-gradient glossy" value="Go" />
                    </form>
                </div>
                
				
                <?=form_dropdown('show',config_item('per_page'),$this->session->userdata('eat_paging'),'id="show" class="select blue-gradient glossy" onchange="changeUrl();" ');?>
			</div>
			<table class="table responsive-table" id="sorting-example1">

				<thead>
					<tr>
						<th scope="col" width="5%">No</th>
						<th scope="col" width="10%" class="align-left hide-on-mobile">ID User</th>
                        <th scope="col" class="align-left hide-on-mobile">Nama</th>
						<th scope="col" class="align-left hide-on-mobile">Jabatan</th>
                        <th scope="col" class="align-center hide-on-mobile">Hari</th>
                        <th scope="col" class="align-center hide-on-mobile">Libur</th>
						<th scope="col" class="align-center hide-on-mobile">Jumlah</th>
						<th scope="col" width="5%" class="align-left hide-on-mobile">Paraf</th>
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
						<td><?=$row['GroupName'];?></td>
                        <td class="align-right hide-on-mobile"><?=$row['Total'];?></td>
                        <td class="align-right hide-on-mobile"><?=$this->session->userdata('eat_holiday');?></td>
						<td class="align-right hide-on-mobile"><?=number_format($total=($row['Total']-$this->session->userdata('eat_holiday')) * $var,0);?></td>
						<td><img style="width:20px;height:auto;" src="<?=base_url('assets/signature/'.$row['UserID'].'.jpg')?>" /></td>
					</tr>
                    <?php 
                        $x=$x+1;
                        endforeach;?>
				
				</tbody>
				
				<tfoot>
					<tr>
						<td colspan="8">
							<?=COUNT($checks).' Data ditemukan.'?>
						</td>
					</tr>
				</tfoot>


			</table>

			<form method="post" target="_blank" action="<?=site_url('payroll/report/eat_preview')?>" class="table-footer button-height large-margin-bottom">
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
            document.location.href = '<?=site_url("payroll/report/eat_paging")?>/' + redirect;
        }
        
        /*function changeUrl2() {
            var redirect;
            redirect = document.getElementById('show2').value;
            document.location.href = '<?=site_url("presensi/report/month_paging")?>/' + redirect;
        }*/
     </script>   