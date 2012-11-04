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
                                $type = $value['OtherType'];
                                $day = substr($value['OtherDateStart'],0,2);
                                $month = substr($value['OtherDateStart'],3,2);
                                $year = substr($value['OtherDateStart'],6,4);
                                $day2 = substr($value['OtherDateFinish'],0,2);
                                $month2 = substr($value['OtherDateFinish'],3,2);
                                $year2 = substr($value['OtherDateFinish'],6,4);
                            else:
                                $ID  = '';
                                $type='';
                                $day = '';
                                $month ='';
                                $year='';
                                $day2 = '';
                                $month2 ='';
                                $year2='';
                            endif; 
                        ?>
                        
						<?=form_dropdown('user',$users,$ID,'class="select" style="width:400px"')?>
                    </p>
                    
                    <p class="inline-large-label button-height">
						<label for="large-label-1" class="label">Type<small> ( Select Type)</small></label>
						<?=form_dropdown('type',config_item('type'),$type,'class="select"')?>
                    </p>
                    
                    <p class="inline-large-label button-height">
                        <label for="large-label-1" class="label">Tanggal<small> (Tanggal Berjalan)</small></label>
                        <?=form_dropdown('day',config_item('day'),$day,'class="select"')?>
                        <?=form_dropdown('month',config_item('month'),$month,'class="select"')?>
                        <?=form_dropdown('year',config_item('year'),$year,'class="select"')?>
                    </p>
                    
                    <!--<p class="inline-large-label button-height">
                        <label for="large-label-1" class="label">Sampai Tanggal<small> (Tanggal Terakhir)</small></label>
                        <?=form_dropdown('day2',config_item('day'),$day2,'class="select"')?>
                        <?=form_dropdown('month2',config_item('month'),$month2,'class="select"')?>
                        <?=form_dropdown('year2',config_item('year'),$year2,'class="select"')?>
                        <input type="hidden" name="ID" value="<?php if($value<>'') echo $value['OtherID'];  ?>" />
                    </p>
                    -->
                    
				    <p class="inline-large-label button-height">
						<label for="large-label-1" class="label">Keterangan<small> ( max 200)</small></label>
						<input type="text" name="description" style="width:90%;" id="large-label-1" class="input medium-margin-right" value="<?php if($value<>'') echo $value['OtherDescription'];  ?>" />
                        
                    </p>
                   	
					<p class="button-height">
						<input type="submit" class="button big" value="Simpan" />
						<a href="<?=site_url('presensi/other/index/'.$this->session->userdata('other_offset'))?>" class="button big">Back</a>
					</p>

				</div>
			</div>
            </form>

		</div>