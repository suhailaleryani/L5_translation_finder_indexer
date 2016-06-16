# L5_translation_finder_indexer
This is a command for artisan , the command called " translate" this will look into the views folder and find all the text or words inside the underscore __example text__ and index it in the lang/en/$viewfilename or lang/en/$viewfoldername/$viewfilename this depends on how you have your views file tree , and in the view file it will replace the " __example text__  " with @trans(' pathe/to/translation/file.example_text ') 
 an example will be :
 
 path_to_view : views/admin/index.blade.php
  this file contain some text inside the underscore __ some text __
 
 the command will create in the lang/en folder :  admin/index.php
  this file will contain 
  
  <?php return array( 'some_text' => 'some text' ); ?>
  
  after the command is finished the file views/admin/index.blade.php
  
  will contain:   @trans('admin/index.some_text')

