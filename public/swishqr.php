<?php   

  $service_url = 'https://mpc.getswish.net/qrg-swish/api/v1/prefilled';
            $curl = curl_init($service_url);
            $jsonData = array(
                    "size"=> 300,
                    "format"=>"png",
                    "payee" => array("value" => "0739022421", "editable" => "false"),
                    "amount" => array("value" => 100, "editable" => "false"),
                    "message" => array("value" => "1ABC123123",  "editable" => "false")
            );
            $headers = array('Content-type: application/json');
            $jsonDataEncoded = json_encode($jsonData);
            curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($curl, CURLOPT_POST, true);
            curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
            curl_setopt($curl, CURLOPT_POSTFIELDS, $jsonDataEncoded);

            $curl_response = curl_exec($curl);
            if ($curl_response === false) {
                $info = curl_getinfo($curl);
                curl_close($curl);
                die('error occured during curl exec. Additioanl info: ' . var_export($info));
            }
            curl_close($curl);

            $decoded = json_decode($curl_response);
            if (isset($decoded->response->status) && $decoded->response->status == 'ERROR') {
                die('error occured: ' . $decoded->response->errormessage);
            }

            $filename = "qr_1ABC123123.png";
            $file_path = 
            "user_content/qr/" . $filename;

            $fp = fopen($file_path, 'w+'); // Create a new file, or overwrite the existing one.
            fwrite($fp, $curl_response);
            fclose($fp);
           
            echo 'response ok!';