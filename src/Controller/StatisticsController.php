<?php

namespace App\Controller;

use App\Entity\Appointment;
use App\Entity\Pathway;
use App\Repository\AppointmentRepository;
use App\Repository\PathwayRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Validator\Constraints\Date;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\Validator\Constraints\Length;

class StatisticsController extends AbstractController
{

    public $date;
    /*
     * @brief Allows to get stats
     * 
     */
    public function index(): Response
    {

        $english_months = array('January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December');
        $french_months = array('janvier', 'février', 'mars', 'avril', 'mai', 'juin', 'juillet', 'août', 'septembre', 'octobre', 'novembre', 'décembre');
        
        global $date;
        $date = date(('Y-m-d'));
        if (isset($_GET["date"])) {
            $date = $_GET["date"];
            $date = str_replace('T12:00:00', '', $date);
        }
        $header="";
        if (isset($_GET["headerResources"])) {
            $header = $_GET["headerResources"];
        }

        $dateFormatted=date_create($date);
        $dateFormatted->format('Y-F-d');
        $dateStr=str_replace($english_months, $french_months,$dateFormatted->format('d F Y'));

        return $this->render('statistics/index.html.twig', [
            'controller_name' => 'StatisticsController',
            'currentdate' => $date,
            'dateFormatted' => $dateStr,
        ]);
    }
}