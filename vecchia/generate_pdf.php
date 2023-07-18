<?php
require('./fpdf/fpdf.php');

class PDF extends FPDF
{
    // Page header
    function Header()
    {
        // Logo
        // $this->Image('./logo.png', 10, 6, 30);
        // Arial bold 15
        $this->SetFont('Arial', 'B', 15);
        // Move to the right
        $this->Cell(80);
        // Title
        $this->Cell(30, 10, 'Prodotto', 1, 0, 'C');
        // Line break
        $this->Ln(20);
    }

    // Page footer
    function Footer()
    {
        // Position at 1.5 cm from bottom
        $this->SetY(-15);
        // Arial italic 8
        $this->SetFont('Arial', 'I', 8);
        // Page number
        $this->Cell(0, 10, 'Page ' . $this->PageNo() . '/{nb}', 0, 0, 'C');
    }
}

$url = 'https://servizi.wpschool.it/wp-json/wc/v3/products';
$username = 'ck_fe204273ad276fc9c7a5e36ad96765b26bbce88b';
$password = 'cs_1ceae7502d5becd86f21d13c215d97f49d8989ff';



// Crea un'istanza di cURL
$ch = curl_init();

// Imposta le opzioni di cURL
curl_setopt($ch, CURLOPT_URL, $url);
curl_setopt($ch, CURLOPT_USERPWD, $username . ':' . $password);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

// Esegui la chiamata API
$response = curl_exec($ch);

// Gestisci la risposta dell'API
if ($response === false) {
    $error = curl_error($ch);
    // Gestisci l'errore
} else {
    $products = json_decode($response);
}
// Chiudi la connessione cURL
curl_close($ch);

$id = $_POST["id"];

$prodotto_da_stampare = '';
foreach ($products as $product) {
    if ($id == $product->id) {
        $prodotto_da_stampare = $product;
    }
}
// Instanciation of inherited class
$pdf = new PDF();
$pdf->AliasNbPages();
$pdf->AddPage();
$pdf->SetFont('Times', '', 12);
$pdf->Cell(0, 10, 'Sku:  ' . $prodotto_da_stampare->sku, 0, 1);
$pdf->Cell(0, 10, 'Nome:  ' . $prodotto_da_stampare->name, 0, 1);
$pdf->Cell(0, 10, 'Descrizione:  ' . $prodotto_da_stampare->description, 0, 1);
$pdf->Cell(0, 10, 'Prezzo:  ' . $prodotto_da_stampare->price, 0, 1);
$pdf->Cell(0, 10, 'Quantita:  ' . $prodotto_da_stampare->stock_quantity, 0, 1);
$pdf->Output();
