<?php

namespace App\Controller;

use App\Service\S3BucketService;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class BucketController extends AbstractController
{
    #[Route('/b', name: 'app_bucket')]
    public function index(): JsonResponse
    {
        return $this->json([
            'message' => 'Welcome to your new controller!',
            'path' => 'src/Controller/BucketController.php',
        ]);
    }

    #[Route('/', name: 'app_bucket1')]
    public function list(S3BucketService $s3BucketService): Response
    {
        $objects = $s3BucketService->getObjects();

        return $this->render('list.html.twig', [
            'objects' => $objects,
        ]);
    }

    #[Route('/upload', name: 'app_upload',methods: ['POST'])]
    public function upload(S3BucketService $s3BucketService,Request $request): Response
    {
        $data=$request->getContent();
        $jsonData = json_decode($data, true);
        $s3BucketService->putObject($jsonData);
        return $this->redirectToRoute('app_bucket1');

    }

    #[Route('/delete', name: 'app_delete',methods: ['POST'])]
    public function delete(S3BucketService $s3BucketService,Request $request): Response
    {
        $data=$request->getContent();
        $jsonData = json_decode($data, true);
        $s3BucketService->deleteObject($jsonData['key']);
        return $this->redirectToRoute('app_bucket1');
    }

}
