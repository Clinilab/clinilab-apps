<?php

namespace WhiteOctober\TCPDFBundle\Controller;

use ReflectionClass;

use \TCPDF;

class PortraitController extends TCPDF
{
    protected $numero = null;
    protected $paciente = null;
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
        $this->SetMargins('5', '50', '5');

        $image_file = __DIR__.'/../../../../public/img/logos/'.$this->institucion['logo'];
        
        $style = array(
            'position' => 'R',
            'align' => 'C',
            'stretch' => false,
            'fitwidth' => true,
            'text' => true,
            'font' => 'helvetica',
            'fontsize' => 8,
            'stretchtext' => 4
        );

        $this->SetY(10);
        $this->write1DBarcode($this->numero, 'C128A', '', '', '', 10, 0.4, $style, 'N');
        
        $this->Image($image_file, '', 10, '', 15, 'JPG', '', 'M', false, 10, '', false, false, 0, 'L', false, false);
        
        $this->SetFont('times', 'BI', 14, 'false'); 
        $this->SetTextColor(19, 141, 117);
        $this->SetY(10);
        $this->SetFont('Times','B',12);
        $this->Cell(0, 0, $this->institucion['nombre'], 0, 1, 'C', 0, '');
        $this->SetFont('times', 'BI', 8, 'false');
        $this->SetTextColor(41, 128, 185);
        $this->Cell(0, 0, 'NIT '.$this->institucion['identificacion'], 0, 1, 'C', 0, '');
        $this->Cell(0, 0, $this->institucion['direccion'].' Tel: '.$this->institucion['telefono'], 0, false, 'C', 0, '', 0, false, 'T', 'M');

        if ($this->paciente) {
            $this->SetTextColor(0, 0, 0);
            $this->SetY(30);
            $this->SetFont('helvetica', 'B', 7, 'false');
            
            $this->html = '<table border="0" cellspacing="0" cellpadding="1">
            <tr>
                <td width="70%"><b>Nombres y Apellidos:'.$this->paciente["nombres"].'</b></td>
                <td width="30%" align="right"><b>Identificación: '.$this->paciente["identificacion"].'</b></td>
            </tr>

            <tr>
                <td width="50%">Fecha nacimiento: '.$this->paciente["fechaNacimiento"].'</td>
                <td width="20%" align="right">Edad: '.$this->paciente["anios"].' Años</td>
                <td width="30%" align="right">Genero: '.$this->paciente["genero"].'</td>
            </tr>

            <tr>
                <td width="50%">Dirección: '.$this->paciente["direccion"].'</td>
                <td width="25%">Servicio: '.$this->paciente["servicio"].'</td>
                <td width="25%" align="right">Teléfono: '.$this->paciente["telefono"].'</td>
            </tr>

            <tr>
                <td width="50%">Fecha de toma de muestras: '.$this->paciente["fecha"].' '.$this->paciente["hora"].'</td>
                <td width="50%" align="right">Fecha impresión de resultado: '.date('d/m/Y h:i:s A').'</td>
            </tr>
            </table>';
            
            $this->writeHTML($this->html, true, false, true, false, '');
        }
        /*$style = array(
            'border' => false,
            'vpadding' => false,
            'hpadding' => false,
            'fgcolor' => array(19, 141, 117),
            'bgcolor' => false, //array(255,255,255)
            'module_width' => 1, // width of a single module in points
            'module_height' => 1 // height of a single module in points
        );

        $this->SetTextColor(19, 141, 117);
        $this->SetXY(15, 5);
        $this->SetFont('times', 'BI', 8, 'false');
        $this->Cell(0, 0, 'Laboratorio Clínico', 0, 1, 'C', 0, '');
        $this->SetFont('times', 'BI', 13, 'false'); 
        $this->SetTextColor(41, 128, 185);
        $this->Cell(0, 0, 'NIT 814000337', 0, 1, 'C', 0, '');
        $this->Cell(0, 0, 'Carrera 2 # 16 - 08 Tel: 7442029 La Unión, Nariño', 0, false, 'C', 0, '', 0, false, 'T', 'M');*/
    } 

    // Page footer
    public function Footer() {
        // Position at 15 mm from bottom
        $this->SetY(-15);
        // Set font
        $this->SetFont('helvetica', 'I', 8);

        //$image_file = __DIR__.'/../../../../public/img/logo-sm-pdf.png';
        //$this->Image($image_file, '', 500, '', 15, 'PNG', '', 'M', false, 10, '', false, false, 0, 'L', false, false);
        $this->html = '<table border="0" cellspacing="3" cellpadding="4">
        <tr>
            <td align="left">
                Pag. '.$this->getAliasNumPage().'/'.$this->getAliasNbPages().'</td>
            <td align="right"><a href="http://clinimetricsas.com.co">www.clinimetricsas.com.co</a></td>
        </tr></table>';
        $this->writeHTML($this->html, true, false, true, false, '');
    }

    public function template($html, $data)
    {
        // create new PDF document
        $pdf = new PortraitController('P', PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
        $pdf->setData($data);

        // set document information
        $pdf->SetCreator(PDF_CREATOR);
        $pdf->SetAuthor('JHWEB PASTO SAS');
        $pdf->SetTitle('Impresión de resultados.');
        $pdf->SetSubject('PDF CLINIMETRICS');
        $pdf->SetKeywords('TCPDF, PDF, example, test, guide');

        // set default header data
        $fecha = new \DateTime('now');

        // set font
        $pdf->SetFont('helvetica', '', 8, '', true);

        // Add a page
        // This method has several options, check the source code documentation for more information.
        $pdf->AddPage('P', 'Letter');

        $pdf->SetMargins('0', '5', '3');
        $pdf->SetX(3);
       
        // Print text using writeHTMLCell()
        //$pdf->writeHTMLCell(0, 0, '', '', $html, 0, 1, 0, true, '', true);

        $pdf->writeHTML($html, true, false, true, false, '');
        // ---------------------------------------------------------
        // Close and output PDF document
        // This method has several options, check the source code documentation for more information.
        $pdf->Output('example_065.pdf', 'I');
    }

    public function templateWorkSheet($html, $data)
    {
        // create new PDF document
        $pdf = new PortraitController('P', PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
        $pdf->setData($data);
 
        // set document information
        $pdf->SetCreator(PDF_CREATOR);
        $pdf->SetAuthor('JHWEB PASTO SAS');
        $pdf->SetTitle('Hoja de trabajo.');
        $pdf->SetSubject('PDF CLINIMETRICS');
        $pdf->SetKeywords('TCPDF, PDF, example, test, guide');

        // set default header data
        $fecha = new \DateTime('now');

        $this->SetMargins('70', '30', '30');

        // set font
        $pdf->SetFont('helvetica', '', 8, '', true);

        // Add a page
        // This method has several options, check the source code documentation for more information.
        $pdf->AddPage('P', 'Letter');
       

        // Print text using writeHTMLCell()
        $pdf->writeHTMLCell(0, 0, '', '', $html, 0, 1, 0, true, '', true);
        // ---------------------------------------------------------
        // Close and output PDF document
        // This method has several options, check the source code documentation for more information.
        $pdf->Output('hoja_trabajo.pdf', 'I');
    }

    public function templateBarcode($html)
    {
        //$pageLayout = array('55', '25');
        // create new PDF document
        //$pdf = new PortraitController('P', 'mm', $pageLayout, true, 'UTF-8', false);
        
        // create new PDF document
        $pdf = new PortraitController('P', PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

        // set document information
        $pdf->SetCreator(PDF_CREATOR);
        $pdf->SetAuthor('JHWEB PASTO SAS');
        $pdf->SetTitle('Impresión de resultados.');
        $pdf->SetSubject('PDF CLINIMETRICS');
        $pdf->SetKeywords('TCPDF, PDF, example, test, guide');

        // set default header data
        $fecha = new \DateTime('now');

        $this->SetMargins('3', '5', '3');

        // set font
        $pdf->SetFont('helvetica', '', 8, '', true);

        // Add a page
        // This method has several options, check the source code documentation for more information.
        $pdf->AddPage('P', 'Letter');

        // Print text using writeHTMLCell()
        $pdf->writeHTMLCell(0, 0, '', '', $html, 0, 1, 0, true, '', true);
        // ---------------------------------------------------------
        // Close and output PDF document
        // This method has several options, check the source code documentation for more information.
        $pdf->Output('example_065.pdf', 'I');
    }
}
