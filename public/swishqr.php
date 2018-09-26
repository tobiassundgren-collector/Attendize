<?php   

function GenerateQr($message, $amount)
{

  $service_url = 'https://mpc.getswish.net/qrg-swish/api/v1/prefilled';
            $curl = curl_init($service_url);
            $jsonData = array(
                    "size"=> 200,
                    "format"=>"png",
                    "payee" => array("value" => "0760959055", "editable" => "false"),
                    "amount" => array("value" => $amount, "editable" => "false"),
                    "message" => array("value" => $message,  "editable" => "false")
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

            $filename = "qr_" . $message . ".png";
            $file_path = 
            "user_content/qr/" . $filename;

            $fp = fopen($file_path, 'w+'); // Create a new file, or overwrite the existing one.
            fwrite($fp, $curl_response);
            fclose($fp);

            return $file_path;
}