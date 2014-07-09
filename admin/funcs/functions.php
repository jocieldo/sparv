<?php 

/*Função para conversão de segundos em horas no formato hh:mm:ss */


function convertTempo($number){

	//verificando a existênca da variável $number e logo após vejo se ela está vazia ou não utilizando a função empty()
	if(isset($number) and !empty($number)){

		$number = (int)$number;

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

function convertTimeSencod($hora, $minuto, $segundo){

	$hr = (int)$hora*3600;
	$mt = (int)$minuto*60;
	$sc = (int)$segundo;

	$totalTime = $hr + $mt + $sc;

	return $totalTime;
}

//Função para calcular idade baseado na data de nascimento
function calcIdade($data){ 

    // Declara a data! :P
    //Exemplo: $data = '29/08/2008';
    
    // Separa em dia, mês e ano
    list($dia, $mes, $ano) = explode('/', $data);
    
    // Descobre que dia é hoje e retorna a unix timestamp
    $hoje = mktime(0, 0, 0, date('m'), date('d'), date('Y'));
    // Descobre a unix timestamp da data de nascimento do fulano
    $nascimento = mktime( 0, 0, 0, $mes, $dia, $ano);
    
    // Depois apenas fazemos o cálculo já citado :)
    $idade = floor((((($hoje - $nascimento) / 60) / 60) / 24) / 365.25);
 
    return $idade;

}

/*Função pega um data no formato dd/mm/YYYY e transforma em YYYY/mm/dd*/

function retornaData($dataToConvertion){
		
		if (!empty($dataToConvertion)) {
			
			$dataStamp = explode("/", $dataToConvertion);

			$newDataStamp = $dataStamp[2]."-".$dataStamp[1]."-".$dataStamp[0];

			return $newDataStamp;

		}else{

			return null;

		}

	}
?>