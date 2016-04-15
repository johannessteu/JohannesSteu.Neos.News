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
 * EEL operation to filter nodes by a given author
 */
class FilterByAuthorOperation extends AbstractOperation
{
    /**
     * {@inheritdoc}
     *
     * @var string
     */
    protected static $shortName = 'filterByAuthor';

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
     * @param array $arguments the arguments for this operation
     * @return mixed
     */
    public function evaluate(FlowQuery $flowQuery, array $arguments)
    {
        if (!isset($arguments[0]) || empty($arguments[0])) {
            throw new \TYPO3\Eel\FlowQuery\FlowQueryException('findByAuthor() needs a author to filter for', 1460741060);
        } else {

            /** @var NodeInterface $author */
            $author = $arguments[0];

            if ($author->getNodeType()->getName() !== 'JohannesSteu.Neos.News:Author') {
                throw new \TYPO3\Eel\FlowQuery\FlowQueryException('findByAuthor() only accepts as parameter Nodes of type JohannesSteu.Neos.News:Author but '.$author->getNodeType().' given', 1460741067);
            }

            $nodesWithAuthorSet = [];
            $nodes = $flowQuery->getContext();

            foreach ($nodes as $node) {
                /** @var $node NodeInterface */

                $nodeAuthor = $node->getProperty("author");
                if (isset($nodeAuthor) && $nodeAuthor === $author) {
                    $nodesWithAuthorSet[] = $node;
                }
            }

            $flowQuery->setContext($nodesWithAuthorSet);
        }
    }
}
