<?php
header('Content-type: text/html; charset=utf-8');
mb_internal_encoding("UTF-8");
use Mike42\Escpos\Printer;
use Mike42\Escpos\EscposImage;
use Mike42\Escpos\ImagickEscposImage;
use Mike42\Escpos\PrintConnectors\NetworkPrintConnector;
use Mike42\Escpos\PrintConnectors\WindowsPrintConnector;
use Mike42\Escpos\PrintConnectors\FilePrintConnector;
use Mike42\Escpos\CapabilityProfile;
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

    public function print_receipt($invoice, $items){

        $newDate = getDate();
        header('Content-type: text/html; charset=utf-8');
        mb_internal_encoding("UTF-8");
        $profile = CapabilityProfile::load("TM-T88III");
        $connector = new WindowsPrintConnector("EPSON-T81III");
        $printer = new Printer($connector, $profile);

        $imageBuffer = new ImagePrintBuffer();
        $imageBuffer -> setFont(__DIR__ . "/../../assets/font/zawgyi.ttf");
        $textBuffer = new EscposPrintBuffer();

        try {
            $printer = new Printer($connector, $profile);
            $printer -> setPrintBuffer($imageBuffer);

            /* Name of shop */
            $printer->setJustification(Printer::JUSTIFY_CENTER);
            $printer->selectPrintMode(Printer::MODE_DOUBLE_WIDTH);
            $printer->setEmphasis(true);
            $imageBuffer->setFontSize(32);
            $printer->text("ေ႐ႊညီကို စတိုး \n");
            $imageBuffer->setFontSize(24);
            $printer->feed();
            $printer->selectPrintMode();
            $printer->text("အခ်ိဳရည္ႏွင့္ ပင္လယ္စာ ျဖန႔္ခ်ီေရး \n");
            $printer->feed();

            $printer->text("ဆိုင္အမွတ္(၈)၊ ေရလဲလမ္း ခ႐ိုင္အားကစား႐ုံေရွ႕ မအူပင္ၿမိဳ႕။\n");
            $printer->text("TEL: 09970166233\n");
            $printer->text("________________________________________________\n");
            $printer->feed();

            /* Title of receipt */
            $printer->setEmphasis(true);
            $printer->setPrintBuffer($textBuffer);
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
            $left = str_pad('Description', 30, ' ', STR_PAD_RIGHT);
            $center = str_pad('Qty', 8, ' ', STR_PAD_LEFT);
            $right = str_pad('Amount', 10, ' ', STR_PAD_LEFT);
            $printer->text("$left$center$right\n");
            $printer->setEmphasis(false);
            $printer->text("------------------------------------------------\n");
            $printer->setJustification(Printer::JUSTIFY_LEFT);

            $total = 0;
            foreach($items as $item){
                $amount = $item->itemQty * $item->itemPrice;
                $total += $amount;
                $itemDetail = $this->CI->ignite_model->get_limit_data('items_price_tbl', 'codeNumber', $item->itemCode)->row();

                $printer->setPrintBuffer($imageBuffer);
                $printer->text($item->itemName);
                
                $printer->setPrintBuffer($textBuffer);
                $printer->text($this->print_option($itemDetail->itemModel, $item->itemQty, $amount, 48)); // for 58mm Font A
            }

            $printer->setPrintBuffer($textBuffer);
            $printer->text("================================================\n");
            
            $printer->setEmphasis(true);
            $printer->text($this->print_option('Total ','', $total, 48));
            

            
            $printer->feed();

            $printer->setJustification(Printer::JUSTIFY_CENTER);
            $printer->text("- Thank You - \n");


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
        $newDate = getDate();
        
        $connector = new WindowsPrintConnector("nippon");
        $printer = new Printer($connector);

        $imageBuffer = new ImagePrintBuffer();
        $imageBuffer -> setFont(__DIR__ . "/../../assets/font/zawgyi.ttf");
        
        $printer->text('testing');
        $printer->close();
    }

    public function print_option($left, $center, $right, $width=48){
        $cCol = 8;
        $rCol = 10;

        if(empty($center) && empty($right)){
            $lCol = $width;
            $str_left = str_pad($left, $lCol);
            return "$str_left\n";
        }
        elseif(empty($center)){
            $lCol = $width - $rCol;
            $str_left = str_pad($left, $lCol);
            $str_right = str_pad($right, $rCol, ' ', STR_PAD_LEFT);
            return "$str_left$str_right\n";
        }
        else{
            $lCol = $width - ($cCol+$rCol);

            $str_left = $this->str_pad_unicode($left, $lCol, ' ', STR_PAD_RIGHT);
            $str_center = $this->str_pad_unicode($center, $cCol, ' ', STR_PAD_LEFT);
            $str_right = $this->str_pad_unicode($right, $rCol, ' ', STR_PAD_LEFT);
            return "$str_left$str_center$str_right\n";
        }
    }

    function str_pad_unicode($str, $pad_len, $pad_str = ' ', $dir = STR_PAD_RIGHT) {
        $str_len = mb_strlen($str);
        $pad_str_len = mb_strlen($pad_str);
        if (!$str_len && ($dir == STR_PAD_RIGHT || $dir == STR_PAD_LEFT)) {
            $str_len = 1; // @debug
        }
        if (!$pad_len || !$pad_str_len || $pad_len <= $str_len) {
            return $str;
        }
       
        $result = null;
        $repeat = ceil($str_len - $pad_str_len + $pad_len);
        if ($dir == STR_PAD_RIGHT) {
            $result = $str . str_repeat($pad_str, $repeat);
            $result = mb_substr($result, 0, $pad_len);
        } else if ($dir == STR_PAD_LEFT) {
            $result = str_repeat($pad_str, $repeat) . $str;
            $result = mb_substr($result, -$pad_len);
        } else if ($dir == STR_PAD_BOTH) {
            $length = ($pad_len - $str_len) / 2;
            $repeat = ceil($length / $pad_str_len);
            $result = mb_substr(str_repeat($pad_str, $repeat), 0, floor($length))
                        . $str
                           . mb_substr(str_repeat($pad_str, $repeat), 0, ceil($length));
        }
       
        return $result;
    }

    

    
}



/* End of file Ignite.php */
/* Location: ./application/libraries/Ignite.php */