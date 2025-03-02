<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // JSON-Daten aus der Anfrage lesen
    $data = json_decode(file_get_contents('php://input'), true);

    if (!empty($data['command'])) {
        $command = strtolower(trim($data['command'])); // Befehl
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

                // Antwort suchen
                if (array_key_exists($command, $normalizedResponses)) {
                    $response = $normalizedResponses[$command];
                    echo json_encode(['response' => $response]);
                } else {
                    // Keine Antwort gefunden
                    http_response_code(404);
                    echo json_encode(['error' => 'zsh: command not found']);
                }
            } else {
                // JSON-Fehler
                http_response_code(500);
                echo json_encode(['error' => 'Invalid JSON in responses.json.']);
            }
        } else {
            // Datei nicht gefunden
            http_response_code(500);
            echo json_encode(['error' => 'responses.json not found.']);
        }
    } else {
        // Ungültiger oder leerer Befehl
        http_response_code(400);
        echo json_encode(['error' => 'No command provided.']);
    }
} else {
    // Ungültige Methode
    http_response_code(405);
    echo json_encode(['error' => 'Invalid request method.']);
}
?>

