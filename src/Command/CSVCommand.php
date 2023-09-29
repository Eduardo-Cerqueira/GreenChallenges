<?php

namespace App\Command;

use App\Entity\Challenge;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

#[AsCommand(
    name: 'CSV:Import',
    description: 'Import a csv about challenges to the database',
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
            ->addArgument('admin_id', InputArgument::OPTIONAL, 'Admin ID')
            ->addArgument('avoid_rows', InputArgument::OPTIONAL, 'Avoid Rows')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        $arg1 = $input->getArgument('csv_path');
        $arg2 = $input->getArgument('admin_id');
        $arg3 = $input->getArgument('avoid_rows');

        if (!$arg1) {
            $io->error('CSV path was not given !');
            return Command::INVALID;
        }

        if (!$arg2) {
            $io->error('The ID of the Admin doing the import was not given !');
            return Command::INVALID;
        }

        if (!$arg3) {
            $io->comment('The number of rows to avoid was not set !');
            $arg3 = 0;
        }

        if (!file_exists($arg1)) {
            $io->error('File does not exist !');
            return Command::INVALID;
        }

        if (($handle = fopen($arg1, "r")) !== false) {
            $em = $this->entityManager;
            $user = $em->getRepository(User::class)->findOneBy(array('id' => $arg2));

            $row = 0;
            while (($data = fgetcsv($handle)) !== false) {
                if ($row > $arg3) {
                    if (!empty($data[1])) {
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
                }
                $row++;
            }
            fclose($handle);
            $em->flush();
        }


        $io->success('CSV file imported to database !');

        return Command::SUCCESS;
    }
}
