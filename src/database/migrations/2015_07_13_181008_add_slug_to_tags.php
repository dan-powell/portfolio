<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

use DanPowell\Portfolio\Models\Tag;
use Illuminate\Support\Str;

class AddSlugToTags extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('tags', function (Blueprint $table) {
            $table->string('slug', 255);
        });


        $query = Tag::where('slug', '=', '');
        $tags = $query->get();

        if ($tags) {
            foreach($tags as $tag) {

                $tag->slug = Str::slug($tag->title);
                $tag->save();

            }


        }

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('tags', function (Blueprint $table) {
            $table->dropColumn('slug');
        });
    }
}
