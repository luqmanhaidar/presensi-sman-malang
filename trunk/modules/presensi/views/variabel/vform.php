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
                    
                    <?php
                        if($value<>''):
                            $type = $value['VariabelType'];
                        else:
                            $type = '';
                        endif; 
                    ?>
                    
                    <p class="inline-large-label button-height">
						<label for="large-label-1" class="label">Type<small> ( Var Type )</small></label>
						<?=form_dropdown('type',config_item('var'),$type,'class="select" id="vartype" onChange="disable_enable();"')?>
                    </p>
                    
                    
                    <p class="inline-large-label button-height">
						<label for="large-label-1" class="label">Nilai<small> ( max 100)</small></label>
						<input id="numeric" type="text" name="value" style="width:90%;" id="large-label-1" class="input medium-margin-right" value="<?php if($value<>'') echo $value['VariabelValue'];  ?>" />
                        <?=form_dropdown('hour',config_item('hour'),'',' id="hour" class="select" style="display:none"')?>
                        <?=form_dropdown('minute',config_item('minute'),'','id="min" class="select" style="display:none"')?>
                    </p>
					
					<p class="button-height">
						<input type="submit" class="button big" value="Simpan" />
						<a href="<?=site_url('presensi/variabel/index/'.$this->session->userdata('variabel_offset'))?>" class="button big">Back</a>
					</p>

				</div>
			</div>
            </form>

		</div>
        
<script type="text/javascript">
    function disable_enable(){ 
         if (document.getElementById('vartype').value=="Waktu (hh:mm)"){
                document.getElementById('numeric').style.display = 'none';
                document.getElementById('hour').style.display = '';
                document.getElementById('min').style.display = '';
                
         } else {
                document.getElementById('numeric').style.display = '';
                document.getElementById('hour').style.display = 'none';
                document.getElementById('min').style.display = 'none';
         }
    }    
</script>    