<?php
session_start();
require_once 'db_connect.php';
require_once 'mail_config.php'; 
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'PHPMailer/Exception.php';
require 'PHPMailer/PHPMailer.php';
require 'PHPMailer/SMTP.php';

header('Content-Type: application/json');

$response = ['success' => false, 'message' => ''];

if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true || !$_SESSION['is_admin']) {
    $response['message'] = 'Unauthorized access.';
    echo json_encode($response);
    exit;
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $reservation_id = $_POST['reservation_id'] ?? null;
    $newStatus = $_POST['status'] ?? null;
    $action = $_POST['action'] ?? null;

    if ($reservation_id === null || ($action === 'update' && $newStatus === null) || empty($action)) {
        $response['message'] = 'Missing required fields.';
        echo json_encode($response);
        exit;
    }

    if ($action === 'update') {
        // First, get reservation details for the email
        $sql_select = "SELECT res_name, res_email, res_date, res_time FROM reservations WHERE reservation_id = ?";
        $stmt_select = mysqli_prepare($link, $sql_select);
        mysqli_stmt_bind_param($stmt_select, "i", $reservation_id);
        mysqli_stmt_execute($stmt_select);
        $result = mysqli_stmt_get_result($stmt_select);
        $reservation_data = mysqli_fetch_assoc($result);
        mysqli_stmt_close($stmt_select);

        if (!$reservation_data) {
            $response['message'] = 'Reservation not found.';
            echo json_encode($response);
            exit;
        }

        $sql_update = "UPDATE reservations SET status = ? WHERE reservation_id = ?";
        if ($stmt_update = mysqli_prepare($link, $sql_update)) {
            mysqli_stmt_bind_param($stmt_update, "si", $newStatus, $reservation_id);
            if (mysqli_stmt_execute($stmt_update) && mysqli_stmt_affected_rows($stmt_update) > 0) {
                $response['success'] = true;
                $response['message'] = 'Reservation status updated successfully.';

                // Send email notification
                $mail = new PHPMailer(true);
                try {
                    $mail->isSMTP();
                    $mail->Host       = SMTP_HOST;
                    $mail->SMTPAuth   = true;
                    $mail->Username   = SMTP_USERNAME;
                    $mail->Password   = SMTP_PASSWORD;
                    $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
                    $mail->Port       = SMTP_PORT;

                    $mail->setFrom(SMTP_USERNAME, 'Tavern Publico');
                    $mail->addAddress($reservation_data['res_email'], $reservation_data['res_name']);

                    $mail->isHTML(true);
                    if ($newStatus === 'Confirmed') {
                        $mail->Subject = 'Your Reservation is Confirmed!';
                        $mail->Body    = "Dear " . $reservation_data['res_name'] . ",<br><br>Your reservation for " . $reservation_data['res_date'] . " at " . date('g:i A', strtotime($reservation_data['res_time'])) . " has been confirmed.<br><br>We look forward to seeing you!<br>Tavern Publico";
                    } elseif ($newStatus === 'Declined') {
                        $mail->Subject = 'Your Reservation has been Declined';
                        $mail->Body    = "Dear " . $reservation_data['res_name'] . ",<br><br>We regret to inform you that your reservation for " . $reservation_data['res_date'] . " at " . date('g:i A', strtotime($reservation_data['res_time'])) . " has been declined due to unavailability.<br><br>Please try booking for another date or time.<br>Tavern Publico";
                    }
                    $mail->send();
                    $response['message'] .= ' Email notification sent.';

                } catch (Exception $e) {
                    $response['message'] .= " Email could not be sent. Mailer Error: {$mail->ErrorInfo}";
                }

            } else {
                $response['message'] = 'No reservation found or status is the same.';
            }
            mysqli_stmt_close($stmt_update);
        } else {
            $response['message'] = 'Database error.';
        }
    }
    // ... (delete action remains unchanged)
}

mysqli_close($link);
echo json_encode($response);
?>