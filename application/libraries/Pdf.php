<?php
defined('BASEPATH') OR exit('No direct script access allowed');

require_once(dirname(__FILE__) . '/dompdf/autoload.inc.php');

class Pdf
{
    function createPDF($html, $filename='', $download=TRUE, $paper='A4', $orientation='portrait'){
        $fontDir = 'lib/fonts';

        $dompdf = new Dompdf\DOMPDF();
        $dompdf->getFontMetrics()->registerFont(
            ['family' => 'ZawDcode', 'style' => 'italic', 'weight' => 'normal'],
            $fontDir . '/ZawDcode.ttf'
        );
        $dompdf->loadHtml($html, 'UTF-8');
        
        $dompdf->set_paper($paper, $orientation);
        $dompdf->render();
        if($download)
            $dompdf->stream($filename.'.pdf', array('Attachment' => 1));
        else
            $dompdf->stream($filename.'.pdf', array('Attachment' => 0));
    }
}

/* End of file Pdf.php */
/* Location: ./application/libraries/Pdf.php */
