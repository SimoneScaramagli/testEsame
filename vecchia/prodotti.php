<?php
include('./phpqrcode/qrlib.php');
// Specifica i dati dell'API di WooCommerce
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
include('header.php');

// Gestisci la risposta dell'API
if ($response === false) {
    $error = curl_error($ch);
    // Gestisci l'errore
} else {
    $orders = json_decode($response);
}
curl_close($ch);
?>
<div class=" container table-responsive pt-3">
    <div class="table-wrapper">
        <?php
        if ($response === false) {
            $error = curl_error($ch);
        } else {
            $products = json_decode($response);
        ?>
            <table id="my-table" class="table table-striped table-hover table-bordered">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>SKU</th>
                        <th>NOME PRODOTTO</th>
                        <th>PREZZO</th>
                        <th>QUANTITA </th>
                        <th>AZIONI</th>
                        <th>QRCODE</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    foreach ($products as $product) {
                    ?>
                        <tr>
                            <td><?php echo $product->id; ?></td>
                            <td><?php echo $product->sku; ?></td>
                            <td><?php echo $product->name; ?></td>
                            <td><?php echo $product->price; ?>€</td>
                            <td><?php echo $product->stock_quantity; ?></td>
                            <td>
                                <a href="#" class="edit" title="Edit" data-toggle="tooltip"><i class="fa-solid fa-pen-to-square"></i></a>
                                <a <?php echo 'href="./delete_order.php?id=' . $product->id . '"' ?> class="delete" title="Delete" data-toggle="tooltip"><i class="fa-solid fa-trash"></i></a>
                                <form action="./generate_pdf.php" method="POST">
                                    <input type="hidden" name="id" value="<?php echo $product->id ?>">
                                    <button type="submit" class="stampa" title="Stampa" data-toggle="tooltip"><i class="fa-sharp fa-solid fa-file-pdf"></i></button>
                                </form>
                            </td>
                            <td>
                                <?php
                                $sku = $product->sku;
                                $newsku = str_replace(' ', '', $sku);
                                $qrimage = $newsku . ".png";
                                QRCODE::png("./products_qr/" . $newsku, $qrimage, 1, 2);
                                ?>
                                <img src="<?php echo $qrimage ?>" />
                            </td>
                        </tr>
                <?php
                    }
                    // Chiudi la connessione cURL
                    curl_close($ch);
                }
                ?>
                </tbody>
                <tfoot>
                    <tr>
                        <td colspan="2">
                            <div id="pagination"></div>
                        </td>
                    </tr>
                </tfoot>
            </table>
    </div>
</div>
</div>

<?php
include('footer.php');
?>

</html>