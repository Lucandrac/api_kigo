<?php
// api/src/Controller/CreateMediaObjectAction.php

namespace App\Controller;

use App\Entity\Media;
use App\Repository\PostRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Attribute\AsController;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

#[AsController]
final class CreateMediaObjectAction extends AbstractController
{
    public function __invoke(Request $request, PostRepository $postRepository): Media
    {
        $uploadedFile = $request->files->get('file');
        if (!$uploadedFile) {
            throw new BadRequestHttpException('"file" is required');
        }

        $postId = $request->request->get('postId');
        $post = $postRepository->find($postId);


        $mediaObject = new Media();
        $mediaObject->setImageFile($uploadedFile);
        $mediaObject->setUrl($uploadedFile->getClientOriginalName());
        $mediaObject->setLabel($uploadedFile->getClientOriginalName());
        $mediaObject->setPost($post);
        return $mediaObject;
    }
}
