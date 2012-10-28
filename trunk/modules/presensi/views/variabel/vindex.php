<hgroup id="main-title" class="thin">
			<h1><?=$title?></h1>
		</hgroup>

		<div class="with-padding">
            
            <p class="wrapped left-icon icon-info-round">
				<a href="<?=site_url('presensi/variabel/add/')?>" class="button big">Add New</a> Add untuk menambah Group Durasi
			</p>

			<h4>Menu untuk mengatur <?=$title?></h4>

			<p>Simple sorting and manual controls if you prefer to handle the table output server-side:</p>

			<p><?=$this->session->flashdata('message')?></p>

			<div class="table-header button-height">
				<div class="float-right">
                    <form method="post" action="<?=site_url('presensi/variabel/search')?>">
					Search&nbsp;<input type="text" name="table_search" id="table_search" value="<?=$this->session->userdata('variabel_search')?>" class="input mid-margin-left">
                    <input type="submit" class="button blue-gradient glossy" value="Go" />
                    </form>
                </div>
                
				Show&nbsp;
                <?=form_dropdown('show',config_item('per_page'),$this->session->userdata('variabel_paging'),'id="show" class="select blue-gradient glossy" onchange="changeUrl();" ');?>
			</div>
			<table class="table responsive-table" id="sorting-example1">

				<thead>
					<tr>
						<th scope="col" width="7%">ID</th>
						<th scope="col" width="10%" class="align-left hide-on-mobile">Variabel</th>
                        <th scope="col">Keterangan</th>
                        <th scope="col" width="15%">Jenis</th>
                        <th scope="col" width="10%">Nilai</th>
                        <th scope="col" class="align-center hide-on-mobile" width="20%" class="align-right">Actions</th>
					</tr>
				</thead>

				<tfoot>
					<tr>
						<td colspan="6">
							<?=COUNT($variabel).' Data ditemukan.'?>
						</td>
					</tr>
				</tfoot>

				<tbody>
					<?php foreach($variabel as $row):?>
					<tr>
						<td><?=$row['VariabelID'];?></td>
						<td><?=$row['VariabelName'];?></td>
                        <td><?=$row['VariabelDescription'];?></td>
                        <td><?=$row['VariabelType'];?></td>
                        <td><?=$row['VariabelValue'];?></td>
						<td class="low-padding align-center">
                            <a href="<?=site_url('presensi/variabel/edit/'.$row['VariabelID'])?>" class="button white-gradient glossy">Update</a>
                            <a onclick="return confirm('<?=config_item('mess_remove').' '.$row['VariabelName']?>')" href="<?=site_url('presensi/variabel/remove/'.$row['VariabelID'])?>" class="button white-gradient glossy">Remove</a>
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
                Show <?=form_dropdown('show2',config_item('per_page'),$this->session->userdata('variabel_paging'),'id="show2" class="select blue-gradient glossy" onchange="changeUrl2();" ');?>
			</form>
			
		</div>
        
     <script>
        function changeUrl() {
            var redirect;
            redirect = document.getElementById('show').value;
            document.location.href = '<?=site_url("presensi/variabel/paging")?>/' + redirect;
        }
        
        function changeUrl2() {
            var redirect;
            redirect = document.getElementById('show2').value;
            document.location.href = '<?=site_url("presensi/variabel/paging")?>/' + redirect;
        }
     </script>   