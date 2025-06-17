<?php
namespace App\Controller;

use App\Service\DashboardService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class DashboardController extends AbstractController
{
    #[Route('/dashboard', name: 'app_dashboard')]
    public function index(DashboardService $dashboardService): Response
    {
        $data = $dashboardService->getDashboardData();
        $fundingData = [];
        
        foreach ($data as $item) {
            if ($item['title'] === 'Funding Activity' && isset($item['metrics'])) {
                $fundingData = $item;
                break;
            }
        }

        return $this->render('dashboard/index.html.twig', [
            'stats' => $data,
            'fundingData' => $fundingData
        ]);
    }
}
