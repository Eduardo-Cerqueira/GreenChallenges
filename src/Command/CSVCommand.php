<?php

namespace App\Command;

use App\Entity\Challenge;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

#[AsCommand(
    name: 'CSV',
    description: 'Add a short description for your command',
)]
class CSVCommand extends Command
{
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        //Import entityManager into class
        $this->entityManager = $entityManager;

        //Initialization of CSVCommand
        parent::__construct();
    }
    
    protected function configure(): void
    {
        $this
            ->addArgument('csv_path', InputArgument::OPTIONAL, 'CSV Path')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        $arg1 = $input->getArgument('csv_path');

        if (!$arg1) {
            $io->error('CSV path was not given !');
            return Command::INVALID;
        }

        if (!file_exists($arg1)) {
            $io->error('File does not exist !');
            return Command::INVALID;
        }
        
        if (($handle = fopen($arg1, "r")) !== false) {
            $em = $this->entityManager;
            $user = $em->getRepository(User::class)->findOneBy(array('id' => 0));

            while (($data = fgetcsv($handle)) !== false) {
                $challenge = new Challenge();
                $challenge->setCreatedBy($user);
                $challenge->setTitle($data[1]);
                $challenge->setCategory($data[2]);
                $challenge->setSubcategory($data[3]);
                $challenge->setDescription($data[4]);
                $challenge->setTips($data[5]);
                $challenge->setStatus("Active");
                $challenge->setPoints(random_int(10, 100));
                //In seconds => between 7 days and 30 days
                $challenge->setDuration(random_int(604800, 2592000));

                $em->persist($challenge);
            }
            fclose($handle);
            $em->flush();
        }


        $io->success('CSV file imported to database !');

        return Command::SUCCESS;
    }
}
