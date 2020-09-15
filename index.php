<?php

require __DIR__ . "/vendor/autoload.php";

# ATENÇÃO:
# VERIFIQUE OS DADOS DE SEU HOST
# E MODIFIQUE O ARQUIVO config.php PRESENTE NO DIRETÓRIO Source/Config/config.php

/**
 * o método bootstrap monta a mensagem de Email
 * subject = assunto
 * body = corpo da mensagemm
 * recipient = email do destinatário
 * recipientName = nome do destinatário
 */

$email = (new \Source\Email\Email())->bootstrap(
        "Olá mundo",
        "Seu texto de mensagem aqui",
        "edem.fbc@gmail.com",
        "Gabriel"
);

/*
 * Caso queira enviar arquivo com anexo
 * utilize o método attach(), antes do método send,
 * e informe no argumento do método attach() o caminho do arquivo que será enviado
 */
//$email->attach("caminhodoarquivo", "nome_do_arquivo");

// envia a mensagem
if ($email->send()) {
    echo "<p>E-mail enviado com sucesso</p>";
} else {
    echo $email->getMessage();
}

