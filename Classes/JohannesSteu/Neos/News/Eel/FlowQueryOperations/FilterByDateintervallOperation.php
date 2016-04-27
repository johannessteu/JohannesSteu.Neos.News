<?php
namespace JohannesSteu\Neos\News\Eel\FlowQueryOperations;

/*                                                                        *
 * This script belongs to the Flow package "JohannesSteu.Neos.News".      *
 *                                                                        */

use TYPO3\Eel\FlowQuery\Operations\AbstractOperation;
use TYPO3\Eel\FlowQuery\FlowQuery;
use TYPO3\Flow\Annotations as Flow;
use TYPO3\TYPO3CR\Domain\Model\NodeInterface;

/**
 * EEL operation to filter nodes by a given date. This operation will return all
 * nodes withing this month if a month is given, otherwise all nodes within the given year
 */
class FilterByDateintervallOperation extends AbstractOperation
{
    /**
     * {@inheritdoc}
     *
     * @var string
     */
    protected static $shortName = 'filterByDateintervall';

    /**
     * {@inheritdoc}
     *
     * @var integer
     */
    protected static $priority = 100;

    /**
     * {@inheritdoc}
     *
     * We can only handle TYPO3CR Nodes.
     *
     * @param mixed $context
     * @return boolean
     */
    public function canEvaluate($context)
    {
        return (isset($context[0]) && ($context[0] instanceof NodeInterface));
    }

    /**
     * {@inheritdoc}
     *
     * @param FlowQuery $flowQuery the FlowQuery object
     * @param array $arguments First argument is the property to filter for, second is the date to filter for
     * @return mixed
     */
    public function evaluate(FlowQuery $flowQuery, array $arguments)
    {
        if (!isset($arguments[0]) || empty($arguments[0])) {
            throw new \TYPO3\Eel\FlowQuery\FlowQueryException('findByDateintervall() needs a property to filter for', 1460741060);
        } else {
            if (!isset($arguments[1]) || empty($arguments[1])) {
                throw new \TYPO3\Eel\FlowQuery\FlowQueryException('findByDateintervall() needs a date to filter in format for', 1460741060);
            }

            // todo pretty dirty & rough implementation right now, make this cleaner
            $property = $arguments[0];
            $dateToFilterFor = explode('-', $arguments[1]);

            $startDate = new \DateTime('now');
            $startDate->setTime(0, 0, 0);

            if (count($dateToFilterFor) > 1) {
                $startDate->setDate($dateToFilterFor[0], $dateToFilterFor[1], 1);
                $endDate = clone $startDate;
                // Add one month
                $endDate->setDate($dateToFilterFor[0], $dateToFilterFor[1] + 1, 1);
            } else {
                $startDate->setDate($dateToFilterFor[0], 1, 1);
                $endDate = clone $startDate;
                // Add one year
                $endDate->setDate($dateToFilterFor[0] + 1, 1, 1);
            }

            $nodesInDateIntervall = [];
            $nodes = $flowQuery->getContext();

            foreach ($nodes as $node) {
                /** @var NodeInterface $node */
                /** @var \DateTime $date */
                $date = $node->getProperty($property);

                if ($date->getTimestamp() >= $startDate->getTimestamp() && $date->getTimestamp() < $endDate->getTimestamp()) {
                    $nodesInDateIntervall[] = $node;
                }
            }
            $flowQuery->setContext($nodesInDateIntervall);
        }
    }
}
