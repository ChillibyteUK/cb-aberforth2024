<section class="fund_hero pb-5">
    <div class="container-xl">
        <div class="row g-4">
            <div class="col-md-8">
                <h1><?=get_field('title')?></h1>
                <a href="#" class="button">SHEET</a>
            </div>
            <div class="col-md-4 d-flex gap-4 justify-content-end">
                <?php
                // Define the XML URL
                $url = get_field('ticker') ?? null;

                if ($url) {
                    // Load the XML data
                    $xmlContent = file_get_contents($url);
                    if ($xmlContent === false) {
                        die("Error: Unable to load XML data.");
                    }

                    // Parse the XML data
                    $xml = simplexml_load_string($xmlContent);
                    if ($xml === false) {
                        die("Error: Failed to parse XML.");
                    }

                    $shares = isset($xml->share) ? $xml->share : [$xml];

                    // Display the data for each share
                    foreach ($shares as $share) {
                        // Convert XML to JSON and then to an associative array for easy access
                        $json = json_encode($share);
                        $data = json_decode($json, true);

                        if ($data) {
                            $symbol = $data['Symbol'];
                            $currentPrice = $data['CurrentPrice'];
                            $change = $data['Change'];
                            $date = $data['Date'];
                            ?>
                    <div class="ticker">
                        <div class="ticker__date"><?=$date?></div>
                        <div class="ticker__symbol"><?=$symbol?></div>
                        <div class="ticker__price"><?=$currentPrice?></div>
                        <div class="ticker__change ticker__change--up"><?=$change?></div>
                    </div>
                            <?php
                        }
                        else {
                            echo 'Error: No data found.';
                        }
                    }
                }
                ?>
            </div>
        </div>
    </div>
</section>