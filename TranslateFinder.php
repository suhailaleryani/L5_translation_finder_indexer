<?php
/*
*  This command created by suhail al eryani please feel free to edit or extend it
*
*
*/


namespace App\Console\Commands;

use Illuminate\Console\Command;
use File;


class TranslateFinder extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'translate';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Find all the text inside the underscore tag "__ example text __" and replace it with the trans() function, index all the texts in the en folder  ';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $new_contents = '';
        $files = File::allFiles('resources/views');
        if( !is_dir('./resources/lang/en/auth') ){ // i had to do this because the auth dir does not work in the loop , i think it is about visibility.
          File::makeDirectory('./resources/lang/en/auth', 0775, true, true);
        }

        foreach ($files as $key1 => $file) {
          if( !is_file( './resources/lang/en/'.str_replace('.blade.php','.php', $file->getRelativePathname()) ) ){
            if( !is_dir( './resources/lang/en/'.$file->getRelativePath() ) ){
              if( !File::makeDirectory( './resources/lang/en/'.$file->getRelativePath(), 0775, true, true) ) {
                echo 'error creating folders tree';
              }
            }
            $contents = File::get( './resources/views/'.$file->getRelativePathname() );
            if( $contents != '' ){
              preg_match_all('/__(.*)__/', $contents , $matches);
              $old_values = $matches[0];
              foreach ($old_values as $key2 => $value) {
                $word_key = str_replace(' ','_', strtolower($matches[1][$key2] ) ) ;
                $result[$word_key] = "'".$matches[1][$key2]."',";
                $id_word = str_replace( array('.blade.php') , array('')  , $file->getRelativePathname() );
                if( $key2 == 0){
                  $new_contents = str_replace($value , '{{ trans("'.$id_word.'.'.$word_key.'") }}' , $contents );
                }else{
                  $new_contents = str_replace($value , '{{ trans("'.$id_word.'.'.$word_key.'") }}' , $new_contents );
                }
              }
              File::put( 'resources/views/'.$file->getRelativePathname() , $new_contents );
              File::put( './resources/lang/en/'.str_replace('.blade.php','.php', $file->getRelativePathname()) ,'<?php return '.str_replace( array('[', ']') , array('"','"') , print_r($result,true) ).'; ?>' );
            }

          }

        }
    }

}
