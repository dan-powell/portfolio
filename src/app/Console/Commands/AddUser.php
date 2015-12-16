<?php namespace DanPowell\Portfolio\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

use Illuminate\Support\Facades\Hash;
use Symfony\Component\Console\Input\InputArgument;

class AddUser extends Command {

    /**e
     * The console command name.
     *
     * @var string
     */
    protected $name = 'portfolio:adduser';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = "Adds a user for authentication";

    /**
     * Execute the console command.
     *
     * @return void
     */
    public function fire()
    {

        $newuser  = array(
	        'email' => $this->argument('username'),
            'name' => $this->argument('username'),
	        'password' => Hash::make($this->argument('password'))
        );

        if ($this->argument('overwrite') == false) {
            DB::table('users')->insert($newuser);
            $this->line('User "' . $this->argument('username') . '" added');
        } else {

            $builder = DB::table('users')->where('id', '=', $this->argument('overwrite'));

            if ($builder->get()) {
                $builder->update($newuser);
                $this->line('User ID: ' . $this->argument('overwrite') . ' overwritten');
            } else {
                $this->line('User ID: ' . $this->argument('overwrite') . ' not found');
            }
        }
    }

    /**
     * Get the console command arguments.
     *
     * @return array
     */
    protected function getArguments()
    {
        return [
            ['username', InputArgument::REQUIRED, 'New user’s Username/Email'],
            ['password', InputArgument::REQUIRED, 'New user’s Password'],
            ['overwrite', InputArgument::OPTIONAL, 'Overwrite corresponding user ID', false],
        ];
    }

}