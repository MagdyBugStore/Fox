
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Upload and Preview Excel</title>
</head>
<body>
    <h2>Upload Excel File</h2>
    <form action="" method="post" enctype="multipart/form-data">
        <input type="file" name="excel_file" required>
        <button type="submit">Upload and Preview</button>
    </form>
    <?php
require 'vendor/autoload.php';

use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Spreadsheet;

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Check if file was uploaded without errors
    if (isset($_FILES['excel_file']) && $_FILES['excel_file']['error'] == 0) {
        $fileTmpPath = $_FILES['excel_file']['tmp_name'];
        $fileName = $_FILES['excel_file']['name'];
        $fileSize = $_FILES['excel_file']['size'];
        $fileType = $_FILES['excel_file']['type'];
        $fileNameCmps = explode(".", $fileName);
        $fileExtension = strtolower(end($fileNameCmps));

        // Allowed file types
        $allowedfileExtensions = ['xls', 'xlsx'];

        if (in_array($fileExtension, $allowedfileExtensions)) {
            // Load the uploaded spreadsheet file
            $spreadsheet = IOFactory::load($fileTmpPath);

            // Get the first worksheet in the spreadsheet
            $worksheet = $spreadsheet->getActiveSheet();

            echo "<h2>Preview of Uploaded Excel File</h2>";
            echo "<table border='1'>";

            // Iterate through rows and columns and print them
            foreach ($worksheet->getRowIterator() as $row) {
                echo "<tr>";
                $cellIterator = $row->getCellIterator();
                $cellIterator->setIterateOnlyExistingCells(false); // Loop through all cells, even empty ones
                foreach ($cellIterator as $cell) {
                    echo "<td>" . $cell->getValue() . "</td>";
                }
                echo "</tr>";
            }
            echo "</table>";
        } else {
            echo "Invalid file type. Only .xls and .xlsx files are allowed.";
        }
    } else {
        echo "There was an error uploading your file.";
    }
}
?>

</body>
</html>
