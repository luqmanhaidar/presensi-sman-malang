<hgroup id="main-title" class="thin">
			<h1><?=$title?></h1>
		</hgroup>

		<div class="with-padding">
            
			<h4>Print Preview <?=$title?></h4>

			<p>Simple sorting and manual controls if you prefer to handle the table output server-side:</p>

			<p><?=$this->session->flashdata('message')?></p>
            
			<div class="table-header button-height">
				<div class="float-right">
                    <form method="post" action="<?=site_url('presensi/report/se_search')?>">
                    <?=form_dropdown('group',$groups,$this->session->userdata('se_group'),'id="group" class="select white-gradient glossy" ');?>
                    <?=form_dropdown('month',config_item('month'),$this->session->userdata('se_month'),'class="select"')?>
                    <?=form_dropdown('year',config_item('year'),$this->session->userdata('se_year'),'class="select"')?>
                    <input type="submit" class="button blue-gradient glossy" value="Go" />
                    </form>
                </div>
                
			
                <?=form_dropdown('show',config_item('per_page'),$this->session->userdata('se_paging'),'id="show" class="select blue-gradient glossy" onchange="changeUrl();" ');?>
			</div>
			<table class="table responsive-table" id="sorting-example1">

				<thead>
					<tr>
						<th scope="col" width="5%">No</th>
						<th scope="col" width="10%" class="align-left hide-on-mobile">ID User</th>
                        <th scope="col" class="align-left hide-on-mobile">Nama</th>
                        <th scope="col" width="5%" class="align-left hide-on-mobile">Paraf</th>
						<th scope="col" class="align-left hide-on-mobile">Bagian</th>
                        <th scope="col">Tgl Awal</th>
						<th scope="col">Tgl.Akhir</th>
					</tr>
				</thead>

				<tfoot>
					<tr>
						<td colspan="7">
							<?=COUNT($checks).' Data ditemukan.'?>
						</td>
					</tr>
				</tfoot>

				<tbody>
					<?php 
                        $x=1;
                        foreach($checks as $row):?>
					<tr>
						<td><?=$x;?></td>
						<td><?=$row['UserID'];?></td>
                        <td><?=$row['Name'];?></td>
                        <td><img style="width:20px;height:auto;" src="<?=base_url('assets/signature/'.$row['UserID'].'.jpg')?>" /></td>
						<td><?=$row['Department'];?></td>
                        <td><?=indonesian_shortDate($row['TransactionTime']).' '.substr($row['TransactionTime'],11,8);?></td>
						<td><?=indonesian_shortDate($row['TransactionTimeMax']) .' '.substr($row['TransactionTimeMax'],11,8);?></td>
					</tr>
                    <?php 
                        $x=$x+1;
                        endforeach;?>
				
				</tbody>

			</table>

			<form method="post" target="_blank" action="<?=site_url('presensi/report/se_preview')?>" class="table-footer button-height large-margin-bottom">
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
            document.location.href = '<?=site_url("presensi/report/se_paging")?>/' + redirect;
        }
     </script>   