<?php

namespace App\Controller;

use App\Entity\Settings;
use App\Repository\SettingsRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Doctrine\ORM\EntityManagerInterface;


/**
 * @file        SettingsController.php
 * @brief       Contains the functions that handle the settings
 * @date        2022
 */

class SettingsController extends AbstractController
{
    /**
      * Allows to display current settings
     */
    public function settingsGet(SettingsRepository $settingRepository): Response
    {
        return $this->render('settings/settings.html.twig', [
            'settings' => $settingRepository->findAll()
        ]);
    }

    /**
      * Allows to edit the current settings
     */
    public function settingsEdit(Request $request, SettingsRepository $settingRepository, EntityManagerInterface $entityManager): Response
    {
        $idAlert = $request->request->get("idAlert");

        $alertTimeMin = $request->request->get("alertTimeMin");
        $alertTime = 60 * $alertTimeMin * 1000;

        $reloadTimeMin = $request->request->get("reloadTimeMin");
        $reloadTime = 60 * $reloadTimeMin * 1000;

        $settings = $settingRepository->findOneBy(['id' => $idAlert]);

        $settings->setAlertmodificationtimer($alertTime);
        $settings->setReloadtime($reloadTime);

        $entityManager->persist($settings);
        $entityManager->flush();

        return $this->redirectToRoute('Settings', [], Response::HTTP_SEE_OTHER);
    }


    /**
      * Allows to set settings to default values
     */
    public function settingsAddDefault(EntityManagerInterface $entityManager): Response
    {
        $settings = new Settings();

        $settings->setAlertmodificationtimer(480000);
        $settings->setZoommultiplier(1);
        $settings->setReloadtime(600000);

        $entityManager->persist($settings);
        $entityManager->flush();

        return $this->redirectToRoute('Settings', [], Response::HTTP_SEE_OTHER);
    }
}
