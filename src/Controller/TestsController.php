<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Doctrine\Persistence\ManagerRegistry;


class TestsController extends AbstractController
{

    public $date;
    public $dateFormatted;
    /*
     * @brief Allows to get stats
     * @param ManagerRegistry $doctrine
     * @return Response
     */
    public function index(ManagerRegistry $doctrine): Response
    {
        global $date;
        global $dateFormatted;
        $date = date(('Y-m-d'));
        if (isset($_GET["date"])) {
            $date = $_GET["date"];
            $date = str_replace('T12:00:00', '', $date);
        }
        $dateFormatted = date_create($date);
        $dateFormatted->format('Y-F-d');
        //render the view
        return $this->render('tests/index.html.twig', [
            'controller_name' => 'TestsController',
            'currentdate' => $date,
        ]);
    }

}