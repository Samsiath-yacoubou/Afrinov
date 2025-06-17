<?php


// src/Service/DashboardService.php
namespace App\Service;

class DashboardService
{
    public function getDashboardData(): array
    {
        $path = '../var/data/dashboard_stats.json';
        return json_decode(file_get_contents($path), true);
    }
}










?>