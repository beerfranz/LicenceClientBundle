<?php

namespace Beerfranz\LicenceClientBundle\Service;

use Beerfranz\LicenceClientBundle\Entity\LicenceInstance;
use Beerfranz\LicenceClientBundle\Repository\LicenceInstanceRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Uid\Uuid;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class LicenceService
{
    public function __construct(
        private LicenceInstanceRepository $repo,
        private EntityManagerInterface $em,
        private HttpClientInterface $httpClient,
        private string $endpoint,
        private string $rootDir,
        private string $product,
        private string $url,
    ) {}

    /**
     * Returns the singleton record, creating it if necessary.
     * The $codeVersion param is used to set or update the code version.
     */
    public function getOrCreate(): LicenceInstance
    {
        $entity = $this->repo->findSingleton();
        if (!$entity) {
            $id = Uuid::v4()->toRfc4122();
            $entity = new LicenceInstance($id);

        }

        $entity->setProduct($this->product);
        $entity->setVersion($this->getVersion());
        $entity->setCodeVersion($this->getCodeVersion());

        $this->em->persist($entity);
        $this->em->flush();

        return $entity;
    }

    public function endpointSync(?LicenceInstance $entity = null)
    {
        if ($entity === null)
        {
            $entity = $this->repo->findSingleton();
        }
        
        $payload = [
            'instance' => $entity->getId(),
            'code_version' => $entity->getCodeVersion(),
            'version' => $entity->getVersion(),
            'product' => $entity->getProduct(),
            'url' => $this->url,
        ];
        try {
            $response = $this->httpClient->request('POST', $this->endpoint, [
                'json' => $payload,
                'timeout' => 30,
            ]);

            $status = $response->getStatusCode();

            if ($status === 200)
            {
                $data = json_decode($response->getContent(), true);

                $entity->setUpdatedAt(new \DateTimeImmutable());
            }
            
        } catch(\Throwable $e) {
            throw $e->getMessage();
        }
    }

    public function refresh()
    {
        $instance = $this->getOrCreate();
        $this->endpointSync($instance);
    }

    protected function getCodeVersion(): ?string
    {
        $directories = ['src', 'templates', 'migrations'];
        $hashes = [];

        foreach ($directories as $directory) {
            $dir = $this->rootDir . '/' . $directory;
            if (!is_dir($dir)) {
                continue;
            }

            $iterator = new \RecursiveIteratorIterator(
                new \RecursiveDirectoryIterator($dir, \FilesystemIterator::SKIP_DOTS)
            );

            foreach ($iterator as $file) {
                if ($file->isFile()) {
                    $hashes[] = md5_file($file->getPathname());
                }
            }
        }

        // Sort to ensure deterministic order
        sort($hashes);

        // Compute global checksum
        $checksum = md5(implode("\n", $hashes) . "\n");

        return $checksum;
    }

    protected function getVersion(): ?string
    {
        $filename = $this->rootDir . '/composer.json';
        $composerData = json_decode(file_get_contents($filename), true);

        return $composerData['version'];
    }
}
