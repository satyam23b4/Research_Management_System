<?php
require '../vendor/autoload.php';

use Goutte\Client;

if (isset($_POST['doi'])) {
    $doi = trim($_POST['doi']);

    // Initialize Goutte client
    $client = new Client();

    $searchUrl = "https://scholar.google.com/scholar?q=doi:{$doi}";

    try {
        // Request the Google Scholar search page for the DOI
        $crawler = $client->request('GET', $searchUrl);

        // Check if the search results are empty
        if ($crawler->filter('div.gs_a.gs_fma_p')->count() > 0) {
            // Extract authors' names and specific journal name from the search results
            $results = $crawler->filter('div.gs_a.gs_fma_p')->each(function ($node) {
                // Extract authors' names
                $authorsList = [];
                $authorsText = $node->filter('div.gs_fmaa')->text();
                // Split the authors' names by comma and trim each name
                $authorsArray = explode(',', $authorsText);
                foreach ($authorsArray as $author) {
                    $authorsList[] = trim($author);
                }

                return [
                    'authors' => $authorsList,
                ];
            });

            // Extract the journal name using XPath
            $journalName = $crawler->filterXPath('//div[@class="gs_a gs_fma_p"]/div[@class="gs_fmaa"]/following-sibling::text()[1]')->text();

            // Prepare data array
            $data = [
                'journalName' => $journalName,
                'authors' => $results,
            ];

            // Encode data as JSON and send it back to the client
            header('Content-Type: application/json');
            echo json_encode($data);
        } else {
            // Send error message as JSON
            header('Content-Type: application/json');
            echo json_encode(['error' => 'No information found for the provided DOI.']);
        }
    } catch (Exception $e) {
        // Log and send exception message as JSON
        error_log('Error fetching data: ' . $e->getMessage());
        header('Content-Type: application/json');
        echo json_encode(['error' => 'An error occurred while fetching data.']);
    }
} else {
    // Handle the case where 'doi' parameter is not set in $_POST
    // Send error message as JSON
    header('Content-Type: application/json');
    echo json_encode(['error' => 'DOI parameter not provided or invalid request.']);
}
?>
