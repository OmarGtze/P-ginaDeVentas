<?php

    $email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
    $nombre = filter_var($_POST['nombre'], FILTER_SANITIZE_STRING);
    $texto = filter_var($_POST['texto'], FILTER_SANITIZE_STRING);

    if(!empty($email) && !empty($nombre) && !empty($texto)){
        $destino = 'revolution6978@gmail.com';
        $asunto = 'Email de testeo';

        $cuerpo = '
        <html>
            <head>
                <title> Correo de testeo</title>
            </head>
            <body>
                <h1>Email de: '.$nombre.'</h1>
                <p> '.$texto.'</p>
            </body>
        </html>
        ';

        //para el envio en formato HTML
        $headers = "MIME-Version: 1.0\r\n";
        $headers .= "Content-type: text/html; charset=utf-8\r\n";

        //dirección del remitente
        $headers .= "From: $nombre <$email>\r\n";

        //ruta del mensaje desde origen al destino
        $headers .= "Return-path: $destino\r\n";

        mail($destino,$asunto,$cuerpo,$headers);

        header("Location: contacto.php");
    }
    else{
        echo "Error al enviar el email";
    }

?>