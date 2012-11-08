<?php

/**
 * @author Boomer
 * @copyright 2012
 */

$row++;
        $col=0;
		$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($col,$row,"MADRASAH ALIYAH NEGERI 3 MALANG"); // Title
        $objPHPExcel->getActiveSheet()->mergeCellsByColumnAndRow($col,$row,$col+5,$row);
        
        $row++;
        $col=0;
		$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($col,$row,"JL.BANDUNG NO.7 Telp.0341-551357,588333"); // Title
        $objPHPExcel->getActiveSheet()->mergeCellsByColumnAndRow($col,$row,$col+5,$row);
        

?>