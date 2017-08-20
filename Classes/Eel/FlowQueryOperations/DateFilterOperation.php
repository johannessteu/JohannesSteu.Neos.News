<?php
namespace JohannesSteu\Neos\News\Eel\FlowQueryOperations;

/*                                                                        *
 * This script belongs to the Flow package "JohannesSteu.Neos.News".      *
 *                                                                        */

use Neos\Eel\FlowQuery\Operations\AbstractOperation;
use Neos\Eel\FlowQuery\FlowQuery;
use Neos\Flow\Annotations as Flow;
use Neos\ContentRepository\Domain\Model\NodeInterface;

/**
 * EEL operation to Build a date filter for news. This operation returns an array with all dates that can be
 * filtered for
 */
class DateFilterOperation extends AbstractOperation
{
    /**
     * {@inheritdoc}
     *
     * @var string
     */
    protected static $shortName = 'dateFilter';

    /**
     * This operation is final and will return an array
     *
     * @var bool
     */
    protected static $final = true;

    /**
     * {@inheritdoc}
     *
     * @var integer
     */
    protected static $priority = 100;

    /**
     * {@inheritdoc}
     *
     * We can only handle NeosCR Nodes.
     *
     * @param mixed $context
     * @return boolean
     */
    public function canEvaluate($context)
    {
        return (isset($context) && is_array($context));
    }

    /**
     * {@inheritdoc}
     *
     * @param FlowQuery $flowQuery the FlowQuery object
     * @param array $arguments the arguments for this operation
     * @return mixed
     */
    public function evaluate(FlowQuery $flowQuery, array $arguments)
    {
        $newsNodes = $flowQuery->getContext();

        $dateFilter = [];

        foreach ($newsNodes as $node) {
            if ($node instanceof NodeInterface && $node->getNodeType()->getName() === "JohannesSteu.Neos.News:News") {
                /** @var \DateTime $date */
                $date = $node->getProperty('publishDate');

                if (!isset($dateFilter[$date->format('Y')][$date->format('m')])) {
                    $dateFilter[$date->format('Y')][$date->format('m')] = 0;
                }

                $dateFilter[$date->format('Y')][$date->format('m')] += 1;
                ksort($dateFilter[$date->format('Y')]);
            }
        }
        ksort($dateFilter);

        return $dateFilter;
    }
}
