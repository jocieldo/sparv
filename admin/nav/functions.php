<?php 

/*Função para conversão de segundos em horas no formato hh:mm:ss */


function convertTempo($number){

	//verificando a existênca da variável $number e logo após vejo se ela está vazia ou não utilizando a função empty()
	if(isset($number) and !empty($number)){

		//calculando os segundos
		$s = floor(floor($number%3600)%60);

		//calculando minutos
		$m = floor(floor($number%3600)/60);

		//calculando horas
		$h = floor($number/3600);

		//logo abaixo usando três 'if' para formatar o tempo da seguinte forma 'hh:mm:ss'
		if($h < 10){ $h = "0".$h; }

		if ($m < 10) { $m = "0".$m; }
		
		if ($s < 10) { $s = "0".$s; }

	}

	//retornado o resultado final
	return $result = "$h:$m:$s";

}

?>