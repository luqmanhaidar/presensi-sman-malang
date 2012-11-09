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