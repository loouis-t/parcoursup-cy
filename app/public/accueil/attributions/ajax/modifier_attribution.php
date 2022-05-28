<?php
    if (
        isset($_GET['mail'])
        && $_GET['mail'] !== ''
        && isset($_GET['ancienne_option'])
        && $_GET['ancienne_option'] !== ''
        && isset($_GET['nouvelle_option'])
        && $_GET['nouvelle_option'] !== ''
    ) {
        $mail = strtolower($_GET['mail']);
        if ( 
            ($ANCIENNE_OPTION = fopen("../../../../backend/db/placesFinales/" . $_GET['ancienne_option'] . ".csv", "r")) !== FALSE
            && ($ANCIENNE_OPTION_TEMP = fopen("../../../../backend/db/placesFinales/" . $_GET['ancienne_option'] . "_temp.csv", "a+")) !== FALSE
            && ($NOUVELLE_OPTION = fopen("../../../../backend/db/placesFinales/" . $_GET['nouvelle_option'] . ".csv", "a+")) !== FALSE
        ) {
            while (($data = fgetcsv($ANCIENNE_OPTION, 1000, ',')) !== FALSE) {
                if (strtolower($data[2]) !== $mail) {
                    fputcsv($ANCIENNE_OPTION_TEMP, $data);
                } else {
                    fputcsv($NOUVELLE_OPTION, $data);
                }
            }
            fclose($ANCIENNE_OPTION);
            fclose($ANCIENNE_OPTION_TEMP);
            fclose($NOUVELLE_OPTION);

            rename("../../../../backend/db/placesFinales/" . $_GET['ancienne_option'] . "_temp.csv", "../../../../backend/db/placesFinales/" . $_GET['ancienne_option'] . ".csv");

            $logs = fopen("../../../../backend/db/logs.csv", "a+");
            fputcsv($logs, [ date('Y-m-d'), date('H:i:s'), htmlspecialchars("Réattribution de l'option " . $_GET['nouvelle_option'] . " à '" . $mail . "' (précédemment: " . $_GET['ancienne_option'] . ").") ]);
            fclose($logs);

            echo 'success';
        }
    }
?>