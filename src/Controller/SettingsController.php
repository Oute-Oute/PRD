<?php

namespace App\Controller;

use App\Entity\Settings;
use App\Repository\SettingsRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Doctrine\ORM\EntityManagerInterface;

class SettingsController extends AbstractController
{
    public function settingsGet(SettingsRepository $settingRepository): Response
    {
        //créer la page de gestion des patients en envoyant la liste de tous les patients stockés en database
        return $this->render('settings/settings.html.twig', [
            'settings' => $settingRepository->findAll()
        ]);
    }

    public function settingsEdit(Request $request, SettingsRepository $settingRepository, EntityManagerInterface $entityManager): Response
    {
        $idAlert = $request->request->get("idAlert");

        $minTimeAlert = $request->request->get("minTimeAlert");
        $minutes=substr($minTimeAlert,0,2);
        $seconds=substr($minTimeAlert,3,2); 
        


        $settings = $settingRepository->findOneBy(['id' => $idAlert]);

        $settings->setAlertmodificationtimer($minutes*60000+$seconds*1000);

        $entityManager->persist($settings);
        $entityManager->flush();

        return $this->redirectToRoute('Settings', [], Response::HTTP_SEE_OTHER);
    }

    public function settingsAddDefault(EntityManagerInterface $entityManager): Response
    {
        $settings = new Settings();

        $settings->setAlertmodificationtimer(480000);
        $settings->setZoommultiplier(1);

        $entityManager->persist($settings);
        $entityManager->flush();

        return $this->redirectToRoute('Settings', [], Response::HTTP_SEE_OTHER);
    }
}
