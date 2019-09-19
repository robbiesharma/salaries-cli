<?php namespace Console;
use Symfony\Component\Console\Command\Command as SymfonyCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
/**
 * Author: Robin Sharma <robinsharma2109@gmail.com>
 */
class Salary extends SymfonyCommand
{
    
    public function __construct()
    {
        parent::__construct();
    }

    public function configure()
    {
        $this->setName('salaries')
             ->setDescription('Greet a user based on the time of the day.')
             ->setHelp('This command allows you to greet a user based on the time of the day...');
    }

    public function run(InputInterface $input, OutputInterface $output)
    {
        // outputs multiple lines to the console (adding "\n" at the end of each line)
        $output -> writeln([
            '====**** Gives salaries dates remainder of this year to financial company ****====',
            '==================================================================================',
            '',
        ]);
        $this->getSalaries();
        // outputs a message without adding a "\n" at the end of the line
        $output -> write('Export has been completed!');
    }

    public function getSalaries()
    {
    	$file = fopen('salaries.csv', 'wb');
		fputcsv($file, array('Month Name', 'Salary Date', 'Bonus Date'));
		$month = date('n');
		$months = 12 - $month;
		for ($i=$month; $i <= 12; $i++) { 

			$monthName = date('F', mktime(0, 0, 0, $i, 10));

			//For bonus
			$bonus = new \DateTime(date('Y').'-'.$i.'-15');
			$bonusDay = date('l', strtotime($bonus->format('Y-m-d')));
			if($bonusDay == "Saturday") { 
				$bonusDate = date('Y-m-d', strtotime('+4 day', strtotime($bonus->format('Y-m-d'))));
			}
			elseif($bonusDay == "Sunday") { 
				$bonusDate = date('Y-m-d', strtotime('+3 day', strtotime($bonus->format('Y-m-d'))));
			}else{
				$bonusDate = $bonus->format('Y-m-d');
			}

			// For Salaries
			$day = new \DateTime(date('Y').'-'.$i.'-01');
			$day= $day->modify('last day of this month'); 
			$lastworkingday = date('l', strtotime($day->format('Y-m-d')));
			if($lastworkingday == "Saturday") { 
				$salary_date = date('Y-m-d', strtotime('-1 day', strtotime($day->format('Y-m-d'))));
			}
			elseif($lastworkingday == "Sunday") { 
				$salary_date = date('Y-m-d', strtotime('-2 day', strtotime($day->format('Y-m-d'))));
			}else{
				$salary_date = $day->format('Y-m-d');
			}
			
			$data[] = [$monthName,$salary_date, $bonusDate];
		}

		// save each row of the data
		foreach ($data as $row)
		{
			fputcsv($file, $row);
		}

		fclose($file);
    }

    public function execute(InputInterface $input, OutputInterface $output)
    {
        $this->run($input, $output);
    }

}