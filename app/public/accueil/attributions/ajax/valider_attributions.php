<?php
    if (
        isset($_GET['validation'])
        && $_GET['validation'] === "true"
    ) {
        if (
            file_exists("../../../../backend/db/placesFinales")
            && ($handle = fopen("../../../../backend/db/logs.csv", "a+")) !== FALSE
        ) {
            fputcsv($handle, [ date('Y-m-d'), date('H:i:s'), htmlspecialchars("Admission : attributions validées.") ]);
            fclose($handle);
            echo 'success';
        } else {
            echo 'fail';
        }
    }
?>