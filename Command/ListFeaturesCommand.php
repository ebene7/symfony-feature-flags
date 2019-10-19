<?php

namespace E7\FeatureFlagsBundle\Command;

use E7\FeatureFlagsBundle\Feature\FeatureBox;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Helper\Table;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Class ListFeaturesCommand
 * @package E7\FeatureFlagsBundle\Command
 */
class ListFeaturesCommand extends Command
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
        $this->setDescription('List Feature Flags');
    }

    /**
     * @inheritDoc
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
//        $box = $this->featureBox;
//        $box->setDefaultState(true);
//        
//        $feature = new \E7\FeatureFlagsBundle\Feature\Feature('foo');
//        $box->addFeature($feature);
//        $feature->addCondition(new \E7\FeatureFlagsBundle\Feature\Conditions\HostCondition('ebene7.com'));
//        $feature->addCondition(new \E7\FeatureFlagsBundle\Feature\Conditions\CallbackCondition(function(){}));
//        
//        
//        $feature2 = new \E7\FeatureFlagsBundle\Feature\Feature('muuh', null, $feature);
//        $box->addFeature($feature2);        
        
        //***
        $featureBox = $this->featureBox;
        
        $output->writeln('');
        $output->writeln('<fg=yellow>Registered Feature Flags</fg=yellow>');
        $output->writeln('<fg=yellow>========================</fg=yellow>');
        
        if ($featureBox->isEmpty()) {
            $output->writeln('<comment>No features configured, yet!</comment>');
            return;
        }
        
        $table = new Table($output);
        $table->setHeaders(['name', 'parent', 'conditions']);
        
        foreach ($this->featureBox as $feature) {
            $conditionsColumn = $feature->hasConditions()
                ? implode(', ', iterator_to_array($feature->getConditions()))
                : '<fg=red>No conditions configured, yet!</fg=red>';
            
            $table->addRow([
                $feature->getName(),
                null !== $feature->getParent() ? $feature->getParent()->getName() : '--',
                $conditionsColumn
            ]);
        }
        
        $table->render();
    }
}
