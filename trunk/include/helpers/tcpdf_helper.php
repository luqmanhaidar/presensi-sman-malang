<?Php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
	
	function tcpdf(){
		require_once(APPPATH.'third_party/tcpdf/config/lang/eng.php');
		require_once(APPPATH.'third_party/tcpdf/tcpdf.php');
		return new TCPDF(PDF_PAGE_ORIENTATION,PDF_UNIT,PDF_PAGE_FORMAT,true);
	}
	
	function html2pdf($orientation='P',$paper='A4',$language='en'){
		require_once(APPPATH.'third_party/html2pdf/html2pdf.class.php');
		return new HTML2PDF($orientation,$paper,$language); 
	}
	
	function mpdf(){
		require_once(APPPATH.'third_party/mpdf/mpdf.php');
        return new mPDF();
	}
	
	function fpdf(){
		require_once(APPPATH.'third_party/fpdf/fpdf.php');
		return new FPDF($orientation='P',$unit='mm',$size='A4');
	}
	
	function ezpdf(){
		require_once(APPPATH.'third_party/ezpdf/class.ezpdf.php');
		return new Cezpdf('LETTER');
	}