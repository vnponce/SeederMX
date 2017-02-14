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
file_put_contents('./entidad.txt', $content);

fclose($fh); 