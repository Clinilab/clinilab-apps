<?php

namespace WhiteOctober\TCPDFBundle\Controller;

use ReflectionClass;

use \TCPDF;

class BarcodeController extends TCPDF
{
    public function setData($data){
        if (array_key_exists('numero', $data)) {
            $this->numero = $data['numero'];
        }
    }
    
     //Page header
     public function Header() {
        // Set font
        $this->SetMargins('3', '12', '0');
        
        $style = array(
            'position' => 'L',
            'align' => 'C',
            'stretch' => false,
            'fitwidth' => true,
            'text' => true,
            'font' => 'helvetica',
            'fontsize' => 5,
            'stretchtext' => 4
        );

        $this->SetY(2);
        $this->write1DBarcode($this->numero, 'C128A', '', '', '', 10, 0.4, $style, 'N');
    }

    public function template($html, $data)
    {
        // create new PDF document
        $pdf = new BarcodeController('P', PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
        $pdf->setData($data);
 
        // set document information
        $pdf->SetCreator(PDF_CREATOR);
        $pdf->SetAuthor('JHWEB PASTO SAS');
        $pdf->SetTitle('Impresión de resultados.');
        $pdf->SetSubject('PDF CLINIMETRICS');
        $pdf->SetKeywords('TCPDF, PDF, example, test, guide');

        $page_format = array(
            'MediaBox' => array ('llx' => 0, 'lly' => 0, 'urx' => 60, 'ury' => 30),
            //'CropBox' => array ('llx' => 0, 'lly' => 0, 'urx' => 210, 'ury' => 297),
            //'BleedBox' => array ('llx' => 5, 'lly' => 5, 'urx' => 205, 'ury' => 292),
            //'TrimBox' => array ('llx' => 10, 'lly' => 10, 'urx' => 200, 'ury' => 287),
            //'ArtBox' => array ('llx' => 15, 'lly' => 15, 'urx' => 195, 'ury' => 282),
            'Dur' => 3,
            'trans' => array(
                'D' => 1.5,
                'S' => 'Split',
                'Dm' => 'V',
                'M' => 'O'
            ),
            'Rotate' => 0,
            'PZ' => 1,
        );

        $pdf->SetMargins(1, 0, 0, true);
        $pdf->SetAutoPageBreak(TRUE, 0);

        $pdf->AddPage('L', $page_format, false, false);
       
        // Print text using writeHTMLCell()
        $pdf->writeHTMLCell(0, 0, '', '', $html, 0, 0, 0, true, '', true);
        // ---------------------------------------------------------
        // Close and output PDF document
        // This method has several options, check the source code documentation for more information.
        $pdf->Output('barcode_'.$data['numero'].'.pdf', 'I');
    }
}
