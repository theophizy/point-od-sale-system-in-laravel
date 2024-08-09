<?php
namespace App\Services;

use Milon\Barcode\DNS1D;

class BarcodeService{
 public function generateBarcode($barcodeValue, $barcodeType = 'EAN13'){
 $barcode = new DNS1D();
 return $barcode->getBarcodeHTML($barcodeValue, $barcodeType);
 }
}

?>