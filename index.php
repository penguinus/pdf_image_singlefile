<?php
use setasign\Fpdi\Fpdi as Fpdi;

require __DIR__ . '/vendor/autoload.php';
ini_set('display_errors', false);
if(!empty($_POST['pdf_url']) && !empty($_FILES['signature']) && isset($_FILES['signature']['tmp_name']) && is_uploaded_file($_FILES['signature']['tmp_name'])) {
    $pdfUrl = $_POST['pdf_url'];
    $imageFile = $_FILES['signature']['tmp_name'];
    $imageResized = prepareImage($imageFile);

    $pdfCache = 'source.pdf';
    if($pdfDownload = file_get_contents($pdfUrl)) {
        file_put_contents($pdfCache, $pdfDownload);

        $pdf = new Fpdi();
        $pagesCount = $pdf->setSourceFile($pdfCache);
        for($i=1; $i<=$pagesCount; $i++) {
            $tpl = $pdf->importPage($i);
            $pdf->addPage();
            $pdf->useTemplate($tpl);
            $pdf->Image($imageResized, 90,260);
        }
        $pdf->Output();
        unlink($pdfCache);
        unlink($imageResized);

        exit;
    } else {
        returnJson([
            'status' => 404,
            'message' => 'PDF file can\'t be downloaded'
        ]);
    }


}


function prepareImage(string $image) : string
{
    $targetFilename = 'tmp_resampled.png';
    $check = getimagesize($image);
    if(is_array($check)) {
        $type = $check[2];
        switch($type) {
            case IMAGETYPE_JPEG:
                $imgSource = imagecreatefromjpeg($image);
                break;
            case IMAGETYPE_PNG:
                $imgSource = imagecreatefrompng($image);
                break;
            case IMAGETYPE_GIF:
                $imgSource = imagecreatefromgif($image);
                break;
            default:
                $imgSource = null;
        }
        $imgResampled = imagecreatetruecolor(150, 110);
        imagealphablending($imgResampled, false);
        imagesavealpha($imgResampled, true);
        imagecopyresampled($imgResampled,$imgSource,0,0,0,0,150,110,imagesx($imgSource),imagesy($imgSource));
        imagepng($imgResampled, __DIR__ . '/' . $targetFilename);
        imagedestroy($imgResampled);
        imagedestroy($imgSource);
    }
    return __DIR__ . '/' . $targetFilename;
}
function returnJson($error) {
    header('Content-Type: application/json');
    echo json_encode($error);
    exit;
}
?>

<!DOCTYPE HTML>
<html lang="en-GB">
<head>
    <title>Stamp PDF</title>
    <style>
        div {
            padding: 10px;
        }
        label {
            margin: 0 10px 0 0;
        }
        input[type=url] {
            min-width: 500px;
        }
    </style>
</head>
<body>
<form enctype="multipart/form-data" method="post">
    <div>
        <label for="pdf_url">PDF URL:</label><input type="url" name="pdf_url" id="pdf_url" value="https://www.w3.org/WAI/ER/tests/xhtml/testfiles/resources/pdf/dummy.pdf">
    </div>
    <div>
        <label for="signature">Signature:</label><input type="file" name="signature" id="signature" >
    </div>
    <div>
        <input type="submit" value="Generate" >
    </div>

</form>
</body>
</html>