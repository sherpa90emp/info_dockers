<?php

namespace App\Service;

class DockerService
{
    public function getDockerStats(): array
    {
        $chandler = curl_init();
        curl_setopt($chandler, CURLOPT_UNIX_SOCKET_PATH, "/var/run/docker.sock");
        curl_setopt($chandler, CURLOPT_URL, "http://localhost/containers/json?all=1");
        curl_setopt($chandler, CURLOPT_RETURNTRANSFER, true);

        $jsonResponse = curl_exec($chandler);

        if ($jsonResponse === false) {
            $error = curl_error($chandler);
            curl_close($chandler);
            return ['error' => 'Errore connessione Socket: ' . $error];
        }

        curl_close($chandler);

        return json_decode($jsonResponse, true);
    }
}
