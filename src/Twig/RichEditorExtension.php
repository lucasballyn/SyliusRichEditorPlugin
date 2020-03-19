<?php

declare(strict_types=1);

namespace MonsieurBiz\SyliusRichEditorPlugin\Twig;

use MonsieurBiz\SyliusRichEditorPlugin\Event\RenderUiElementEvent;
use MonsieurBiz\SyliusRichEditorPlugin\Exception\UndefinedUiElementTypeException;
use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;
use MonsieurBiz\SyliusRichEditorPlugin\UiElement\Factory;
use Twig\Environment;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

final class RichEditorExtension extends AbstractExtension
{
    /**
     * @var Factory
     */
    private $factory;

    /**
     * @var Environment
     */
    private $twig;

    /**
     * @var EventDispatcherInterface
     */
    private $eventDispatcher;

    public function __construct(Factory $factory, Environment $twig, EventDispatcherInterface $eventDispatcher)
    {
        $this->factory = $factory;
        $this->twig = $twig;
        $this->eventDispatcher = $eventDispatcher;
    }

    public function getFilters()
    {
        return [
            new TwigFilter('mbiz_rich_editor_render', [$this, 'renderRichEditorField'], ['is_safe' => ['html']]),
        ];
    }

    /**
     * @param string $content
     *
     * @return string
     * @throws \Twig\Error\LoaderError
     * @throws \Twig\Error\RuntimeError
     * @throws \Twig\Error\SyntaxError
     */
    public function renderRichEditorField(string $content)
    {
        $elements = json_decode($content, true);
        if ($elements === false) {
            return $content;
        }

        $html = '';
        foreach ($elements as $element) {
            if (!isset($element['type'])) {
                continue;
            }

            try {
                $uiElement = $this->factory->getUiElementByType($element['type']);
            } catch (UndefinedUiElementTypeException $exception) {
                continue;
            }

            $template = $uiElement->getTemplate();

            $event = new RenderUiElementEvent($uiElement, $element);
            $this->eventDispatcher->dispatch($event);
            $element = $event->getElement();

            $html .= $this->twig->render($template, [
                'uiElement' => $uiElement,
                'element' => $element['fields'],
            ]);
        }

        return $html;
    }
}
