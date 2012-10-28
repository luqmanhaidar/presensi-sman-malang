<hgroup id="main-title" class="thin">
			<h1><?=$title?></h1>
		</hgroup>

		<div class="with-padding">

			<!--<p class="wrapped left-icon icon-info-round">
				
				<a href="add-edit-user.html" class="button big">Add New</a>  Add untuk menambah User 
			</p>
            -->
				<h4>Menu untuk mengatur <?=$title?></h4>

			<p>Simple sorting and manual controls if you prefer to handle the table output server-side:</p>

			<p><?=$this->session->flashdata('update_success')?></p>

			<div class="table-header button-height">
				<div class="float-right">
                    <form method="post" action="<?=site_url('employee/history/search')?>">
					Search&nbsp;<input type="text" name="table_search" id="table_search" value="<?=$this->session->userdata('logHistory_search')?>" class="input mid-margin-left">
				    <?php //form_dropdown('table_group',$groups,$this->session->userdata('admin_group'),'id="group" class="select white-gradient glossy" ');?>
                    <input type="submit" class="button blue-gradient glossy" value="Go" />
                    </form>
                </div>
                
				Show&nbsp;
                <?=form_dropdown('show',config_item('per_page'),$this->session->userdata('logHistory_paging'),'id="show" class="select blue-gradient glossy" onchange="changeUrl();" ');?>
			</div>
			<table class="table responsive-table" id="sorting-example1">

				<thead>
					<tr>
						<th scope="col" width="10%">Tanggal</th>
						<th scope="col" class="align-left hide-on-mobile">jam</th>
						<th scope="col" class="align-left hide-on-mobile">Log Name</th>
						<th scope="col" class="align-left hide-on-mobile">Keterangan</th>
					</tr>
				</thead>

				<tfoot>
					<tr>
						<td colspan="4">
							<?=COUNT($users).' Data ditemukan.'?>
                            
                            
						</td>
					</tr>
				</tfoot>

				<tbody>
					<?php foreach($users as $row):?>
					<tr>
						<td><?=$row['LogDate'];?></td>
						<td><?=$row['LogTime'];?></td>
						<td><?=$row['LogName'];?></td>
						<td><?=$row['LogDescription'];?></td>
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
                
           
                Show <?=form_dropdown('show2',config_item('per_page'),$this->session->userdata('logHistory_paging'),'id="show2" class="select blue-gradient glossy" onchange="changeUrl2();" ');?>
			</form>
			
		</div>
        
     <script>
        function changeUrl() {
            var redirect;
            redirect = document.getElementById('show').value;
            document.location.href = '<?=site_url("employee/history/paging")?>/' + redirect;
        }
        
        function changeUrl2() {
            var redirect;
            redirect = document.getElementById('show2').value;
            document.location.href = '<?=site_url("employee/history/paging")?>/' + redirect;
        }
     </script>   