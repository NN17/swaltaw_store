<?php
use Mike42\Escpos\Printer;
use Mike42\Escpos\EscposImage;
use Mike42\Escpos\ImagickEscposImage;
use Mike42\Escpos\PrintConnectors\NetworkPrintConnector;
use Mike42\Escpos\PrintConnectors\WindowsPrintConnector;
use Mike42\Escpos\PrintConnectors\FilePrintConnector;
use Mike42\Escpos\CapabilityProfile;
use Mike42\Escpos\Experimental\Unifont\UnifontPrintBuffer;
use Mike42\Escpos\PrintBuffers\EscposPrintBuffer;
use Mike42\Escpos\PrintBuffers\ImagePrintBuffer;
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

    public function print_receipt($invoice, $itemBuy, $itemSell){
        $newDate = getDate();
        header('Content-type: text/html; charset=utf-8');
        mb_internal_encoding("UTF-8");
        $profile = CapabilityProfile::load("simple");
        $connector = new WindowsPrintConnector("XP");
        $printer = new Printer($connector, $profile);

        
        // $date = date('d M Y h:i:s A', $newDate[0]);
        
        $logo = EscposImage::load(__DIR__."/resources/imgs/unity-logo.png", false);
        $imageBuffer = new ImagePrintBuffer();
        // $imageBuffer -> setFont(__DIR__ . "/../../assets/fonts/unicode.ttf");
        $textBuffer = new EscposPrintBuffer();
        // $textBuffer->setFont(__DIR__."/../../assets/fonts/unicode.ttf");


        try {
            $printer = new Printer($connector, $profile);
            $printer->setJustification(Printer::JUSTIFY_CENTER);
            $printer->selectPrintMode(Printer::MODE_DOUBLE_WIDTH);

            /* Print top logo */
            if ($profile->getSupportsGraphics()) {
                $printer->graphics($logo);
            }
            if ($profile->getSupportsBitImageRaster() && !$profile->getSupportsGraphics()) {
                $printer->bitImage($logo);
            }

            $printer->feed();

            /* Name of shop */
            $printer->setJustification(Printer::JUSTIFY_CENTER);
            $printer->selectPrintMode(Printer::MODE_DOUBLE_WIDTH);
            $printer->text("Unity Collection \n");
            $printer->selectPrintMode();
            $printer->text("Authorized Money Changer \n");
            $printer->feed();
            $printer->text("No-148, Anawrahta Road, Corner of 35th Street, \nKyauktada Township, Yangon, Myanmar.\n");
            $printer->feed();

            /* Title of receipt */
            $printer->setEmphasis(true);
            $printer->text("SALES INVOICE\n");
            $printer->setEmphasis(false);
            $printer->feed();

            $printer->setJustification(Printer::JUSTIFY_LEFT);
            $printer->text("Date - ".date('d-M-Y', strtotime($invoice->created_date)) . "\n");
            $printer->text("Time - ".date('h:i:s a', strtotime($invoice->created_time)) . "\n");
            $printer->text("Invoice ID - ".$invoice->invoiceSerial."\n");
            
            /* Items */
            $printer->text("------------------------------------------------\n");
            $printer->setEmphasis(true);
            $printer->text($this->printOption('Description', 'Qty', 'Amount', 48));
            $printer->setEmphasis(false);
            $printer->text("------------------------------------------------\n");
            $printer->setJustification(Printer::JUSTIFY_LEFT);
            
            $subTotal_buy = 0;
            $subTotal_sell = 0;

            if(count($itemBuy) > 0){

                $printer->setEmphasis(true);
                $printer->text("Buy \n");
                $printer->setEmphasis(false);

                foreach ($itemBuy as $bItem) {
                    $rows = $this->CI->ignite_model->get_limit_datas('invoice_detail_tbl', ['invoiceId' => $bItem->invoiceId, 'itemName' => $bItem->itemName])->result();
                    $cTotal_buy = 0;

                    foreach($rows as $row){

                        $bItemName = $row->itemName .' '. $row->itemAmount .' ( '.$row->itemPrice.' ) ';
                        $bTotal = ($row->itemPrice * $row->itemQty);
                        $printer->text($this->printOption($bItemName, $row->itemQty, $bTotal, 48)); // for 58mm Font A
                        $subTotal_buy += $bTotal;
                        $cTotal_buy += $row->itemQty;
                    }

                    if(count($rows) > 1){
                        $printer->setEmphasis(true);
                        $printer->text($this->printOption('Total ' .$bItem->itemName, $cTotal_buy, '', 48)); 
                        $printer->setEmphasis(false);
                    }
                }
            }

            $printer->feed();
            if(count($itemSell) > 0){

                $printer->setEmphasis(true);
                $printer->text("Sell \n");
                $printer->setEmphasis(false);

                foreach ($itemSell as $sItem) {
                    $rows = $this->CI->ignite_model->get_limit_datas('invoice_detail_tbl', ['invoiceId' => $sItem->invoiceId, 'itemName' => $sItem->itemName])->result();
                    $cTotal_sell = 0;

                    foreach($rows as $row){

                        $sItemName = $row->itemName .' '. $row->itemAmount .' ( '.$row->itemPrice.' ) ';
                        $sTotal = ($row->itemPrice * $row->itemQty);
                        
                        $printer->text($this->printOption($sItemName, $row->itemQty, $sTotal, 48)); // for 58mm Font A
                        $subTotal_sell += $sTotal;
                        $cTotal_sell += $row->itemQty;
                    } // End of rows

                    if(count($rows) > 1){
                        $printer->setEmphasis(true);
                        $printer->text($this->printOption('Total ' .$sItem->itemName, $cTotal_sell, '', 48)); 
                        $printer->setEmphasis(false);
                    }
                }
            }

            $printer->text("------------------------------------------------\n");
           
            if($invoice->type === "CE"){                
                $printer->setEmphasis(false);
                $printer->text($this->printOption('Total Buy ', '', $subTotal_buy, 48));
                $printer->feed();

                /* Tax and total */
                $printer->text($this->printOption('Total Sell ', '', $subTotal_sell, 48));
                $printer->feed();
                // $printer->selectPrintMode(Printer::MODE_DOUBLE_WIDTH);
                $printer->setEmphasis(true);
                $printer->text($this->printOption('Total Balance ','', ($subTotal_buy - $subTotal_sell), 48));
                $printer->selectPrintMode();
            }
            else{
                $printer->setEmphasis(true);
                $printer->text($this->printOption('Total ','', ($subTotal_sell), 48));
                $printer->selectPrintMode();
            }

            $printer->text("================================================\n");

            $printer->feed();
            $printer->setJustification(Printer::JUSTIFY_CENTER);
            $printer->text("https://www.facebook.com/UnityMoneyChanger\n");

            /* Footer */
            $printer->feed(2);
            $printer->setJustification(Printer::JUSTIFY_CENTER);
            $printer->text("09 760133427, 09 31469082, 09 790222570, \n01-8378035, 01-8370225\n");
            // $printer->feed(2);
            


            // Demo that alignment QRcode is the same as text
            // $printer2 = new Printer($connector); // dirty printer profile hack !!
            // $printer2->setJustification(Printer::JUSTIFY_CENTER);
            // $printer2->qrCode($checkIn['r_invoice_serial'], Printer::QR_ECLEVEL_M, 8);
            // $printer2->setJustification();
            // $printer2->feed();


            /* Cut the receipt and open the cash drawer */
            $printer->cut();
            $printer->pulse();
        } catch (Exception $e) {
            echo "Couldn't print to this printer: " . $e -> getMessage() . "\n";
        } finally {
            $printer->close();
        }
        
        
    }

    public function print_order(){
        header('Content-type: text/html; charset=utf-8');
        mb_internal_encoding("UTF-8");
        $profile = CapabilityProfile::load("SP2000");
        // $printPDF = __DIR__.'/../../assets/resources/document.pdf';
        $connector = new NetworkPrintConnector("192.168.1.101", 9100);
        $printer = new Printer($connector, $profile);
        
        // Use Unifont to render text
        // $unifontBuffer = new UnifontPrintBuffer("/usr/share/unifont/unifont.hex");
        // $printer -> setPrintBuffer($unifontBuffer);

        // Most simple example
        $printer->text("မြန်မာစာသည်တို့စာ\n");
        $printer->cut();
        $printer->close();
    }

    public function printOption($name, $qty, $price, $width = 48){
        $rightCols = 15;
        $centerCols = 7;
        if(!empty($qty)){
            $leftCols = $width - ($rightCols + $centerCols);
            if(is_numeric($qty) && is_numeric($price)){
                $center = str_pad($qty, $centerCols, ' ', STR_PAD_BOTH);
                $left = str_pad($name, $leftCols, ' ', STR_PAD_RIGHT);
                $right = str_pad(number_format($price, 2), $rightCols, ' ', STR_PAD_LEFT);
                return "$left$center$right\n";
            }else{
                $center = str_pad($qty, $centerCols, ' ', STR_PAD_BOTH);
                $left = str_pad($name, $leftCols, ' ', STR_PAD_RIGHT);
                $right = str_pad($price, $rightCols, ' ', STR_PAD_LEFT);
                return "$left$center$right\n";
            }
        }else{
            $leftCols = $width - $rightCols;
            $left = str_pad($name, $leftCols, ' ', STR_PAD_LEFT);
            $right = str_pad(number_format($price,2), $rightCols, ' ', STR_PAD_LEFT);
            return "$left$right\n";
        }


    }

    

    
}



/* End of file Ignite.php */
/* Location: ./application/libraries/Ignite.php */