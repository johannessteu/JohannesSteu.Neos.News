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
 * EEL operation to remove important news
 */
class RemoveImportantNewsOperation extends AbstractOperation
{
    /**
     * {@inheritdoc}
     *
     * @var string
     */
    protected static $shortName = 'removeImportantNews';

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
        return (isset($context[0]) && ($context[0] instanceof NodeInterface));
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
        if (!is_array($arguments)) {
            throw new \Neos\Eel\FlowQuery\FlowQueryException('removeImportantNews() needs a true/false argument', 1332492263);
        } else {
            if (!$arguments[0]) {
                $nodes = $flowQuery->getContext();
                $nonImportantNews = [];

                foreach ($nodes as $node) {
                    /** @var $node NodeInterface */
                    $isImportant = $node->getProperty("important");
                    if (!$isImportant) {
                        $nonImportantNews[] = $node;
                    }
                }

                $flowQuery->setContext($nonImportantNews);
            }
        }

    }
}
