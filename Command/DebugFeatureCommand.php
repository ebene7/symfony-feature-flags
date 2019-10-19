<?php

namespace E7\FeatureFlagsBundle\Command;

use E7\FeatureFlagsBundle\Feature\FeatureBox;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Helper\Table;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Class DebugFeatureCommand
 * @package E7\FeatureFlagsBundle\Command
 */
class DebugFeatureCommand extends Command
{
    /** @var FeatureBox */
    private $featureBox;
    
    /**
     * 
     * @param FeatureBox $featureBox
     * @param type $name
     */
    public function __construct(FeatureBox $featureBox)
    {
        parent::__construct();
        $this->featureBox = $featureBox;
    }

    /**
     * @inheritDoc
     */
    protected function configure()
    {
        $this
            ->setDescription('Debug Feature Flag')
            ->addArgument('name', InputArgument::REQUIRED, 'The name of the feature flag.');
    }

    /**
     * @inheritDoc
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $box = $this->featureBox;
        $box->setDefaultState(true);
        
        $feature = new \E7\FeatureFlagsBundle\Feature\Feature('foo');
        $box->addFeature($feature);
        $feature->addCondition(new \E7\FeatureFlagsBundle\Feature\Conditions\HostCondition('ebene7.com'));
        $feature->addCondition(new \E7\FeatureFlagsBundle\Feature\Conditions\CallbackCondition(function(){}));
        
        $feature2 = new \E7\FeatureFlagsBundle\Feature\Feature('muuh', null, $feature);
        $box->addFeature($feature2);
        
        //***
        $name = $input->getArgument('name');
        $featureBox = $this->featureBox;
        $feature = $featureBox->getFeature($name);
        
        $headline = 'Debug Feature Flag \'' . $name . '\'';
        
        $output->writeln('');
        $output->writeln('<fg=yellow>' . $headline . '</fg=yellow>');
        $output->writeln('<fg=yellow>' . str_repeat('=', strlen($headline)). '</fg=yellow>');
        
        if (null === $feature) {
            $output->writeln('<comment>There is no feature named ' . $name .'</comment>');
            return;
        }
        
        $table = new Table($output);
        $table->setStyle('borderless');
        $table->addRows([
            ['Name:', $feature->getName()],
            ['Parent:', null !== $feature->getParent() ? $feature->getParent()->getName() : '--'],
        ]);
        $table->render();
        
        $output->writeln('');
        
        if (!$feature->hasConditions()) {
            $output->writeln('<fg=red>No conditions configured, yet!</fg=red>');
            return;
        }
        
        $conditions = $feature->getConditions();
        
        $table2 = new Table($output);
        $table2->setHeaders(['name', 'class']);
                
        foreach ($conditions as $condition) {
            $table2->addRow([
                $condition->getName(),
                get_class($condition),
            ]);
        }
        
        $table2->render();
    }
}
