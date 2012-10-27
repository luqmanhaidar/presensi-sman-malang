<hgroup id="main-title" class="thin">
			<h1>Manage User Grup</h1>
		</hgroup>

		<div class="with-padding">

			<p class="wrapped left-icon icon-info-round">
				
				<a href="add-edit-user.html" class="button big">Add New</a>  Add untuk menambah User 
			</p>

			
			<h4>Menu untuk mengatur User</h4>

			<p>Simple sorting and manual controls if you prefer to handle the table output server-side:</p>

			<p><b>Tip:</b> try clicking on a row to show an extra line style!</p>

			<div class="table-header button-height">
				<div class="float-right">
					Search&nbsp;<input type="text" name="table_search" id="table_search" value="" class="input mid-margin-left">
				</div>

				Show&nbsp;<select name="range" class="select blue-gradient glossy">
					<option value="1">10</option>
					<option value="2">20</option>
					<option value="3" selected="selected">40</option>
					<option value="4">100</option>
				</select> entries
			</div>
			<table class="table responsive-table" id="sorting-example1">

				<thead>
					<tr>
						<th scope="col"><input type="checkbox" name="check-all" id="check-all" value="1"></th>
						<th scope="col" width="7%">ID</th>
						<th scope="col" class="align-left hide-on-mobile">Nama User</th>
						<th scope="col" class="align-left hide-on-mobile">Nama Kelompok</th>
						<th scope="col" width="100" class="align-right">Actions</th>
					</tr>
				</thead>

				<tfoot>
					<tr>
						<td colspan="6">
							<?=COUNT($users).' Data ditemukan.'?>
						</td>
					</tr>
				</tfoot>

				<tbody>
					<?php foreach($users as $row):?>
					<tr>
						<th scope="row" class="checkbox-cell"><input type="checkbox" name="checked[]" id="check-2" value="2"></th>
						<td><?=$row['ID'];?></td>
						<td><?=$row['Name'];?></td>
						<td><?='-'?></td>
						<td class="low-padding">
							<span class="select compact full-width" tabindex="0">
								<a href="javascript:void(0);" class="select-value">Edit</a>
								<span class="select-arrow"></span>
								<span class="drop-down">
									<a href="javascript:void(0);">Put offline</a>
									<a href="javascript:void(0);">Review</a>
									<a href="javascript:void(0);">Put to trash</a>
									<a href="javascript:void(0);">Delete</a>
								</span>
							</span>
						</td>
					</tr>
                    <?php endforeach;?>
				
				</tbody>

			</table>

			<form method="post" action="" class="table-footer button-height large-margin-bottom">
				<div class="float-right">
					<div class="button-group">
						<a href="javascript:void(0);" title="First page" class="button blue-gradient glossy">First</a>
						<a href="javascript:void(0);" title="Previous page" class="button blue-gradient glossy">Previous</a>
					</div>

					<div class="button-group">
						<a href="javascript:void(0);" title="Page 1" class="button blue-gradient glossy">1</a>
						<a href="javascript:void(0);" title="Page 2" class="button blue-gradient glossy active">2</a>
						<a href="javascript:void(0);" title="Page 3" class="button blue-gradient glossy">3</a>
						<a href="javascript:void(0);" title="Page 4" class="button blue-gradient glossy">4</a>
					</div>

					<div class="button-group">
						<a href="javascript:void(0);" title="Next page" class="button blue-gradient glossy">Next</span></a>
						<a href="javascript:void(0);" title="Last page" class="button blue-gradient glossy">Last</a>
					</div>
				</div>

				With selected:
				<select name="select90" class="select blue-gradient glossy mid-margin-left">
					<option value="0">Delete</option>
					<option value="1">Duplicate</option>
					<option value="2">Put offline</option>
					<option value="3">Put online</option>
					<option value="4">Move to trash</option>
				</select>
				<button type="submit" class="button blue-gradient glossy">Go</button>
			</form>

			
			

		</div>