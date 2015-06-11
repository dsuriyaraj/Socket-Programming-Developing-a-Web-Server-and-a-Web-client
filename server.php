<html>
    <head>
        <title>Server</title>
    </head>
    
    <body>
        <?php
            $ip_address = "127.0.0.1";
            $port_number=22223;

            //To avoid the time out
            set_time_limit(0);

            //Socket cretaion
            if(!($sock = socket_create(AF_INET, SOCK_STREAM, 0)))
            {
                $errorcode = socket_last_error();
                $errormsg = socket_strerror($errorcode);
                die("Couldn't create socket: [$errorcode] $errormsg \n");
            }

            // Bind the socket to the specified port
            if( !socket_bind($sock, $ip_address , $port_number) )
            {
                $errorcode = socket_last_error();
                $errormsg = socket_strerror($errorcode);
                die("Could not bind socket : [$errorcode] $errormsg \n");
            }

            //Listens for request from client
            if(!socket_listen ($sock , 10))
            {
                $errorcode = socket_last_error();
                $errormsg = socket_strerror($errorcode);
                die("Could not listen on socket : [$errorcode] $errormsg \n");
            }

            //Accept incoming connection from client
            $client = socket_accept($sock);

            //display information about the client who is connected
            if(socket_getpeername($client , $ip_address , $port_number))
            {
                echo "Client: <b>$ip_address</b>, Port: <b>$port_number</b> is now connected with the server.<br>";
            }

            // read the input shared from client
            $input = socket_read($client, 1024) or die("Could not read input\n");
            $trim_input = trim($input);

            echo "<br>Client Message : <b>$trim_input</b><br>";

            // Using the file command to read the contents from file
            $file_content = file("$trim_input");

            //Encode the HTML content as JSON
            $output = json_encode($file_content,JSON_HEX_TAG);
            echo "<br>Converting the <b>index.html</b> file to <b>JSON</b> Format:<br> $output <br>";

            // Sending the data to client to read the html
            socket_write($client, $output, strlen ($output)) or die("Could not write output\n");

            echo "<br>Data sent, check the client to see the HTML Content for the web page: <b>$trim_input</b>";

            //close the sockets
            socket_close($client);
            socket_close($sock);
        ?>
    </body>
</html>