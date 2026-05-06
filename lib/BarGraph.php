<?

class BarGraph extends Graph{
	protected string $_GraphType = 'BarGraph';

	/** @var array<string, bool|callable|float|int|string> */
	protected array $_BarGraphSettings = [
		'bar_space' => 8,

		'show_bar_totals' => true,
		'bar_total_font_weight' => 'bold',
		'bar_total_font' => 'Roboto',
		'bar_total_font_size' => 16,
		'data_label_angle' => 90,

		'pad_top' => 60, // Make room for totals at top of the bar.
	];

	public function __construct(){
		$this->Settings = array_merge($this->Settings, $this->_BarGraphSettings);
	}

	/** @var array<string> */
	protected array $_Colors = ['css-color-1'];

	/**
	 * Render the bar graph as an SVG.
	 */
	public function Render(): string{
		if(sizeof($this->Values[0]) > 48){
			$this->Settings['show_bar_totals'] = false;
			$this->Settings['pad_top'] = 0;
			$this->Settings['show_data_labels'] = false;
		}

		$originalLabelAngle = $this->Settings['data_label_angle'] ?? null;
		$this->Settings['data_label_angle'] = 90;
		$svg = parent::Render();

		if($originalLabelAngle === null){
			unset($this->Settings['data_label_angle']);
		}
		else{
			$this->Settings['data_label_angle'] = $originalLabelAngle;
		}

		return $this->UnrotateDataLabelsThatFit();
	}

	/**
	 * Render data labels horizontally when their text fits within the bar width.
	 */
	private function UnrotateDataLabelsThatFit(): string{
		$normalSettings = $this->Settings;
		$normalSettings['data_label_angle'] = 0;
		$normalDom = $this->RenderSvgDomWithSettings($normalSettings);
		$svgElement = $this->Dom->documentElement;
		$normalSvgElement = $normalDom->documentElement;

		if($svgElement === null || $normalSvgElement === null){
			return $this->OutputDom();
		}

		$rotatedLabels = $this->GetTextElementsByFillPrefix($this->Dom, 'css-data-label-color');
		$normalLabels = $this->GetTextElementsByFillPrefix($normalDom, 'css-data-label-color');
		[$graph, $defaultFont, $defaultFontSize] = $this->GetSvgTextMeasuringContext($normalSvgElement);

		foreach($rotatedLabels as $index => $rotatedLabel){
			$normalLabel = $normalLabels[$index] ?? null;

			if($normalLabel === null){
				continue;
			}

			$barWidth = $this->GetBarWidthForDataLabel($this->Dom, $rotatedLabel);

			if($barWidth === null){
				continue;
			}

			[, , $normalLabelWidth] = $this->GetTextElementBounds($normalLabel, $graph, $defaultFont, $defaultFontSize);

			if($normalLabelWidth > $barWidth){
				continue;
			}

			// Copy text positioning attributes from one SVG text element to another.
			$rotatedLabel->setAttribute('x', $normalLabel->getAttribute('x'));
			$rotatedLabel->setAttribute('y', $normalLabel->getAttribute('y'));
			$rotatedLabel->setAttribute('text-anchor', $normalLabel->getAttribute('text-anchor'));
			$rotatedLabel->removeAttribute('transform');
		}

		return $this->OutputDom();
	}

	/**
	 * Get the width of the bar associated with a data label.
	 */
	private function GetBarWidthForDataLabel(DOMDocument $dom, DOMElement $textElement): ?float{
		$textX = $this->GetAttributeFloatValue($textElement, 'x', 0);

		foreach($dom->getElementsByTagName('rect') as $rect){
			if(!in_array($rect->getAttribute('fill'), $this->_Colors, true) || !$rect->hasAttribute('x') || !$rect->hasAttribute('width')){
				continue;
			}

			$rectX = $this->GetAttributeFloatValue($rect, 'x', 0);
			$rectWidth = $this->GetAttributeFloatValue($rect, 'width', 0);

			if($textX >= $rectX && $textX <= $rectX + $rectWidth){
				return $rectWidth;
			}
		}

		return null;
	}
}
