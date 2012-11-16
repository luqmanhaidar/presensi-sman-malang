        <hgroup id="main-title" class="thin">
			<h1><?=$title?></h1>
		</hgroup>

		<div class="with-padding">
            
            <form method="post" action="<?=site_url($action)?>" class="columns">
			<div class="columns">

				<div class="twelve-columns twelve-columns-tablet">

						
					<h4 class="thin underline"><?=$title?></h4>
                    
                    <?php
                        if($value<>''):
                            $type = $value['HolidayType'];
							$index = $value['IndexKey'];
                        else:
                            $type = '';
							$index='';
                        endif; 
                    ?>
                    
                    <p class="inline-large-label button-height">
						<label for="large-label-1" class="label">Type<small> ( Type )</small></label>
						<?=form_dropdown('type',config_item('holiday_type'),$type,'class="select" id="holidaytype" ')?>
                        <?=form_hidden('ID',$index)?>
                    </p>
                    
                    <?php
                            if($value<>''):
                                $day = substr($value['MyDate'],0,2);
                                $month = substr($value['MyDate'],3,2);
                                $year = substr($value['MyDate'],6,4);
                            else:
                                $day = '';
                                $month ='';
                                $year='';
                            endif; 
                    ?>
                    
					<p class="inline-large-label button-height" style="width: 400px;">
                        <label for="large-label-1" class="label">Tanggal<small> (Tanggal)</small></label>
                        <?=form_dropdown('day',config_item('day'),$day,'class="select"')?>
                        <?=form_dropdown('month',config_item('month'),$month,'class="select"')?>
                        <?=form_dropdown('year',config_item('year'),$year,'class="select"')?>
                    </p>
                    
                    <p class="inline-large-label button-height">
						<label for="large-label-1" class="label">Keterangan<small> ( max 100)</small></label>
						<input type="text" name="description" style="width:100%;" id="large-label-1" class="input large-margin-right" value="<?php if($value<>'') echo $value['HolidayDescription'];  ?>" />
                    </p>
                    
                    
					<p class="button-height">
						<input type="submit" class="button big" value="Simpan" />
						<a href="<?=site_url('presensi/holiday/index/'.$this->session->userdata('holiday_offset'))?>" class="button big">Back</a>
					</p>

				</div>
			</div>
            </form>

		</div>