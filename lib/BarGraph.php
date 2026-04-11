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

		'pad_top' => 60, // Make room for totals at top of the bar.
	];

	public function __construct(){
		$this->Settings = array_merge($this->Settings, $this->_BarGraphSettings);
	}

	/** @var array<string> */
	protected array $_Colors = ['css-color-1'];

	public function Render(): string{
		if(sizeof($this->Values[0]) > 48){
			$this->Settings['show_bar_totals'] = false;
			$this->Settings['show_data_labels'] = false;
			$this->Settings['pad_top'] = 0;
		}

		return parent::Render();
	}
}
