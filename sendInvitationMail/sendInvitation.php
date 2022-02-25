<?php 

header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Headers: *');

require_once '../lib/class.smtp.php';
require_once '../lib/class.phpmailer.php';

$response = array();


$files = array();
$path = getcwd() . "/upload";
$file = $_FILES;
$json = file_get_contents("php://input");
$data = json_decode($json, TRUE);

$subject = "CHANEL INVITATION:";
$msg =    "<strong>Chanel:</strong> " . $data['chanelName'] . "<br>\r\n";
$msg .=   $data['url'] . "<br>\r\n";   

if (isset($data['email'])) {

    $mail = new PHPMailer(true);
    try {
        $mail->CharSet = "utf-8";
        $mail->isSMTP();
        $mail->Host = "smtp.gmail.com";
        $mail->Port = 587;
        $mail->SMTPAuth = true;
        $mail->Username = 'kontotestowe88828@gmail.com';
        $mail->Password = 'Kontotestowe1';
        $mail->SetFrom('kontotestowe88828@gmail.com');
        $mail->FromName = 'Invitation';
        $mail->Subject = $subject;

        $mail->SMTPOptions = array(
            'ssl' => array(
                'verify_peer' => false,
                'verify_peer_name' => false,
                'allow_self_signed' => true
            )
        );

        $mail->AddAddress($data['email']);


        $mail->MsgHTML($msg);
        $mail->Send();

        $response["status"] = 200;
        $response["msg"] = "Dziękujemy za skorzystanie z formularza.";

        echo json_encode($response);
    } catch (phpmailerException $e) {
        header("Content-type: application/json; charset=utf-8");
        $response["status"] = 400;
        $response["msg"] = "Wystąpił błąd w trakcie wysyłania formularza.";
        echo json_encode($response);
        echo $e->errorMessage(); //Pretty error messages from PHPMailer
    } catch (Exception $e) {
        header("Content-type: application/json; charset=utf-8");
        $response["status"] = 400;
        $response["msg"] = "Wystąpił błąd w trakcie wysyłania formularza.";
        echo json_encode($response);
        echo $e->getMessage(); //Boring error messages from anything else!
    }
} else {
    header("Content-type: application/json; charset=utf-8");
    $response["status"] = 400;
    $response["msg"] = "Proszę wypełnić wszystkie pola formularza";
    echo json_encode($response);
}
?>