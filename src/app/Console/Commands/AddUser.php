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

        $user  = array(
	        'email' => $this->argument('username'),
            'name' => $this->argument('username'),
	        'password' => Hash::make($this->argument('password'))
        );

        if ($this->argument('overwrite') == false) {
            DB::table('users')->insert($user);
        } else {
            DB::table('users')
                ->where('id', $this->argument('overwrite'))
                ->update($user);
        }

        $this->line($this->argument('overwrite') ? 'User ID: ' . $this->argument('overwrite') . ' overwritten' : '');
        $this->line('Added new user: Username/Email = '. $this->argument('username') .' Password = ' . $this->argument('password'));
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