<?php
require_once '../vendor/autoload.php';
require_once '../entities/Book.php';

header("Content-Type: application/json; charset=UTF-8");
$mode = filter_input(INPUT_GET, 'mode', FILTER_SANITIZE_STRING);
$offset = filter_input(INPUT_GET, 'offset', FILTER_SANITIZE_STRING);
$bookID = filter_input(INPUT_GET, 'book_id', FILTER_SANITIZE_STRING);
$userID = filter_input(INPUT_GET, 'user_id', FILTER_SANITIZE_STRING);
$deviceID = filter_input(INPUT_GET, 'device_id', FILTER_SANITIZE_STRING);

if (!in_array($mode, ['view', 'read', 'list'])) {
    echo json_encode(
        array('body' => array(), 'count' => 0)
    );
    return;
}

$book = new Book();
$stmt = $book->read($bookID);
$count = $stmt->rowCount();

const MAX_PAGES = 7;

if ($count > 0) {
    $books = array();
    $books['body'] = array();
    $books['count'] = $count;
    $books['content'][$bookID] = array();

    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        extract($row);
        array_push($books["body"], $row);

        // get pdf content
        $pdfPath = __DIR__ . "/../ebooks/{$bookID}.pdf";
        if ($mode === 'read' && !empty($userID)) {
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

//    echo print_r($books, 1);
    echo json_encode($books);
} else {
    echo json_encode(
        array('body' => array(), 'count' => 0)
    );
}
