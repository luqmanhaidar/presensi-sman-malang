<hgroup id="main-title" class="thin">
			<h1><?=$title?></h1>
		</hgroup>

		<div class="with-padding">
            
			<h4>Print Preview <?=$title?></h4>

			<p>Simple sorting and manual controls if you prefer to handle the table output server-side:</p>

			<p><?=$this->session->flashdata('message')?></p>
            
			<div class="table-header button-height">
				<div class="float-right">
                    <form method="post" action="<?=site_url('presensi/report/month_search')?>">
                    <?=form_dropdown('group',$groups,$this->session->userdata('month_group'),'id="group" class="select white-gradient glossy" ');?>
					<?=form_dropdown('month',config_item('month'),$this->session->userdata('month_search'),'class="select"')?>
                    <?=form_dropdown('year',config_item('year'),$this->session->userdata('year_search'),'class="select"')?>
                    <input type="submit" class="button blue-gradient glossy" value="Go" />
                    </form>
                </div>
                
				Show&nbsp;
                <?=form_dropdown('show',config_item('per_page'),$this->session->userdata('month_paging'),'id="show" class="select blue-gradient glossy" onchange="changeUrl();" ');?>
			</div>
			<table class="table responsive-table" id="sorting-example1">

				<thead>
					<tr>
						<th scope="col" width="5%">No</th>
						<th scope="col" width="10%" class="align-left hide-on-mobile">ID User</th>
                        <th scope="col" class="align-left hide-on-mobile">Nama</th>
						<th scope="col" class="align-left hide-on-mobile">Bagian</th>
                        <th scope="col">Tgl Awal</th>
						<th scope="col">Tgl.Akhir</th>
					</tr>
				</thead>

				<tfoot>
					<tr>
						<td colspan="6">
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
						<td><?=$row['Department'];?></td>
                        <td><?=indonesian_shortDate($row['TransactionTime']).' '.substr($row['TransactionTime'],11,8);?></td>
						<td><?=indonesian_shortDate($row['TransactionTimeMax']) .' '.substr($row['TransactionTimeMax'],11,8);?></td>
					</tr>
                    <?php 
                        $x=$x+1;
                        endforeach;?>
				
				</tbody>

			</table>

			<form method="post" action="<?=site_url('presensi/report/month_preview')?>" class="table-footer button-height large-margin-bottom">
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
            document.location.href = '<?=site_url("presensi/report/month_paging")?>/' + redirect;
        }
        
        /*function changeUrl2() {
            var redirect;
            redirect = document.getElementById('show2').value;
            document.location.href = '<?=site_url("presensi/report/month_paging")?>/' + redirect;
        }*/
     </script>   