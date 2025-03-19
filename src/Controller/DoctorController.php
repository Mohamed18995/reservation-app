<?php

namespace App\Controller;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class DoctorController extends AbstractController
{
    #[Route('/doctor/{id}/appointments', name: 'doctor_appointments')]
    public function consultation($id, EntityManagerInterface $em): Response
    {
        // Récupérer les rendez-vous pour le médecin en fonction de son ID

        $doctorAppointments = $em->getRepository(Rendezvous::class)->findBy(['doctor' => $id]);

        // Retourner les résultats dans une vue
        return $this->render('doctor/appointments.html.twig', [
            'appointments' => $doctorAppointments,
        ]);
    }
}
