<?php
    /*header('Content-Type: application/json');
    $request = file_get_contents('php://input');
    $req_dump = print_r( $request, true );
    $json_data = file_put_contents( 'request.log', $req_dump );
    $action = json_decode($json_data, true);*/
    ?>

<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $raw_data = file_get_contents('php://input');
    $payload = json_decode($raw_data, true);

    if (is_array($payload)) {
        $ext = ".txt";
        //$namef = date('Ymd-His', time());
        $namef = $payload["sku"];
        $filename = $namef.$ext;

        $fh = fopen("CARRITO-WEB/INFO-PARA-DOS/".$filename, "a+");

        if ($fh) {
            $sku = (int)$payload["sku"];
            $el_sku = sprintf('%05d', $sku);
            $stock = $payload["stock_quantity"];
            $el_stock = sprintf('%08d', $stock);
            //fwrite($fh, date('Y-m-d H:i:s', time()).PHP_EOL);
            fwrite($fh, $el_sku . "=" . $el_stock . PHP_EOL);
            fclose($fh);
        } else {
            echo("Sorry, Unable to open file!");
        }
    } else {
        echo("sorry, Invalid payload!");
    }
} else {
    echo("sorry, Invalid request!");
}
?>