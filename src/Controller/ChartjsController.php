<?php

namespace App\Controller;

use App\Entity\CurrentChallenge;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\UX\Chartjs\Builder\ChartBuilderInterface;
use Symfony\UX\Chartjs\Model\Chart;

#[Route('/chart', name: 'chart')]
class ChartjsController extends AbstractController
{
    #[Route('/challenge/status', name: 'app_chart')]
    public function index(ChartBuilderInterface $chartBuilder): Response
    {
        $em = $this->getDoctrine()->getManager();
        $doing = $em->getRepository(CurrentChallenge::class)->findBy(['status' => 'Doing']);
        $completed = $em->getRepository(CurrentChallenge::class)->findBy(['status' => 'Completed']);
        $timeout = $em->getRepository(CurrentChallenge::class)->findBy(['status' => 'Timeout']);
        $abandoned = $em->getRepository(CurrentChallenge::class)->findBy(['status' => 'Abandoned']);



        $chart = $chartBuilder->createChart(Chart::TYPE_DOUGHNUT);

        $chart->setData([
            'labels' => ['Doing', 'Completed', 'Timeout', 'Abandoned'],
            'datasets' => [
                [
                    'label' => 'Users challenges',
                    'backgroundColor' => ['rgb(255, 99, 132)', 'rgb(255, 99, 0)', 'rgb(255, 0, 132)', 'rgb(0, 99, 132)'],
                    'borderColor' => 'rgb(255, 255, 255)',
                    'data' => [sizeof($doing), sizeof($completed), sizeof($timeout), sizeof($abandoned)],
                ],
            ],
        ]);

        $chart->setOptions([
            'scales' => [
                'y' => [
                    'suggestedMin' => 0,
                    'suggestedMax' => 100,
                ],
            ],
        ]);

        return $this->render('chart/doughtnut.html.twig', [
            'chart' => $chart,
        ]);
    }
}