<?Php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
	require_once(APPPATH.'third_party/tcpdf/config/lang/eng.php');
	require_once(APPPATH.'third_party/tcpdf/tcpdf.php');
	require_once(APPPATH.'third_party/html2fpdf/html2fpdf.php');
	function tcpdf(){
		return new TCPDF(PDF_PAGE_ORIENTATION,PDF_UNIT,PDF_PAGE_FORMAT,true);
	}
	
	function html2pdf(){
		return new HTML2FPDF();
	}