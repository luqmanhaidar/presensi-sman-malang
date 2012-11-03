<?php

class ExcelModel extends CI_Model
{
    function __construct()
    {
        parent::__construct(); // Call the Model constructor
    }
    
    function personal_excel($user,$name){
        //$this->user_logs->saveLog('Export Excell Admission Report');           						
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
        
        //No
		$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($col,$row,$user.'-'.$name);
       
		$row=$row+1;
        $col=0;
		
		//No
		$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($col,$row,'No');
        $objPHPExcel->getActiveSheet()->getColumnDimensionByColumn($col)->setWidth(5);
        $objPHPExcel->getActiveSheet()->getStyleByColumnAndRow($col,$row)->applyFromArray($styleArray);
        $objPHPExcel->getActiveSheet()->getStyleByColumnAndRow($col,$row)->getFill()->applyFromArray($fill);
		
		//Tanggal
		$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($col+1,$row,'Tanggal');
        $objPHPExcel->getActiveSheet()->getColumnDimensionByColumn($col+1)->setWidth(15);
        $objPHPExcel->getActiveSheet()->getStyleByColumnAndRow($col+1,$row)->applyFromArray($styleArray);
        $objPHPExcel->getActiveSheet()->getStyleByColumnAndRow($col+1,$row)->getFill()->applyFromArray($fill);
		
		//Jenis
		$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($col+2,$row,'Jenis');
        $objPHPExcel->getActiveSheet()->getColumnDimensionByColumn($col+2)->setWidth(20);
        $objPHPExcel->getActiveSheet()->getStyleByColumnAndRow($col+2,$row)->applyFromArray($styleArray);
        $objPHPExcel->getActiveSheet()->getStyleByColumnAndRow($col+2,$row)->getFill()->applyFromArray($fill);
		
		//Waktu
		$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($col+3,$row,'Waktu');
        $objPHPExcel->getActiveSheet()->getColumnDimensionByColumn($col+3)->setWidth(15);
        $objPHPExcel->getActiveSheet()->getStyleByColumnAndRow($col+3,$row)->applyFromArray($styleArray);
        $objPHPExcel->getActiveSheet()->getStyleByColumnAndRow($col+3,$row)->getFill()->applyFromArray($fill);
        
        $row=$row + 1;
        $data['checks']	= $this->authlog->getAllRecords('','',$this->session->userdata('personal_search'),$this->session->userdata('personal_key'),$this->session->userdata('personal_start'),$this->session->userdata('personal_finish'));
		$i=1;
		foreach($data['checks'] as $rec):
			$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($col,$row,$i);
			$objPHPExcel->getActiveSheet()->getStyleByColumnAndRow($col,$row)->applyFromArray($styleArray);
			 
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($col+1,$row,indonesian_shortDate($rec['TransactionTime']));
			$objPHPExcel->getActiveSheet()->getStyleByColumnAndRow($col+1,$row)->applyFromArray($styleArray);
			 
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($col+2,$row,functionKey($rec['FunctionKey']));
			$objPHPExcel->getActiveSheet()->getStyleByColumnAndRow($col+2,$row)->applyFromArray($styleArray);
			 
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($col+3,$row,substr($rec['TransactionTime'],11,8));
			$objPHPExcel->getActiveSheet()->getStyleByColumnAndRow($col+3,$row)->applyFromArray($styleArray);
            $i++;
            $row++;
        endforeach;    
		
        // Save it as an excel 2007 file
        $objWriter = IOFactory::createWriter($objPHPExcel, "Excel2007");
		//$objWriter = IOFactory::createWriter($objPHPexcel,'PDF'); 
        $file="Personal.xlsx";
        $objWriter->save('assets/'.$file);
        redirect(base_url('assets/'.$file),301);
    }
    
    function month_excel($recs,$start,$end,$group,$days,$pos,$desc){      						
		$objPHPExcel = new PHPExcel();
        $objDrawing = new Worksheet_Drawing();  // PHPExcel_Worksheet_MemoryDrawing
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
		$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($col,$row,"Laporan Presensi Bulanan Periode ".$start." s/d ".$end); // Title
        $objPHPExcel->getActiveSheet()->mergeCellsByColumnAndRow($col,$row,$col+5,$row);
        
        $row++;
        $col=0;
		$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($col,$row,"Group ".$pos['Name']); // Title
        $objPHPExcel->getActiveSheet()->mergeCellsByColumnAndRow($col,$row,$col+5,$row);
		
        $row=$row+3;
        $col=0;
        //No
		$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($col,$row-1,'No');
        $objPHPExcel->getActiveSheet()->getStyleByColumnAndRow($col,$row-1)->getAlignment()->setVertical(Style_Alignment::VERTICAL_CENTER);
        $objPHPExcel->getActiveSheet()->mergeCellsByColumnAndRow($col,$row-1,$col,$row);
        $objPHPExcel->getActiveSheet()->getColumnDimensionByColumn($col)->setWidth(5);
        $objPHPExcel->getActiveSheet()->getStyleByColumnAndRow($col,$row-1)->applyFromArray($styleArray);
        $objPHPExcel->getActiveSheet()->getStyleByColumnAndRow($col,$row-1)->getFill()->applyFromArray($fill);
        
		$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($col+1,$row-1,'Nama');
        $objPHPExcel->getActiveSheet()->getStyleByColumnAndRow($col+1,$row-1)->getAlignment()->setVertical(Style_Alignment::VERTICAL_CENTER);
        $objPHPExcel->getActiveSheet()->getStyleByColumnAndRow($col+1,$row-1)->getAlignment()->setHorizontal(Style_Alignment::HORIZONTAL_CENTER);
        $objPHPExcel->getActiveSheet()->mergeCellsByColumnAndRow($col+1,$row-1,$col+1,$row);
        $objPHPExcel->getActiveSheet()->getColumnDimensionByColumn($col+1)->setWidth(30);
        $objPHPExcel->getActiveSheet()->getStyleByColumnAndRow($col+1,$row-1)->applyFromArray($styleArray);
        $objPHPExcel->getActiveSheet()->getStyleByColumnAndRow($col+1,$row-1)->getFill()->applyFromArray($fill);
        
        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($col+2,$row-1,'Paraf');
        $objPHPExcel->getActiveSheet()->getStyleByColumnAndRow($col+2,$row-1)->getAlignment()->setVertical(Style_Alignment::VERTICAL_CENTER);
        $objPHPExcel->getActiveSheet()->getStyleByColumnAndRow($col+2,$row-1)->getAlignment()->setHorizontal(Style_Alignment::HORIZONTAL_CENTER);
        $objPHPExcel->getActiveSheet()->mergeCellsByColumnAndRow($col+2,$row-1,$col+2,$row);
        $objPHPExcel->getActiveSheet()->getColumnDimensionByColumn($col+2)->setWidth(10);
        $objPHPExcel->getActiveSheet()->getStyleByColumnAndRow($col+2,$row-1)->applyFromArray($styleArray);
        $objPHPExcel->getActiveSheet()->getStyleByColumnAndRow($col+2,$row-1)->getFill()->applyFromArray($fill);
        
        $i=3;
        foreach($days as $rec):
            //Days
    		$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($col+$i,$row,substr($rec['DAY'],0,2));
            $objPHPExcel->getActiveSheet()->getColumnDimensionByColumn($col+$i)->setWidth(7);
            $objPHPExcel->getActiveSheet()->getStyleByColumnAndRow($col+$i,$row)->applyFromArray($styleArray);
            $objPHPExcel->getActiveSheet()->getStyleByColumnAndRow($col+$i,$row)->getFill()->applyFromArray($fill);
            $objPHPExcel->getActiveSheet()->getStyleByColumnAndRow($col+$i,$row)->getAlignment()->setHorizontal(Style_Alignment::HORIZONTAL_CENTER);
        $i++;
        endforeach;
        
        //Tanggal
		$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($col+3,$row-1,$desc);
        $objPHPExcel->getActiveSheet()->mergeCellsByColumnAndRow($col+3,$row-1,$col+($i-1),$row-1);
        $objPHPExcel->getActiveSheet()->getStyleByColumnAndRow($col+3,$row-1)->getAlignment()->setHorizontal(Style_Alignment::HORIZONTAL_CENTER);
        $objPHPExcel->getActiveSheet()->getStyleByColumnAndRow($col+3,$row-1,$col+($i-1),$row-1)->applyFromArray($styleArray);
        $objPHPExcel->getActiveSheet()->getStyleByColumnAndRow($col+3,$row-1,$col+($i-1),$row-1)->getFill()->applyFromArray($fill);
        
        
        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($col+$i+0,$row-1,'Keterangan');
        $objPHPExcel->getActiveSheet()->getStyleByColumnAndRow($col+$i+1,$row-1)->getAlignment()->setVertical(Style_Alignment::VERTICAL_CENTER);
        $objPHPExcel->getActiveSheet()->mergeCellsByColumnAndRow($col+$i+0,$row-1,$col+$i+2,$row-1);
        $objPHPExcel->getActiveSheet()->getColumnDimensionByColumn($col+$i+0)->setWidth(25);
        $objPHPExcel->getActiveSheet()->getStyleByColumnAndRow($col+$i+0,$row-1)->applyFromArray($styleArray);
        $objPHPExcel->getActiveSheet()->getStyleByColumnAndRow($col+$i+0,$row-1)->getFill()->applyFromArray($fill);
        
        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($col+$i+0,$row,'S');
        $objPHPExcel->getActiveSheet()->getStyleByColumnAndRow($col+$i+0,$row)->getAlignment()->setHorizontal(Style_Alignment::HORIZONTAL_CENTER);
        $objPHPExcel->getActiveSheet()->getColumnDimensionByColumn($col+$i+0)->setWidth(7);
        $objPHPExcel->getActiveSheet()->getStyleByColumnAndRow($col+$i+0,$row)->applyFromArray($styleArray);
        $objPHPExcel->getActiveSheet()->getStyleByColumnAndRow($col+$i+0,$row)->getFill()->applyFromArray($fill);
        
        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($col+$i+1,$row,'I');
        $objPHPExcel->getActiveSheet()->getStyleByColumnAndRow($col+$i+1,$row)->getAlignment()->setHorizontal(Style_Alignment::HORIZONTAL_CENTER);
        $objPHPExcel->getActiveSheet()->getColumnDimensionByColumn($col+$i+1)->setWidth(7);
        $objPHPExcel->getActiveSheet()->getStyleByColumnAndRow($col+$i+1,$row)->applyFromArray($styleArray);
        $objPHPExcel->getActiveSheet()->getStyleByColumnAndRow($col+$i+1,$row)->getFill()->applyFromArray($fill);
        
        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($col+$i+2,$row,'T');
        $objPHPExcel->getActiveSheet()->getStyleByColumnAndRow($col+$i+2,$row)->getAlignment()->setHorizontal(Style_Alignment::HORIZONTAL_CENTER);
        $objPHPExcel->getActiveSheet()->getColumnDimensionByColumn($col+$i+2)->setWidth(7);
        $objPHPExcel->getActiveSheet()->getStyleByColumnAndRow($col+$i+2,$row)->applyFromArray($styleArray);
        $objPHPExcel->getActiveSheet()->getStyleByColumnAndRow($col+$i+2,$row)->getFill()->applyFromArray($fill);
		
        $row=$row+1;
        $col=0;
        $i=1;
        foreach($recs as $var):
            
             /** Paraf Datang PK **/
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($col,$row,$i);
			$objPHPExcel->getActiveSheet()->getStyleByColumnAndRow($col,$row)->applyFromArray($styleArray);
            $objPHPExcel->getActiveSheet()->mergeCellsByColumnAndRow($col,$row,$col,$row+3);
            $objPHPExcel->getActiveSheet()->getStyleByColumnAndRow($col,$row)->getAlignment()->setVertical(Style_Alignment::VERTICAL_CENTER);
            $objPHPExcel->getActiveSheet()->getStyleByColumnAndRow($col,$row)->getAlignment()->setHorizontal(Style_Alignment::HORIZONTAL_CENTER);
            
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($col+1,$row,$var['Name']);
			$objPHPExcel->getActiveSheet()->getStyleByColumnAndRow($col+1,$row)->applyFromArray($styleArray);
            $objPHPExcel->getActiveSheet()->mergeCellsByColumnAndRow($col+1,$row,$col+1,$row+1);
            $objPHPExcel->getActiveSheet()->getStyleByColumnAndRow($col+1,$row)->getAlignment()->setVertical(Style_Alignment::VERTICAL_CENTER);
            
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($col+2,$row,"Paraf");
			$objPHPExcel->getActiveSheet()->getStyleByColumnAndRow($col+2,$row)->applyFromArray($styleArray);
            
            $j=3;
            foreach($days as $rec):
                //Days
        		$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($col+$j,$row,'');
                $objPHPExcel->getActiveSheet()->getStyleByColumnAndRow($col+$j,$row)->applyFromArray($styleArray);
                $objPHPExcel->getActiveSheet()->getStyleByColumnAndRow($col+$j,$row)->getAlignment()->setHorizontal(Style_Alignment::HORIZONTAL_CENTER);
                $j++;
            endforeach;
            
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($col+$j+0,$row,'-');
            $objPHPExcel->getActiveSheet()->mergeCellsByColumnAndRow($col+$j+0,$row,$col+$j+0,$row+3);
            $objPHPExcel->getActiveSheet()->getStyleByColumnAndRow($col+$j+0,$row)->getAlignment()->setVertical(Style_Alignment::VERTICAL_CENTER);
            $objPHPExcel->getActiveSheet()->getStyleByColumnAndRow($col+$j+0,$row)->getAlignment()->setHorizontal(Style_Alignment::HORIZONTAL_CENTER);
            $objPHPExcel->getActiveSheet()->getStyleByColumnAndRow($col+$j+0,$row)->applyFromArray($styleArray);
            $objPHPExcel->getActiveSheet()->getStyleByColumnAndRow($col+$j+0,$row)->getAlignment()->setHorizontal(Style_Alignment::HORIZONTAL_CENTER);
            
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($col+$j+1,$row,'-');
            $objPHPExcel->getActiveSheet()->mergeCellsByColumnAndRow($col+$j+1,$row,$col+$j+1,$row+3);
            $objPHPExcel->getActiveSheet()->getStyleByColumnAndRow($col+$j+1,$row)->getAlignment()->setVertical(Style_Alignment::VERTICAL_CENTER);
            $objPHPExcel->getActiveSheet()->getStyleByColumnAndRow($col+$j+1,$row)->getAlignment()->setHorizontal(Style_Alignment::HORIZONTAL_CENTER);
            $objPHPExcel->getActiveSheet()->getStyleByColumnAndRow($col+$j+1,$row)->applyFromArray($styleArray);
            $objPHPExcel->getActiveSheet()->getStyleByColumnAndRow($col+$j+1,$row)->getAlignment()->setHorizontal(Style_Alignment::HORIZONTAL_CENTER);
            
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($col+$j+2,$row,'-');
            $objPHPExcel->getActiveSheet()->mergeCellsByColumnAndRow($col+$j+2,$row,$col+$j+2,$row+3);
            $objPHPExcel->getActiveSheet()->getStyleByColumnAndRow($col+$j+2,$row)->getAlignment()->setVertical(Style_Alignment::VERTICAL_CENTER);
            $objPHPExcel->getActiveSheet()->getStyleByColumnAndRow($col+$j+2,$row)->getAlignment()->setHorizontal(Style_Alignment::HORIZONTAL_CENTER);
            $objPHPExcel->getActiveSheet()->getStyleByColumnAndRow($col+$j+2,$row)->applyFromArray($styleArray);
            $objPHPExcel->getActiveSheet()->getStyleByColumnAndRow($col+$j+2,$row)->getAlignment()->setHorizontal(Style_Alignment::HORIZONTAL_CENTER);
            
            $i++;
            $row++;
            
            /** Datang PK **/
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($col,$row,'');
			$objPHPExcel->getActiveSheet()->getStyleByColumnAndRow($col,$row)->applyFromArray($styleArray);
            
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($col+1,$row,'');
			$objPHPExcel->getActiveSheet()->getStyleByColumnAndRow($col+1,$row)->applyFromArray($styleArray);
            
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($col+2,$row,"Dtg.PK");
			$objPHPExcel->getActiveSheet()->getStyleByColumnAndRow($col+2,$row)->applyFromArray($styleArray);
            
            $j=3;
            foreach($days as $rec):
                //Days
        		$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($col+$j,$row,substr($this->authlog->getUserTime($rec['DAY'],$var['UserID'],1),0,5));
                $objPHPExcel->getActiveSheet()->getStyleByColumnAndRow($col+$j,$row)->applyFromArray($styleArray);
                $objPHPExcel->getActiveSheet()->getStyleByColumnAndRow($col+$j,$row)->getAlignment()->setHorizontal(Style_Alignment::HORIZONTAL_CENTER);
                $j++;
            endforeach;
            
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($col+$j+0,$row,'');
            $objPHPExcel->getActiveSheet()->getStyleByColumnAndRow($col+$j+0,$row)->applyFromArray($styleArray);
            $objPHPExcel->getActiveSheet()->getStyleByColumnAndRow($col+$j+0,$row)->getAlignment()->setHorizontal(Style_Alignment::HORIZONTAL_CENTER);
            
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($col+$j+1,$row,'');
            $objPHPExcel->getActiveSheet()->getStyleByColumnAndRow($col+$j+1,$row)->applyFromArray($styleArray);
            $objPHPExcel->getActiveSheet()->getStyleByColumnAndRow($col+$j+1,$row)->getAlignment()->setHorizontal(Style_Alignment::HORIZONTAL_CENTER);
            
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($col+$j+2,$row,'');
            $objPHPExcel->getActiveSheet()->getStyleByColumnAndRow($col+$j+2,$row)->applyFromArray($styleArray);
            $objPHPExcel->getActiveSheet()->getStyleByColumnAndRow($col+$j+2,$row)->getAlignment()->setHorizontal(Style_Alignment::HORIZONTAL_CENTER);
            
            
            $i++;
            $row++;
            
            /** Paraf Pulang PK **/
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($col,$row,'');
			$objPHPExcel->getActiveSheet()->getStyleByColumnAndRow($col,$row)->applyFromArray($styleArray);
            
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($col+1,$row,'Jab: '.$var['Department']);
			$objPHPExcel->getActiveSheet()->getStyleByColumnAndRow($col+1,$row)->applyFromArray($styleArray);
            $objPHPExcel->getActiveSheet()->mergeCellsByColumnAndRow($col+1,$row,$col+1,$row+1);
            $objPHPExcel->getActiveSheet()->getStyleByColumnAndRow($col+1,$row)->getAlignment()->setVertical(Style_Alignment::VERTICAL_CENTER);
            
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($col+2,$row,"Paraf");
			$objPHPExcel->getActiveSheet()->getStyleByColumnAndRow($col+2,$row)->applyFromArray($styleArray);
            
            $j=3;
            foreach($days as $rec):
                //Days
        		$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($col+$j,$row,'');
                $objPHPExcel->getActiveSheet()->getStyleByColumnAndRow($col+$j,$row)->applyFromArray($styleArray);
                $objPHPExcel->getActiveSheet()->getStyleByColumnAndRow($col+$j,$row)->getAlignment()->setHorizontal(Style_Alignment::HORIZONTAL_CENTER);
                $j++;
            endforeach;
            
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($col+$j+0,$row,'');
            $objPHPExcel->getActiveSheet()->getStyleByColumnAndRow($col+$j+0,$row)->applyFromArray($styleArray);
            $objPHPExcel->getActiveSheet()->getStyleByColumnAndRow($col+$j+0,$row)->getAlignment()->setHorizontal(Style_Alignment::HORIZONTAL_CENTER);
            
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($col+$j+1,$row,'');
            $objPHPExcel->getActiveSheet()->getStyleByColumnAndRow($col+$j+1,$row)->applyFromArray($styleArray);
            $objPHPExcel->getActiveSheet()->getStyleByColumnAndRow($col+$j+1,$row)->getAlignment()->setHorizontal(Style_Alignment::HORIZONTAL_CENTER);
            
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($col+$j+2,$row,'');
            $objPHPExcel->getActiveSheet()->getStyleByColumnAndRow($col+$j+2,$row)->applyFromArray($styleArray);
            $objPHPExcel->getActiveSheet()->getStyleByColumnAndRow($col+$j+2,$row)->getAlignment()->setHorizontal(Style_Alignment::HORIZONTAL_CENTER);
            
            
            $i++;
            $row++;
            
            /** Pulang PK **/
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($col,$row,'');
			$objPHPExcel->getActiveSheet()->getStyleByColumnAndRow($col,$row)->applyFromArray($styleArray);
            
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($col+1,$row,'');
			$objPHPExcel->getActiveSheet()->getStyleByColumnAndRow($col+1,$row)->applyFromArray($styleArray);
            
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($col+2,$row,"Plg.PK");
			$objPHPExcel->getActiveSheet()->getStyleByColumnAndRow($col+2,$row)->applyFromArray($styleArray);
            
            $j=3;
            foreach($days as $rec):
                //Days
        		$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($col+$j,$row,substr($this->authlog->getUserTime($rec['DAY'],$var['UserID'],2),0,5));
                $objPHPExcel->getActiveSheet()->getStyleByColumnAndRow($col+$j,$row)->applyFromArray($styleArray);
                $objPHPExcel->getActiveSheet()->getStyleByColumnAndRow($col+$j,$row)->getAlignment()->setHorizontal(Style_Alignment::HORIZONTAL_CENTER);
                $j++;
            endforeach;
            
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($col+$j+0,$row,'');
            $objPHPExcel->getActiveSheet()->getStyleByColumnAndRow($col+$j+0,$row)->applyFromArray($styleArray);
            $objPHPExcel->getActiveSheet()->getStyleByColumnAndRow($col+$j+0,$row)->getAlignment()->setHorizontal(Style_Alignment::HORIZONTAL_CENTER);
            
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($col+$j+1,$row,'');
            $objPHPExcel->getActiveSheet()->getStyleByColumnAndRow($col+$j+1,$row)->applyFromArray($styleArray);
            $objPHPExcel->getActiveSheet()->getStyleByColumnAndRow($col+$j+1,$row)->getAlignment()->setHorizontal(Style_Alignment::HORIZONTAL_CENTER);
            
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($col+$j+2,$row,'');
            $objPHPExcel->getActiveSheet()->getStyleByColumnAndRow($col+$j+2,$row)->applyFromArray($styleArray);
            $objPHPExcel->getActiveSheet()->getStyleByColumnAndRow($col+$j+2,$row)->getAlignment()->setHorizontal(Style_Alignment::HORIZONTAL_CENTER);
            
            
            $i++;
            $row++;
            $i=$i-3;
        endforeach;
        
        // Save it as an excel 2007 file
        $objWriter = IOFactory::createWriter($objPHPExcel, "Excel2007"); 
        $file="Lap-Bulanan.xlsx";
        $objWriter->save('assets/'.$file);
        redirect(base_url('assets/'.$file),301);
    }
    
    function overtime_excel($recs,$var){         						
		$objPHPExcel = new PHPExcel();
        $objDrawing  = new Worksheet_Drawing();
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
        
        //Judul
		$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($col,$row,"Laporan Lembur");
       
		$row=$row+1;
        $col=0;
		
		//No
		$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($col,$row,'No');
        $objPHPExcel->getActiveSheet()->getColumnDimensionByColumn($col)->setWidth(5);
        $objPHPExcel->getActiveSheet()->getStyleByColumnAndRow($col,$row)->applyFromArray($styleArray);
        $objPHPExcel->getActiveSheet()->getStyleByColumnAndRow($col,$row)->getFill()->applyFromArray($fill);
		
		//ID User
		$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($col+1,$row,'ID User');
        $objPHPExcel->getActiveSheet()->getColumnDimensionByColumn($col+1)->setWidth(10);
        $objPHPExcel->getActiveSheet()->getStyleByColumnAndRow($col+1,$row)->applyFromArray($styleArray);
        $objPHPExcel->getActiveSheet()->getStyleByColumnAndRow($col+1,$row)->getFill()->applyFromArray($fill);
		
		//Nama
		$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($col+2,$row,'Nama');
        $objPHPExcel->getActiveSheet()->getColumnDimensionByColumn($col+2)->setWidth(25);
        $objPHPExcel->getActiveSheet()->getStyleByColumnAndRow($col+2,$row)->applyFromArray($styleArray);
        $objPHPExcel->getActiveSheet()->getStyleByColumnAndRow($col+2,$row)->getFill()->applyFromArray($fill);
		
		//Jabatan
		$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($col+3,$row,'Tanggal');
        $objPHPExcel->getActiveSheet()->getColumnDimensionByColumn($col+3)->setWidth(12);
        $objPHPExcel->getActiveSheet()->getStyleByColumnAndRow($col+3,$row)->applyFromArray($styleArray);
        $objPHPExcel->getActiveSheet()->getStyleByColumnAndRow($col+3,$row)->getFill()->applyFromArray($fill);
        
        //Hari
		$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($col+4,$row,'Mulai');
        $objPHPExcel->getActiveSheet()->getColumnDimensionByColumn($col+4)->setWidth(10);
        $objPHPExcel->getActiveSheet()->getStyleByColumnAndRow($col+4,$row)->applyFromArray($styleArray);
        $objPHPExcel->getActiveSheet()->getStyleByColumnAndRow($col+4,$row)->getFill()->applyFromArray($fill);
        
        //Jumlah
		$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($col+5,$row,'Selesai');
        $objPHPExcel->getActiveSheet()->getColumnDimensionByColumn($col+5)->setWidth(10);
        $objPHPExcel->getActiveSheet()->getStyleByColumnAndRow($col+5,$row)->applyFromArray($styleArray);
        $objPHPExcel->getActiveSheet()->getStyleByColumnAndRow($col+5,$row)->getFill()->applyFromArray($fill);
        
        //Hari
		$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($col+6,$row,'Durasi');
        $objPHPExcel->getActiveSheet()->getColumnDimensionByColumn($col+6)->setWidth(10);
        $objPHPExcel->getActiveSheet()->getStyleByColumnAndRow($col+6,$row)->applyFromArray($styleArray);
        $objPHPExcel->getActiveSheet()->getStyleByColumnAndRow($col+6,$row)->getFill()->applyFromArray($fill);
        
        //Hari
		$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($col+7,$row,'Nominal');
        $objPHPExcel->getActiveSheet()->getColumnDimensionByColumn($col+7)->setWidth(12);
        $objPHPExcel->getActiveSheet()->getStyleByColumnAndRow($col+7,$row)->applyFromArray($styleArray);
        $objPHPExcel->getActiveSheet()->getStyleByColumnAndRow($col+7,$row)->getFill()->applyFromArray($fill);
        
        //Hari
		$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($col+8,$row,'Uang Makan');
        $objPHPExcel->getActiveSheet()->getColumnDimensionByColumn($col+8)->setWidth(12);
        $objPHPExcel->getActiveSheet()->getStyleByColumnAndRow($col+8,$row)->applyFromArray($styleArray);
        $objPHPExcel->getActiveSheet()->getStyleByColumnAndRow($col+8,$row)->getFill()->applyFromArray($fill);
        
        //Hari
		$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($col+9,$row,'Total');
        $objPHPExcel->getActiveSheet()->getColumnDimensionByColumn($col+9)->setWidth(12);
        $objPHPExcel->getActiveSheet()->getStyleByColumnAndRow($col+9,$row)->applyFromArray($styleArray);
        $objPHPExcel->getActiveSheet()->getStyleByColumnAndRow($col+9,$row)->getFill()->applyFromArray($fill);
        
        $row=$row + 1;
		$i=1;
        $grandtotal = 0;
		foreach($recs as $rec):
			$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($col,$row,$i);
			$objPHPExcel->getActiveSheet()->getStyleByColumnAndRow($col,$row)->applyFromArray($styleArray);
			 
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($col+1,$row,$rec['UserID']);
			$objPHPExcel->getActiveSheet()->getStyleByColumnAndRow($col+1,$row)->applyFromArray($styleArray);
			 
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($col+2,$row,$rec['Name']);
			$objPHPExcel->getActiveSheet()->getStyleByColumnAndRow($col+2,$row)->applyFromArray($styleArray);
			 
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($col+3,$row,$rec['MyDate']);
			$objPHPExcel->getActiveSheet()->getStyleByColumnAndRow($col+3,$row)->applyFromArray($styleArray);
            
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($col+4,$row,$rec['OvertimeStart']);
			$objPHPExcel->getActiveSheet()->getStyleByColumnAndRow($col+4,$row)->applyFromArray($styleArray);
            
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($col+5,$row,$rec['OvertimeEnd']);
			$objPHPExcel->getActiveSheet()->getStyleByColumnAndRow($col+5,$row)->applyFromArray($styleArray);
            
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($col+6,$row,$rec['OvertimeDuration']);
			$objPHPExcel->getActiveSheet()->getStyleByColumnAndRow($col+6,$row)->applyFromArray($styleArray);
            
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($col+7,$row,$total = $var * $rec['OvertimeDuration']);
            $objPHPExcel->getActiveSheet()->getStyleByColumnAndRow($col+7,$row)->getNumberFormat()->setFormatCode("#,##0");
			$objPHPExcel->getActiveSheet()->getStyleByColumnAndRow($col+7,$row)->applyFromArray($styleArray);
            
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($col+8,$row,$rec['OvertimeMeal']);
            $objPHPExcel->getActiveSheet()->getStyleByColumnAndRow($col+8,$row)->getNumberFormat()->setFormatCode("#,##0");
			$objPHPExcel->getActiveSheet()->getStyleByColumnAndRow($col+8,$row)->applyFromArray($styleArray);
            
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($col+9,$row,$sub_total=$total + $rec['OvertimeMeal']);
            $objPHPExcel->getActiveSheet()->getStyleByColumnAndRow($col+9,$row)->getNumberFormat()->setFormatCode("#,##0");
			$objPHPExcel->getActiveSheet()->getStyleByColumnAndRow($col+9,$row)->applyFromArray($styleArray);
            
            $i++;
            $row++;
            $grandtotal = $grandtotal + $sub_total;
        endforeach; 
        
        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($col,$row,"");
		$objPHPExcel->getActiveSheet()->getStyleByColumnAndRow($col,$row)->applyFromArray($styleArray);
			 
        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($col+1,$row,"");
		$objPHPExcel->getActiveSheet()->getStyleByColumnAndRow($col+1,$row)->applyFromArray($styleArray);
			 
        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($col+2,$row,"");
		$objPHPExcel->getActiveSheet()->getStyleByColumnAndRow($col+2,$row)->applyFromArray($styleArray);
			 
        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($col+3,$row,"");
		$objPHPExcel->getActiveSheet()->getStyleByColumnAndRow($col+3,$row)->applyFromArray($styleArray);
            
        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($col+4,$row,"");
		$objPHPExcel->getActiveSheet()->getStyleByColumnAndRow($col+4,$row)->applyFromArray($styleArray);
                
        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($col+5,$row,"");
		$objPHPExcel->getActiveSheet()->getStyleByColumnAndRow($col+5,$row)->applyFromArray($styleArray);  
        
        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($col+6,$row,"");
		$objPHPExcel->getActiveSheet()->getStyleByColumnAndRow($col+6,$row)->applyFromArray($styleArray); 
        
        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($col+7,$row,"");
		$objPHPExcel->getActiveSheet()->getStyleByColumnAndRow($col+7,$row)->applyFromArray($styleArray);  
        
        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($col+8,$row,"Total");
		$objPHPExcel->getActiveSheet()->getStyleByColumnAndRow($col+8,$row)->applyFromArray($styleArray); 
        
        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($col+9,$row,$grandtotal);
        $objPHPExcel->getActiveSheet()->getStyleByColumnAndRow($col+9,$row)->getNumberFormat()->setFormatCode("#,##0");
		$objPHPExcel->getActiveSheet()->getStyleByColumnAndRow($col+9,$row)->applyFromArray($styleArray); 
        
        // Save it as an excel 2007 file
        $objWriter = IOFactory::createWriter($objPHPExcel, "Excel2007");
        $file="Lap-Lembur.xlsx";
        $objWriter->save('assets/'.$file);
        redirect(base_url('assets/'.$file),301);
    }
}    