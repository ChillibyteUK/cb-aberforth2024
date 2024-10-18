<section class="trusts_funds py-5">
    <div class="container-xl">
        <h2 class="text-center mb-5">Trusts &amp; Funds</h2>
        <div class="row g-4">
            <div class="col-md-6">
                <div class="trusts_funds__card theme--ascot">
                    <h3 class="trusts_funds__header">Aberforth Smaller Companies Trust plc</h3>
                    <div class="trusts_funds__inner">
                        <div>An investment trust whose shares are traded on the London Stock Exchange. It is the largest trust within the UK Smaller Companies sector.</div>
                        <div class="lined">
                            Launched: 10 December 1990
                        </div>
                        <div class="stats">
                            <div class="stats--ascot">
                                <div class="stats__title">TOTAL ASSETS</div>
                                <div class="stats__value">&pound;XM</div>
                                <div class="stats__date">date</div>
                            </div>
                            <div class="stats--ascot">
                                <div class="stats__title">EST. NAV</div>
                                <div class="stats__value">XX%</div>
                                <div class="stats__date">date</div>
                            </div>
                            <div class="stats--ascot">
                                <div class="stats__title">YIELD</div>
                                <div class="stats__value">XX%</div>
                                <div class="stats__date">date</div>
                            </div>
                        </div>
                        <div class="text-end">
                            <a href="/trusts-and-funds/aberforth-smaller-companies-trust-plc/" class="button">Learn more</a>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="trusts_funds__card theme--agvit">
                    <h3 class="trusts_funds__header">Aberforth Geared Value &amp; Income Trust plc</h3>
                    <div class="trusts_funds__inner">
                        <div>An investment trust with two classes of share - Ordinary Shares and Zero Dividend Preference (ZDP) Shares - both of which trade on the London Stock Exchange.</div>
                        <div class="lined">
                            <span>Launched: 1 July 2014 <span class="mx-2 bar"></span> Planned wind up: 30 June 2031</span>
                        </div>
                        <div class="tickers">
                        <?php
                        $url = 'https://irs.tools.investis.com/Clients/uk/aberforth_geared_value/XML/xml.aspx';
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
                        ?>
                        </div>
                        <div class="text-end">
                            <a href="/trusts-and-funds/aberforth-smaller-companies-trust-plc/" class="button">Learn more</a>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="trusts_funds__card theme--afund">
                    <h3 class="trusts_funds__header">Aberforth UK Small Companies Fund</h3>
                    <div class="trusts_funds__inner">
                        <p>An Authorised Unit Trust. The Fund is managed by Aberforth Unit Trust Managers Limited and the Trustee is NatWest Trustee and Depositary Services Limited and is a limited issue fund in accordance with the regulations governing authorised unit trusts.</p>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="trusts_funds__card theme--aslit">
                    <h3 class="trusts_funds__header">Aberforth Split Level Income Trust plc</h3>
                    <div class="trusts_funds__inner">
                        <p>A split capital investment trust with two classes of share - Ordinary Shares and Zero Dividend Preference (ZDP) Shares - both of which traded on the London Stock Exchange.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>