<?php

class Module
{
	protected $messages = array();

	public function AddDiagnostic(bool $success, string $msg)
	{
		array_push($this->messages, array($success, $msg));
	}

	public function FlushDiagnostics()
	{
		$this->messages = array();
	}

	public function DisplayDiagnostics()
	{
		?>
		<style>
			.mfdiagnostic_banner {
				background-color: orangered;
				width: 100%;
				height: 200px;
			}

			.mfdiagnostic_error {
				background-color: red;
				width: 100%;
			}

			.mfdiagnostics_success {
				background-color: green;
				width: 100%;
			}
		</style>
		<h1 class="mfdiagnostic_banner">MF Module Diagnostics</h1>
		
		<?php
			foreach ($this->messages as $item) {
				if ($item[0])
				{
					?>
					<p class="mfdiagnostics_success">
						<?php echo $item[1]; ?>
					</p>
					<?php
				} else
				{
					?>
					<p class="mfdiagnostics_error">
						<?php echo $item[1]; ?>
					</p>
					<?php
				}
			}
		$this->FlushDiagnostics();
	}
}

?>
