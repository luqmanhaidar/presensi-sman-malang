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
                                $key = $value['FunctionKey'];
                                $day = substr($value['MyDate'],0,2);
                                $month = substr($value['MyDate'],3,2);
                                $year = substr($value['MyDate'],6,4);
                                $hour = substr($value['MyTime'],0,2);
                                $min = substr($value['MyTime'],3,2);
                                $sec = substr($value['MyTime'],6,2);
                            else:
                                $ID  = '';
                                $key = 1;
                                $day = '';
                                $month ='';
                                $year='';
                                $hour = '';
                                $min ='';
                                $sec='';
                            endif; 
                        ?>
                        
						<?=form_dropdown('user',$users,$ID,'class="select" style="width:400px"')?>
                    </p>
                    
                    <p class="inline-large-label button-height">
						<label for="large-label-1" class="label">Function Key<small> ( Select Key)</small></label>
						<?=form_dropdown('key',config_item('fkey'),$key,'class="select"')?>
                        <input type="hidden" name="ID" value="<?php if($value<>'') echo $value['IndexKey'];?>" />
                    </p>
                    
                    <p class="inline-large-label button-height">
                        <label for="large-label-1" class="label">Tanggal<small> (Tanggal Berjalan)</small></label>
                        <?=form_dropdown('day',config_item('day'),$day,'class="select"')?>
                        <?=form_dropdown('month',config_item('month'),$month,'class="select"')?>
                        <?=form_dropdown('year',config_item('year'),$year,'class="select"')?>
                    </p>
                    
					<p class="inline-large-label button-height">
						<label for="large-label-1" class="label">Jam <small> (jam kantor.)</small></label>
						<?=form_dropdown('hour',config_item('hour'),$hour,'class="select"')?>
                        <?=form_dropdown('minute',config_item('minute'),$min,'class="select"')?>
                        <?=form_dropdown('second',config_item('minute'),$sec,'class="select"')?>
					</p>
                   
					
					<p class="button-height">
						<input type="submit" class="button big" value="Simpan" />
						<a href="<?=site_url('presensi/checkin/index/'.$this->session->userdata('log_offset'))?>" class="button big">Back</a>
					</p>

				</div>
			</div>
            </form>

		</div>