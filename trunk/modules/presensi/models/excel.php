<?php

class Excel extends CI_Model
{
    function __construct()
    {
        parent::__construct(); // Call the Model constructor
    }
    
    function personal_excel(){
        $this->user_logs->saveLog('Export Excell Admission Report');           						
		$objPHPExcel = new PHPExcel();
		$objPHPExcel->getProperties()->setTitle("title")
					->setDescription("description");
					 $objPHPExcel->setActiveSheetIndex(0);
		$styleArray = array( 'borders' => array( 'allborders' => array(
                             'style' => Style_Border::BORDER_THIN )));
        $fill = array(
                        'type'       => Style_Fill::FILL_SOLID,
                        'rotation'   => 0,
                        'startcolor' => array(
                                'rgb' => 'CCCCCC'
                        ),
                        'endcolor'   => array(
                                'argb' => 'CCCCCC'
                        )
                );       
	  	
		$fontArray = array(
			'font' => array(
			'bold' => true
			)
			);
		
		$row=1;
        $col=0;
		
		$objDrawing = new Worksheet_Drawing();
		$objDrawing->setName('Water_Level');
		$objDrawing->setDescription('Water_Level');
		$objDrawing->setPath($data['table']['setting']['hospital_logo']);
		$objDrawing->setHeight(45);
		$objDrawing->setCoordinates('A1');
		$objDrawing->setWorksheet($objPHPExcel->getActiveSheet());
		
		$row=$row+1;
		
		$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($col+2,$row,$data['table']['setting']['hospital_name']);
		$objPHPExcel->getActiveSheet()->mergeCells('C1:D1');
		$objPHPExcel->getActiveSheet()->getStyleByColumnAndRow($col+2,$row)->applyFromArray($fontArray);
		
		$row=$row+1;
		$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($col+2,$row,$data['table']['setting']['hospital_description']);
		$objPHPExcel->getActiveSheet()->getStyleByColumnAndRow($col+2,$row)->applyFromArray($fontArray);
		$objPHPExcel->getActiveSheet()->mergeCells('C2:D2');
		
		$row=$row+2;
		$objPHPExcel->getActiveSheet()->mergeCells('C4:D4');
		$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($col+2,$row,'Admission Report');
		
		$row=$row+1;
		$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($col+2,$row,'From '.indo_tgl($date1,'/').' Until '.indo_tgl($date2,'/'));
		$objPHPExcel->getActiveSheet()->mergeCells('C5:D5');
		
		$row=$row+2;
        // Save it as an excel 2007 file
        $objWriter = IOFactory::createWriter($objPHPExcel, "Excel2007");
        $file="Personal.xlsx";
        $objWriter->save('assets/'.$file);
    }
}    