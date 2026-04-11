<?
use Goat1000\SVGGraph\SVGGraph;

abstract class Graph{
	/** @var array<array<string, float|int>> */
	public array $Values = [];

	/** @var array<string> */
	public array $LegendEntries = [];

	/** @var array<string, bool|callable|float|int|string> */
	public array $Settings = [
		'auto_fit' => true,
		'back_colour' => 'transparent',
		'back_stroke_width' => 0,
		'stroke_colour' => 'css-stroke-color', // Styled in `dashboard.css`.
		'show_tooltips' => false,

		'division_size_h' => 0, // Disable x-axis tick marks.

		// Configure horizontal grid lines
		'grid_colour_h' => 'transparent',
		'grid_colour' => 'css-grid-color', // Styled in `dashboard.css`.
		'grid_stroke_width' => .25,
		'minimum_units_y' => 1,

		'axis_font' => 'Roboto',
		'axis_font_size' => 16,
		'axis_text_angle_h' => 90,
		'axis_colour' => 'css-axis-color', // Styled in `dashboard.css`.
		'axis_stroke_width' => .5,

		'show_data_labels' => true,
		'data_label_font' => 'Roboto',
		'data_label_font_size' => 16,
		'data_label_colour' => 'css-data-label-color', // Styled in `dashboard.css`.
		'data_label_colour_outside' => 'css-data-label-color-outside', // Styled in `dashboard.css`.
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
		'legend_colour' => 'css-legend-color', // Styled in `dashboard.css`. This must be set so we can use XPath to find the legend when rendering the graph.

		'minimum_grid_spacing' => 20,
		'pad_left' => 0,
		'pad_right' => 0,
		'pad_bottom' => 0,
		'pad_top' => 0,
	];

	/** @var array<string> */
	protected array $_Colors = ['css-color-1', 'css-color-2', 'css-color-3', 'css-color-4', 'css-color-5', 'css-color-6']; // Defined in `dashboard.css`.

	protected string $_GraphType = '';

	public function Render(): string{
		$settings = $this->Settings;
		if(sizeof($this->LegendEntries) > 0){
			$settings['legend_entries'] = $this->LegendEntries;
		}

		$graph = new SVGGraph(960, 480, $settings);
		$graph->colours($this->_Colors);
		$graph->values($this->Values);

		return $this->FixLegendStyles($graph->fetch($this->_GraphType, false));
	}

	/**
	 * SVGGraph sets the colors of the legend using `@style` attributes, instead of `@fill` attributes. This function replaces those styles with `@fill`.
	 */
	protected function FixLegendStyles(string $svg): string{
		$dom = new DOMDocument();
		$dom->loadXML($svg);
		$svgElement = $dom->documentElement;

		if($svgElement === null){
			return $svg;
		}

		$xpath = new DOMXPath($dom);
		$xpath->registerNamespace('svg', 'http://www.w3.org/2000/svg');
		$legendNodes = $xpath->query('//svg:g[@fill="css-legend-color"]');

		if($legendNodes === false || sizeof($legendNodes) == 0){
			return $svg;
		}

		$legend = $legendNodes->item(0);

		if(!$legend instanceof DOMElement){
			return $svg;
		}

		foreach($legend->getElementsByTagName('*') as $element){
			if(!$element->hasAttribute('style')){
				continue;
			}

			$style = $element->getAttribute('style');

			$declarations = explode(';', $style);
			$style = [];
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

				$style[] = $property . ':' . $value;
			}

			if($fill === null){
				continue;
			}

			$element->setAttribute('fill', $fill);
			$style = implode(';', $style);

			if($style == ''){
				$element->removeAttribute('style');
			}
			else{
				$element->setAttribute('style', $style);
			}
		}

		return $dom->saveXML($svgElement) ?: $svg;
	}
}
