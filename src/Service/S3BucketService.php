<?php

namespace App\Service;

use Aws\S3\S3Client;

class S3BucketService
{
    private $s3Client;
    private $bucketName;

    public function __construct(string $bucketName)
    {
        $this->bucketName = $bucketName;
        $this->s3Client = new S3Client([
            'version' => 'latest',
            'region'  => 'ap-south-1', // Replace with your AWS region
            'credentials' => [
                'key' => "AKIATNZOVECWOINCMESO",
                'secret' =>"5TfVtZbeb7lv1cyjfCzm/4QtZngnlO8xqC1LSJEs",
            ],
        ]);
    }

    public function getObjects(): array
    {
        $paginator = $this->s3Client->getPaginator('ListObjects', [
            'Bucket' => $this->bucketName,
        ]);
        $objects = [];
        foreach ($paginator as $page) {
            $objects = array_merge($objects, $page['Contents']);
        }

        return $objects;
    }

    public function putObject($jsonData)
    {
        $jsonData['body'] = json_encode($jsonData['body']);
        #dd($jsonData);
        $this->s3Client->putObject([
            'Bucket' => $this->bucketName,
            'Key' => $jsonData['key'],
            'Body' => $jsonData['body'],
        ]);
    }

    public function deleteObject($key)
    {
        $this->s3Client->deleteObject([
            'Bucket' => $this->bucketName,
            'Key' => $key,
        ]);
    }
}
