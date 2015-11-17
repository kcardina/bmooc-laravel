<?php

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;
use App\Artefact;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Model::unguard();

        // $this->call(UserTableSeeder::class);
		$this->call('ArtefactSeeder');
        $this->command->info('Artefact app seeds finished.');

        Model::reguard();
    }
}

class ArtefactSeeder extends Seeder
{
	public function run()
	{
		DB::table('artefact_types')->delete();
		DB::table('artefacts_tags')->delete();
		DB::table('artefacts')->delete();
		DB::table('instructions_artefact_types')->delete();
		DB::table('instructions')->delete();
		DB::table('tags')->delete();
		DB::table('users')->delete();
		
		$kcardina = DB::table('users')->insertGetId(['name' => 'Kris Cardinaels','email' => 'kris.cardinaels@pandora.be','password' => bcrypt('secret'), 'username' => 'kcardina', 'provider_id'=>str_random(25), 'role'=>'editor']);
		$thomas = DB::table('users')->insertGetId(['name' => 'Thomas Storme','email' => 'thomas.storme@luca-arts.be','password' => bcrypt('secret'), 'username' => 'tstorme', 'provider_id'=>str_random(25), 'role'=>'editor']);

		$tag1 = DB::table('tags')->insertGetId(['tag' => str_random(7)]);
		$tag2 = DB::table('tags')->insertGetId(['tag' => str_random(7)]);
		$tag3 = DB::table('tags')->insertGetId(['tag' => str_random(7)]);
		$tag4 = DB::table('tags')->insertGetId(['tag' => str_random(7)]);
		$tag5 = DB::table('tags')->insertGetId(['tag' => str_random(7)]);
		$tag6 = DB::table('tags')->insertGetId(['tag' => str_random(7)]);
		
		$type1 = DB::table('artefact_types')->insertGetId(['description' => 'text']);
		$type2 = DB::table('artefact_types')->insertGetId(['description' => 'local_image']);
		$type3 = DB::table('artefact_types')->insertGetId(['description' => 'remote_image']);
		$type4 = DB::table('artefact_types')->insertGetId(['description' => 'video_youtube']);
		$type5 = DB::table('artefact_types')->insertGetId(['description' => 'video_vimeo']);
		$type6 = DB::table('artefact_types')->insertGetId(['description' => 'local_pdf']);
		$type7 = DB::table('artefact_types')->insertGetId(['description' => 'remote_pdf']);
		$type8 = DB::table('artefact_types')->insertGetId(['description' => 'local_document']);
		$type9 = DB::table('artefact_types')->insertGetId(['description' => 'remote_document']);
		
		$i1 = DB::table('instructions')->insertGetId([
			'thread' => 1,
			'active_from' => '2015-10-20 10:00:00',
			'author' => 'kcardina',
			'instruction_type' => $type1,
			'title' => 'Cabaret'
		]);
		
		DB::table('instructions_artefact_types')->insert(['instruction_id' => $i1,'artefact_type_id' => $type1]);
		DB::table('instructions_artefact_types')->insert(['instruction_id' => $i1,'artefact_type_id' => $type2]);
		DB::table('instructions_artefact_types')->insert(['instruction_id' => $i1,'artefact_type_id' => $type3]);
		DB::table('instructions_artefact_types')->insert(['instruction_id' => $i1,'artefact_type_id' => $type4]);
		DB::table('instructions_artefact_types')->insert(['instruction_id' => $i1,'artefact_type_id' => $type5]);
		DB::table('instructions_artefact_types')->insert(['instruction_id' => $i1,'artefact_type_id' => $type6]);
		DB::table('instructions_artefact_types')->insert(['instruction_id' => $i1,'artefact_type_id' => $type7]);
		DB::table('instructions_artefact_types')->insert(['instruction_id' => $i1,'artefact_type_id' => $type8]);
		DB::table('instructions_artefact_types')->insert(['instruction_id' => $i1,'artefact_type_id' => $type9]);
		
		$a1 = DB::table('artefacts')->insertGetId([
			'thread' => '1',
			'author' => $thomas,
			'artefact_type' => $type2,
			'title' => 'Herman Finkers',
			'url' => 'test.png',
		]);
		DB::table('artefacts_tags')->insert(['artefact_id'=>$a1, 'tag_id'=>$tag1]);
		DB::table('artefacts_tags')->insert(['artefact_id'=>$a1, 'tag_id'=>$tag4]);
		DB::table('artefacts_tags')->insert(['artefact_id'=>$a1, 'tag_id'=>$tag6]);
		
		$a2 = DB::table('artefacts')->insertGetId([
			'parent_id' => $a1,
			'thread' => '1',
			'author' => $kcardina,
			'artefact_type' => $type3,
			'title' => 'Herman Finkers bis',
			'url' => 'https://yt3.ggpht.com/-ql4Zez5OScY/AAAAAAAAAAI/AAAAAAAAAAA/HJKa3hjGwTw/s900-c-k-no/photo.jpg'
		]);
		DB::table('artefacts_tags')->insert(['artefact_id'=>$a1, 'tag_id'=>$tag2]);
		DB::table('artefacts_tags')->insert(['artefact_id'=>$a1, 'tag_id'=>$tag4]);
		DB::table('artefacts_tags')->insert(['artefact_id'=>$a1, 'tag_id'=>$tag5]);
	}
}