<?php
require_once '../entities/User.php';
require_once '../entities/Book.php';
require_once '../vendor/autoload.php';

header("Content-Type: application/json; charset=UTF-8");
$mode = filter_input(INPUT_GET, 'mode', FILTER_SANITIZE_STRING);
$bookID = filter_input(INPUT_GET, 'book_id', FILTER_SANITIZE_STRING);
$deviceID = filter_input(INPUT_GET, 'device_id', FILTER_SANITIZE_STRING);
$userEmail = filter_input(INPUT_GET, 'user_email', FILTER_SANITIZE_STRING);
$firstLoad = filter_input(INPUT_GET, 'first_load', FILTER_SANITIZE_STRING);
$safetyGap = filter_input(INPUT_GET, 'gap_to_safety', FILTER_SANITIZE_STRING);
$retrievePage = filter_input(INPUT_GET, 'retrieve_page', FILTER_SANITIZE_STRING);

$firstLoad = ($firstLoad == 'true') ? true : false;

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

$readingNowIndex = 0;
$lastRecord = $book->recordExists('user_readings', ['user_email' => $user->email, 'book_id' => $bookID], 'page_no DESC', true);
if ($lastRecord && ((int) $lastRecord['page_no'] > 1)) {
    $readingNowIndex = ((int) $lastRecord['page_no']) - 1;
}

$fromIndex = $toIndex = ((int) $retrievePage) - 1;
if ($firstLoad) {
    if ($readingNowIndex === 0) {
        $fromIndex = 0;
        $toIndex = $safetyGap;
    } else {
        $fromIndex = ($readingNowIndex - $safetyGap) < 0 ? 0 : $readingNowIndex - $safetyGap;
        $toIndex = ($fromIndex === 0) ? $safetyGap : $readingNowIndex + $safetyGap;
    }
}

//error_log('----------');
//error_log("FROM => {$fromIndex}");
//error_log("TO => {$toIndex}");
//error_log('----------');

if ($count > 0) {
    $books = array();
    $books['body'] = array();
    $books['count'] = $count;
    $books['content'][$bookID] = array();
    $books['page_no'] = ($lastRecord) ? $lastRecord['page_no'] : 0;    // if record found then user is reading page 1 at least, otherwise 0 meaning never read this book

    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        extract($row);
        array_push($books["body"], $row);

        // get pdf content
        $pdfPath = __DIR__ . "/../ebooks/{$bookID}.pdf";
        if ($mode === 'read' && !empty($userEmail)) {
            for ($i = $fromIndex; $i <= $toIndex; ++$i) {
                $mpdf = new Mpdf\Mpdf();
                $pagecount = $mpdf->setSourceFile($pdfPath);

                if ($i < $pagecount) {
                    $tplId = $mpdf->ImportPage($i + 1);
                    $mpdf->useTemplate($tplId);
                    $pdfString = $mpdf->Output('', \Mpdf\Output\Destination::STRING_RETURN);
                    array_push($books['content'][$bookID], ['pageLength' => strlen($pdfString), 'pageData' => base64_encode($pdfString), 'pageIndex' => $i]);
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
