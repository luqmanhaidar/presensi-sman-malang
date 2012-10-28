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
						<label for="large-label-1" class="label">Nama Group Durasi<small> ( max 100)</small></label>
						<input type="text" name="name" style="width:90%;" id="large-label-1" class="input medium-margin-right" value="<?php if($value<>'') echo $value['GroupDurationName'];  ?>" />
                        <input type="hidden" name="ID" value="<?php if($value<>'') echo $value['ID'];?>" />
                    </p>
					
					<p class="inline-large-label button-height">
						<label for="large-label-1" class="label">Jam Masuk<small> (jam masuk kantor.)</small></label>
                        <?php
                            if($value<>''):
                                $hour = substr($value['Start'],0,2);
                                $min  = substr($value['Start'],4,2);  
                                $hour2= substr($value['Finish'],0,2);
                                $min2 = substr($value['Finish'],4,2);
                            else:
                                $hour = '';
                                $min  = ''; 
                                $hour2= '';
                                $min2 = ''; 
                            endif;    
                        ?>
						<?=form_dropdown('hour',config_item('hour'),$hour,'class="select"')?>
                        <?=form_dropdown('minute',config_item('minute'),$min,'class="select"')?>
					</p>
                    <p class="inline-large-label button-height">
						<label for="large-label-1" class="label">Jam Pulang<small> (jam keluar kantor.)</small></label>
						<?=form_dropdown('hour2',config_item('hour'),$hour2,'class="select"')?>
                        <?=form_dropdown('minute2',config_item('minute'),$min2,'class="select"')?>
					</p>
					
					<p class="button-height">
						<input type="submit" class="button big" value="Simpan" />
						<a href="<?=site_url('employee/group/index/'.$this->session->userdata('group_offset'))?>" class="button big">Back</a>
					</p>

				</div>
			</div>
            </form>

		</div>