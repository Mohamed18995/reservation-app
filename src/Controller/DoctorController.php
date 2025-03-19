<?php

namespace App\Controller;

use App\Entity\Appointment;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class DoctorController extends AbstractController
{
    #[Route('/doctor/{id}/appointments', name: 'doctor_appointments')]
    public function consultation($id, EntityManagerInterface $em): Response
    {
        // Récupérer les rendez-vous pour le médecin en fonction de son ID

        $doctorAppointments = $em->getRepository(Appointment::class)->findBy(['doctor' => $id]);

        // Retourner les résultats dans une vue
        return $this->render('doctor/appointments.html.twig', [
            'appointments' => $doctorAppointments,
        ]);
    }

    #[Route('/doctor/{id}/add-appointment', name: 'doctor_add_appointment')]
    public function addAppointment($id, Request $request, EntityManagerInterface $em): Response
    {
        $appointment = new Appointment();

        // Si le formulaire est soumis
        if ($request->isMethod('POST')) {
            // Récupérer les données du formulaire
            $appointment->setDate(new \DateTime($request->request->get('date')));
            $appointment->setTime(new \DateTime($request->request->get('time')));
            // Associer le patient
            $patient = $em->getRepository(User::class)->find($request->request->get('patient'));
            $appointment->setUser($patient);
            // Associer le médecin (le médecin actuel)
            $appointment->setDoctor($this->getUser());

            // Sauvegarder l'entité rendez-vous
            $em->persist($appointment);
            $em->flush();

            // Rediriger vers la page des rendez-vous du médecin
            return $this->redirectToRoute('doctor_appointments', ['id' => $id]);
        }

        // Liste des patients pour la sélection
        $patients = $em->getRepository(User::class)->findAll();

        // Retourner la vue avec les données
        return $this->render('doctor/add_appointment.html.twig', [
            'patients' => $patients,
        ]);
    }

}
