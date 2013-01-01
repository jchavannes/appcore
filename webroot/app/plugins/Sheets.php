<?php

class Sheets {
	
	function displayArray($array) {
		if(!is_array($array)) return;
		echo "<sheet>";
			echo "<row>";
			foreach($array[0] as $name => $cell) {
				echo "<cell><b>".$name."</b></cell>";
			}
			echo "</row>";
		foreach($array as $row) {
			echo "<row>";
			foreach($row as $cell) {
				echo "<cell>".$cell."</cell>";
			}
			echo "</row>";
		}
		echo "</sheet>";
	}

}