<hgroup id="main-title" class="thin">
			<h1><?=$title?></h1>
		</hgroup>

		<div class="with-padding">
            
            <p class="wrapped left-icon icon-info-round">
				<a href="<?=site_url('presensi/holiday/add/')?>" class="button big">Add New</a> Add untuk menambah Group Durasi
			</p>

			<h4>Menu untuk mengatur <?=$title?></h4>

			<p>Simple sorting and manual controls if you prefer to handle the table output server-side:</p>

			<p><?=$this->session->flashdata('message')?></p>

			<div class="table-header button-height">
				<div class="float-right">
                    <form method="post" action="<?=site_url('presensi/holiday/search')?>">
					<?=form_dropdown('day',config_item('day'),substr($this->session->userdata('holiday_start'),3,2),'class="select"')?>
                    <?=form_dropdown('month',config_item('month'),substr($this->session->userdata('holiday_start'),0,2),'class="select"')?>
                    <?=form_dropdown('year',config_item('year'),substr($this->session->userdata('holiday_start'),6,4),'class="select"')?>
                    s/d
                    <?=form_dropdown('day2',config_item('day'),substr($this->session->userdata('holiday_finish'),3,2),'class="select"')?>
                    <?=form_dropdown('month2',config_item('month'),substr($this->session->userdata('holiday_finish'),0,2),'class="select"')?>
                    <?=form_dropdown('year2',config_item('year'),substr($this->session->userdata('holiday_finish'),6,4),'class="select"')?>
                    <?=form_dropdown('type',config_item('holiday_stype'),$this->session->userdata('holiday_type'),'id="holiday_type" class="select white-gradient glossy" ');?>
                    <input type="submit" class="button blue-gradient glossy" value="Go" />
                    </form>
                </div>

                <?=form_dropdown('show',config_item('per_page'),$this->session->userdata('holiday_paging'),'id="show" class="select blue-gradient glossy" onchange="changeUrl();" ');?>
			</div>
			<table class="table responsive-table" id="sorting-example1">

				<thead>
					<tr>
						<th scope="col" width="10%" class="align-left hide-on-mobile">Tanggal</th>
                        <th scope="col" width="12%" class="align-left hide-on-mobile">Type</th>
                        <th scope="col">Keterangan</th>
                        <th scope="col" class="align-center hide-on-mobile" width="20%" class="align-right">Actions</th>
					</tr>
				</thead>

				<tfoot>
					<tr>
						<td colspan="6">
							<?=COUNT($holiday).' Data ditemukan.'?>
						</td>
					</tr>
				</tfoot>

				<tbody>
					<?php foreach($holiday as $row):?>
					<tr>
						<td><?=$row['HolidayDate'];?></td>
                        <td><?=$row['HolidayType'];?></td>
                        <td><?=$row['HolidayDescription'];?></td>
						<td class="low-padding align-center">
                            <a href="<?=site_url('presensi/holiday/edit/'.$row['IndexKey'])?>" class="button white-gradient glossy">Update</a>
                            <a onclick="return confirm('<?=config_item('mess_remove').' '.$row['HolidayDate']?>')" href="<?=site_url('presensi/holiday/remove/'.$row['IndexKey'])?>" class="button white-gradient glossy">Remove</a>
						</td>
					</tr>
                    <?php endforeach;?>
				
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
                Show <?=form_dropdown('show2',config_item('per_page'),$this->session->userdata('holiday_paging'),'id="show2" class="select blue-gradient glossy" onchange="changeUrl2();" ');?>
			</form>
			
		</div>
        
     <script>
        function changeUrl() {
            var redirect;
            redirect = document.getElementById('show').value;
            document.location.href = '<?=site_url("presensi/holiday/paging")?>/' + redirect;
        }
        
        function changeUrl2() {
            var redirect;
            redirect = document.getElementById('show2').value;
            document.location.href = '<?=site_url("presensi/holiday/paging")?>/' + redirect;
        }
     </script>   