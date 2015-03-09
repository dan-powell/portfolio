<?php namespace DanPowell\Portfolio\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use DanPowell\Portfolio\Models\Project;
use DanPowell\Portfolio\Models\Section;
use DanPowell\Portfolio\Models\Tag;

class Seed extends Command {
	/**
	 * The console command name.
	 *
	 * @var string
	 */
	protected $name = 'portfolio:seed';
	/**
	 * The console command description.
	 *
	 * @var string
	 */
	protected $description = 'Seed test data';
	/**
	 * Execute the console command.
	 *
	 * @return mixed
	 */
	public function handle()
	{

        $this->comment("Seeding Projects...");
        $this->seedProjects();

        $this->comment("Seeding Tags...");
        $this->seedTags();

        $this->comment("Portfolio seeded!");


	}

  	private function seedProjects()
  	{

  		$faker = \Faker\Factory::create();
  		Model::unguard();

  		// create some Portfolio Items
		for ($i = 0; $i < 20; $i++)
		{
		  Project::create(array(
            'created_at' => $faker->dateTimeBetween($startDate = '-1 years', $endDate = 'now'),
            'updated_at' => $faker->dateTimeBetween($startDate = '-1 years', $endDate = 'now'),
		    'title' => $faker->sentence(rand(2, 5)),
		    'slug' => $faker->slug,
		    'seo_title' => $faker->sentence(rand(1, 4)),
		    'seo_description' => $faker->paragraph(1),
		    'markup' => $faker->paragraph(rand(3, 8)),
		    'featured' => $faker->randomElement([0, 1])
		  ));
		}

		// Add some Sections to projects
		for ($i = 0; $i < 40; $i++)
		{
		  Section::create(array(
		    'markup' => $faker->paragraph(rand(3, 8)),
		    'attachment_id' => $faker->numberBetween(1, 20),
		    'attachment_type' => 'DanPowell\Portfolio\Models\Project'
		  ));
		}

		// Assign some tags to projects
		for ($i = 0; $i < 16; $i++)
		{
		  DB::table('taggables')->insert(array(
		    'tag_id' => $faker->numberBetween(1, 10),
		    'taggable_id' => $faker->numberBetween(1, 20),
            'taggable_type' => 'DanPowell\Portfolio\Models\Project'
		  ));
		}

  	}

    private function seedTags()
    {
        $faker = \Faker\Factory::create();
        Model::unguard();

		for ($i = 0; $i < 10; $i++)
		{
            Tag::create(array(
                'title' => $faker->word,
                'created_at' => $faker->dateTimeBetween($startDate = '-1 years', $endDate = 'now'),
                'updated_at' => $faker->dateTimeBetween($startDate = '-1 years', $endDate = 'now'),
            ));
		}
    }

}