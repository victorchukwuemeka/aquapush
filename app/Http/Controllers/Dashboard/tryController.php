<?php




/** Create a Droplet on DigitalOcean with full error handling */
public function create_droplet($apiToken, $dropletName, $region, $size, $image, $publicKey = null)
{
    try {
        // Validate required parameters FIRST
        if (!$apiToken || !$dropletName || !$region || !$size || !$image) {
            throw new \Exception("Missing required droplet parameters.");
        }

        $sshFingerprint = null;

        /** --- SSH Key Handling --- */
        if ($publicKey) {
            try {
                $sshFingerprint = $this->get_or_add_ssh_key($apiToken, $publicKey);
            } catch (\Throwable $e) {
                throw new \Exception("Failed to process SSH key: " . $e->getMessage());
            }
        }

        /** --- Generate Cloud Init Script --- */
        try {
            $cloud_init_script = $this->cloud_init_service->generateCloudInitScript();
        } catch (\Throwable $e) {
            throw new \Exception("Failed to generate cloud-init script: " . $e->getMessage());
        }

        /** --- Build Payload --- */
        $payload = [
            'name'    => $dropletName,
            'region'  => $region,
            'size'    => $size,
            'image'   => $image,
            'backups' => false,
            'user_data' => $cloud_init_script,
        ];

        if ($sshFingerprint) {
            $payload['ssh_keys'] = [$sshFingerprint];
        }

        /** --- Make API Call to Create Droplet --- */
        try {
            $response = $this->client->post('https://api.digitalocean.com/v2/droplets', [
                'headers' => [
                    'Authorization' => 'Bearer ' . $apiToken,
                    'Content-Type' => 'application/json',
                ],
                'json' => $payload,
            ]);
        } catch (\GuzzleHttp\Exception\ClientException $e) {
            // DigitalOcean returned 4xx (bad request)
            $body = $e->getResponse()->getBody()->getContents();
            throw new \Exception("DigitalOcean Client Error: " . $body);
        } catch (\GuzzleHttp\Exception\ServerException $e) {
            // DigitalOcean returned 5xx (server error)
            $body = $e->getResponse()->getBody()->getContents();
            throw new \Exception("DigitalOcean Server Error: " . $body);
        } catch (\Throwable $e) {
            throw new \Exception("Failed to send request to DigitalOcean: " . $e->getMessage());
        }

        /** --- Decode and Validate Response --- */
        $responseArray = json_decode($response->getBody(), true);

        if (!isset($responseArray['droplet']['id'])) {
            throw new \Exception("Droplet creation failed: No droplet ID returned.");
        }

        $droplet_id = $responseArray['droplet']['id'];

        /** --- Retrieve IP Address --- */
        sleep(5); // safe enough, but DO returns droplet without IP immediately

        try {
            $this->ip_address = $this->get_droplet_ip_address($droplet_id, $apiToken);
        } catch (\Throwable $e) {
            throw new \Exception("Droplet created but failed to fetch IP address: " . $e->getMessage());
        }

        /** --- Final Success --- */
        return [
            'status' => 'success',
            'droplet_id' => $droplet_id,
            'ip_address' => $this->ip_address
        ];

    } catch (\Throwable $e) {
        // FINAL error catch — nothing escapes unhandled
        return [
            'status' => 'error',
            'message' => $e->getMessage()
        ];
    }
}
