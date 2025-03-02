<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // JSON-Daten aus der Anfrage lesen
    $data = json_decode(file_get_contents('php://input'), true);

    if (!empty($data['command'])) {
        $command = strtolower(trim($data['command'])); // Befehl
        $timestamp = date('Y-m-d H:i:s');

        // Dateipfade
        $logFile = 'log/commands.txt';
        $responsesFile = 'responses.json';

        // Prüfen, ob die responses.json existiert
        if (file_exists($responsesFile)) {
            $responses = json_decode(file_get_contents($responsesFile), true);

            if (json_last_error() === JSON_ERROR_NONE) {
                // Normalisiere die Keys der responses.json in Kleinbuchstaben
                $normalizedResponses = [];
                foreach ($responses as $key => $value) {
                    $normalizedResponses[strtolower($key)] = $value;
                }

                // Prüfen, ob der Befehl in responses.json vorhanden ist (case-insensitive)
                $isNew = !array_key_exists($command, $normalizedResponses);
                $logTag = $isNew ? " #New" : "";

                // Format: [Zeitstempel] Befehl #New (falls neu)
                $logEntry = "[$timestamp] $command$logTag\n";

                // Eintrag senden
                if (file_put_contents($logFile, $logEntry, FILE_APPEND | LOCK_EX)) {
                    echo json_encode(['status' => 'success', 'message' => 'Command entered successfully.', 'new' => $isNew]);
                } else {
                    http_response_code(500);
                    echo json_encode(['status' => 'error', 'message' => 'Failed to send command.']);
                }
            } else {
                // JSON-Fehler
                http_response_code(500);
                echo json_encode(['error' => 'Invalid JSON in responses.json.']);
            }
        } else {
            // responses.json nicht gefunden
            http_response_code(500);
            echo json_encode(['error' => 'responses.json not found.']);
        }
    } else {
        // Ungültiger oder leerer Befehl
        http_response_code(400);
        echo json_encode(['error' => 'No command provided.']);
    }
    exit;
} else {
    // Ungültige Methode
    http_response_code(405);
    echo json_encode(['error' => 'Invalid request method.']);
}
?>

