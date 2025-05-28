<?php
session_start();
header('Content-Type: application/json');

$input = json_decode(file_get_contents('php://input'), true);
$message = strtolower(trim($input['message'] ?? ''));

// Example session variables (replace or pull from DB if needed)
$clientName = $_SESSION['client_name'] ?? 'Client';
$appointment = $_SESSION['appointment_date'] ?? 'June 15 at 2:00 PM';
$officer = $_SESSION['officer_name'] ?? 'Officer Garcia';
$contact = $_SESSION['contact_number'] ?? '0917-123-4567';

// Smart rules
$reply = "Sorry, I didn't understand. Ask about appointments, officer, or help.";

if (strpos($message, 'appointment') !== false || strpos($message, 'schedule') !== false) {
    $reply = "Hi $clientName, your next appointment is on *$appointment*.";
} elseif (strpos($message, 'officer') !== false) {
    $reply = "Your assigned parole officer is *$officer*.";
} elseif (strpos($message, 'contact') !== false) {
    $reply = "You can contact us at *$contact*.";
} elseif (strpos($message, 'reschedule') !== false) {
    $reply = "To reschedule, call $contact or message your officer.";
} elseif (strpos($message, 'condition') !== false) {
    $reply = "Your conditions are in your dashboard. Contact $officer for details.";
} elseif (strpos($message, 'help') !== false) {
    $reply = "I can assist with appointments, officers, or conditions.";
} elseif (strpos($message, 'hello') !== false || strpos($message, 'hi') !== false) {
    $reply = "Hello $clientName! How can I help today?";
} elseif (strpos($message, 'bye') !== false) {
    $reply = "Goodbye $clientName! Come back if you need help.";
}

echo json_encode(['reply' => $reply]);
?>