  $file = storage_path('app/TestTournaments.xlsx');

    $data = Excel::toArray([], $file)[0];

    // Check if file has data
    if (empty($data) || count($data) < 2) {
        return [
            'success' => false,
            'message' => 'File is empty or has no data rows.'
        ];
    }

    // Extract header and validate required columns
    $header = array_map('strtolower', array_map('trim', $data[0]));
    $requiredColumns = ['account_no', 'adjusted_gross_score', 'slope_rating', 'course_rating', 'holes_completed', 'date_played', 'tee_code', 'course_code', 'tournament_name'];

    foreach ($requiredColumns as $column) {
        if (!in_array($column, $header)) {
            return [
                'success' => false,
                'message' => "Missing required column: {$column}. Required columns: " . implode(', ', $requiredColumns)
            ];
        }
    }



    $newFormat = [];
    foreach ($data as $index => $row) {
        // Skip header row
        if ($index === 0) {
            continue;
        }

        $newFormat[$row[8]][$row[7]][$row[9]][$row[5]][$row[3]][$row[4]][$row[8]][$row[7]] = $row;

        // echo '<pre>';
        // print_r($row);
        // echo '</pre>';

        // Process each row as needed
        // For example, you can validate and store the data in the database
    }


    echo '<pre>';
    print_r($newFormat);
    echo '<pre>';
    return;
