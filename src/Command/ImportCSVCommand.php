<?php
namespace App\Command;

use App\Entity\Client;
use App\Entity\ClientRecord;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

class ImportCSVCommand extends Command
{
    protected static $defaultName = 'app:import-csv-data';
    private EntityManagerInterface $em;

    protected function configure()
    {
        $this->setDescription('Import CSV data into the database and score the clients');
    }
    public function __construct(EntityManagerInterface $em)
    {
        parent::__construct();
        $this->em = $em;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

        $clientRecordRepository = $this->em->getRepository(ClientRecord::class);

        $io->title('Importing CSV data');
        $filePath = '/home/willhem-gr/brevo_case_study/src/brevo_casestudy_data.csv';

        if (!file_exists($filePath)) {
            $io->error("The file $filePath does not exist.");
            return Command::FAILURE;
        }

        $handle = fopen($filePath, 'r');
        if ($handle === false) {
            $io->error("Could not open the file $filePath.");
            return Command::FAILURE;
        }

        $header = fgetcsv($handle);
        $clientRepository = $this->em->getRepository(Client::class);

        while(($data = fgetcsv($handle)) !== false) {
            $record = array_combine($header, $data);
            $client = $clientRepository->find($record['client_id']);

            if (!$client) {
                $client = new Client();
                $client->setClientId($record['client_id']);
                $this->em->persist($client);
            }

            $clientRecord = new ClientRecord();
            $clientRecord->setClient($client);
            $clientRecord->setTotalSent($record['total_sent']);
            $clientRecord->setOpenRate($record['open_rate']);
            $clientRecord->setUnsubscriptionRate($record['unsubscription_rate']);
            $clientRecord->setBounceRate($record['bounce_rate']);
            $clientRecord->setComplaintRate($record['complaint_rate']);

            $client->addRecord($clientRecord);

            $this->em->persist($clientRecord);
        }

        fclose($handle);

        $this->em->flush();

        $io->success('Data imported successfully.');

        $clients = $clientRepository->findAll();

        foreach ($clients as $client) {
            $totalEmailSent = 0;
            $score = 0;

            $records = $clientRecordRepository->findBy(['client' => $client]);
            $totalEmailSent = array_reduce($records, function($carry, $record) {
                return $carry + $record->getTotalSent();
            }, 0);


            $client->setTotalEmailSent($totalEmailSent);
            
            $this->em->persist($client);
        }

        $this->em->flush();

        $io->title('Scoring clients');
        
        $io->progressStart(count($clients));

        foreach ($clients as $client) {
            $score = $this->calculateScore($client);
            $client->setScore($score);
            $this->em->persist($client);

            $io->progressAdvance();
        }

        $io->progressFinish();
        
        $io->success('Clients scored successfully.');

        $io->title('Categorizing clients');
        $this->categorizeClients();
        $io->success('Clients categorized successfully.');

        $this->em->flush();

        
        return Command::SUCCESS;
    }

    private function calculateScore(Client $client): int
    {
        $score = 0;
        $records = $client->getRecords();
        // means for all rates
        $openRate = 0;
        $unsubscriptionRate = 0;
        $bounceRate = 0;
        $complaintRate = 0;

        foreach ($records as $record) {
            $openRate += $record->getOpenRate();
            $unsubscriptionRate += $record->getUnsubscriptionRate();
            $bounceRate += $record->getBounceRate();
            $complaintRate += $record->getComplaintRate();
        }

        $openRate = $openRate / count($records);

        // average of all rates
        $unsubscriptionRate = $unsubscriptionRate / count($records);
        $bounceRate = $bounceRate / count($records);
        $complaintRate = $complaintRate / count($records);

        // the lower the better for these rates so we need to invert them
        // to increase the diffences between the scores we square the rates
        $unsubscriptionRate = (1-($unsubscriptionRate/100)) ** 2 * 100;
        $bounceRate = (1-($bounceRate/100)) ** 2 * 100;
        $complaintRate = (1-($complaintRate/100)) ** 2 * 100;
        
        // find the max email sent in all clients
        $clientRepository = $this->em->getRepository(Client::class);
        $maxEmailSent = $clientRepository->findMaxEmailSent();

        $totalEmailSent = $client->getTotalEmailSent();
        $totalEmailSent = $totalEmailSent / $maxEmailSent * 100;
        
        // complaint rate is more important than the other rates because it's related to the client's reputation
        $score = ($openRate + $unsubscriptionRate + $bounceRate + $complaintRate * 2 + $totalEmailSent) / 6;

        return $score;
    }

    public function categorizeClients(): void
    {
        $clientRepository = $this->em->getRepository(Client::class);
        $clients = $clientRepository->findAllOrderedByEmailsSent();
        // split in  3 categories with equal amount of emails sent

        $totalEmailsSent = array_reduce($clients, function ($carry, $client) {
            return $carry + $client->getTotalEmailSent();
        }, 0);

        $threshold = $totalEmailsSent / 3;

        $currentGroup = 0;
        $currentEmailSum = 0;

        foreach ($clients as $client) {
            $currentEmailSum += $client->getTotalEmailSent();
            $client->setCategory($currentGroup);
            $this->em->persist($client);

            if ($currentEmailSum >= $threshold && $currentGroup < 2) {
                $currentGroup++;
                $currentEmailSum = 0;
            }
        }
    }
}