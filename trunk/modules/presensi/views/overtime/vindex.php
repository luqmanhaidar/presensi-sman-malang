<hgroup id="main-title" class="thin">
			<h1><?=$title?></h1>
		</hgroup>

		<div class="with-padding">
            
            <p class="wrapped left-icon icon-info-round">
				<a href="<?=site_url('presensi/overtime/add/')?>" class="button big">Add New</a> Add untuk menambah <?=$title?>
			</p>

			<h4>Menu untuk mengatur <?=$title?></h4>

			<p>Simple sorting and manual controls if you prefer to handle the table output server-side:</p>

			<p><?=$this->session->flashdata('message')?></p>
            
			<div class="table-header button-height">
				<div class="float-right">
                    <form method="post" action="<?=site_url('presensi/overtime/search')?>">
					ID User&nbsp;<input type="text" placeholder="All User" name="table_search" style="width:10%;" id="table_search" value="<?=$this->session->userdata('overtime_search')?>" class="input mid-margin-left">
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
                
				
                <?=form_dropdown('show',config_item('per_page'),$this->session->userdata('overtime_paging'),'id="show" class="select blue-gradient glossy" onchange="changeUrl();" ');?>
			</div>
			<table class="table responsive-table" id="sorting-example1">

				<thead>
					<tr>
						<th scope="col" width="5%">No</th>
						<th scope="col" width="10%" class="align-left hide-on-mobile">ID User</th>
						<th scope="col" width="20%" class="align-left hide-on-mobile">Nama</th>
                        <th scope="col" width="10%">Tanggal</th>
                        <th scope="col">Keterangan</th>
                        <th scope="col" class="align-center hide-on-mobile" width="20%" class="align-right">Actions</th>
					</tr>
				</thead>

				<tfoot>
					<tr>
						<td colspan="6">
							<?=COUNT($overtime).' Data ditemukan.'?>
						</td>
					</tr>
				</tfoot>

				<tbody>
					<?php 
                        $i=1;
                        foreach($overtime as $row):?>
					<tr>
						<td><?=$i;?></td>
						<td><?=$row['UserID'];?></td>
						<td><?=$row['Name'];?></td>
                        <td><?=indonesian_shortDate($row['OvertimeDate']);?></td>
                        <td><?=$row['OvertimeDescription'];?></td>
						<td class="low-padding align-center">
                            <a href="<?=site_url('presensi/overtime/edit/'.$row['OvertimeID'])?>" class="button white-gradient glossy">Update</a>
                            <a onclick="return confirm('<?=config_item('mess_remove').' '.$row['UserID'].' Pada Tanggal '.indonesian_shortDate($row['OvertimeDate'])?>')" href="<?=site_url('presensi/overtime/remove/'.$row['OvertimeID'])?>" class="button white-gradient glossy">Remove</a>
						</td>
					</tr>
                    <?php 
                        $i++;
                        endforeach;?>
				
				</tbody>

			</table>

			<form method="post" action="" class="table-footer button-height large-margin-bottom">
                <div class="float-right">
                    <div class="button-group">
                      <?php if(!empty($pagination))
                                echo $pagination;
                      ?>
                    </div>
				</div>
                Show <?=form_dropdown('show2',config_item('per_page'),$this->session->userdata('overtime_paging'),'id="show2" class="select blue-gradient glossy" onchange="changeUrl2();" ');?>
			</form>
			
		</div>
        
     <script>
        function changeUrl() {
            var redirect;
            redirect = document.getElementById('show').value;
            document.location.href = '<?=site_url("presensi/overtime/paging")?>/' + redirect;
        }
        
        function changeUrl2() {
            var redirect;
            redirect = document.getElementById('show2').value;
            document.location.href = '<?=site_url("presensi/overtime/paging")?>/' + redirect;
        }
     </script>   