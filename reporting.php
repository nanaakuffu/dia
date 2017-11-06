<?php
    session_start();

    require_once("db_functions.php");
    require_once("public_functions.php");
    require_once("fpdf/fpdf.php");

    $db = new Database();
    $con = $db->connect_to_db();

    $pdf = new FPDF("L", "pt", "A4");
    $pdf->AddPage();

    $fields = array('exam_subject', 'class_work_score', 'exam_score', 'total_score', 'average_score', 'grade',
              'remark', 'teacher_initials');

    unset($_POST['submit']);
    // echo print_r($_POST);
    // $field_list = implode(", ", $fields);
    // $query = "SELECT $field_list FROM exams WHERE ";
    // foreach ($_POST as $key => $value) {
    //   $query .= $key." = "."'".$value."'"." AND ";
    // }
    // $query = substr($query, 0, strlen($query) - 4);
    // $query .= "ORDER BY exam_subject ASC";
    //
    // echo "$query";



    $records = $db->search_by_multiple($con, "exams", $fields, $_POST, 'exam_subject');

    $full_name = $_SESSION['full_name'];

    $headers = [];
    foreach ($fields as $key => $value) {
        $headers[] = get_column_name($value);
    }
    // $pdf->SetFont('Arial', '', 12);

    $info_array = $_POST;

    $data_array = $records;

    $keys = $headers;

    // $pdf->SetY(100);
    $pdf->SetFont('Arial', 'B', 12);
    $pdf->Cell(90, 20, 'Student Name', 1);
    $pdf->SetFont('Arial', '');
    $pdf->Cell(220, 20, $info_array['student_full_name'], 1);

    $pdf->SetFont('Arial', 'B', 12);
    $pdf->Cell(90, 20, 'Class / Grade', 1);
    $pdf->SetFont('Arial', '');
    $pdf->Cell(90, 20, $info_array['class_name'], 1);

    $pdf->SetFont('Arial', 'B', 12);
    $pdf->Cell(90, 20, 'Form Teacher', 1);
    $pdf->SetFont('Arial', '');
    $pdf->Cell(200, 20, "Nana Baah Akuffu", 1, 1);

    $pdf->Ln(0);

    $pdf->SetFont('Arial', 'B');
    $pdf->Cell(180, 25, "Parent / Guardian Name", 1);
    $pdf->Cell(150, 25, "Academic Year", 0, 0,'C');
    $pdf->SetFont('Arial', 'B', 18);
    $pdf->Cell(150, 25, "DAYSPRING INTERNATIONAL ACADEMY", 0, 1);
    // $pdf->Ln(0);
    $address = "Mr Isaac K. Gyan \nP. O. Box KN 4211 \nKaneshie, Accra\n";
    $pdf->Ln(0);
    $pdf->SetFont('Arial', 'I', 12);
    $pdf->MultiCell(180, 30, $address, 1);

    $pdf->SetXY(350, 70);
    $pdf->SetFont('Arial', '', 11);
    $school_address = "East-Legon Ext. Nmai Dzorn,\n PO BOX AF 1841, Adenta Accra Ghana\n Tel:(233) 0302-937-881 / 0302-541154\n Cel: (233) 024-251-1271, 024-817-0663,\n 020-946-6192";
    $pdf->MultiCell(250, 15, $school_address, 0, 'C');

    $grading_system ="A = 100 - 90 (Excellent)\nB = 89 - 80 (Very Good)\nC = 79 - 70 (Good)\nD = 69 - 60 (Satisfactory)\nE = 59 - 50 (Fair)\nF = 49 - 0 (Fail)";
    $pdf->SetXY(600, 70);
    $pdf->SetFont('Arial', '', 11);
    $pdf->MultiCell(250, 15, $grading_system, 0, 'L');

    $pdf->SetXY(209, 70);
    $pdf->SetFont('Arial', 'B', 12);
    $pdf->Cell(150, 25, $info_array['academic_year'], 0, 0, 'C');

    $pdf->Image( "site_pics/mid_term_logo_sm.jpg", 230, 90, 100, "JPG" );

    $pdf->SetXY(0, 180);
    $pdf->Ln(0);
    $pdf->SetFont('Arial', 'B', 18, 0);
    $pdf->Cell(0, 25, "MIDTERM PROGRESS REPORT", 0, 1, 'C');

    // Draw the table

    // $pdf->Cell(0, 25, "MIDTERM PROGRESS REPORT", 1, 1);
    foreach ($keys as $key => $value) {
      $pdf->SetFont('Arial', 'B', 12, 0);
      $pdf->Cell(100, 25, $value, 1);
    }
    // foreach($keys as $heading) {
    // 	foreach($heading as $column_heading)
    // 		$pdf->Cell(90,12,$column_heading,1);
    // }

    foreach ($records as $data) {
      $pdf->Ln();
      foreach ($data as $key => $value) {
        $pdf->SetFont('Arial', '', 12, 0);
        $pdf->Cell(100, 25, $value, 1);
      }
    }
    // $pdf->Ln();
    // foreach ($data_array as $key => $value) {
    //   $pdf->SetFont('Arial', '', 12, 0);
    //   $pdf->Cell(100, 25, $value, 1);
    // }

    $pdf->Output();

    // $pdf->AddPage();
    // $full_name = $_SESSION['full_name'];
    // $pdf->Generate_Mid_Term_Report($con, $full_name, $_POST, $query, $headers);
?>
