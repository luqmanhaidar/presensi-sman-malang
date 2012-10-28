        <hgroup id="main-title" class="thin">
			<h1><?=$title?></h1>
		</hgroup>

		<div class="with-padding">
            
            <form method="post" action="<?=site_url($action)?>" class="columns">
			<div class="columns">

				<div class="twelve-columns twelve-columns-tablet">

						
					<h4 class="thin underline"><?=$title?></h4>

					<p class="inline-large-label button-height">
						<label for="large-label-1" class="label">Variabel<small> ( max 3)</small></label>
						<input type="text" maxlength="3" name="name" style="width:30%;" id="large-label-1" class="input medium-margin-right" value="<?php if($value<>'') echo $value['VariabelName'];  ?>" />
                        <input type="hidden" name="ID" value="<?php if($value<>'') echo $value['VariabelID'];?>" />
                    </p>
                    
                    <p class="inline-large-label button-height">
						<label for="large-label-1" class="label">Keterangan<small> ( max 100)</small></label>
						<input type="text" name="description" style="width:200%;" id="large-label-1" class="input large-margin-right" value="<?php if($value<>'') echo $value['VariabelDescription'];  ?>" />
                    </p>
                    
                    <p class="inline-large-label button-height">
						<label for="large-label-1" class="label">Type<small> ( max 100)</small></label>
						<input type="text" name="type" style="width:90%;" id="large-label-1" class="input medium-margin-right" value="<?php if($value<>'') echo $value['VariabelType'];  ?>" />
                    </p>
                    
                    <p class="inline-large-label button-height">
						<label for="large-label-1" class="label">Nilai<small> ( max 100)</small></label>
						<input type="text" name="value" style="width:90%;" id="large-label-1" class="input medium-margin-right" value="<?php if($value<>'') echo $value['VariabelValue'];  ?>" />
                    </p>
					
					<p class="button-height">
						<input type="submit" class="button big" value="Simpan" />
						<a href="<?=site_url('presensi/variabel/index/'.$this->session->userdata('variabel_offset'))?>" class="button big">Back</a>
					</p>

				</div>
			</div>
            </form>

		</div>