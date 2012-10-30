        <hgroup id="main-title" class="thin">
			<h1><?=$title?></h1>
		</hgroup>

		<div class="with-padding">
            
            <form method="post" action="<?=site_url('employee/user/update')?>" class="columns">
			<div class="columns">

				<div class="twelve-columns twelve-columns-tablet">

					<br/>	
					<h4 class="thin underline"><?=$title?></h4>

					<p class="inline-large-label button-height">
						<label for="large-label-1" class="label">ID User<small>( ID User)</small></label>
						<input type="text" name="large-label-1" disabled="disabled" id="large-label-1" class="input medium-margin-right" value="<?=$value['ID']?>" />
					    <input type="hidden" name="ID" value="<?=$value['ID']?>" />
                    </p>
					
					<p class="inline-large-label button-height">
						<label for="large-label-1" class="label">Group Durasi<small>(255 chars max.)</small></label>
						<?=form_dropdown('Group',$groups,$value['GroupDurationID'],'class="select"')?>
					</p>
                    
                    <p class="inline-large-label button-height">
						<label for="large-label-1" class="label">Senin-Kamis<small>(255 chars max.)</small></label>
						<?=form_dropdown('Work',$works,$value['GroupWork'],'class="select"')?>
					</p>
                    
                    <p class="inline-large-label button-height">
						<label for="large-label-1" class="label">Jumat<small>(255 chars max.)</small></label>
						<?=form_dropdown('Friday',$friday,$value['GroupFriday'],'class="select"')?>
					</p>
					
					<p class="button-height">
						<input type="submit" class="button big" value="Simpan" />
						<a href="<?=site_url('employee/user/index/'.$this->session->userdata('user_offset'))?>" class="button big">Back</a>
					</p>

				</div>
			</div>
            </form>

		</div>