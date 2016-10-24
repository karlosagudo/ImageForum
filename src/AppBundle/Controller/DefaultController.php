<?php

namespace AppBundle\Controller;

use AppBundle\Entity\ImageMessage;
use AppBundle\Form\ImageMessageType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\ResponseHeaderBag;

class DefaultController extends Controller
{
    /**
     * @Route("/", name="homepage")
     * @Method({"GET","POST"})
     */
    public function indexAction(Request $request)
    {
        $page = $request->get('page', 0);

        $em = $this->get('doctrine.orm.default_entity_manager');
        $form = $this->ManageForm($request, $em);

        $numberOfViews = $this->get('app_bundle.counter_pages')->getViews();
        $numberPerPage = $this->getParameter('posts_per_page');
        $numberOfPosts = $em->getRepository('AppBundle:ImageMessage')->getTotal();

        $messages = $em->getRepository('AppBundle:ImageMessage')->getPaginate($numberPerPage, $page);

        // replace this example code with whatever you need
        return $this->render('AppBundle:default:index.html.twig', [
            'numberOfPosts' => $numberOfPosts,
            'numberOfViews' => $numberOfViews,
            'form' => $form->createView(),
            'messages' => $messages,
            'page' => $page,
        ]);
    }

    /**
     * Manage the form.
     *
     * @param Request $request
     * @param $em
     *
     * @return ImageMessageType
     */
    private function ManageForm(Request $request, $em)
    {
        $imageMessage = new ImageMessage();
        $form = $this->createForm(ImageMessageType::class, $imageMessage);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            /** @var Symfony\Component\HttpFoundation\File\UploadedFile $file */
            $file = $imageMessage->getImage();

            $imageUrl = $this->get('app_bundle.image_uploader')->upload($file);

            $imageMessage->setImage($imageUrl);

            $em->persist($imageMessage);
            $em->flush($imageMessage);

            $this->addFlash(
                'notice',
                'Your changes were saved!'
            );
        }

        return $form;
    }

    /**
     * @Route("/update-counters", name="update-counters")
     * @Method({"GET"})
     */
    public function ajaxUploadCounters(Request $request)
    {
        $em = $this->get('doctrine.orm.default_entity_manager');
        $numberOfViews = $this->get('app_bundle.counter_pages')->getViews(false);
        $numberOfPosts = $em->getRepository('AppBundle:ImageMessage')->getTotal();

        return new JsonResponse(['views' => $numberOfViews, 'posts' => $numberOfPosts]);
    }

    /**
     * @Route("/export-cvs", name="export-cvs")
     * @Method({"GET","POST"})
     */
    public function exportCvsAction(Request $request)
    {
        $em = $this->get('doctrine.orm.default_entity_manager');
        $messages = $em->getRepository('AppBundle:ImageMessage')->getAll();

        $filename = tempnam(sys_get_temp_dir(), 'cvs').'.cvs';

        $file = fopen($filename, 'w+');
        fputcsv($file, $messages);

        return $this->BinaryFile($filename);
    }

    /**
     * @Route("/export-excel", name="export-excel")
     * @Method({"GET","POST"})
     */
    public function exportExcelAction(Request $request)
    {
        $em = $this->get('doctrine.orm.default_entity_manager');
        $messages = $em->getRepository('AppBundle:ImageMessage')->getAll();

        $phpExcelObject = $this->get('app_bundle.export_excel')
            ->generateExcelObjectFromArray($messages, ['Id', 'Title', 'Filename']);

        // create the writer
        $writer = $this->get('phpexcel')->createWriter($phpExcelObject, 'Excel5');
        $filename = tempnam(sys_get_temp_dir(), 'xls-').'.xls';
        // create filename
        $writer->save($filename);

        return $this->BinaryFile($filename);
    }

    /**
     * @Route("/export-zip", name="export-zip")
     * @Method({"GET","POST"})
     */
    public function exportZipAction(Request $request)
    {
        $em = $this->get('doctrine.orm.default_entity_manager');
        $messages = $em->getRepository('AppBundle:ImageMessage')->getAll();

        $phpExcelObject = $this->get('app_bundle.export_excel')
            ->generateExcelObjectFromArray($messages, ['Id', 'Title', 'Filename']);

        // create the writer
        $writer = $this->get('phpexcel')->createWriter($phpExcelObject, 'Excel5');
        $filenameExcel = tempnam(sys_get_temp_dir(), 'xls-').'.xls';
        // create filename
        $writer->save($filenameExcel);

        $zipFile = tempnam(sys_get_temp_dir(), 'zip-').'.zip';

        $zip = new \ZipArchive();
        $zip->open($zipFile, \ZipArchive::CREATE);

        $zip->addFile($filenameExcel, basename($filenameExcel));
        $imageDir = $this->getParameter('kernel.root_dir');
        $folderImages = $imageDir.DIRECTORY_SEPARATOR.'..'.DIRECTORY_SEPARATOR."web".DIRECTORY_SEPARATOR;
        foreach ($messages as $message) {
            $zip->addFile($folderImages.$message[2], "ID-".$message[0]."-".basename($message[2]));
        }

        $zip->close();

        return $this->BinaryFile($zipFile);
    }

    /**
     * @param $filename
     *
     * @return BinaryFileResponse
     */
    private function BinaryFile($filename)
    {
        $response = new BinaryFileResponse($filename);
        $response->setContentDisposition(ResponseHeaderBag::DISPOSITION_ATTACHMENT);

        return $response;
    }
}
