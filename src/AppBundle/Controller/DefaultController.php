<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Forum;
use AppBundle\Entity\ImageMessage;
use AppBundle\Form\ImageMessageType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\ResponseHeaderBag;
use Symfony\Component\VarDumper\VarDumper;

class DefaultController extends Controller
{
    /**
     * @Route("/", name="homepage")
     * @Method({"GET","POST"})
     */
    public function indexAction(Request $request)
    {
        $page = $request->get('page',0);

        $em = $this->get('doctrine.orm.default_entity_manager');
        $form = $this->ManageForm($request, $em);

        $numberPerPage = $this->getParameter('posts_per_page');

        $numberOfPosts = $em->getRepository('AppBundle:ImageMessage')->getTotal();
        $numberOfViews = $this->getViews();
        $messages = $em->getRepository('AppBundle:ImageMessage')->getPaginate($numberPerPage, $page);

        // replace this example code with whatever you need
        return $this->render('AppBundle:default:index.html.twig', [
            'numberOfPosts' => $numberOfPosts,
            'numberOfViews' => $numberOfViews,
            'form' => $form->createView(),
            'messages' => $messages,
            'page' => $page
        ]);
    }

    /**
     * @Route("/export-cvs", name="export-cvs")
     * @Method({"GET","POST"})
     */
    public function exportAction(Request $request)
    {
        $em = $this->get('doctrine.orm.default_entity_manager');
        $messages = $em->getRepository('AppBundle:ImageMessage')->getAll();

        $filename = tempnam(sys_get_temp_dir(), 'cvs') . '.cvs';

        $file = fopen($filename,'w+');
        fputcsv($file, $messages);

        $response = new BinaryFileResponse($filename);
        $response->setContentDisposition(ResponseHeaderBag::DISPOSITION_ATTACHMENT);

        return $response;
    }

    /**
     * @Route("/export-excel", name="export-excel")
     * @Method({"GET","POST"})
     */
    public function exportExcelAction(Request $request)
    {
        $em = $this->get('doctrine.orm.default_entity_manager');
        $messages = $em->getRepository('AppBundle:ImageMessage')->getAll();

        $phpExcelObject = $this->generateExcelObjectFromArray($messages);

        // create the writer
        $writer = $this->get('phpexcel')->createWriter($phpExcelObject, 'Excel5');
        $filename = tempnam(sys_get_temp_dir(), 'xls-') . '.xls';
        // create filename
        $writer->save($filename);

        $response = new BinaryFileResponse($filename);
        $response->setContentDisposition(ResponseHeaderBag::DISPOSITION_ATTACHMENT);

        return $response;
    }

    private function getViews()
    {
        $em = $this->get('doctrine.orm.default_entity_manager');

        if (!$forum = $em->getRepository('AppBundle:Forum')->find(1)) { //when we start
            $forum = new Forum();
            $forum->setViews(0);
            $forum->setId(1);
        }

        $numberOfViews = $forum->getViews();
        $forum->setViews($numberOfViews + 1);

        $em->persist($forum);
        $em->flush($forum);

        return $numberOfViews+1;
    }

    /**
     * Manage the form
     * @param Request $request
     * @param $em
     */
    public function ManageForm(Request $request, $em)
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
     * @param $messages
     * @return \PHPExcel
     */
    public function generateExcelObjectFromArray($messages)
    {
        $phpExcelObject = $this->get('phpexcel')->createPHPExcelObject();

        $phpExcelObject->getProperties()->setCreator("Carlos Agudo")
            ->setLastModifiedBy("Carlos Agudo")
            ->setTitle("Export")
            ->setSubject("Export")
            ->setDescription("Test document for Office 2005 XLSX, generated using PHP classes.")
            ->setKeywords("office 2005 openxml php")
            ->setCategory("Test result file");
        $phpExcelObject->setActiveSheetIndex(0)
            ->setCellValue('A1', 'Id')
            ->setCellValue('B1', 'Title')
            ->setCellValue('C1', 'FileName');

        $i = 2;
        foreach ($messages as $result) {
            $phpExcelObject->setActiveSheetIndex(0)
                ->setCellValue('A' . $i, $result[0])
                ->setCellValue('B' . $i, $result[1])
                ->setCellValue('C' . $i, $result[2]);
            $i++;
        }

        $phpExcelObject->getActiveSheet()->setTitle('Simple');
        // Set active sheet index to the first sheet, so Excel opens this as the first sheet
        $phpExcelObject->setActiveSheetIndex(0);
        return $phpExcelObject;
    }


}
