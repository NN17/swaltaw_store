<?php
use Mike42\Escpos\Printer;
use Mike42\Escpos\EscposImage;
use Mike42\Escpos\ImagickEscposImage;
use Mike42\Escpos\PrintConnectors\NetworkPrintConnector;
use Mike42\Escpos\PrintConnectors\WindowsPrintConnector;
defined('BASEPATH') OR exit('No direct script access allowed');

class Escpos
{
    protected $CI;

    public function __construct()
    {
        $this->CI =& get_instance();

        $this->CI->load->library('session');
        $this->CI->load->helper('url');
    }

    public function print_receipt($pdf){
        
        $printPDF = __DIR__.'/../../'.$pdf;
        $connector = new WindowsPrintConnector("XP-80C");
        $printer = new Printer($connector);
        $pages = ImagickEscposImage::loadPdf($printPDF);
        foreach ($pages as $page) {
            $printer -> bitImage($page);
        }
        // $printer -> text("Hello world and what the fucking escpos-php \n");
        $printer -> cut();
        $printer -> close();
    }

    public function print_order($pdf){
        $printPDF = __DIR__.'/../../'.$pdf;
        $connector = new NetworkPrintConnector("192.168.123.100", 9100);
        $printer = new Printer($connector);
        $pages = ImagickEscposImage::loadPdf($printPDF);
        foreach ($pages as $page) {
            $printer -> bitImage($page);
        }
        // $printer -> text("Hello world and what the fucking escpos-php \n");
        $printer -> cut();
        $printer -> close();
    }

    
}

/* End of file Ignite.php */
/* Location: ./application/libraries/Ignite.php */
