<?php
require_once '../vendor/autoload.php';
require_once '../entities/Book.php';

use mikehaertl\pdftk\Pdf;

header("Content-Type: application/json; charset=UTF-8");
$id = filter_input(INPUT_GET, 'id', FILTER_SANITIZE_STRING);

$book = new Book();
$stmt = $book->read($id);
$count = $stmt->rowCount();

if ($count > 0) {
    $books = array();
    $books["body"] = array();
    $books["count"] = $count;
    $books["content"] = array();
    $books["location"] = array();
    $books["messages"] = array();

    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        extract($row);
        array_push($books["body"], $row);

        // get pdf content
        $pdfPath = __DIR__ . "/../ebooks/{$id}.pdf";
        array_push($books['location'], __DIR__ . "/document1.pdf");
        $pdftk = new Pdf(__DIR__ . "/document1.pdf", [
            'useExec' => false,
        ]);
        $pdfCut = $pdftk->cat(1, 5);
        $pdfCut->saveAs('pdfcut.pdf');
        $content = $pdfCut->toString();
        $data = $pdftk->getData();
        array_push($books['content'], $pdftk->getData());
        array_push($books['command'], $pdftk->getCommand());
        array_push($books['messages'], $pdftk->getCommand()->getOutput());
    }

    echo json_encode($books);
} else {
    echo json_encode(
        array("body" => array(), "count" => 0)
    );
}
