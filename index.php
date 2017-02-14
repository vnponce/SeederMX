<?php 
$file = "./entidad.sql";
$fh = fopen($file,'r+');

$entidades = [];

while(!feof($fh)) {
    $entidad = fgets($fh);
    $entidades[] = preg_replace('/INSERT INTO estados\(number_id, name, abbrev, country_id\) VALUES \((.+)\);/', "$1", $entidad);
}


$content = "<?php

use Illuminate\Database\Seeder;

class EntidadesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {";
foreach ($entidades as $e) {
	$aux = explode(',',$e);
	$content .= "
		factory(\App\Entidad::class)->create([
			'id' => $aux[0],
			'name' => $aux[1],
			'abbrev' => $aux[2],
			'country' => $aux[3]
		]);";
};

$content .="
    }
}
";

// using file_put_contents() instead of fwrite()
file_put_contents('./EntidadesTableSeeder.php', $content);

fclose($fh); 

echo "El factory de Estados ( model Entidad ) se encuentra en entidad.txt\r\n";

####
#### Municipios 
####

$file = "./municipio.sql";
$fh = fopen($file,'r+');

$municipios = [];

while(!feof($fh)) {
    $municipios[] = preg_replace('/INSERT INTO municipios\(estado_id, number, name, number_cab, name_cab\) VALUES \((.+)\);/', "$1", fgets($fh));
}


$content = "<?php

use Illuminate\Database\Seeder;

class MunicipiosTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {";
foreach ($municipios as $m) {
    $municipio = explode(',',$m);
    $content .= "
        factory(\App\Municipio::class)->create([
            'name' => $municipio[2],
            'entidad_id' => $municipio[0],
            'number' => $municipio[1],
        ]);";
};

$content .="
    }
}
";

// using file_put_contents() instead of fwrite()
file_put_contents('./MunicipiosTableSeeder.php', $content);

fclose($fh); 

echo "El factory de Municipios ( model Municipio ) se encuentra en entidad.txt";
