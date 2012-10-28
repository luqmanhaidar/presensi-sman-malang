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
                    <form method="post" action="<?=site_url('employee/user/search')?>">
					Search&nbsp;<input type="text" name="table_search" id="table_search" value="<?=$this->session->userdata('user_search')?>" class="input mid-margin-left">
				    <?=form_dropdown('table_group',$groups,$this->session->userdata('user_group'),'id="group" class="select white-gradient glossy" ');?>
                    <input type="submit" class="button blue-gradient glossy" value="Go" />
                    </form>
                </div>
                
				Show&nbsp;
                <?=form_dropdown('show',config_item('per_page'),$this->session->userdata('user_paging'),'id="show" class="select blue-gradient glossy" onchange="changeUrl();" ');?>
			</div>
			<table class="table responsive-table" id="sorting-example1">

				<thead>
					<tr>
						<th scope="col" width="7%">ID</th>
						<th scope="col" class="align-left hide-on-mobile">Nama User</th>
						<th scope="col" class="align-left hide-on-mobile">Nama Kelompok</th>
						<th scope="col" class="align-center hide-on-mobile" width="100" class="align-right">Actions</th>
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
						<td><?=$row['ID'];?></td>
						<td><?=$row['Name'];?></td>
						<td><?=$row['GroupDurationName'];?></td>
						<td class="low-padding align-center">
                            <a href="<?=site_url('employee/user/edit/'.$row['ID'])?>" class="button white-gradient glossy">Update</a>
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
                
                <!--
				With selected:
				<select name="select90" class="select blue-gradient glossy mid-margin-left">
					<option value="0">Delete</option>
					<option value="1">Duplicate</option>
					<option value="2">Put offline</option>
					<option value="3">Put online</option>
					<option value="4">Move to trash</option>
				</select>
				<button type="submit" class="button blue-gradient glossy">Go</button>
                -->
                Show <?=form_dropdown('show2',config_item('per_page'),$this->session->userdata('user_paging'),'id="show2" class="select blue-gradient glossy" onchange="changeUrl2();" ');?>
			</form>
			
		</div>
        
     <script>
        function changeUrl() {
            var redirect;
            redirect = document.getElementById('show').value;
            document.location.href = '<?=site_url("employee/user/paging")?>/' + redirect;
        }
        
        function changeUrl2() {
            var redirect;
            redirect = document.getElementById('show2').value;
            document.location.href = '<?=site_url("employee/user/paging")?>/' + redirect;
        }
     </script>   