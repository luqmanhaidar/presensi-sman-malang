        <hgroup id="main-title" class="thin">
			<h1><?=$title?></h1>
		</hgroup>

		<div class="with-padding">
            
            <form method="post" action="<?=site_url($action)?>" class="columns">
			<div class="columns">

				<div class="twelve-columns twelve-columns-tablet">

					<br/>	
					<h4 class="thin underline"><?=$title?></h4>

					<p class="inline-large-label button-height">
						<label for="large-label-1" class="label">ID User<small> ( Auto Complete)</small></label>
                        <?php
                            if($value<>''):
                                $ID  = $value['UserID'];
                                $day = substr($value['MyDate'],0,2);
                                $month = substr($value['MyDate'],3,2);
                                $year = substr($value['MyDate'],6,4);
                            else:
                                $ID  = '';
                                $day = '';
                                $month ='';
                                $year='';
                            endif; 
                        ?>
                        
						<?=form_dropdown('user',$users,$ID,'class="select" style="width:400px"')?>
                    </p>
                    
                    
                    <p class="inline-large-label button-height">
                        <label for="large-label-1" class="label">Tanggal<small> (Tanggal Berjalan)</small></label>
                        <?=form_dropdown('day',config_item('day'),$day,'class="select"')?>
                        <?=form_dropdown('month',config_item('month'),$month,'class="select"')?>
                        <?=form_dropdown('year',config_item('year'),$year,'class="select"')?>
                        <input type="hidden" name="ID" value="<?php if($value<>'') echo $value['OvertimeID'];  ?>" />
                    </p>
                    
				    <p class="inline-large-label button-height">
						<label for="large-label-1" class="label">Keterangan<small> ( max 200)</small></label>
						<input type="text" name="description" style="width:90%;" id="large-label-1" class="input medium-margin-right" value="<?php if($value<>'') echo $value['OvertimeDescription'];  ?>" />
                        
                    </p>
                   
					
					<p class="button-height">
						<input type="submit" class="button big" value="Simpan" />
						<a href="<?=site_url('presensi/overtime/index/'.$this->session->userdata('overtime_offset'))?>" class="button big">Back</a>
					</p>

				</div>
			</div>
            </form>

		</div>