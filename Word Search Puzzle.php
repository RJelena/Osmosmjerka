<?php

class Osmosmjerka{

	public $imeIgraca, $brojPokusaja, $brojPogodenihRijeci, $gameOver, $errorMessage;
	public $poljeSlova, $popisRijeci;
	public $stanjeSlova; //matrica; za svako slovo upisana vrijednost -1(nije pogodeno dosad), 0(pogodeno dosad, ali nekad prije), 1(pogodeno zadnje)
	public $stanjeRijeci; //polje; za svaku rijec upisana vrijednost -1(nije pogodena dosad), 0(pogodena dosad, ali nekad prije), 1(pogodena zadnja)
	const N = 5, M = 6; //N = dimenzija osmosmjerke (tj. njezinog retka ili stupca); M = broj rijeci na popisu

	function __construct(){

		$this->imeIgraca = false;
		$this->brojPokusaja = 0;
		$this->gameOver = false;
		$this->errorMessage = false;

		$this->poljeSlova[0] = "PATKA";
		$this->poljeSlova[1] = "EISON";
		$this->poljeSlova[2] = "VPSLI";
		$this->poljeSlova[3] = "AAVMA";
		$this->poljeSlova[4] = "SLOVO";

		$this->popisRijeci[0] = "PATKA";
		$this->popisRijeci[1] = "PISMO";
		$this->popisRijeci[2] = "SAVE";
		$this->popisRijeci[3] = "NOSI";
		$this->popisRijeci[4] = "OSA";
		$this->popisRijeci[5] = "SLOVO";

		for($i = 0; $i < Osmosmjerka::M; $i++)
			$this->brojPogodenihRijeci[$i] = 0;

		for($i = 0; $i < Osmosmjerka::N; $i++)
			for($j = 0; $j < Osmosmjerka::N; $j++)
				$this->stanjeSlova[$i][$j] = -1;

		for($i = 0; $i < Osmosmjerka::M; $i++)
			$this->stanjeRijeci[$i] = -1;

	}


	// Ispisuje formu za unosenje imena igraca
	function formaIme(){
		?>

		<!DOCTYPE html>
		<html>
		<head>
			<meta charset="utf-8" />
			<title>Osmosmjerka</title>
			<style>body { background-color: <?php echo $boja;?>; }</style>
		</head>
		<body>
			<h1> Osmosmjerka! </h1>

			<form action="<?php echo htmlentities( $_SERVER['PHP_SELF']); ?>" method="post">
				<label for="imeIgraca">Unesi svoje ime:</label>
				<input type="text" name="imeIgraca" id="imeIgraca" value="" />

				<button type="submit">Započni igru!</button>
			</form>

			<?php if( $this->errorMessage !== false ) echo '<p>Greška: ' . htmlentities( $this->errorMessage ) . '</p>'; ?>
		</body>
		</html>

		<?php
	}


	// Ispisuje osmosmjerku (i sav ostali tekst iznad i ispod nje)
	function formaIgra(){
		?>

		<!DOCTYPE html>
		<html>
		<head>
			<meta charset="utf-8" />
			<title>Osmosmjerka</title>
			<style>
				table,td { border: 1px solid; }
				td { padding: 5px 10px; }
			</style>
		</head>
		<body>
			<h1> Osmosmjerka! </h1>

			<p> Igrač: <?php echo htmlentities( $this->imeIgraca ); ?> </p>
			<p> Broj pokušaja: <?php echo htmlentities( $this->brojPokusaja ); ?> </p>


			<table>
				<?php
				//idi po recima
				for($i = 0; $i < Osmosmjerka::N; $i++){
					echo '<tr>';
						//idi po stupcima
						for($j = 0; $j < Osmosmjerka::N; $j++){

							// Ako ovo polje nije nikad pogođeno, ostavi bijelo
							if( $this->stanjeSlova[$i][$j] === -1 )
								echo '<td>' . $this->poljeSlova[$i][$j] . '</td>';

							// Ako je ovo polje pogođeno nekad prije (ne zadnji put), obojaj ga žuto
							else if( $this->stanjeSlova[$i][$j] === 0 )
								echo '<td style="background-color: yellow">' . $this->poljeSlova[$i][$j] . '</td>';

							// Ako je ovo polje pogođeno zadnji put, obojaj ga zeleno
							else
								echo '<td style="background-color: green">' . $this->poljeSlova[$i][$j] . '</td>';
						}

					echo '</tr>';
				}
				?>
			</table>


			<br>

			<?php
				echo "Pronađi riječ s popisa:";
				for($i = 0; $i < Osmosmjerka::M; $i++){
					echo " ";

					// Ako rijec nije pogodena dosad, ostavi je normalno
					if( $this->stanjeRijeci[$i] === -1 )
						echo '<a>' . $this->popisRijeci[$i] . '</a>';

					// Ako je rijec pogodena nekad prije (ne u zadnjem pokusaju), obojaj žuto i precrtaj
					else if( $this->stanjeRijeci[$i] === 0 )
						echo '<a style="background-color: yellow"><strike>' . $this->popisRijeci[$i] . '</strike></a>';

					// Ako je rijec pogodena u zadnjem pokusaju, obojaj zeleno i precrtaj
					else
						echo '<a style="background-color: green"><strike>' . $this->popisRijeci[$i] . '</strike></a>';
				}
			?>

			<br><br>

			<form action="<?php echo htmlentities( $_SERVER['PHP_SELF']); ?>" method="post">
				<label for="rijec">Našao sam riječ:</label>
				<input type="text" name="rijec" id="rijec" value="" />

				<br>

				<label for="redak">Prvo slovo je u redu </label>
					<select name="redak" id="redak">
						<option selected="selected" value="1">1</option>
						<?php
							for($i = 2; $i <= Osmosmjerka::N; $i++){
								?>
								<option value=" <?php echo $i ?> "> <?php echo $i ?> </option>
								<?php
							}
						?>
					</select>

				<label for="stupac">i u stupcu </label>
					<select name="stupac" id="stupac">
						<option selected="selected" value="1">1</option>
						<?php
							for($i = 2; $i <= Osmosmjerka::N; $i++){
								?>
								<option value=" <?php echo $i ?> "> <?php echo $i ?> </option>
								<?php
							}
						?>
					</select>

				<br><br>

				<button type="submit" name="submit">Označi pronađenu riječ!</button>
				<br>
				<button type="submit" name="reset">Hoću sve ispočetka!</button>

			</form>

		</body>
		</html>
		<?php
	}


	// Provjerava je li unesena rijec na popisu i je li tocno uneseno njezino mjesto
	function isPogodena($i,$j,$pronadenaRijec){

	  // ... Prvo provjeri je li unesena rijec zaista na popisu
		$flag = 0;

		for($m = 0; $m < Osmosmjerka::M; $m++)
			if($this->popisRijeci[$m] === $pronadenaRijec)
				$flag = 1;

		if($flag === 0) return 0;



	  // ... Zatim provjeri je li unesena rijec na navedenom mjestu (za svaki slucaj je drugaciji return da bi nam kasnije pomogao u obradiFormu() )

		//  Trazi rijec VODORAVNO DESNO

		$string = '';
		// Spremi ponudenu rijec u string
		for($l = $j; $l < Osmosmjerka::N; $l++){
			$string .= $this->poljeSlova[$i][$l];
			if( strlen($string) === strlen($pronadenaRijec) ) break;
		}

		// Je li pronadenaRijec na tocnom mjestu? (poklapa li se sa stringom)
		if( $string === $pronadenaRijec ) return 1;



		// Trazi rijec VODORAVNO LIJEVO

		$string = '';
		// Spremi ponudenu rijec u string
		for($l = $j; $l >= 0; $l--){
			$string .= $this->poljeSlova[$i][$l];
			if( strlen($string) === strlen($pronadenaRijec) ) break;
		}
		// Je li pronadenaRijec na tocnom mjestu? (poklapa li se sa stringom)
		if( $string === $pronadenaRijec ) return 2;



		// Trazi rijec OKOMITO GORE

		$string = '';
		// Spremi ponudenu rijec u string
		for($k = $i; $k >= 0; $k--){
			$string .= $this->poljeSlova[$k][$j];
			if( strlen($string) === strlen($pronadenaRijec) ) break;
		}
		// Je li pronadenaRijec na tocnom mjestu? (poklapa li se sa stringom)
		if( $string === $pronadenaRijec ) return 3;



		// Trazi rijec OKOMITO DOLJE

		$string = '';
		// Spremi ponudenu rijec u string
		for($k = $i; $k < Osmosmjerka::N; $k++){
			$string .= $this->poljeSlova[$k][$j];
			if( strlen($string) === strlen($pronadenaRijec) ) break;
		}
		// Je li pronadenaRijec na tocnom mjestu? (poklapa li se sa stringom)
		if( $string === $pronadenaRijec ) return 4;



		// Trazi rijec DIJAGONALNO GORE DESNO

		$string = '';
		// Spremi ponudenu rijec u string
		$k = $i; $l = $j;
		while($k >= 0 && $l < Osmosmjerka::N){
			$string .= $this->poljeSlova[$k][$l];
			if( strlen($string) === strlen($pronadenaRijec) ) break;
			$k--; $l++;
		}
		// Je li pronadenaRijec na tocnom mjestu? (poklapa li se sa stringom)
		if( $string === $pronadenaRijec ) return 5;



		// Trazi rijec DIJAGONALNO DOLJE DESNO

		$string = '';
		// Spremi ponudenu rijec u string
		$k = $i; $l = $j;
		while($k < Osmosmjerka::N && $l < Osmosmjerka::N){
			$string .= $this->poljeSlova[$k][$l];
			if( strlen($string) === strlen($pronadenaRijec) ) break;
			$k++; $l++;
		}
		// Je li pronadenaRijec na tocnom mjestu? (poklapa li se sa stringom)
		if( $string === $pronadenaRijec ) return 6;



		// Trazi rijec DIJAGONALNO DOLJE LIJEVO

		$string = '';
		// Spremi ponudenu rijec u string
		$k = $i; $l = $j;
		while($k < Osmosmjerka::N && $l >= 0){
			$string .= $this->poljeSlova[$k][$l];
			if( strlen($string) === strlen($pronadenaRijec) ) break;
			$k++; $l--;
		}
		// Je li pronadenaRijec na tocnom mjestu? (poklapa li se sa stringom)
		if( $string === $pronadenaRijec ) return 7;



		// Trazi rijec DIJAGONALNO GORE LIJEVO

		$string = '';
		// Spremi ponudenu rijec u string
		$k = $i; $l = $j;
		while($k >= 0 && $l >= 0){
			$string .= $this->poljeSlova[$k][$l];
			if( strlen($string) === strlen($pronadenaRijec) ) break;
			$k--; $l--;
		}
		// Je li pronadenaRijec na tocnom mjestu? (poklapa li se sa stringom)
		if( $string === $pronadenaRijec ) return 8;


		return 0;
	}


	// Obradi jedan igracev potez
	function obradiFormu(){

		// Ako je igrac kliknuo "Hocu sve ispocetka!"
		if( isset($_POST['reset']) ){

			$this->brojPokusaja = 0;
			$this->gameOver = false;
			$this->errorMessage = false;

			for($i = 0; $i < Osmosmjerka::M; $i++)
				$this->brojPogodenihRijeci[$i] = 0;

			for($i = 0; $i < Osmosmjerka::N; $i++)
				for($j = 0; $j < Osmosmjerka::N; $j++)
					$this->stanjeSlova[$i][$j] = -1;

			for($i = 0; $i < Osmosmjerka::M; $i++)
				$this->stanjeRijeci[$i] = -1;

		}

		// Ako je igrac kliknuo "Oznaci pronadenu rijec!"
		else if( isset($_POST['submit']) ){

			// ... Povecaj broj pokusaja
			$this->brojPokusaja++;



			// ... Spremi podatke koje je unio igrac - redak, stupac i unesenu rijec
			$i = $_POST['redak'] - 1; // poljeSlova je indeksirano od 0 pa oduzimamo 1
			$j = $_POST['stupac'] - 1;
			$pronadenaRijec = $_POST['rijec'];


			// ... Provjeri je li pronadena rijec pogodena ili nije
			$pogodak = $this->isPogodena($i,$j,$pronadenaRijec);

			// Ako se unesena rijec ne sastoji od slova engleske abecede
			if( !preg_match( '/^[a-zA-Z]+$/', $_POST['rijec'] ) )
				echo '<a style="font-size: 30px; color: red">' .
						'Unesena riječ se mora sastojati od jednog ili više slova engleske abecede!' .
						'</a>';

			// Ili ako pronadenaRijec nije dobro pogodena
			else if( $pogodak === 0 )
				echo '<a style="font-size: 30px; color: red">Nije pogođena riječ!</a>';

			// Ili ako je pronadenaRijec dobro pogodena
			else{

				// Povecaj broj pogodenih rijeci
				for($z = 0; $z < Osmosmjerka::M; $z++)
					if( $this->popisRijeci[$z] === $pronadenaRijec )
						$this->brojPogodenihRijeci[$z] = 1;

				// Onu rijec koja je dosada bila zadnja pogodena, sada promijeni u "zuto" jer dolazi nova koja ce biti zelena
				for($k = 0; $k < Osmosmjerka::M; $k++)
					if( $this->stanjeRijeci[$k] === 1 )
						$this->stanjeRijeci[$k] = 0;

				// Promijeni stanjeRijeci (da tu rijec sad oboji zeleno sljedece)
				for($k = 0; $k < Osmosmjerka::M; $k++)
					if( $pronadenaRijec === $this->popisRijeci[$k] )
						$this->stanjeRijeci[$k] = 1;

				// Ona slova u osmosmjerci koja su dosada bila zadnja pogodena, sada promijeni u "zuto" jer dolaze nova koja ce biti zelena
				for($k = 0; $k < Osmosmjerka::N; $k++)
					for($l = 0; $l < Osmosmjerka::N; $l++)
						if( $this->stanjeSlova[$k][$l] === 1 )
							$this->stanjeSlova[$k][$l] = 0;

				// Promijeni $stanjeSlova (nova slova oboji zeleno)
				// Pomocu varijable $pogodak (tj. funkcije isPogodena() ) znamo u kojem smjeru ide rijec u osmosmjerci (za to sluzi niz sljedecih if-ova)
				$brojac = 0; //brojac cuva broj obojenih slova

				if( $pogodak === 1 ) // Vodoravno desno
					for($l = $j; $l < Osmosmjerka::N; $l++){
						$this->stanjeSlova[$i][$l] = 1;
						$brojac++;
						if( $brojac === strlen($pronadenaRijec) ) break;
					}


				else if( $pogodak === 2 ) // Vodoravno lijevo
					for($l = $j; $l >= 0; $l--){
						$this->stanjeSlova[$i][$l] = 1;
						$brojac++;
						if( $brojac === strlen($pronadenaRijec) ) break;
					}


				else if( $pogodak === 3 ) // Okomito gore
					for($k = $i; $k >= 0; $k--){
						$this->stanjeSlova[$k][$j] = 1;
						$brojac++;
						if( $brojac === strlen($pronadenaRijec) ) break;
					}


				else if( $pogodak === 4 ) // Okomito dolje
					for($k = $i; $k < Osmosmjerka::N; $k++){
						$this->stanjeSlova[$k][$j] = 1;
						$brojac++;
						if( $brojac === strlen($pronadenaRijec) ) break;
					}


				else if( $pogodak === 5 ){ // Dijagonalno gore desno
					$k = $i; $l = $j;
					while( $k >= 0 && $l < Osmosmjerka::N ){
						$this->stanjeSlova[$k][$l] = 1;
						$brojac++;
						if( $brojac === strlen($pronadenaRijec) ) break;
						$k--; $l++;
					}
				}

				else if( $pogodak === 6 ){ // Dijagonalno dolje desno
					$k = $i; $l = $j;
					while( $k < Osmosmjerka::N && $l < Osmosmjerka::N ){
						$this->stanjeSlova[$k][$l] = 1;
						$brojac++;
						if( $brojac === strlen($pronadenaRijec) ) break;
						$k++; $l++;
					}
				}

				else if( $pogodak === 7 ){ // Dijagonalno dolje lijevo
					$k = $i; $l = $j;
					while( $k < Osmosmjerka::N && $l >= 0 ){
						$this->stanjeSlova[$k][$l] = 1;
						$brojac++;
						if( $brojac === strlen($pronadenaRijec) ) break;
						$k++; $l--;
					}
				}

				else{ // Dijagonalno gore lijevo
					$k = $i; $l = $j;
					while( $k >= 0 && $l >= 0 ){
						$this->stanjeSlova[$k][$l] = 1;
						$brojac++;
						if( $brojac === strlen($pronadenaRijec) ) break;
						$k--; $l--;
					}
				}


			}
		}
	}


	function isGameOver(){
		return $this->gameOver;
	}


	function cestitka(){

		?>

		<!DOCTYPE html>
		<html>
		<head>
			<meta charset="utf-8">
			<title>Osmosmjerka</title>
		</head>
		<body>
			<h1> Osmosmjerka! </h1>
			</br>
			<p>
				Bravo, <?php echo htmlentities( $this->imeIgraca ); ?>!
				<br />
				Završio si igru nakon <?php echo $this->brojPokusaja; ?> pokušaja!
			</p>
		</body>
		</html>

		<?php

	}


	function getImeIgraca(){
		// Ako je vec prije uneseno imeIgraca
		if( $this->imeIgraca !== false )
			return $this->imeIgraca;

		// Ako nismo imali imeIgraca dosad, ali upravo se unijelo
		if( isset( $_POST['imeIgraca'] ) ){

			// Ako se imeIgraca ne sastoji samo od slova engleske abecede, onda ipak nemamo imeIgraca
			if( !preg_match( '/^[a-zA-Z]+$/', $_POST['imeIgraca'] ) ){
				$this->errorMessage = 'Ime igrača nije dobro!';
				return false;
			}

			// Ako je dobro uneseno imeIgraca, onda ga spremamo
			else{
				$this->imeIgraca = $_POST['imeIgraca'];
				return $this->imeIgraca;
			}

		}

		// Ako nemamo ime
		return false;
	}


	function pokreniIgru(){

		// Ako nije uneseno imeIgraca
		if( $this->getImeIgraca() === false ){
			$this->formaIme();
			return;
		}

		// Ako je uneseno imeIgraca, obavi sve ispod
		$this->obradiFormu(); // obradi potez

		$brojac = 0; // broji koliko je pogodenih rijeci
		for($i = 0; $i < Osmosmjerka::M; $i++)
			if( $this->brojPogodenihRijeci[$i] === 1 )
				$brojac++;

		if( $brojac === Osmosmjerka::M ){ // ako je broj pogodenih rijeci potpun, isprintaj cestitku i zavrsi igru
			$this->cestitka();
			$this->gameOver = true;
		}

		else // ako broj rijeci nije potpun, samo ispisi osmosmjerku za sljedeci potez
			$this->formaIgra(); // ispisi osmosmjerku

	}


};


///////////////////////////////////////////////////////////////////////////////////////


session_start();

// Ako ne postoji zapoceta igra, stvori novu
if( !isset( $_SESSION['igra'] ) ){
	$igra = new Osmosmjerka();
	$_SESSION['igra'] = $igra;
}

// Ako postoji zapoceta igra, "aktiviraj" ju
else
	$igra = $_SESSION['igra'];


// Pokreni igru tamo gdje je stala
$igra->pokreniIgru();

// Ako je igra gotova, unisti sesiju da bi se mogla pokrenuti nova
if( $igra->isGameOver() ){
	session_unset();
	session_destroy();
}

// Ako igra nije gotova, spremi njezino stanje u sesiju da bi se mogla pokrenuti tamo gdje je stala
else
	$_SESSION['igra'] = $igra;


?>
