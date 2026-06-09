<?php
// download_photos.php

$bearerToken = 'eyJhbGciOiJIUzI1NiJ9.eyJhdWQiOiI3ODkwZGQ1ZDQwYmFiMWIxZmY3NjJiNDdhMmFjYTBjNSIsIm5iZiI6MTc4MDU1NzUyMi41MDg5OTk4LCJzdWIiOiI2YTIxMjZkMmNkZjM5MWI2N2ZhZDVhYTMiLCJzY29wZXMiOlsiYXBpX3JlYWQiXSwidmVyc2lvbiI6MX0.iQx91T7oiAQOfdqmzIjL4mxt1ycmo0EUjY2u3ypG22w';
$savePath = __DIR__ . '/public/img/personnalites/';

if (!is_dir($savePath)) {
    mkdir($savePath, 0777, true);
}

$pdo = new PDO('mysql:host=127.0.0.1;dbname=cineforall;charset=utf8', 'root', '');
$stmt = $pdo->query("SELECT idPer, nomPer, prePer FROM personnalite");
$personnalites = $stmt->fetchAll(PDO::FETCH_ASSOC);

function fetchUrl($url, $bearerToken = null) {
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLOPT_TIMEOUT, 10);
    curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0');
    if ($bearerToken) {
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Authorization: Bearer ' . $bearerToken,
            'accept: application/json',
        ]);
    }
    $result = curl_exec($ch);
    $error  = curl_error($ch);
    curl_close($ch);
    if ($error) {
        echo "(curl error: $error) ";
        return false;
    }
    return $result;
}

foreach ($personnalites as $p) {
    $idPer      = $p['idPer'];
    $nomComplet = trim($p['prePer'] . ' ' . $p['nomPer']);
    $fichier    = $savePath . $idPer . '.jpg';

    if (file_exists($fichier)) {
        echo "Déjà téléchargé : $nomComplet\n";
        continue;
    }

    echo "Recherche : $nomComplet ... ";

    $url  = 'https://api.themoviedb.org/3/search/person?query='
        . urlencode($nomComplet) . '&language=fr-FR';

    $json = fetchUrl($url, $bearerToken);
    if (!$json) {
        echo "Erreur réseau\n";
        continue;
    }

    $data = json_decode($json, true);

    if (empty($data['results']) || empty($data['results'][0]['profile_path'])) {
        echo "Pas de photo trouvée\n";
        continue;
    }

    $profilePath = $data['results'][0]['profile_path'];
    $imgUrl      = 'https://image.tmdb.org/t/p/w300' . $profilePath;

    $imgData = fetchUrl($imgUrl);
    if (!$imgData) {
        echo "Erreur téléchargement image\n";
        continue;
    }

    file_put_contents($fichier, $imgData);
    echo "OK → $idPer.jpg\n";

    usleep(300000);
}

echo "\nTerminé !\n";
