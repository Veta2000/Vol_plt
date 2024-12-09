<?php
session_start();
require_once '../config.php';
require_once '../vendor/fpdf/fpdf/src/Fpdf/Fpdf.php';

// Проверка, что пользователь авторизован
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'volunteer') {
    header("Location: /auth/login.php");
    exit;
}

// Получение из post
$userId = $_SESSION['user_id'];
$eventId = $_POST['event_id'] ?? null;

if (!$eventId) {
    header("Location: /404.php");
    exit;
}

// Проверка на участие 
$stmt = $pdo->prepare("SELECT * FROM event_participants WHERE event_id = ? AND user_id = ?");
$stmt->execute([$eventId, $userId]);
$participation = $stmt->fetch();

if (!$participation) {
    echo "Вы не участвовали в этом мероприятии, сертификат нельзя сгенерировать.";
    exit;
}

// Получение данных о мероприятии
$stmt = $pdo->prepare("SELECT * FROM events WHERE id = ?");
$stmt->execute([$eventId]);
$event = $stmt->fetch();

if (!$event) {
    echo "Мероприятие не найдено.";
    exit;
}

$certificateDir = '../uploads/certificates/';
if (!is_dir($certificateDir)) {
    mkdir($certificateDir, 0777, true);
}

// Генерация сертификата
$pdf = new Fpdf\Fpdf();
$pdf->AddPage();
$pdf->SetFont('Arial', 'B', 16);
$pdf->Cell(0, 10, "Certificate of Participation", 0, 1, 'C');
$pdf->Ln(10);

$pdf->SetFont('Arial', '', 12);
$pdf->Cell(0, 10, "This certificate is awarded to:", 0, 1, 'C');
$pdf->Ln(10);

$pdf->SetFont('Arial', 'B', 14);
$pdf->Cell(0, 10, $_SESSION['username'], 0, 1, 'C');
$pdf->Ln(10);

$pdf->SetFont('Arial', '', 12);
$pdf->Cell(0, 10, "For participating in the event:", 0, 1, 'C');
$pdf->Ln(5);

$pdf->SetFont('Arial', 'B', 14);
$pdf->Cell(0, 10, $event['name'], 0, 1, 'C'); 
$pdf->Ln(10);

$pdf->SetFont('Arial', '', 12);
$pdf->Cell(0, 10, "Held on: " . $event['event_date'], 0, 1, 'C'); 
$pdf->Ln(20);

$pdf->SetFont('Arial', '', 12);
$pdf->Cell(0, 10, "Thank you for your participation!", 0, 1, 'C');

$fileName = $certificateDir . "certificate_" . $eventId . "_" . $userId . ".pdf";
$pdf->Output('F', $fileName);

echo "Сертификат успешно сгенерирован: <a href='{$fileName}' target='_blank'>Скачать</a>";
?>
