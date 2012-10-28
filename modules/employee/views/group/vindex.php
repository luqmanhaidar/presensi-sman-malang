<hgroup id="main-title" class="thin">
			<h1><?=$title?></h1>
		</hgroup>

		<div class="with-padding">
            
            <p class="wrapped left-icon icon-info-round">
				<a href="<?=site_url('employee/group/add/')?>" class="button big">Add New</a> Add untuk menambah Group Durasi
			</p>

			<h4>Menu untuk mengatur <?=$title?></h4>

			<p>Simple sorting and manual controls if you prefer to handle the table output server-side:</p>

			<p><?=$this->session->flashdata('message')?></p>

			<div class="table-header button-height">
				<div class="float-right">
                    <form method="post" action="<?=site_url('employee/group/search')?>">
					Search&nbsp;<input type="text" name="table_search" id="table_search" value="<?=$this->session->userdata('group_search')?>" class="input mid-margin-left">
                    <input type="submit" class="button blue-gradient glossy" value="Go" />
                    </form>
                </div>
                
				Show&nbsp;
                <?=form_dropdown('show',config_item('per_page'),$this->session->userdata('group_paging'),'id="show" class="select blue-gradient glossy" onchange="changeUrl();" ');?>
			</div>
			<table class="table responsive-table" id="sorting-example1">

				<thead>
					<tr>
						<th scope="col" width="7%">ID</th>
						<th scope="col" class="align-left hide-on-mobile">Nama Group Durasi</th>
                        <th scope="col" width="10%">Jam Masuk</th>
                        <th scope="col" width="10%">Jam Keluar</th>
                        <th scope="col" class="align-center hide-on-mobile" width="20%" class="align-right">Actions</th>
					</tr>
				</thead>

				<tfoot>
					<tr>
						<td colspan="5">
							<?=COUNT($groups).' Data ditemukan.'?>
						</td>
					</tr>
				</tfoot>

				<tbody>
					<?php foreach($groups as $row):?>
					<tr>
						<td><?=$row['ID'];?></td>
						<td><?=$row['GroupDurationName'];?></td>
                        <td><?=$row['Start'];?></td>
                        <td><?=$row['Finish'];?></td>
						<td class="low-padding align-center">
                            <a href="<?=site_url('employee/group/edit/'.$row['ID'])?>" class="button white-gradient glossy">Update</a>
                            <a onclick="return confirm('<?=config_item('mess_remove').' '.$row['GroupDurationName']?>')" href="<?=site_url('employee/group/remove/'.$row['ID'])?>" class="button white-gradient glossy">Remove</a>
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
                Show <?=form_dropdown('show2',config_item('per_page'),$this->session->userdata('group_paging'),'id="show2" class="select blue-gradient glossy" onchange="changeUrl2();" ');?>
			</form>
			
		</div>
        
     <script>
        function changeUrl() {
            var redirect;
            redirect = document.getElementById('show').value;
            document.location.href = '<?=site_url("employee/group/paging")?>/' + redirect;
        }
        
        function changeUrl2() {
            var redirect;
            redirect = document.getElementById('show2').value;
            document.location.href = '<?=site_url("employee/group/paging")?>/' + redirect;
        }
     </script>   