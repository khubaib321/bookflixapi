<?php
require_once '../entities/User.php';
require_once '../entities/Book.php';
require_once '../vendor/autoload.php';

header("Content-Type: application/json; charset=UTF-8");
$mode = filter_input(INPUT_GET, 'mode', FILTER_SANITIZE_STRING);
$bookID = filter_input(INPUT_GET, 'book_id', FILTER_SANITIZE_STRING);
$deviceID = filter_input(INPUT_GET, 'device_id', FILTER_SANITIZE_STRING);
$userEmail = filter_input(INPUT_GET, 'user_email', FILTER_SANITIZE_STRING);

if (!in_array($mode, ['view', 'read', 'list'])) {
    echo json_encode(
        array('body' => array(), 'count' => 0)
    );
    return;
}

$user = new User();
$user->create(['email' => $userEmail]);

$book = new Book();
$stmt = $book->read(empty($bookID), $bookID);
$count = $stmt->rowCount();

$lastRecord = $book->recordExists('user_readings', ['user_email' => $user->email, 'book_id' => $bookID], 'page_no DESC', true);
$offset = 0;
if ($lastRecord && ((int) $lastRecord['page_no'] > 0)) {
    $offset = ((int) $lastRecord['page_no']) - 1;
}
const MAX_PAGES = 7;

if ($count > 0) {
    $books = array();
    $books['body'] = array();
    $books['count'] = $count;
    $books['content'][$bookID] = array();
    $books['page_no'] = ($lastRecord) ? $offset + 1 : 0;    // if record found then at user is at page 1, else page 0

    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        extract($row);
        array_push($books["body"], $row);

        // get pdf content
        $pdfPath = __DIR__ . "/../ebooks/{$bookID}.pdf";
        if ($mode === 'read' && !empty($userEmail)) {
            $start = empty($offset) ? 0 : (int) $offset;
            $limit = $start + MAX_PAGES;
            for ($i = $start; $i < $limit; ++$i) {
                $mpdf = new Mpdf\Mpdf();
                $pagecount = $mpdf->setSourceFile($pdfPath);

                if ($i < $pagecount) {
                    $tplId = $mpdf->ImportPage($i + 1);
                    $mpdf->useTemplate($tplId);
                    $pdfString = $mpdf->Output('', \Mpdf\Output\Destination::STRING_RETURN);
                    array_push($books['content'][$bookID], ['pageLength' => strlen($pdfString), 'pageData' => base64_encode($pdfString)]);
                } else {
                    break;
                }
            }
        } else {
            unset($books['content']);
        }
    }

    echo json_encode($books);
} else {
    echo json_encode(
        array('body' => array(), 'count' => 0)
    );
}
