<?php namespace laranaija\Http\Controllers;

use laranaija\Project;
use Validator;
use Input;
use Redirect;

class ProjectController extends Controller {


  /*
  |--------------------------------------------------------------------------
  | Project Controller
  |--------------------------------------------------------------------------
  |
  */
  public function index()
  {
    $projects = Project::where('approval_status', '=', 1 )->paginate(5);
    return view('project')->withProject( $projects );
  }


  public function show( $id ){

  }


  public function store(){
    // create the validation rules ------------------------
    $rules = array(
      'title'            => 'required',
      'url'              => 'required',             // just a normal required validation
      'description'      => 'required',   // required and must be unique in the ducks table
      'categories'       => 'required',
      'tags'             => 'required'
    );


  // create custom validation messages ------------------
  $messages = array(
    'required' => 'The :attribute is very important.'
  );

  // do the validation ----------------------------------
  // validate against the inputs from our form
  $validator = Validator::make(Input::all(), $rules, $messages);

  // check if the validator failed -----------------------
  if($validator->fails()) {

    // get the error messages from the validator
    $messages = $validator->messages();

    // redirect our user back to the form with the errors from the validator
    return Redirect::to('projects/create')->withErrors($validator)->withInput();

  } else {
    // validation successful ---------------------------

    // our duck has passed all tests!
    // let him enter the database

    // create the data for our duck
    $project = new Project;
    $project->name            = Input::get('title');
    $project->url             = Input::get('url');
    $project->description     = Input::get('description');
    $project->categories      = Input::get('categories')[0];
    $project->email          =  Input::get('from');
    $project->tags            = Input::get('tags')[0];
    $project->approval_status = Input::get('approval_status');


    // save our project
    $project->save();

    $success_msg = "Project Successfully Submitted, Approval happens within 24 hours";

    // redirect ----------------------------------------
    // redirect our user back to the form so they can do it all over again
    return Redirect::to('projects/create')->withMessage( $success_msg );
  }

}
  public function create(){
    return view('projcreate');
  }
}