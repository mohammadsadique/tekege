<?php
    require('db.php');
    require_once __DIR__ . '/vendor/autoload.php'; // Path to autoload.php

    use Smalot\PdfParser\Parser;

    $response = array( 
        'status' => 0, 
        'message' => 'Form submission failed, please try again.' 
    ); 
    if(isset($_FILES['pdfFile'])) {
        $targetDirectory = 'uploads/';
        $filename = basename($_FILES['pdfFile']['name']);
        $targetFile = $targetDirectory . $filename;
        $pdfFileType = strtolower(pathinfo($targetFile, PATHINFO_EXTENSION));
        $uploadStatus = 1; 
        
        $msg = '';
        if($pdfFileType == "pdf"){
            if (move_uploaded_file($_FILES['pdfFile']['tmp_name'], $targetFile)) {
                
                $uploadedFilePath = $targetFile; // Update this with the actual path
                // Initialize PdfParser
                $parser = new Parser();

                // Parse PDF and extract text
                $pdf = $parser->parseFile($uploadedFilePath);
                $text = $pdf->getText();

                $sql = 'INSERT INTO files (file_name, content) VALUES ("'.$filename.'", "'.$text.'")';
                $conn->query($sql);

                $response['status'] = 1; 
                $response['message'] = 'Form data submitted successfully!';
            } else {
                $uploadStatus = 0; 
                $response['message'] = 'Sorry, there was an error uploading your file.'; 
            }
        } else {
            $uploadStatus = 0; 
            $response['message'] = 'Sorry, only PDF files are allowed to upload.';
        }
    }
    echo json_encode($response);

?>



