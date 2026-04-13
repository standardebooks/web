<?

class StackedBarGraph extends BarGraph{
	protected string $_GraphType = 'StackedBarGraph';

	/** @var array<string, bool|callable|float|int|string> */
	public array $_StackedBarGraphSettings = [
		'data_label_angle' => 90,
	];

	/** @var array<string> */
	protected array $_Colors = ['css-color-1', 'css-color-2', 'css-color-3', 'css-color-4', 'css-color-5', 'css-color-6']; // Defined in `reports.css`.

	public function __construct(){
		parent::__construct();
		$this->Settings = array_merge($this->Settings, $this->_StackedBarGraphSettings);
	}

	public function Render(): string{
		if(sizeof($this->Values[0]) > 36){
			$this->Settings['show_bar_totals'] = false;
			$this->Settings['show_data_labels'] = false;
		}

		return parent::Render();
	}
}
