<?
use Goat1000\SVGGraph\BarGraph as SvgBarGraph;
use Goat1000\SVGGraph\SVGGraph;
use Goat1000\SVGGraph\Text as SvgText;

use function Safe\preg_match;
use function Safe\preg_split;

abstract class Graph{
	protected int $_Height = 480;

	protected int $_Width = 960;

	/** @var array<array<string, float|int>> */
	public array $Values = [];

	/** @var array<string> */
	public array $LegendEntries = [];

	/** @var array<string, bool|callable|float|int|string|array<string>> */
	public array $Settings = [
		'auto_fit' => true,
		'back_colour' => 'transparent',
		'back_stroke_width' => 0,
		'stroke_colour' => 'css-stroke-color', // Styled in `reports.css`.
		'show_tooltips' => false,

		'division_size_h' => 0, // Disable x-axis tick marks.

		// Configure horizontal grid lines
		'grid_colour_h' => 'transparent',
		'grid_colour' => 'css-grid-color', // Styled in `reports.css`.
		'grid_stroke_width' => .25,
		'minimum_units_y' => 1,

		'axis_font' => 'Roboto',
		'axis_font_size' => 16,
		'axis_text_angle_h' => 90,
		'axis_colour' => 'css-axis-color', // Styled in `reports.css`.
		'axis_stroke_width' => .5,

		'show_data_labels' => true,
		'data_label_font' => 'Roboto',
		'data_label_font_size' => 16,
		'data_label_colour' => 'css-data-label-color', // Styled in `reports.css`.
		'data_label_colour_outside' => 'css-data-label-color-outside', // Styled in `reports.css`.
		'data_label_filter' => 'nonzero', // Don't show zero labels.

		'legend_draggable' => false,
		'legend_back_colour' => 'transparent',
		'legend_stroke_width' => 0,
		'legend_shadow_opacity' => 0,
		'legend_font' => 'Roboto',
		'legend_font_size' => 16,
		'legend_entry_height' => 16,
		'legend_entry_width' => 16,
		'legend_spacing' => 2,
		'legend_position' => 'outer bottom center',
		'legend_colour' => 'css-legend-color', // Styled in `reports.css`. This must be set so we can use XPath to find the legend when rendering the graph.

		'minimum_grid_spacing' => 20,
		'pad_left' => 0,
		'pad_right' => 0,
		'pad_bottom' => 0,
		'pad_top' => 0,
	];

	/** @var array<string> */
	protected array $_Colors = ['css-color-1', 'css-color-2', 'css-color-3', 'css-color-4', 'css-color-5', 'css-color-6']; // Defined in `reports.css`.

	protected string $_GraphType = '';

	private DOMDocument $Dom;

	/**
	 * Render the graph as an SVG.
	 */
	public function Render(): string{
		if(sizeof($this->LegendEntries) > 0){
			$this->Settings['legend_entries'] = $this->LegendEntries;
		}

		$graph = new SVGGraph($this->_Width, $this->GetRealHeight(), $this->Settings);
		$graph->colours($this->_Colors);
		$graph->values($this->Values);

		$svg = $graph->fetch($this->_GraphType, false);
		$this->Dom = new DOMDocument();
		$this->Dom->loadXML($svg);

		$this->FixLegendStyles();
		$this->MoveLegendBelowText();
		$this->FitViewBoxToText();

		$svgElement = $this->Dom->documentElement;

		if($svgElement === null){
			return $svg;
		}

		// We have to pass `$svgElement` to prevent it from outputting the XML header, which is not desirable in an inline SVG.
		return $this->Dom->saveXML($svgElement) ?: $svg;
	}

	/**
	 * Calculate the actual height of the graph, accounting for rotated horizontal axis labels.
	 *
	 * SVGGraph auto-fits axis labels by adding padding within the graph’s fixed height. Very long rotated horizontal axis labels can exceed the available height, causing the plot area to be pushed outside of the viewbox. When labels are rotated, add extra height based on the tallest measured label while preserving the default height for ordinary labels.
	 */
	private function GetRealHeight(): int{
		$angleSetting = $this->Settings['axis_text_angle_h'] ?? 0;
		$angle = is_numeric($angleSetting) ? (float)$angleSetting : 0;

		if(abs($angle) % 180 == 0){
			return $this->_Height;
		}

		$labelHeight = $this->GetTallestHorizontalAxisLabelHeight($angle);
		$labelHeightBudget = floor($this->_Height / 2);
		$extraHeight = (int)max(0, ceil($labelHeight) - $labelHeightBudget);

		return $this->_Height + $extraHeight;
	}

	/**
	 * Measure the tallest horizontal axis label using SVGGraph's text metrics.
	 */
	private function GetTallestHorizontalAxisLabelHeight(float $angle): float{
		$graph = new SvgBarGraph($this->_Width, $this->_Height, $this->Settings);
		$fontSetting = $this->Settings['axis_font'] ?? null;
		$fontSizeSetting = $this->Settings['axis_font_size'] ?? 16;
		$text = new SvgText($graph, is_string($fontSetting) ? $fontSetting : null);
		$fontSize = is_numeric($fontSizeSetting) ? (float)$fontSizeSetting : 16;
		$labelCallback = $this->Settings['axis_text_callback_h'] ?? null;
		$maxHeight = 0;

		foreach($this->Values as $dataset){
			$index = 0;

			foreach($dataset as $key => $value){
				$label = (string)$key;

				if(is_callable($labelCallback)){
					$label = (string)$labelCallback($index, $key);
				}

				[, $height] = $text->measure($label, $fontSize, $angle);
				$maxHeight = max($maxHeight, $height);
				$index++;
			}
		}

		return $maxHeight;
	}

	/**
	 * Move the legend below any graph text, so it doesn't overlap rotated axis labels. SVGGraph overlaps it with labels by default.
	 */
	protected function MoveLegendBelowText(): void{
		$svgElement = $this->Dom->documentElement;
		$legend = $this->GetLegendElement();

		if($svgElement === null || !$svgElement->hasAttribute('viewBox') || $legend === null){
			return;
		}

		[$graph, $defaultFont, $defaultFontSize] = $this->GetSvgTextMeasuringContext($svgElement);
		$xpath = new DOMXPath($this->Dom);
		$xpath->registerNamespace('svg', 'http://www.w3.org/2000/svg');
		$labelTextElements = $xpath->query('//svg:text[not(ancestor::svg:g[@fill="css-legend-color"])]');
		$legendTextElements = $xpath->query('.//svg:text', $legend);
		$labelMaxY = null;
		$legendMinY = null;

		if($labelTextElements === false || $legendTextElements === false){
			return;
		}

		foreach($labelTextElements as $textElement){
			if(!$textElement instanceof DOMElement){
				continue;
			}

			[, $textY, , $textHeight] = $this->GetTextElementBounds($textElement, $graph, $defaultFont, $defaultFontSize);
			$labelMaxY = max($labelMaxY ?? $textY + $textHeight, $textY + $textHeight);
		}

		foreach($legendTextElements as $textElement){
			if(!$textElement instanceof DOMElement){
				continue;
			}

			[, $textY] = $this->GetTextElementBounds($textElement, $graph, $defaultFont, $defaultFontSize);
			$legendMinY = min($legendMinY ?? $textY, $textY);
		}

		if($labelMaxY === null || $legendMinY === null){
			return;
		}

		$legendTopMargin = 16;
		$overlap = $labelMaxY + $legendTopMargin - $legendMinY;

		if($overlap <= 0){
			return;
		}

		[$legendX, $legendY] = $this->GetElementTranslate($legend);
		$legend->setAttribute('transform', 'translate(' . $legendX . ' ' . ($legendY + $overlap) . ')');
	}

	/**
	 * Expand the SVG viewbox to fit all rendered `<text>` elements, because SVGGraph can output graphs where labels are outside of the SVG viewbox.
	 */
	private function FitViewBoxToText(): void{
		$svgElement = $this->Dom->documentElement;

		if($svgElement === null){
			return;
		}

		$viewBox = preg_split('/\\s+/u', trim($svgElement->getAttribute('viewBox')));

		if(sizeof($viewBox) != 4){
			return;
		}

		[$minX, $minY, $width, $height] = array_map('floatval', $viewBox);
		$maxX = $minX + $width;
		$maxY = $minY + $height;
		[$graph, $defaultFont, $defaultFontSize] = $this->GetSvgTextMeasuringContext($svgElement);

		foreach($svgElement->getElementsByTagName('text') as $textElement){
			[$textX, $textY, $textWidth, $textHeight] = $this->GetTextElementBounds($textElement, $graph, $defaultFont, $defaultFontSize);

			$minX = min($minX, floor($textX) - 1);
			$minY = min($minY, floor($textY) - 1);
			$maxX = max($maxX, ceil($textX + $textWidth) + 1);
			$maxY = max($maxY, ceil($textY + $textHeight) + 1);
		}

		$svgElement->setAttribute('viewBox', implode(' ', [$minX, $minY, $maxX - $minX, $maxY - $minY]));
	}

	/**
	 * SVGGraph sets the colors of the legend using `@style` attributes, instead of `@fill` attributes. This function replaces those styles with `@fill`.
	 */
	private function FixLegendStyles(): void{
		$legend = $this->GetLegendElement();

		if($legend === null){
			return;
		}

		foreach($legend->getElementsByTagName('*') as $element){
			$declarations = explode(';', $element->getAttribute('style'));
			$styles = [];
			$fill = null;

			foreach($declarations as $declaration){
				$declarationParts = explode(':', $declaration, 2);

				if(sizeof($declarationParts) != 2){
					continue;
				}

				$property = trim($declarationParts[0]);
				$value = trim($declarationParts[1]);

				if($property == 'fill'){
					$fill = $value;
					continue;
				}

				$styles[] = $property . ': ' . $value;
			}

			if($fill === null){
				continue;
			}

			$element->setAttribute('fill', $fill);
			$styles = implode('; ', $styles);

			if($styles == ''){
				$element->removeAttribute('style');
			}
			else{
				$element->setAttribute('style', $styles);
			}
		}
	}

	/**
	 * Get the SVGGraph text measuring context.
	 *
	 * @return array{0: SvgBarGraph, 1: ?string, 2: float}
	 */
	private function GetSvgTextMeasuringContext(DOMElement $svgElement): array{
		$viewBox = preg_split('/\\s+/u', trim($svgElement->getAttribute('viewBox')));
		$height = isset($viewBox[3]) ? (int)$viewBox[3] : $this->_Height;
		$graph = new SvgBarGraph($this->_Width, $height, $this->Settings);
		$fontSizeSetting = $this->Settings['axis_font_size'] ?? 16;
		$defaultFontSize = is_numeric($fontSizeSetting) ? (float)$fontSizeSetting : 16;
		$fontSetting = $this->Settings['axis_font'] ?? null;
		$defaultFont = is_string($fontSetting) ? $fontSetting : null;

		return [$graph, $defaultFont, $defaultFontSize];
	}

	/**
	 * Get the bounds of a rendered SVG `<text>` element.
	 *
	 * @return array{0: float, 1: float, 2: float, 3: float}
	 */
	private function GetTextElementBounds(DOMElement $textElement, SvgBarGraph $graph, ?string $defaultFont, float $defaultFontSize): array{
		if(!$textElement->hasAttribute('x') || !$textElement->hasAttribute('y')){
			return [0, 0, 0, 0];
		}

		$text = new SvgText($graph, $textElement->getAttribute('font-family') ?: $defaultFont);
		$fontSize = $this->GetAttributeFloatValue($textElement, 'font-size', $defaultFontSize);
		$x = $this->GetAttributeFloatValue($textElement, 'x', 0);
		$y = $this->GetAttributeFloatValue($textElement, 'y', 0);
		$anchor = $textElement->getAttribute('text-anchor') ?: 'start';
		[$angle, $rotationX, $rotationY] = $this->GetElementRotate($textElement);
		[$textX, $textY, $textWidth, $textHeight] = $text->measurePosition($textElement->textContent, $fontSize, 0, $x, $y, $anchor, $angle, $rotationX, $rotationY);
		[$offsetX, $offsetY] = $this->GetElementTotalTranslate($textElement);

		return [$textX + $offsetX, $textY + $offsetY, $textWidth, $textHeight];
	}

	/**
	 * Get the numeric value of the given attribute on the given element.
	 */
	private function GetAttributeFloatValue(DOMElement $element, string $attribute, float $default): float{
		if(!$element->hasAttribute($attribute)){
			return $default;
		}

		preg_match('/-?\\d+(?:\\.\\d+)?/u', $element->getAttribute($attribute), $matches);

		return (float)($matches[0] ?? $default);
	}

	/**
	 * Get the element representing the graph legend.
	 */
	protected function GetLegendElement(): ?DOMElement{
		$xpath = new DOMXPath($this->Dom);
		$xpath->registerNamespace('svg', 'http://www.w3.org/2000/svg');
		$legendNodes = $xpath->query('//svg:g[@fill="css-legend-color"]');

		if($legendNodes === false || sizeof($legendNodes) == 0){
			return null;
		}

		$legend = $legendNodes->item(0);

		return $legend instanceof DOMElement ? $legend : null;
	}

	/**
	 * Get the cumulative parent translate transform for a `<text>` element.
	 *
	 * @return array{0: float, 1: float}
	 */
	private function GetElementTotalTranslate(DOMElement $element): array{
		$offsetX = 0;
		$offsetY = 0;
		$parent = $element->parentNode;

		while($parent instanceof DOMElement){
			[$translateX, $translateY] = $this->GetElementTranslate($parent);
			$offsetX += $translateX;
			$offsetY += $translateY;
			$parent = $parent->parentNode;
		}

		return [
			$offsetX,
			$offsetY
		];
	}

	/**
	 * Get text rotation data from an SVG `@transform` attribute.
	 *
	 * @return array{0: float, 1: float, 2: float}
	 */
	private function GetElementRotate(DOMElement $element): array{
		preg_match('/rotate\\((-?\\d+(?:\\.\\d+)?)(?:\\s+(-?\\d+(?:\\.\\d+)?)\\s+(-?\\d+(?:\\.\\d+)?))?\\)/u', $element->getAttribute('transform'), $matches);

		if(!isset($matches[1])){
			return [0, 0, 0];
		}

		return [
			(float)$matches[1],
			(float)($matches[2] ?? 0),
			(float)($matches[3] ?? 0)
		];
	}

	/**
	 * Get the translate values from an SVG element's `@transform` attribute.
	 *
	 * @return array{0: float, 1: float}
	 */
	private function GetElementTranslate(DOMElement $element): array{
		preg_match('/translate\\((-?\\d+(?:\\.\\d+)?)(?:[\\s,]+(-?\\d+(?:\\.\\d+)?))?\\)/u', $element->getAttribute('transform'), $matches);

		if(!isset($matches[1])){
			return [0, 0];
		}

		return [
			(float)$matches[1],
			(float)($matches[2] ?? 0)
		];
	}
}
