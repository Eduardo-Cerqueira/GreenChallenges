<?php

namespace App\Controller;

use App\Entity\CurrentChallenge;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\UX\Chartjs\Builder\ChartBuilderInterface;
use Symfony\UX\Chartjs\Model\Chart;

class ChartjsController extends AbstractController
{
    #[Route('/chart/admin/challenge/status', name: 'app_chart')]
    public function index(ChartBuilderInterface $chartBuilder): Response
    {
        $currentchallenge = $this->getDoctrine()->getManager()->getRepository(CurrentChallenge::class);;
        
        $doing = $currentchallenge->getSumStatus('Doing');
        $completed = $currentchallenge->getSumStatus('Completed');
        $timeout = $currentchallenge->getSumStatus('Timeout');
        $abandoned = $currentchallenge->getSumStatus('Abandoned');

        $chart = $chartBuilder->createChart(Chart::TYPE_DOUGHNUT);

        $chart->setData([
            'labels' => ['Doing', 'Completed', 'Timeout', 'Abandoned'],
            'datasets' => [
                [
                    'label' => 'Users challenges',
                    'backgroundColor' => ['rgb(255, 99, 132)', 'rgb(255, 99, 0)', 'rgb(255, 0, 132)', 'rgb(0, 99, 132)'],
                    'borderColor' => 'rgb(255, 255, 255)',
                    'data' => [$doing["total"], $completed["total"], $timeout["total"], $abandoned["total"]],
                ],
            ],
        ]);

        return $this->render('chart/doughtnut.html.twig', [
            'chart' => $chart,
        ]);
    }
}