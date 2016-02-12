# PHP Datastore Helper
The creatively named Datastore Helper is a fluent, builder style approach to making [Google Cloud Datastore](https://cloud.google.com/datastore/) easier to use in PHP.

Note that this is a fairly thin wrapper and is nowhere near all-inclusive.

##Getting Started
Straight from Google's [Getting Started](https://cloud.google.com/appengine/docs/python/gettingstartedpython27/usingdatastore) page:

We'll create a parent key to ensure that all 'Greetings' are in the same entity group
```
$guestbook_key = (new Key\Builder())
    ->withPath(
        (new Key\Path\Builder())
            ->withKind('Guestbook')
            ->withName($guestbook_name)
            ->build()
    )->build();
```
We need a model representing an author
```
class Author extends SimpleEntityModel
{
    function defineProperties()
    {
        $this->defineProperty('identity', Type::_STRING);
        $this->defineProperty('email', Type::_STRING);
    }
}
```
We also need a main model for representing an individual Guestbook entry
```
class Greeting extends SimpleEntityModel
{
    function defineProperties()
    {
        $this->defineProperty('author', Type::_ENTITY, true);
        $this->defineProperty('content', Type::_STRING);
        $this->defineProperty('date', Type::_DATE_TIME, true);
    }
}
```
Now let's pretend we got a greeting from a friend
```
// Create a datastore helper instance
$client = new Google_Client();
$datastore = new DatastoreHelper(new Google_Service_Datastore($client));

// Build a new a greeting entity
$author = new Author();
$author->identity = 'some_user_id';
$author->email = 'some_email@example.com';

$greeting = new Greeting();
$greeting->setParentKey($guestbookKey);
$greeting->author = $author;
$greeting->content = 'Hiya, buddy!';
$greeting->date = new DateTime();

// write it to Datastore
$datastore->commitMutation(
    (new Mutation\Builder)
        ->withInsertAutoId($greeting)
);
```