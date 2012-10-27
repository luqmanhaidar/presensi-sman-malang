        <hgroup id="main-title" class="thin">
			<h1>Dashboard</h1>
			<h2><?=indonesian_longDate(date('Y-m-d'))?></h2>
		</hgroup>
		
		<div class="dashboard">
			<div class="columns">
			  
			</div>
		</div>

		<div class="with-padding">

			<div class="columns">

				<div class="six-columns twelve-columns-tablet">

					<h3 class="thin">Group Kerja</h3>
					<ul class="files-icons">
						<li><a href="<?=site_url('employee/group/index')?>" class="file-link"><span class="icon file-rtf"></span>Group Kerja</a></li>
						<li><a href="<?=site_url('employee/user/index')?>" class="file-link"><span class="icon file-aac"></span>User Group</a></li>
					</ul>
					
					<h3 class="thin">Input Presensi</h3>
					<ul class="files-icons">
						<li><a href="<?=site_url('presensi/checkin/index')?>" class="file-link"><span class="icon file-access"></span>Manual Checkin</a></li>
						<li><a href="<?=site_url('presensi/overtime/index')?>" class="file-link"><span class="icon file-bat"></span>Daftar Lembur</a></li>
						<li><a href="<?=site_url('presensi/other/index')?>" class="file-link"><span class="icon file-avi"></span>Ijin Kerja</a></li>
					</ul>
					
					<h3 class="thin">Setting</h3>
					<ul class="files-icons">
						<li><a href="<?=site_url('setting/presensi')?>" class="file-link"><span class="icon file-xsl"></span>Variabel Presensi</a></li>
						<li><a href="<?=site_url('setting/user/password')?>" class="file-link"><span class="icon file-desktop"></span>User Login</a></li>
						<li><a href="<?=site_url('setting/log')?>" class="file-link"><span class="icon file-dat"></span>Log History</a></li>
					</ul>

				</div>

				<div class="six-columns twelve-columns-tablet">

					<h3 class="thin">Laporan Presensi</h3>
					<ul class="files-icons">
						<li><a href="<?=site_url('presensi/report/personal')?>" class="file-link"><span class="icon file-excel"></span>Laporan Individu</a></li>
						<li><a href="<?=site_url('presensi/report/monthly')?>" class="file-link"><span class="icon file-excel"></span>Laporan Bulanan</a></li>
						<li><a href="<?=site_url('presensi/report/weekly')?>" class="file-link"><span class="icon file-excel"></span>Laporan Mingguan</a></li>
					</ul>
					
					<ul class="files-icons">
						<li><a href="<?=site_url('presensi/report/overtime')?>" class="file-link"><span class="icon file-excel"></span>Laporan Lembur</a></li>
						<li><a href="<?=site_url('presensi/report/special_employee')?>" class="file-link"><span class="icon file-excel"></span>Laporan Pegawai Khusus</a></li>
					</ul>
				
					<h3 class="thin">Laporan Gaji</h3>
					<ul class="files-icons">
						<li><a href="<?=site_url('payroll/report/eat')?>" class="file-link"><span class="icon file-excel"></span>Gaji Format 1</a></li>
						<li><a href="<?=site_url('payroll/report/transport')?>" class="file-link"><span class="icon file-excel"></span>Gaji Format 2</a></li>
					</ul>

				</div>

			</div>

		</div>