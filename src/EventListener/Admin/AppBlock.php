<?php

declare(strict_types=1);

namespace MonsieurBiz\SyliusRichEditorPlugin\EventListener\Admin;

use MonsieurBiz\SyliusRichEditorPlugin\Factory\UiElementFactory;
use Sonata\BlockBundle\Event\BlockEvent;
use Sonata\BlockBundle\Model\Block;

class AppBlock
{
    /** @var string */
    private $template;

    /** @var UiElementFactory */
    private $uiElementFactory;

    /**
     * AppBlock constructor.
     *
     * @param UiElementFactory $uiElementFactory
     * @param string $template
     */
    public function __construct(UiElementFactory $uiElementFactory, string $template)
    {
        $this->template = $template;
        $this->uiElementFactory = $uiElementFactory;
    }

    /**
     * @param BlockEvent $event
     */
    public function onBlockEvent(BlockEvent $event): void
    {
        $block = new Block();
        $block->setId(uniqid('', true));
        $block->setSettings(array_replace($event->getSettings(), [
            'template' => $this->template,
            'attr' => ['ui_elements' => $this->uiElementFactory->getUiElements(), 'debug' => false ]
        ]));
        $block->setType('sonata.block.service.template');

        $event->addBlock($block);
    }
}
