        <hgroup id="main-title" class="thin">
			<h1><?=$title?></h1>
		</hgroup>

		<div class="with-padding">
            
            <form method="post" action="<?=site_url('employee/admin/update')?>" class="columns">
			<div class="columns">

				<div class="twelve-columns twelve-columns-tablet">

					<br/>	
					<h4 class="thin underline"><?=$title?></h4>

					<p class="inline-large-label button-height">
						<label for="large-label-1" class="label">ID Login<small>( ID User)</small></label>
						<input type="text" name="large-label-1" disabled="disabled" id="large-label-1" class="input medium-margin-right" value="<?=$value['ID']?>" />
					    <input type="hidden" name="ID" value="<?=$value['ID']?>" />
                    </p>
					
					<p class="inline-large-label button-height">
						<label for="large-label-1" class="label">Password<small>(50 chars max.)</small></label>
						<input type="password" name="password"  id="large-label-1" class="input medium-margin-right" value="***" />
					</p>
					
					<p class="button-height">
						<input type="submit" class="button big" value="Simpan" />
						<a href="<?=site_url('employee/admin/index/'.$this->session->userdata('admin_offset'))?>" class="button big">Back</a>
					</p>

				</div>
			</div>
            </form>

		</div>