<?

function extract_request_var($nome, &$destino, $default="") {
    $destino = $default;
    $resp = isset($_REQUEST[$nome]);
    if ($resp) $destino = $_REQUEST[$nome];
    return $resp;
}

?>
