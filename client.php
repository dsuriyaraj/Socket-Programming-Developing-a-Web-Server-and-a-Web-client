<html>
    <head>
        <title>Client</title>
    </head>
    
    <body>
        <?php
            $message="index.html";
            echo "Requesting HTML code from the server for the Web page: <b>{$message}</b>"."<br>";
            //Socket creation
            if(!($sock = socket_create(AF_INET, SOCK_STREAM, 0)))
            {
                $errorcode = socket_last_error();
                $errormsg = socket_strerror($errorcode);
                die("Couldn't create socket: [$errorcode] $errormsg \n");
            }

            $ip_address = "127.0.0.1";
            $port_number=22223;

            //Connect socket to the server
            if(!socket_connect($sock , $ip_address , $port_number))
            {
                $errorcode = socket_last_error();
                $errormsg = socket_strerror($errorcode);
                die("Could not connect: [$errorcode] $errormsg \n");
            }

            //Send the message/filename to the server
            if( !socket_write($sock, $message, strlen($message)))
            {
                $errorcode = socket_last_error();
                $errormsg = socket_strerror($errorcode);
                die("Could not send data to the server: [$errorcode] $errormsg \n");
            }

            //Now receive reply from server
            $result = socket_read ($sock, 100000) or die("Could not read server response\n");
			
			echo "<br>Data received from the server in the form of <b>JSON</b><br>";
			echo "<br>Decoding the content from <b>JSON</b> to <b>HTML</b><br>";

            // Decoding the content from JSON format
            $json_content = json_decode($result,true);

			echo "<br><b>HTML Code:</b>";
            //Displaying the contents as Plain HTML format.
            echo '<pre>'; 
            foreach($json_content as $content)
            {
                echo htmlspecialchars($content);
            }
            echo '</pre>';

            // close the socket
            socket_close($sock);
        ?>
    </body>
</html>