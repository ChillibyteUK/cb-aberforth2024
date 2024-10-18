<section class="fund_hero">
    <div class="container-xl">
        <div class="row">
            <div class="col-md-9">
                <h1><?=get_field('title')?></h1>
                <a href="#" class="button">SHEET</a>
            </div>
            <div class="col-md-3">
                <?php
                // Define the XML URL
                $url = get_field('ticker') ?? null;

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

                // Convert XML to JSON and then to an associative array for easy access
                $json = json_encode($xml);
                $data = json_decode($json, true);

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
            </div>
        </div>
    </div>
</section>