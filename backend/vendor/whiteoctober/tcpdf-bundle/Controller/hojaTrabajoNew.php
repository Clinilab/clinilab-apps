<?php

namespace WhiteOctober\TCPDFBundle\Controller;

use ReflectionClass;

use \TCPDF;

class hojaTrabajoNew extends TCPDF
{
    protected $numero;
    protected $paciente;
    protected $institucion;
    public $data;

    public function setData($data){
        if (array_key_exists('numero', $data)) {
            $this->numero = $data['numero'];
        }

        if (array_key_exists('paciente', $data)) {
            $this->paciente = $data['paciente'];
        }

        if (array_key_exists('institucion', $data)) {
            $this->institucion = $data['institucion'];
        }

        //return $this->data;
    }

    public function setNumero($numero, $paciente){
        $this->numero = $numero;
        $this->paciente = $paciente;
    }
    
     //Page header
     public function Header() {
        // Set font
        $this->SetMargins('helvetica', 'B', 8);
        $this->SetMargins('5', '30', '5');

        $image_file = __DIR__.'/../../../../public/img/logos/'.$this->institucion['logo'];
        
        $this->SetY(2);

        $this->Image($image_file, '', 5, '', 20, 'JPG', '', 'M', false, 10, '', false, false, 0, 'L', false, false);
        
        $this->SetFont('times', 'BI', 14, 'false'); 
        $this->SetTextColor(19, 141, 117);
        $this->SetY(5);
        $this->SetFont('Times','B',12);
        $this->Cell(0, 0, $this->institucion['nombre'], 0, 1, 'C', 0, '');
        $this->SetFont('times', 'BI', 8, 'false');
        $this->SetTextColor(41, 128, 185);
        $this->Cell(0, 0, 'NIT '.$this->institucion['identificacion'], 0, 1, 'C', 0, '');
        $this->Cell(0, 0, $this->institucion['direccion'].' Tel: '.$this->institucion['telefono'], 0, false, 'C', 0, '', 0, false, 'T', 'M');
    } 

    // Page footer
    public function Footer() {
        // Position at 15 mm from bottom
        $this->SetY(-15);
        // Set font
        $this->SetFont('helvetica', 'I', 8);

        $image_file = __DIR__.'/../../../../public/img/logoF.png';
        $this->html = '<table border="0" cellspacing="3" cellpadding="4">
        <tr>
            <td align="left">
                <img src="'.$image_file.'" height="15" border="0" />
            </td>
            <td align="center">Impresión '.date('d/m/Y').'<br>Pag. '.$this->getAliasNumPage().'/'.$this->getAliasNbPages().'</td>
            <td align="right"><a href="http://clinimetricsas.com.co">www.clinimetricsas.com.co</a></td>
        </tr>';
        $this->writeHTML($this->html, true, false, true, false, '');
    }

    public function template($html, $data)
    {
        // create new PDF document
        $pdf = new LandscapeController('L', PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
        
        $pdf->setData($data);
        
        // set document information
        $pdf->SetCreator(PDF_CREATOR);
        $pdf->SetAuthor('JHWEB PASTO SAS');
        $pdf->SetTitle('Impresión de resultados.');
        $pdf->SetSubject('PDF CLINIMETRICS');
        $pdf->SetKeywords('TCPDF, PDF, example, test, guide');

        // set default header data
        $fecha = new \DateTime('now');

        $this->SetMargins('40', '30', '30');

        // set font
        $pdf->SetFont('helvetica', '', 8, '', true);

        // Add a page
        // This method has several options, check the source code documentation for more information.
        $pdf->AddPage('P', 'Legal');
       

        // Print text using writeHTMLCell()
        $pdf->writeHTMLCell(0, 0, '', '', $html, 0, 1, 0, true, '', true);
        // ---------------------------------------------------------
        // Close and output PDF document
        // This method has several options, check the source code documentation for more information.
        $pdf->Output('example_065.pdf', 'I');
    }

    public function templateWorkSheet($html, $data)
    {
        // create new PDF document
        $pdf = new LandscapeController('L', PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);        
        
        $pdf->setData($data);
 
        // set document information
        $pdf->SetCreator(PDF_CREATOR);
        $pdf->SetAuthor('JHWEB PASTO SAS');
        $pdf->SetTitle('Hoja de trabajo.');
        $pdf->SetSubject('PDF CLINIMETRICS');
        $pdf->SetKeywords('TCPDF, PDF, example, test, guide');

        // set default header data
        $fecha = new \DateTime('now');

        $this->SetMargins('3', '5', '3');

        // set font
        $pdf->SetFont('helvetica', '', 8, '', true);

        // Add a page
        // This method has several options, check the source code documentation for more information.
        $pdf->AddPage('L', 'Legal');
       

        // Print text using writeHTMLCell()
        $pdf->writeHTMLCell(0, 0, '', '', $html, 0, 1, 0, true, '', true);
        // ---------------------------------------------------------
        // Close and output PDF document
        // This method has several options, check the source code documentation for more information.
        $pdf->Output('hoja_trabajo.pdf', 'I');
    }
}
